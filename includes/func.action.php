<?php

    // Nous plaçons cette condition au début de chaque includes
    // elle garanti l'inclusion depuis les fichiers autorisés
    // (fichiers qui définissent la constante avec la bonne valeur)
    if(SAFE_PLACE != "f7039d22fa42daa3e57553db3807c933") die();

    function doSwitchAction() {

        if( isset( $_GET['action'] ) ) {
            
            $url = null;
            
            switch ($_GET['action']) {

                    case "conf":
                        if(isAllowed())
                            $url = doConfApp();
                        break;

                    case "add_article":
                        if(isAllowed())
                             $url = doAddArticle();
                        break;

                    case "delete_article":
                        if(isAllowed())
                             $url = doDeleteArticle();
                        break;

                    case "edit_form_article":
                        if(isAllowed())
                             $url = null;
                        break;
                    
                    case "edit_article":
                        if(isAllowed())
                             $url = doEditArticle();
                        break;

                    case "login":
                        $url = doLogin();
                        break;

                    case "logout":
                        if(isAllowed())
                             $url = doLogout();
                        break;

                    case "get_JSON_articles":
                        $url = getJSONArticles();
                        break;

                    case "add_testify":
                        $url = addTestify();
                        break;

                    case "ina":
                        $url = ina();
                        break;
                    
                    default:
                        $url = "index.php?err_log=16:0";
                        break;
            }

            if($url != null) {
                header("Location: ".$url);
                exit;
            }
        }
    }

    function getJSONArticles() {

        /* @var $e ErrorsLog */
        /* @var $database dbMySQL */
        global $e, $database;

        if(is_numeric($_GET["category"])) {
            $query = "SELECT A.*, O.option_value FROM ".TABLE_PREFIX."article A,".TABLE_PREFIX."option O
                      WHERE article_category = O.ID
                      AND   article_category = ".$_GET["category"]."
                      AND   article_langue = '".LANG."'
                      ORDER BY A.position, A.article_date ,A.ID";

            $database->query($query);

            $a_json = Array();
            while($row = $database->fetch()){
                $row["media_preview"] = (file_exists(MEDIA_PREV_DIR.$row["ID"].".jpg"))?'1':'0';
                $a_json[] = $row;
            }
            echo $json = json_encode($a_json);
       
        }

        return null;
    }



    function doEditArticle() {

        /* @var $e ErrorsLog */
        /* @var $database dbMySQL */
        global $e, $database;

        $article_media = "";
        if( $_POST["opt_mediatype"] == "video" ) {

            $article_media = _POST("article_video");

        }  elseif( $_POST["opt_mediatype"] == "vf24" ) {

            $article_media = str_replace('"', '&quot;', $_POST["article_vf24"]);

        }  elseif( $_POST["opt_mediatype"] == "ina" ) {

            $article_media = checkInaEmbed($_POST["article_ina"]);

        } elseif( $_POST["opt_mediatype"] == "image" ) {


            // reception de l'image de fond
            // ----------------------------
            //
            // il y a t-il des erreurs de transfert ?
            if ($_FILES['article_image']['error']) {
                switch ($_FILES['"article_image']['error']){

                    case 1: // UPLOAD_ERR_INI_SIZE
                        $e->add(11);
                        break;

                    case 2: // UPLOAD_ERR_FORM_SIZE
                        $e->add(12);
                        break;

                    case 3: // UPLOAD_ERR_PARTIAL
                        $e->add(13);
                        break;

                }


            // si le format de l'image est le bon
            } elseif(  $_FILES['article_image']['type'] == "image/jpeg"
                    || $_FILES['article_image']['type'] == "image/jpg"
                    || $_FILES['article_image']['type'] == "image/png"
                    || $_FILES['article_image']['type'] == "image/gif"
                    || preg_match( "#jpg|jpeg|gif|png#i", $_FILES['article_image']['name'] ) ) {

                    // on déplace l'image du dossier temporaire vers le bon dossier
                    $imageName = imageName($_FILES['article_image']['name']);
                    move_uploaded_file( $_FILES['article_image']['tmp_name'], IMG_DIR.$imageName);

                    // on met à jour la base
                    $article_media = $imageName;

            } else {
                $e->add(15);
            }


        } else $e->add(16);


        if(! $e->hasErr() ) {
            $query = "UPDATE ".TABLE_PREFIX."article  SET article_name      = '"._POST("article_titre")."',
                                                          article_author    = '"._POST("article_author")."',
                                                          article_date      = '"._POST("article_date")."',
                                                          article_category  =  "._POST("article_cat").",
                                                          article_source    = '"._POST("article_source")."',
                                                          article_content   = '"._POST("article_content")."',
                                                          article_mediatype = '"._POST("opt_mediatype")."',
                                                          article_media     = '".$article_media."',
                                                          position          = '"._POST("article_position")."'
                                                      WHERE ID = "._POST("ID");
          
            $database->query($query);
            $e->add(19, 1);

        }
        if (!empty($_POST["article-media-prev-delete"])) unlink(MEDIA_PREV_DIR.$_POST["ID"].".jpg");

        if (!empty($_POST["ID"])) {

            if (!empty($_FILES['article-media-prev']['name'])) {
                      
                    $file = $_FILES['article-media-prev'];
                    list($width, $height) = getimagesize($file["tmp_name"]);
                    $image = null;
                    if ($file['type'] == "image/jpeg") $image = imagecreatefromjpeg($file["tmp_name"]);
                    if ($file['type'] == "image/png") $image = imagecreatefrompng($file["tmp_name"]);
                    if ($file['type'] == "image/gif") $image = imagecreatefromgif($file["tmp_name"]);
                    
                    if (!is_null($image)) {
                        if (($width > 130) OR ($height > 75)) {
                                if ($width > 130) {
                                        $new_width = 130;
                                        $new_height = $new_width*$height/$width;
                                }
                                elseif ($height > 75) {
                                        $new_height = 130;
                                        $new_width = $new_height*$width/$height;
                                }
                        }
                        else {
                                $new_width = $width;
                                $new_height = $height;
                        }
                        if ($height > $width) {$tow = $toh = $width;}
                        else {$tow = $toh = $height;}

                        $image_p = imagecreatetruecolor($new_width, $new_height);

                        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                        if (!imagejpeg($image_p, MEDIA_PREV_DIR.$_POST["ID"].".jpg", 75)) echo ("erreur imagejpeg");

                        unlink($file["tmp_name"]);
                }
                //else $e->add(15);
            }
        }

        return APP_URL."?".$e->doUrl()."#tabs-2";
    }


    function doDeleteArticle() {
        /* @var $e ErrorsLog */
        /* @var $database dbMySQL */
        global $e, $database;
        

        if(! is_numeric($_GET["ID"]) )
            $e->add(16);
        else {
            $query = "DELETE FROM ".TABLE_PREFIX."article WHERE ID=".$_GET["ID"];
            $database->query($query);
            $e->add(18, 1);
        }

        return APP_URL."?".$e->doUrl()."#tabs-2";
    }

    function doAddArticle() {
        /* @var $e ErrorsLog */
        /* @var $database dbMySQL */
        global $e, $database;
        
        $article_media = "";
        if( $_POST["opt_mediatype"] == "video" ) {
            
            $article_media = _POST("article_video");
            
        } elseif( $_POST["opt_mediatype"] == "vf24" ) {

            $article_media = str_replace('"', '\"', $_POST["article_vf24"]);

        } elseif( $_POST["opt_mediatype"] == "ina" ) {

            $article_media = checkInaEmbed($_POST["article_ina"]);

        } elseif( $_POST["opt_mediatype"] == "image" ) {
            

            // reception de l'image de fond
            // ----------------------------
            //
            // il y a-t-il des erreurs de transfert ?
            if ($_FILES['article_image']['error']) {
                switch ($_FILES['"article_image']['error']){

                    case 1: // UPLOAD_ERR_INI_SIZE
                        $e->add(11);
                        break;

                    case 2: // UPLOAD_ERR_FORM_SIZE
                        $e->add(12);
                        break;

                    case 3: // UPLOAD_ERR_PARTIAL
                        $e->add(13);
                        break;

                }


            // si le format de l'image est le bon
            } elseif(  $_FILES['article_image']['type'] == "image/jpeg"
                    || $_FILES['article_image']['type'] == "image/jpg"
                    || $_FILES['article_image']['type'] == "image/png"
                    || $_FILES['article_image']['type'] == "image/gif"
                    || preg_match( "#jpg|jpeg|gif|png#i", $_FILES['article_image']['name'] ) ) {

                    // on déplace l'image du dossier temporaire vers le bon dossier
                    $imageName = imageName($_FILES['article_image']['name']);
                    move_uploaded_file( $_FILES['article_image']['tmp_name'], IMG_DIR.$imageName);
                    chmod($imageName, 0777);

                    // on met à jour la base
                    $article_media = $imageName;

            } else {
                $e->add(15);
            }

        
        } else $e->add(16);

        // traitement de l'image de prévisualisation du média

        
        if(! $e->hasErr() ) {

            $query = "INSERT INTO ".TABLE_PREFIX."article VALUES('',
                                                               '"._POST("article_author")."',
                                                               '"._POST("article_date")."',
                                                               '"._POST("article_titre")."',
                                                               '"._POST("article_langue")."',
                                                                "._POST("article_cat").",
                                                               '"._POST("article_source")."',
                                                               '"._POST("article_content")."',
                                                               '"._POST("opt_mediatype")."',
                                                               '".$article_media."',
                                                               '"._POST("article_position")."')";

            $database->query($query);
            $article_id = $database->lastid();
            $e->add(17, 1);
            
        }
        if (!empty($article_id)) {
            if (!empty($_FILES['article-media-prev']['name'])) {
                    $file = $_FILES['article-media-prev'];
                    list($width, $height) = getimagesize($file["tmp_name"]);
                    $image = null;
                    if ($file['type'] == "image/jpeg") $image = imagecreatefromjpeg($file["tmp_name"]);
                    if ($file['type'] == "image/png") $image = imagecreatefrompng($file["tmp_name"]);
                    if ($file['type'] == "image/gif") $image = imagecreatefromgif($file["tmp_name"]);

                    if (!is_null($image)) {
                        if (($width > 130) OR ($height > 75)) {
                                if ($width > 130) {
                                        $new_width = 130;
                                        $new_height = $new_width*$height/$width;
                                }
                                elseif ($height > 75) {
                                        $new_height = 130;
                                        $new_width = $new_height*$width/$height;
                                }
                        }
                        else {
                                $new_width = $width;
                                $new_height = $height;
                        }
                        if ($height > $width) {$tow = $toh = $width;}
                        else {$tow = $toh = $height;}

                        $image_p = imagecreatetruecolor($new_width, $new_height);

                        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                        if (!imagejpeg($image_p, MEDIA_PREV_DIR.$article_id.".jpg", 75)) echo ("erreur imagejpeg");

                        unlink($file["tmp_name"]);
                }
                //else $e->add(15);
            }
        }
        
        return APP_URL."?".$e->doUrl()."#tabs-2";
    }
    
    
    function doConfApp() {

        /* @var $database dbMySQL */
        /* @var $e ErrorsLog */
        global $database;
        global $e;

        
        if(isset($_POST["nom_defunt"]))
            updateOption("nom_defunt", _POST("nom_defunt"));


        if(isset($_POST["date_naissance"]))
            updateOption("date_naissance", _POST("date_naissance"));


        if(isset($_POST["date_mort"]))
            updateOption("date_mort", _POST("date_mort"));


        if(isset($_POST["categorie0"]))
            updateOption("categorie0", _POST("categorie0"), true);


        if(isset($_POST["categorie1"]))
            updateOption("categorie1", _POST("categorie1"), true);


        if(isset($_POST["categorie2"]))
            updateOption("categorie2", _POST("categorie2"), true);


        if(isset($_POST["categorie3"]))
            updateOption("categorie3", _POST("categorie3"), true);

        
        if(isset($_POST["bitly"]))
            updateOption("bitly", _POST("bitly"), true);

        
        if(isset($_POST["doc_app"]))
            updateOption("doc_app", _POST("doc_app"), true);


        if(isset($_FILES['bg_image']) ) {
            
            // reception de l'image de fond
            // ----------------------------

            // il y a-t-il des erreurs de transfert ?
            if ($_FILES['bg_image']['error']) {
                
                switch ($_FILES['bg_image']['error']){

                    case 1: // UPLOAD_ERR_INI_SIZE
                        $e->add(11);
                        break;

                    case 2: // UPLOAD_ERR_FORM_SIZE
                        $e->add(12);
                        break;

                    case 3: // UPLOAD_ERR_PARTIAL
                        $e->add(13);
                        break;

                }

                
            // si le format de l'image est le bon
            } elseif(  $_FILES['bg_image']['type'] == "image/jpeg"
                    || $_FILES['bg_image']['type'] == "image/jpg"
                    || $_FILES['bg_image']['type'] == "image/png"
                    || $_FILES['bg_image']['type'] == "image/gif"
                    || preg_match( "#jpg|jpeg|gif|png#i", $_FILES['bg_image']['name'] ) ) {
                
                    // on déplace l'image du dossier temporaire vers le bon dossier
                    $bg_image = IMG_DIR.$_FILES['bg_image']['name'];
                    move_uploaded_file( $_FILES['bg_image']['tmp_name'], $bg_image);
                    chmod($bg_image, 0777);

                    // supprime l'ancienne image
                    if(get_appinfo("bg_image") != -1)
                        unlink( IMG_DIR.get_appinfo("bg_image") );
                    
                    // on met à jour la base
                    updateOption("bg_image", $_FILES['bg_image']['name']);

            } else {
                $e->add(15);
            }

        }

        // OK
        if(! $e->hasErr())
            $e->add(14, 1);

        return APP_URL."?".$e->doUrl()."#tabs-1";
        
    }

    function addTestify() {

        /* @var $FB FBconnect */
        /* @var $TW TWconnect */
        /* @var $database dbMySQL */
        /* @var $e ErrorsLog */
        global $FB, $TW, $database, $e;
        
        // ON NE FAIT RIEN SI ON EST PAS CONNECTÉ À FACEBOOK
        // (et si on a bien reçu un témoignane)
        // -------------------------------------------------

        if( ($FB->isConnected() || $TW->isConnected()) && $_POST['content'] != "" ) {

            // ajoute l'autheur ?
            // -----------------------------------------------------------------------------------------------


            // on utilise facebook
            if( $FB->isConnected() ) {
                
                $user = $FB->getUser();
                $fb_cookie = $FB->getCookie();
                $type_network = "FACEBOOK";
                
            } else {
                // on utilise TWITTER
                $user = $TW->getUser();
                $type_network = "TWITTER";
            }

            

            // regarde si l'utilisateur existe déjà dans la base
            $query = "SELECT ID FROM ".TABLE_PREFIX."testify_author WHERE network_ID='".$user->id."' AND type = '$type_network'";
            $database->query($query);

            // si non, on l'insère
            if(! $database->numrows() || $database->numrows() == 0) {

                    if( $FB->isConnected() )
                        $query = "INSERT INTO ".TABLE_PREFIX."testify_author VALUES ('', '".$user->name."', '".$user->email."', '$type_network', ".$user->id.")";
                    else
                        $query = "INSERT INTO ".TABLE_PREFIX."testify_author VALUES ('', '".$user->screen_name."', '".$user->email."', '$type_network', ".$user->id.")";

                    $database->query($query);

                    // on va chercher l'ID dernièrement créé
                    $query = "SELECT ID FROM ".TABLE_PREFIX."testify_author WHERE network_ID='".$user->id."' AND type = '$type_network'";
                    $database->query($query);
            }

            $row = $database->fetch();
            $userID = $row["ID"];

            // ajoute le témoignage
            // -----------------------------------------------------------------------------------------------
            
            $query = "INSERT INTO ".TABLE_PREFIX."testify VALUES ('', ".$userID.", ".time().", '"._POST("content")."', '".LANG."')";
            $database->query($query);


            // envoie le témoignage sur les réseaux sociaux
            // -----------------------------------------------------------------------------------------------
            

            if( $FB->isConnected() ) {

                // publie sur FACEBOOK
                // -------------------
                $message =  str_replace("\'", "'", rawurldecode( $_POST["content"] ) );                
                $picture = str_replace(Array("index.php","temoigner.php"), Array("",""), APP_URL.getThumb(IMG_DIR.get_appinfo("bg_image"), 100, 75) );
                $link    = str_replace(Array("index.php","temoigner.php"), Array("",""), APP_URL);
                $name    = "[APP] In memoriam: ".get_appinfo("nom_defunt");
                $post    = "message=$message&access_token=".$fb_cookie["access_token"]."&picture=$picture&link=$link&name=$name";

                $curl = curl_init("https://graph.facebook.com/me/feed");

                curl_setopt($curl,CURLOPT_RETURNTRANSFER, true); // pour ne pas afficher le résultat
                curl_setopt($curl,CURLOPT_POST, true);
                curl_setopt($curl,CURLOPT_POSTFIELDS,$post);

                // curl_exec($curl);
                curl_close($curl);
                
            } else {

                // publie sur TWITTER
                // -------------------
                // $TW->doPublish( get_appinfo("nom_defunt").", ".__(31, false)." ".get_appinfo("bitly", ""));

            }

            $e->add(28, 1);
        } else {
            $e->add(29);
        }

        return "temoigner.php?".$e->doUrl();
    }


    function ina() {

        /* @var $database dbMySQL */
        global $database;

        $query = "SELECT article_media FROM ".TABLE_PREFIX."article WHERE ID = "._POST("id");
        $database->query($query);
        $row = $database->fetch();

        ?>
        <!DOTYPE>
        <html>
            <head></head>
            <body style="margin:0px;padding:0px;">
                <script type="text/javascript" src="./includes/js/obg_player_embed.js"></script>
                <script type="text/javascript" src="<?php echo $row["article_media"]; ?>"></script>
            </body>
        </html>            
        <?php        
        exit();
        return null;
    }
    

    function _POST($name) {
        return nl2br( htmlspecialchars($_REQUEST[$name], ENT_QUOTES, "UTF-8") );
    }


    function imageName($filename) {

        // dossier de l'année
        if(! file_exists(IMG_DIR.date("Y")) ) {
            mkdir(IMG_DIR.date("Y"));
            chmod(IMG_DIR.date("Y"), 0777);
        }

        // dossuer du mois
        if(! file_exists(IMG_DIR.date("Y")."/".date("m") ) ) {
            mkdir(IMG_DIR.date("Y")."/".date("m"));
            chmod(IMG_DIR.date("Y")."/".date("m"), 0777);
        }

        // fichier du même nom ?
        for( $i = 0; file_exists(IMG_DIR.date("Y")."/".date("m")."/".$filename); $i++)
            // on cherche un nouveau nom
            $filename = $i."-".$filename;

        return date("Y")."/".date("m")."/".$filename;
    }

    // retourne le code embed adapté pour l'app
    function checkInaEmbed($embed) {
            return preg_replace("#^.+(http://www\.ina\.fr/player/embed/w/\d+/h/\d+/id_notice/\w+/id_utilisateur/\d+/hash/\w+).+$#i", '$1', $embed);            
    }
?>
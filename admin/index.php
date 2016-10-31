<?php
    /** HP APP
    * @author Pierre Romera - pierre.romera@gmail.com
    * @version 1.0
    * @desc La page d'accueil de l'application
    */

    // Cette constante est une sécurité pour les includes
    define("SAFE_PLACE", "f7039d22fa42daa3e57553db3807c933");
    
    // Cette constante est essentielle au bon fonctionement de l'app,
    // elle indique le dossier rassemblant toutes les librairies php, js et le thème css
    // (tout ce qui est inclue d'une façon ou d'une autre)
    define("INC_DIR", "../includes/");
    // le répertoire qui contient les contenus utilisateur
    define("CONTENT_DIR", "../content/");

    // le coeur de l'application, c-a-d toute ce qu'il faut charger
    // ou définir avant de commencer à travailler...
    require_once(INC_DIR."init.core.php");

    if(!isAllowed())
        header("location: ".APP_URL."auth.php");
?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="fr" lang="fr">
    <head>
            <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
            <meta name="viewport" content="width=990">


            <title><?php __(0); ?> &lsaquo; <?php __(1); ?></title>

            <!-- Pour utiliser le thème JQUERY UI -->
            <link type="text/css" rel="stylesheet" href="<?php echo THEME_DIR; ?>smoothness/jquery-ui-1.8.5.custom.css" />

            <!-- LE THÈME DE BASE -->
            <link type="text/css" rel="stylesheet" href="<?php echo THEME_DIR; ?>admin.css" media="screen" />

            <!-- Pour utiliser JQUERY et JQUERY UI-->
            <script type="text/javascript" src="<?php echo JS_DIR; ?>jquery-1.4.2.min.js"></script>
            <script type="text/javascript" src="<?php echo JS_DIR; ?>jquery-ui-1.8.5.custom.min.js"></script>
            
            <!-- Pour générer des infobulles personnalisées -->
            <script type="text/javascript" src="<?php echo JS_DIR; ?>jquery-roro-hidden-title.js"></script>
            <!-- Des fonctions utiles homemade -->
            <script type="text/javascript" src="<?php echo JS_DIR; ?>roro-utils.js"></script>
            <script type="text/javascript" src="<?php echo JS_DIR; ?>jquery-roro-center.js"></script>
            
            <script type="text/javascript">
                $(function() {
                    $("#app").tabs();
                    $(".datepicker").datepicker();
                    $("input:submit,input[type=button]").button();

                    $(".delete_article").click(function () {
                        return confirm("Êtes-vous certain de vouloir supprimer cet article ?");
                    });
                });
            </script>

    </head>
    <body onload="window.scrollTo(0, 1)">
        
        <?php
            if($e->hasErr()) { ?>
            <div class="msg-log">
                <div class="ui-corner-bottom bulle">
                    <ul>
                        <?php
                            foreach( $e->getLog() as $err)
                                if($err["type"] == 1)
                                    echo "<li><span class='ui-icon ui-icon-info'></span>".__($err["ID"], 0)."</li>";
                                elseif($err["type"] == 0)
                                    echo "<li class='ui-state-error-text'><span class='ui-icon ui-icon-alert'></span>".__($err["ID"], 0)."</li>";
                        ?>
                    </ul>
                    <div style="text-align:right; font-size:0.8em; margin-bottom:5px;">
                        <input type="button" class="close-errors-log" value="Fermer" />
                    </div>
                </div>
            </div>
            <script type="text/javascript">

                $(".msg-log").css({opacity:0,marginTop:-1 * $(".msg-log .bulle").outerHeight() });


                $(function () {
                    $(".msg-log").animate({opacity:1,display:'block',marginTop:0 },700);
                });


                $(".close-errors-log").click(function () {
                    $(".msg-log").animate({opacity:0,display:'none',marginTop:-1 * $(".msg-log .bulle").outerHeight() },700);
                });
                
            </script>
        <?php } ?>
        
        <h1><?php __(0); ?> &lsaquo; <?php __(1); ?></h1>
        <div id="app" class=".tabs">
                <div class="ui-state-default ui-corner-all back-app"><span  class="ui-icon ui-icon-arrowreturnthick-1-n"></span><a  href="../index.php">Voir l'application</a></div>



                <!-- --------------------------------------------------------------------------------------
                ---  ONGLETS PRINCIPAUX
                ---- -------------------------------------------------------------------------------------->

                <ul>
                    <li><a href="#tabs-1">Paramétrer l'application</a></li>
                    <li><a href="#tabs-2">Gérer les articles</a></li>
                    <?php if( $_GET["action"] == "edit_form_article" && isset($_GET["ID"])) { ?>
                        <li><a href="#tabs-3">Éditer un article</a></li>
                    <?php } ?>
                    <li><a href="#tabs-0">Visualiser l'application</a></li>
                </ul>



                <!-- --------------------------------------------------------------------------------------
                ---  PARAMÈTRES DE L'APPLICATION
                ---- -------------------------------------------------------------------------------------->

                <div id="tabs-1">
                    <form action="index.php?action=conf" method="POST" enctype="multipart/form-data">
                        <div class="labelling">
                            <label for="nom_defunt">Nom du défunt :</label>
                            <input type="text" name="nom_defunt" id="nom_defunt" value="<?php appinfo("nom_defunt", ""); ?>"  class="text"/>
                        </div>

                        <div class="labelling odd">
                            <label for="date_naissance">Date de naissance:</label>
                            <input type="text" name="date_naissance" id="date_naissance" class="datepicker text" value="<?php appinfo("date_naissance", "") ?>"  /><br />
                            <label for="date_mort">Date de mort :</label>
                            <input type="text" name="date_mort" id="date_mort"  value="<?php appinfo("date_mort", ""); ?>"  class="datepicker text" />
                        </div>

                        <div class="labelling">
                            <label for="bg_image">Choisir l'image de fond :<br /><small>Idéalement, de 910x587 pixels (elle serra redimensionée), 3 Mo maximum.</small></label>
                            <input type="hidden" name="MAX_FILE_SIZE" value="3072000">
                            <input type="file" name="bg_image" id="bg_image" />

                            <?php if(get_appinfo("bg_image") != -1) { ?>
                            <div class="hasThumb">
                                <img src="<?php echo INC_DIR; ?>do.thumb.php?src=<?php echo IMG_DIR.get_appinfo("bg_image"); ?>&h=40&w=40" alt="bg_image" />
                                <p>Une image de fond est déjà définie.<br />Pour la remplacer, choisissez une autre image.</p>
                            </div>
                            <?php } ?>
                                 
                        </div>


                        <div class="labelling odd">
                            <label for="categorie0">Catégories :<br />
                            <small>Séparez par des virgules: [FR], [EN], [AR]</small></label>
                            <input type="text" name="categorie0" id="categorie0" class="text" value="<?php appinfo("categorie0", ""); ?>" /><br />    
                            <label>&nbsp;</label>
                            <input type="text" name="categorie1" id="categorie1" class="text" value="<?php appinfo("categorie1", ""); ?>" /><br />
                            <label>&nbsp;</label>
                            <input type="text" name="categorie2" id="categorie2" class="text" value="<?php appinfo("categorie2", ""); ?>" /><br />
                            <label>&nbsp;</label>
                            <input type="text" name="categorie3" id="categorie3" class="text" value="<?php appinfo("categorie3", ""); ?>" />
                        </div>

                        <div class="labelling">
                            <label for="doc_app">Document à partager :</label>
                            <input type="text" name="doc_app" id="doc_app" value="<?php appinfo("doc_app", ""); ?>"  class="text"/>
                        </div>
                        
                        <div class="labelling odd">
                            <label for="bitly"><a href="http://bit.ly/">Bit.ly</a> vers l'application :</label>
                            <input type="text" name="bitly" id="bitly" value="<?php appinfo("bitly", ""); ?>"  class="text"/>
                        </div>

                        <div style="text-align:right">
                            <input type="submit" value="Enregister" />
                        </div>
                    </form>
                </div>




                <div id="tabs-2">

                    <h2>Vos articles</h2>

                    <?php for($i=0;$i < 4;$i++): ?>
                            <?php if( get_appinfo("categorie".$i) != -1 ) :

                                    /* @var $database dbMySQL */
                                    $database->query("SELECT A.* FROM ".TABLE_PREFIX."article A WHERE article_category = ".get_appinfo("categorie".$i, 0, true)." ORDER BY A.position, A.article_date, A.ID DESC");
                                    if($database->numrows() > 0) { ?>
                    
                                        <h3><?php echo _LANGEX( get_appinfo("categorie".$i) ); ?></h3>
                                        <table class="vos-articles art-category-<?php echo $i; ?>">
                                            <tbody>
                                                <?php

                                                /* @var $database dbMySQL */
                                                $database->query("SELECT A.* FROM ".TABLE_PREFIX."article A WHERE article_category = ".get_appinfo("categorie".$i, 0, true)." ORDER BY A.position, A.article_date, A.ID DESC");

                                                while( $row = $database->fetch() ){ ?>

                                                    <tr class="ui-state-default">
                                                        <td style="width:10px;"><strong><?php echo $row["position"]; ?></strong></td>
                                                        <td style="width:20px;"><span class="ui-icon ui-icon-arrowthick-1-e"></span></td>
                                                        <td><strong><?php echo $row["article_name"]; ?></strong></td>
                                                        <td><?php echo $row["article_langue"]; ?></td>
                                                        <td class="ctrl"><a href="?action=edit_form_article&ID=<?php echo $row["ID"]; ?>#tabs-3"><span class="ui-icon ui-icon-pencil" ></span>Edit.</a></td>
                                                        <td class="ctrl"><a href="?action=delete_article&ID=<?php echo $row["ID"]; ?>" class="delete_article"><span class="ui-icon ui-icon-trash" ></span>Suppr.</a></td>
                                                    </tr>

                                                <?php } ?>
                                            </tbody>
                                        </table>
                                  <? }
                        endif; ?>
                                        
                    <?php endfor; ?>


                   

                    <h2>Nouvel article</h2>
                    <p><em>Ajouter un article depuis ce formulaire. Tous les champs sont obligatoires.</em></p>
                    <form id="add_article" action="index.php?action=add_article" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="MAX_FILE_SIZE" value="3072000">
                        
                        <div class="labelling odd">
                            <label for="article_titre">Titre de l'article :</label>
                            <input type="text" name="article_titre" id="article_titre" class="text" value="" />
                        </div>

                        <div class="labelling">
                            <label for="article_author">Auteur de l'article :</label>
                            <input type="text" name="article_author" id="article_author" class="text" value="" />
                        </div>

                        <div class="labelling odd">
                            <label for="article_date">Date :</label>
                            <input type="text" name="article_date" id="article_date" class="text datepicker" value="" />
                        </div>

                        <div class="labelling">
                            <label for="article_cat">Catégorie :</label>
                            <select name="article_cat" id="article_cat">
                                <?php
                                    if(get_appinfo("categorie0") != -1)
                                        echo "<option value='".get_appinfo("categorie0", -1, true)."'>"._LANGEX(get_appinfo("categorie0"), 0)."</option>";

                                    if(get_appinfo("categorie1") != -1)
                                        echo "<option value='".get_appinfo("categorie1", -1, true)."'>"._LANGEX(get_appinfo("categorie1"), 0)."</option>";


                                    if(get_appinfo("categorie2") != -1)
                                        echo "<option value='".get_appinfo("categorie2", -1, true)."'>"._LANGEX(get_appinfo("categorie2"), 0)."</option>";


                                    if(get_appinfo("categorie3") != -1)
                                        echo "<option value='".get_appinfo("categorie3", -1, true)."'>"._LANGEX(get_appinfo("categorie3"), 0)."</option>";

                                ?>
                            </select>
                        </div>

                        <div class="labelling odd">
                            <label for="article_content">Texte d'accroche :</label>
                            <textarea class="text" rows="6" name="article_content"></textarea>
                        </div>

                        <div class="labelling mediatype">
                            <label>Media :</label>
                            
                            <label for="opt_image" class="radio"><input type="radio" checked="checked" id="opt_image" value="image" name="opt_mediatype" />&nbsp;Image</label>
                            <label for="opt_video" class="radio"><input type="radio" id="opt_video" value="video" name="opt_mediatype" />&nbsp;Vidéo <abbr title="Youtube">YT</abbr></label>
                            <label for="opt_vf24" class="radio"><input type="radio" id="opt_vf24" value="vf24" name="opt_mediatype" />&nbsp;Vidéo <abbr title="France24">F24</abbr></label>
                            <label for="opt_ina" class="radio"><input type="radio" id="opt_ina" value="ina" name="opt_mediatype" />&nbsp;Vidéo Ina</label>

                            <hr style="clear:both;"/>
                            <div class="article_image">
                                <label for="article_image">Choisissez l'image qui illustre l'article :</label><input type="file" name="article_image"  id="article_image" />
                            </div>
                            <div class="article_video" style="display:none">
                                <label for="article_video">Indiquez l'ID de la vidéo Youtube :<br /><small>http://www.youtube.com/watch?v=<strong>1Qrkj_7a2uE</strong></small></label><input type="text" class="text" name="article_video" id="article_video" />
                                <br style="clear:both;" /><br style="clear:both;" />
                            </div>
                            <div class="article_vf24" style="display:none">
                                <label for="article_vf24">Indiquez le &lt;param&gt; de la vidéo :</label><input type="text" class="text" name="article_vf24" id="article_vf24" />
                            </div>
                            <div class="article_ina" style="display:none">
                                <label for="article_ina">Indiquez le &lt;script&gt; de la vidéo :</label><input type="text" class="text" name="article_ina" id="article_ina" />
                            </div>
                            <div id="article_media_prev_upload" style="clear:both;">
                                <label for="article-media-prev">Choisissez une image de prévisualisation :</label>
                                <input type="file" name="article-media-prev" id="article-media-prev" value="" class="text" value="" /><br />
                                    <em>JPEG seulement, 130x75 pixels</em>
                            </div>
                        </div>

                            <script type="text/javascript">
                                $("#add_article :input[name=opt_mediatype]").click(function () {
                                    if($(this).val() == "image") {

                                        $("#add_article .article_video").hide();
                                        $("#add_article .article_image").show();
                                        $("#add_article .article_vf24").hide();
                                        $("#add_article .article_ina").hide();

                                    } else if($(this).val() == "video") {

                                        $("#add_article .article_video").show();
                                        $("#add_article .article_image").hide();
                                        $("#add_article .article_vf24").hide();
                                        $("#add_article .article_ina").hide();

                                    } else if($(this).val() == "vf24") {

                                        $("#add_article .article_video").hide();
                                        $("#add_article .article_image").hide();
                                        $("#add_article .article_vf24").show();
                                        $("#add_article .article_ina").hide();

                                    }  else if($(this).val() == "ina") {

                                        $("#add_article .article_video").hide();
                                        $("#add_article .article_image").hide();
                                        $("#add_article .article_vf24").hide();
                                        $("#add_article .article_ina").show();

                                    }

                                    $(".mediatype").effect("highlight", 1000);
                                });
                            </script>
                        
                        <div class="labelling odd">
                            <label for="article_langue">Langue de l'article :</label>
                            <select name="article_langue" id="article_langue">
                                <?php
                                    foreach(getLangDispo() as $lang) {

                                        echo "<option value='".$lang."'>".$lang."</option>";
                                        
                                    }
                                ?>
                            </select>
                        </div>


                        <div class="labelling">
                            <label for="article_source">Source (lien) :</label>
                            <input type="text" class="text" name="article_source"  id="article_source" />
                        </div>

                        <?php

                            $database->query("SELECT MAX(A.position) max FROM ".TABLE_PREFIX."article A");
                            $row = $database->fetch();
                            $position = $row["max"] + 1;

                        ?>
                        <div class="labelling odd">
                            <label for="article_position">Position :<br /><small>1 pour le premier, 2 pour le second, etc</small></label>
                            <input type="text" class="text" name="article_position" value="<?php echo $position; ?>" id="article_position" />
                            <br style="clear:both;" />
                        </div>
                        
                        <div style="text-align:right">
                            <input type="submit" value="Ajouter" />
                        </div>
                    </form>
                </div>


                <!-- --------------------------------------------------------------------------------------
                ---  EDITION D'UN ARTICLE
                ---- -------------------------------------------------------------------------------------->

                <?php if($_GET["action"] == "edit_form_article" && is_numeric($_GET["ID"])) {

                    $database->query("SELECT * FROM ".TABLE_PREFIX."article WHERE ID=".$_GET["ID"]);
                    $post = $database->fetch();

                    ?>
                    <div id="tabs-3">
                        <form id="edit_article" action="index.php?action=edit_article" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="MAX_FILE_SIZE" value="3072000">
                            <input type="hidden" name="ID" value="<?php echo $_GET["ID"]; ?>">

                            <div class="labelling odd">
                                <label for="edit_article_titre">Titre de l'article :</label>
                                <input type="text" name="article_titre" value="<?php echo $post["article_name"]; ?>" id="edit_article_titre" class="text" value="" />
                            </div>

                            <div class="labelling">
                                <label for="edit_article_author">Auteur de l'article :</label>
                                <input type="text" name="article_author" id="edit_article_author" class="text" value="<?php echo $post["article_author"]; ?>" />
                            </div>

                            <div class="labelling odd">
                                <label for="edit_article_date">Date :</label>
                                <input type="text" name="article_date" id="edit_article_date" class="text datepicker" value="<?php echo $post["article_date"]; ?>" />
                            </div>

                            <div class="labelling">
                                <label for="edit_article_cat">Catégorie :</label>
                                <select name="article_cat" id="edit_article_cat">
                                    <?php
                                        if(get_appinfo("categorie0") != -1)
                                            echo "<option value='".get_appinfo("categorie0", -1, true)."' ".($post["article_category"] == get_appinfo("categorie0", -1, true) ? "selected='selected'" : "").">"._LANGEX(get_appinfo("categorie0"), 0)."</option>";

                                        if(get_appinfo("categorie1") != -1)
                                            echo "<option value='".get_appinfo("categorie1", -1, true)."' ".($post["article_category"] == get_appinfo("categorie1", -1, true) ? "selected='selected'" : "").">"._LANGEX(get_appinfo("categorie1"), 0)."</option>";


                                        if(get_appinfo("categorie2") != -1)
                                            echo "<option value='".get_appinfo("categorie2", -1, true)."' ".($post["article_category"] == get_appinfo("categorie2", -1, true) ? "selected='selected'" : "").">"._LANGEX(get_appinfo("categorie2"), 0)."</option>";


                                        if(get_appinfo("categorie3") != -1)
                                            echo "<option value='".get_appinfo("categorie3", -1, true)."' ".($post["article_category"] == get_appinfo("categorie3", -1, true) ? "selected='selected'" : "").">"._LANGEX(get_appinfo("categorie3"), 0)."</option>";

                                    ?>
                                </select>
                            </div>

                            <div class="labelling odd">
                                <label for="edit_article_content">Texte d'accroche :</label>
                                <textarea class="text" rows="6" id="edit_article_content" name="article_content"><?php echo $post["article_content"]; ?></textarea>
                            </div>

                            <div class="labelling mediatype">
                                <label>Media :</label>

                                <label for="edit_opt_image" class="radio"><input type="radio" <?php echo $post["article_mediatype"] == "image" ? "checked='checked'" : ""; ?> id="edit_opt_image" value="image" name="opt_mediatype" />&nbsp;Image</label>
                                <label for="edit_opt_video" class="radio"><input type="radio" <?php echo $post["article_mediatype"] == "video" ? "checked='checked'" : ""; ?> id="edit_opt_video" value="video" name="opt_mediatype" />&nbsp;Vidéo <abbr title="Youtube">YT</abbr></label>
                                <label for="edit_opt_vf24"  class="radio"><input type="radio" <?php echo $post["article_mediatype"] == "vf24" ? "checked='checked'" : ""; ?> id="edit_opt_vf24" value="vf24" name="opt_mediatype" />&nbsp;Vidéo <abbr title="France24">F24</abbr></label>
                                <label for="edit_opt_ina"  class="radio"><input type="radio" <?php echo $post["article_mediatype"] == "ina" ? "checked='checked'" : ""; ?> id="edit_opt_ina" value="ina" name="opt_mediatype" />&nbsp;Vidéo Ina</label>

                                <hr style="clear:both;"/>
                                
                                <div class="article_image" style="<?php echo $post["article_mediatype"] != "image" ? "display:none" : ""; ?>">
                                    <label for="edit_article_image">Choisissez l'image qui illustre l'article :</label><input type="file" name="article_image"  id="edit_article_image" />


                                    <?php if($post["article_mediatype"] == "image" && $post["article_media"] != "") { ?>
                                    <div class="hasThumb">
                                        <img src="<?php echo INC_DIR; ?>do.thumb.php?src=<?php echo IMG_DIR.$post["article_media"]; ?>&h=40&w=40" alt="bg_image" />
                                        <p>Une image est déjà définie pour cet article.<br />Pour la remplacer, choisissez une autre image.</p>
                                    </div>
                                    <?php } ?>
                                </div>

                                <div class="article_video" style="<?php echo $post["article_mediatype"] != "video" ? "display:none" : ""; ?>">
                                    <label for="edit_article_video">Indiquez l'ID de la vidéo Youtube :<br /><small>http://www.youtube.com/watch?v=<strong>1Qrkj_7a2uE</strong></small></label>
                                    <input type="text"
                                           class="text"
                                           name="article_video"
                                           id="edit_article_video"
                                           value="<?php echo $post["article_mediatype"] == "video" ? $post["article_media"] : ""; ?>" />
                                    <br style="clear:both;" /><br style="clear:both;" />
                                </div>


                                <div class="article_vf24" style="<?php echo $post["article_mediatype"] != "vf24" ? "display:none" : ""; ?>">
                                    <label for="article_vf24">Indiquez le &lt;param&gt; de la vidéo :</label>
                                    <input type="text"
                                           class="text"
                                           name="article_vf24"
                                           id="edit_article_vf24"
                                           value="<?php echo $post["article_mediatype"] == "vf24" ? $post["article_media"] : ""; ?>" />
                                </div>
                                
                                <div class="article_ina" style="<?php echo $post["article_mediatype"] != "ina" ? "display:none" : ""; ?>">
                                    <label for="article_ina">Indiquez les &lt;script&gt; de la vidéo :</label>
                                    <input type="text"
                                           class="text"
                                           name="article_ina"
                                           id="edit_article_ina"
                                           value="<?php echo $post["article_mediatype"] == "ina" ? $post["article_media"] : ""; ?>" />
                                </div>
                                <div id="article_media_prev" style="clear:both;">
                                    <?php
                                        $preview_img = MEDIA_PREV_DIR. $_GET["ID"].".jpg";
                                        if (file_exists($preview_img)) {
                                            echo '<label for="article-media-prev">Image actuelle de prévisualisation :</label><img src="'.$preview_img.'" alt="media preview" width="130" height="75" /><br />
                                                <input type="checkbox" name="article-media-prev-delete" id="article-media-prev-delete" value="1" /> <label for="article-media-prev-delete">supprimer</label>';
                                        }
                                        else {
                                            echo '<label for="article-media-prev">pas d\'image de prévisualisation actuellement</label><div style="position:relative;display: block; width: 130px; height: 75px;"></div>';
                                        }
                                    ?>
                                </div>
                                <div id="article_media_prev_upload" style="clear:both;">
                                    <label for="article-media-prev">Charger une nouvelle image de prévisualisation :</label>
                                    <input type="file" name="article-media-prev" id="article-media-prev" value="" class="text" value="" /><br />
                                        <em>JPEG seulement, 130x75 pixels</em>
                                </div>
                            </div>

                                <script type="text/javascript">
                                    $("#edit_article :input[name=opt_mediatype]").click(function () {

                                        if($(this).val() == "image") {

                                            $("#edit_article .article_video").hide();
                                            $("#edit_article .article_image").show();
                                            $("#edit_article .article_vf24").hide();
                                            $("#edit_article .article_ina").hide();

                                        } else if($(this).val() == "video") {

                                            $("#edit_article .article_video").show();
                                            $("#edit_article .article_image").hide();
                                            $("#edit_article .article_vf24").hide();
                                            $("#edit_article .article_ina").hide();

                                        } else if($(this).val() == "vf24") {

                                            $("#edit_article .article_video").hide();
                                            $("#edit_article .article_image").hide();
                                            $("#edit_article .article_vf24").show();
                                            $("#edit_article .article_ina").hide();

                                        } else if($(this).val() == "ina") {

                                            $("#edit_article .article_video").hide();
                                            $("#edit_article .article_image").hide();
                                            $("#edit_article .article_vf24").hide();
                                            $("#edit_article .article_ina").show();

                                        }

                                        $(".mediatype").effect("highlight", 1000);
                                    });
                                </script>

                            <div class="labelling odd">
                                <label for="edit_article_langue">Langue de l'article :</label>
                                <select name="article_langue" id="edit_article_langue">
                                    <?php
                                        foreach(getLangDispo() as $lang) {

                                            echo "<option value='".$lang."' ".( $post["article_langue"] == $lang ? "selected='selected'" : "" ).">".$lang."</option>";

                                        }
                                    ?>
                                </select>
                            </div>


                            <div class="labelling">
                                <label for="edit_article_source">Source (lien) :</label>
                                <input type="text" class="text" name="article_source"  value="<?php echo $post["article_source"]; ?>"  id="edit_article_source" />
                            </div>

                            <div class="labelling odd">
                                <label for="edit_article_position">Position :<br /><small>1 pour le premier, 2 pour le second, etc</small></label>
                                <input type="text" class="text" name="article_position"  value="<?php echo $post["position"]; ?>"  id="edit_article_position" />
                                <br style="clear:left;" />
                            </div>

                            <div style="text-align:right">
                                <input type="submit" value="Mettre à jour" />
                            </div>
                        </form>
                    </div>
                <?php } ?>

                <div id="tabs-0">
                    <object data="../index.php" type="text/html" style="height:667px;width:990px;margin-left:-120px;"></object>
                </div>

        </div>

        <div class="footer">
            Une application propulsée par <a href="http://22mars.com">22mars</a> | <a href="index.php?action=logout">Déconnexion</a>
        </div>

    </body>
</html>
<?php echo exit; ?>
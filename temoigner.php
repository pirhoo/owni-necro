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
    define("INC_DIR", "includes/");
    // le répertoire qui contient les contenus utilisateur
    define("CONTENT_DIR", "content/");

    // le coeur de l'application, c-a-d toute ce qu'il faut charger
    // ou définir avant de commencer à travailler...
    require_once(INC_DIR."init.core.php");

    // on vérifie que l'application à bien étée configurée
    // sinon on sort
    checkRequiredOptions();

?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="fr" lang="fr">
    <head>
            <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
            <meta name="viewport" content="width=990">

            <meta property="og:image"     content="<?php echo APP_URL.getThumb(IMG_DIR.get_appinfo("bg_image"), 100, 75); ?>"/>
            <meta property="fb:app_id"    content="<?php echo FACEBOOK_APP_ID; ?>"/>
            <meta property="og:site_name" content="France24"/>
            <meta property="og:title"     content="<?php appinfo("nom_defunt"); ?>"/>

            <title><?php appinfo("nom_defunt", "Veuillez configurer l'application"); ?> &lsaquo; <?php __(1); ?></title>

            <!-- Pour utiliser le thème JQUERY UI -->
            <link type="text/css" rel="stylesheet" href="<?php echo THEME_DIR; ?>smoothness/jquery-ui-1.8.custom.css" />

            <!-- LE THÈME DE BASE -->
            <link type="text/css" rel="stylesheet" href="<?php echo THEME_DIR; ?>style.css" media="screen" />

            <link href='http://fonts.googleapis.com/css?family=Crimson+Text&subset=latin' rel='stylesheet' type='text/css'>

            <!-- Pour utiliser JQUERY et JQUERY UI-->
            <script type="text/javascript" src="<?php echo JS_DIR; ?>jquery-1.4.2.min.js"></script>
            <script type="text/javascript" src="<?php echo JS_DIR; ?>jquery-ui-1.8.5.custom.min.js"></script>

            <!-- Pour générer des infobulles personnalisées -->
            <script type="text/javascript" src="<?php echo JS_DIR; ?>jquery-roro-hidden-title.js"></script>
            <!-- Des fonctions utiles homemade -->
            <script type="text/javascript" src="<?php echo JS_DIR; ?>roro-utils.js"></script>
            <script type="text/javascript" src="<?php echo JS_DIR; ?>jquery-roro-center.js"></script>

            <script type="text/javascript" src="<?php echo JS_DIR; ?>class.Necroapp.js"></script>

            <script type="text/javascript">

                // Multi-langue
                var _more = "<?php __(24); ?>";

                // composants élémentaires de toutes les APP
                // ------------------------------------------
                $(document).ready(function () {

                    // centre les éléments avec la classe .center millieu de l'écran (ici l'app)
                    $(".center").center();

                    // Déclenche les infobulles personnalisées sur les éléments .share et leur ajoute la classe "shareTitle"
                    // Seulement si le visiteur n'est pas sur Ipad'
                    if(! RR_UTILS.isIpad()) {
                        $(".share").addTitle("shareTitle");
                        $(".copier").addTitle("copierTitle");
                    }

                    // cache le mask si on lui clique dessus
                    // sauf si il contient la classe "hold"
                    $("#mask").click(function () {

                        // une classe hold permet de bloquer la fermeture du mask
                        if( ! $(this).hasClass("hold") ) {

                            // pas de fondu sur IPAD et IPHONE
                            if(RR_UTILS.isApple())
                                $(this).hide(0);
                            else
                                $(this).stop().fadeOut(300);
                        }
                    });

                });

            </script>

            <style type="text/css">
                #workspace {
                    background-image: url(<?php echo getThumb( IMG_DIR.get_appinfo("bg_image"), 910, 587); ?>);
                }
            </style>
    </head>
    <body onload="window.scrollTo(0, 1)">

        <?php
            /* @var $FB FBconnect */
            $FB->doFBhoot();

            /* @var $TW TWconnect */
            $TW->doTWhoot();


            $naiss = get_appinfo("date_naissance");
            $naiss = explode("/", $naiss);
            $naiss = $naiss[2];

            $mort = get_appinfo("date_mort");
            $mort = explode("/", $mort);
            $mort = $mort[2];

        ?>

        <!-- L'APP en elle même, de 990x667 -->
        <div id="app" class="center">

            <!-- Une surcouche sur la div APP avec un overflow hidden de 990x667 -->
            <div id="overflow">

                <!-- Là où l'application se déroule -->
                <div id="workspace">

                    <ul class="categories">
                        <?php
                            if(get_appinfo("categorie0") != -1)
                                echo "<li>
                                        <a href='index.php?cat=".get_appinfo("categorie0", -1, true)."'>"
                                            ._LANGEX(get_appinfo("categorie0"))."
                                        </a>
                                      </li>";

                            if(get_appinfo("categorie1") != -1)
                                echo "<li>
                                         <a href='index.php?cat=".get_appinfo("categorie1", -1, true)."'>"
                                            ._LANGEX(get_appinfo("categorie1"))."
                                        </a>
                                    </li>";


                            if(get_appinfo("categorie2") != -1)
                                echo "<li>
                                        <a href='index.php?cat=".get_appinfo("categorie2", -1, true)."'>"
                                            ._LANGEX(get_appinfo("categorie2"))."
                                        </a>
                                    </li>";


                            if(get_appinfo("categorie3") != -1)
                                echo "<li>
                                        <a href='index.php?cat=".get_appinfo("categorie3", -1, true)."'>"
                                            ._LANGEX(get_appinfo("categorie3"))."
                                        </a>
                                    </li>";
                         ?>
                    </ul>

                    <div style="text-align:center;">
                        <div class="titre">
                            <h1><a href="index.php"><?php appinfo("nom_defunt"); ?></a></h1>

                            <span class="annees"><?php echo $naiss. "&nbsp;-&nbsp;".$mort; ?></span>
                        </div>
                    </div>

                    <div id="content" class="temoigner">
                        <h2><?php __(25); ?></h2>
                        <form action="?action=add_testify" id="add_testify" method="POST">
                            <textarea name="content" cols="20" rows="10"></textarea>
                            
                            <?php

                            ?>
                            <?php if ( !$FB->isConnected() && !$TW->isConnected() ) { ?>
                                <div class="connect">
                                    <h3>
                                        <?php __(26); ?>
                                    </h3>

                                    <div class="choice">

                                        <fb:login-button perms="email,publish_stream" onlogin="$('#add_testify').submit()"></fb:login-button>

                                        &nbsp;ou&nbsp;

                                        <span style="position:relative;bottom:-7px;">
                                            <?php $TW->doButton(THEME_DIR."img/sign-in-with-twitter-l.png"); ?>
                                        </span>
                                        
                                        <script type="text/javascript">
                                            // FONCTION APPELLÉE À LA FERMETURE DE LA FENÊTRE DE LOG TWITTER
                                            function TWonLogin() {
                                                 $('#add_testify').submit();
                                            }
                                        </script>
                                        
                                    </div>
                                    
                                </div>
                            <?php } else { ?>
                                <div style="text-align:right;">
                                    <input type="submit"  value="<?php  __(27); ?>" />
                                </div>
                            <?php } ?>
                            
                        </form>

                        <div class="testify-list">
                            <?php
                                $query = "SELECT T.*, A.name, A.network_ID AS AID, A.type
                                          FROM ".TABLE_PREFIX."testify AS T, ".TABLE_PREFIX."testify_author A
                                          WHERE lang='".LANG."'
                                          AND A.ID = T.author ORDER BY time DESC";
                                $database->query($query);

                                while( $row = $database->fetch() ) { ?>
                                    <div class="testify">
                                        <?php if($row["type"] == 'FACEBOOK') { ?>
                                            <p><a class="author" target="_blank" href="http://facebook.com/profile.php?id=<?php echo $row["AID"]; ?>"><?php echo $row["name"]; ?></a>&nbsp;:&nbsp;<?php echo $row["content"]; ?></p>
                                        <?php } else { ?>
                                            <p><a class="author" target="_blank" href="http://twitter.com/<?php echo $row["name"]; ?>"><?php echo $row["name"]; ?></a>&nbsp;:&nbsp;<?php echo $row["content"]; ?></p>
                                        <?php } ?>
                                    </div>
                                <?php }
                             ?>
                        </div>
                    </div>

                </div>

                <!-- Un masque sombre qui recouvre l'application (pour des popups) -->
                <div id="mask"></div>

                <!-- Barre de partage (cachée) -->
                <div id="footer">

                    <!-- Les outils pour partager l'APP (Facebook, Twitter, etc) -->
                    <?php include(INC_DIR."inc.share.php"); ?>

                </div>
            </div>
        </div>
    </body>
</html>
<?php echo exit; ?>
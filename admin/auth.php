<?php
    /** AUTH
    * @author Pierre Romera - pierre.romera@gmail.com
    * @version 1.0
    * @desc La page d'authentification
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

?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="fr" lang="fr">
    <head>
            <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
            <meta name="viewport" content="width=990">


            <title><?php __(22); ?> &rsaquo; <?php __(0); ?> &lsaquo; <?php __(1); ?></title>

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
                });
            </script>

    </head>
    <body onload="window.scrollTo(0, 1)">

        <?php if($e->hasErr()) { ?>
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
                    <li><a href="#tabs-1"><?php __(22); ?></a></li>
                </ul>



                <!-- --------------------------------------------------------------------------------------
                ---  PARAMÈTRES DE L'APPLICATION
                ---- -------------------------------------------------------------------------------------->


                <div id="tabs-1">
                    <form action="index.php?action=login" method="POST">
                        <div class="labelling">
                            <label for="pseudo">Pseudo :</label>
                            <input type="text" name="pseudo" id="pseudo" value=""  class="text"/>
                        </div>

                        <div class="labelling odd">
                            <label for="password">Mot de passe :</label>
                            <input type="password" name="password" id="password" value=""  class="text"/>
                        </div>

                        <div style="text-align:right">
                            <input type="submit" value="Enregister" />
                        </div>
                    </form>
                </div>

        </div>

    </body>
</html>
<?php echo exit; ?>
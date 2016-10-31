<?php

    /** init.core.php
    * @author Pierre Romera - pierre.romera@gmail.com
    * @version 1.0
    * @desc Charge les composants essentiels de l'application
    */
    
    // Nous plaÃ§ons cette condition au dÃ©but de chaque includes
    // elle garanti l'inclusion depuis les fichiers autorisÃ©s
    // (fichiers qui dÃ©finissent la constante avec la bonne valeur)
    if(SAFE_PLACE != "f7039d22fa42daa3e57553db3807c933") die();

    // permet d'afficher les messages d'erreur
    if(isset($_GET['debug'])) { //
        ini_set('display_errors', 1);
        ini_set('log_errors', 1);
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', 0);
        ini_set('log_errors', 0);
        error_reporting(null);
    }
    

    // Fonctions permettant de crÃ©er une application multi-langues
    require_once(INC_DIR."func.lang.php");
    // Fonctions permettant d'utiliser les options de l'APP
    require_once (INC_DIR."func.option.php");
    // Fonctions permettant de choisir que faire en cas de request
    require_once (INC_DIR."func.action.php");
    // Fonctions permettant d'authentifier l'utilisateur
    require_once (INC_DIR."func.auth.php");
    // Classe permettant d'Ã©tablir une connexion avec MySql
    require_once(INC_DIR."class.Mysql.php");
    // Classe permettant de ce connecter Ã  l'aide de Facebook Connect
    require_once(INC_DIR."class.FBconnect.php");
    // Classe permettent de loguer les erreurs
    require_once(INC_DIR."class.ErrorsLog.php");

    // Classe pour twitter connect
    require_once(INC_DIR."class.TWconnect.php");

    
    // Ecran courrant de l'application
    // (typiquement, il serra contenut dans la div #workspace)
    if(!isset($_GET['e']))
        define("ECRAN", 1);
    else
        define("ECRAN", $_GET['e']);

    
    // le repertoire contenant les images
    define("IMG_DIR", CONTENT_DIR."img/");
    // le repertoire contenant les vignettes de prévisualisation des média
    define("MEDIA_PREV_DIR", CONTENT_DIR."img/prev/");

    
    // D'autres implacements indispensables,
    // sous rÃ©pertoires de INC_DIR:

    // le rÃ©pertoire qui contient les fichiers de langue
    define("LANG_DIR", INC_DIR."lang/");
    // le rÃ©pertoire qui contient le style
    define("THEME_DIR", INC_DIR."style/");
    // le rÃ©pertoire qui contient les javascript
    define("JS_DIR", INC_DIR."js/");

    // url de l'APP
    define("APP_URL", "http://".$_SERVER["SERVER_NAME"].str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]) );


    /* @var $e ErrorsLog */
    $e = new ErrorsLog();
    global $e;

    
    // MySQL
    // inspensable pour faire cohabiter plusieurs de ces apps
    define('TABLE_PREFIX', 'mand_');
    
    /* @var $database dbMySQL */
    //$database = new dbMySQL("cust_necro", "root", "root", "localhost");
    $database = new dbMySQL("stmownnecro", "stmownnecro", "moo6xab0Ouda", "78.109.88.210");
    global $database;

    // Etablis la connexion Ã  la BDD
    $database->connection();

    session_start();
    //session_destroy();

    // Configuration pour Twitter
    // --------------------------
    define("TW_CONSUMER_KEY",    "9sa8zeQaLZFvAqptcaJi2w");
    define("TW_CONSUMER_SECRET", "UD8QQbMjgCHcVEahTOUCIUBbsge7owg8TfLHsaf9qA");
    define("TW_OAUTH_CALLBACK",  "http://localhost/Atelier/Sign%20In%20With%20Twitter/");

    // pour s'authentifier auprÃ¨s de Twitter
    $TW = new TWconnect(TW_CONSUMER_KEY, TW_CONSUMER_SECRET, TW_OAUTH_CALLBACK);


    // Configuration Facebook Connect
    // ------------------------------
    
    // L'ID de l'APP Facebook
    // (conf http://www.facebook.com/developers/ )
    define('FACEBOOK_APP_ID', '100469823352520');
    // Passphrase de l'APP Facebook
    define('FACEBOOK_SECRET', '3414fc723d49cce6caca11cef549247f');


    /* @var $FB FBconnect */
    $FB = new FBconnect(FACEBOOK_APP_ID, FACEBOOK_SECRET);
    global $FB;

    // EXEMPLE ---------------
    // Force une pseudo connexion Ã  l'aide d'un token personalisÃ©...
    // Pour trouver un token http://developers.facebook.com/docs/api
    //
    //$FB->startSimulation("2227470867|2._c1CE42SzMJlVlsMzse8ww__.3600.1286384400-686299757|xyS_MAaK3_MDW1W8OGgfQRFICjA");
    // --------------------------------------------------------------------------------------------------------------------


    
    // On dÃ©fini la langue de l'application:
    // Si une langue est demandÃ©e (en GET), on la dÃ©fini
    // (seulement un jeu de langue est autorisÃ© dans cette fonction)
    if(!isset($_GET['lang']))
        defineLang();
    else
        defineLang($_GET['lang']);

    doSwitchAction();


    function getThumb($src, $w, $h) {
        return INC_DIR."do.thumb.php?src=".$src."&w=".$w."&h=".$h;
    }

?>

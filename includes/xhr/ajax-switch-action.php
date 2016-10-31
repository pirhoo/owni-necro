<?php

    /** HP APP
    * @author Pierre Romera - pierre.romera@gmail.com
    * @version 1.0
    * @desc Retourne le contenu demandé par l'utilisateur au format JSON
    */

    @header('Content-Type: text/html; charset=UTF-8');

    // Cette constante est une sécurité pour les includes
    define("SAFE_PLACE", "f7039d22fa42daa3e57553db3807c933");

    // Cette constante est essentielle au bon fonctionement de l'app,
    // elle indique le dossier rassemblant toutes les librairies php, js et le thème css
    // (tout ce qui est inclue d'une façon ou d'une autre)
    define("INC_DIR", "../");
    // le répertoire qui contient les contenus utilisateur
    define("CONTENT_DIR", "../../content/");
    
    // le coeur de l'application, c-a-d toute ce qu'il faut charger
    // ou définir avant de commencer à travailler...
    require_once(INC_DIR."init.core.php");

    // bloque les includes
    exit();
?>

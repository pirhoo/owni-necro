<?php

        /** func.lang.php
        * @author Pierre Romera - pierre.romera@gmail.com
        * @version 1.0
        * @desc Outils pour le multi-languisme de l'application
        */


        // Nous plaçons cette condition au début de chaque includes
        // elle garanti l'inclusion depuis les fichiers autorisés
        // (fichiers qui définissent la constante avec la bonne valeur)
        if(SAFE_PLACE != "f7039d22fa42daa3e57553db3807c933") die();


	/** Function __
	* @desc Consulte le fichier langue (s'il n'est pas déjà chargé et retourne la ligne demandée
	* @param: $index : Numéro de la ligne du fichier à afficher (commence à 0)
        * @param: $display : Boolean indique si la valeur de la ligne doit-être affiché
	* @return ligne du fichier à afficher
	*/
        function __($index, $display = true) {

            // variable static pour ne pas charger le fichier 2 fois
            static $file;

            // on a pas encore chargé le fichier
            if( empty($file) ) {

                $file = Array();
                // ouvertue du fichier
                $lines = file(LANG_DIR.LANG.'.lang');

                // lecture du fichier ligne par ligne
                foreach ($lines as $lineNumber => $lineContent)
                        $file[] = $lineContent;
            }

            $str = $file[$index];
            
            $str = str_replace(CHR(10),"",$str);
            $str = str_replace(CHR(13),"",$str);

            if($display) echo $str;
            
            return $str;

        }


	/** Function defineLang
	* @desc Définie la langue à utiliser par l'application (et donc le fichier à utiliser)
	* @param: $lang : (facultatif) La langue de l'application
	* @return null
	*/
        function defineLang($lang = null) {

            // langue disponible
            $lang_dispo = getLangDispo();


            if($lang == null) {

                if(isset($_SESSION['lang']) && @in_array($_SESSION['lang'], $lang_dispo))
                    define("LANG", $_SESSION['lang']);
                else
                    define("LANG", "FR_fr");

            } else {

                if( ! @in_array($lang, $lang_dispo) )
                    $lang = "FR_fr";

                define("LANG", $lang);
                $_SESSION['lang'] = $lang;

            }

            // id de la langue
            define("LANG_ID", array_search(LANG, getLangDispo() ) );
                
        }

        function getLangDispo() {
            return Array("FR_fr", "EN_en", "AR_ar");
        }

        function _LANGEX($str, $key = LANG_ID) {
            $a = explode(",", $str);
            return $a[$key];
        }
?>

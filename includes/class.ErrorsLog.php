<?php

// Nous plaçons cette condition au début de chaque includes
// elle garanti l'inclusion depuis les fichiers autorisés
// (fichiers qui définissent la constante avec la bonne valeur)
if(SAFE_PLACE != "f7039d22fa42daa3e57553db3807c933") die();

/**
 * Description of classErrorsLog
 *
 * @author pirhoo
 */
class ErrorsLog {

    private $log;

    public function __construct() {
        if(isset($_GET["err_log"])) {
            $errs = explode(",", $_GET["err_log"]);
            foreach($errs as $e) {
                $err = explode(":", $e);
                $this->add($err[0], $err[1]);
            }
        }
    }
    
    public function add($error, $type = 0) {
        // le numéro de chaque erreur correspond à une ligne dans le fichier de langue
        // le type 0 := negatif, 1 := positif
        if(is_numeric($error) && is_numeric($type))
            $this->log[] = Array("ID" => $error, "type" => $type);
    }

    public function getLog() {
        return $this->log;
    }

    // génère un paramètre d'URL contenant le code de chaque erreur
    public function doUrl() {
        
        $url = "";
        $i = 0;
        
        foreach($this->log as $log) {
            if($i++ > 0)
                $url .= ",";

            $url .= $log["ID"].":".$log["type"];
        }

        return $url != "" ? "err_log=".$url : "";
        
    }

    // retourne la phrase correspondant à l'erreur dans le fichier de langue
    public function getLogMsg($index) {
        return __($index, false);
    }

    public function hasErr() {
        return count($this->log) > 0 ;
    }
}
?>

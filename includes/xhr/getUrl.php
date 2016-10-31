<?php

    /** getUrl.php
    * @author Pierre Romera - pierre.romera@gmail.com
    * @version 1.0
    * @desc Émule le CURL pour javascript
    */

    @header('Content-Type: text/html; charset=UTF-8');
    echo file_get_contents($_REQUEST['url']);
?>

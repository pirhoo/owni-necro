<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author pirhoo
 */

require_once('class.OAuth.php');
require_once('class.TwitterOAuth.php');

class TWconnect {


    function __construct($key, $secret, $callback_url = NULL) {

        $this->key = $key;
        $this->secret = $secret;
        $this->callback_url = $callback_url;

        $this->connexion();

        if(isset($_REQUEST["TW_action"]))
            switch($_REQUEST["TW_action"]) {
                    case "log_TRY":
                        $this->requestLogin();
                        break;
            }
    }


    public function connexion() {

        if($this->isLoggedOnTwitter == null) {

            if( isset($_SESSION) ) @session_start();

            $this->isLoggedOnTwitter = false;

            if (!empty($_SESSION['access_token'])
             && !empty($_SESSION['access_token']['oauth_token'])
             && !empty($_SESSION['access_token']['oauth_token_secret'])) {
                    // On a les tokens d'accès, l'authentification est OK.

                    $access_token = $_SESSION['access_token'];

                    /* On créé la connexion avec twitter en donnant les tokens d'accès en paramètres.*/
                    $this->TWoAuth = new TwitterOAuth($this->key, $this->secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

                    /* On récupère les informations sur le compte twitter du visiteur */
                    $this->twitterInfos = $this->TWoAuth->get('account/verify_credentials');
                    $this->isLoggedOnTwitter = true;

            } elseif(isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] === $_REQUEST['oauth_token']) {

                    // Les tokens d'accès ne sont pas encore stockés, il faut vérifier l'authentification

                    /* On créé la connexion avec twitter en donnant les tokens d'accès en paramètres.*/
                    $this->TWoAuth = new TwitterOAuth($this->key, $this->secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

                    /* On vérifie les tokens et récupère le token d'accès */
                    $access_token = $this->TWoAuth->getAccessToken($_REQUEST['oauth_verifier']);

                    /* On stocke en session les token d'accès et on supprime ceux qui ne sont plus utiles. */
                    $_SESSION['access_token'] = $access_token;

                    if (200 == $this->TWoAuth->http_code) {
                            $this->twitterInfos = $this->TWoAuth->get('account/verify_credentials');
                            $this->isLoggedOnTwitter = true;
                    }
                    else
                        $this->isLoggedOnTwitter = false;


            }
            else
                $this->isLoggedOnTwitter = false;

        }
    }


    public function isConnected() {
        return $this->isLoggedOnTwitter;
    }
    
    public function requestLogin() {

            if( isset($_SESSION) ) @session_start();

            /* Créer une connexion twitter avec les accès de l'application */
            $this->connexion();

            /* On demande les tokens à Twitter, et on passe l'URL de callback */
            if ($_SERVER['HTTP_REFERER'] != "")
               $request_token = $this->TWoAuth->getRequestToken($_SERVER['HTTP_REFERER'].( preg_match("#\?#", $_SERVER['HTTP_REFERER']) ? "&" : "?")."TW_action=log_SUCCESS");
            else
               $request_token = $this->TWoAuth->getRequestToken($this->callback_url.( preg_match("#\?#", $this->callback_url) ? "&" : "?")."TW_action=log_SUCCESS");
            
            /* On sauvegarde le tout en session */
            $_SESSION['oauth_token'] = $token = $request_token['oauth_token']['oauth_token'];
            $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

            /* On test le code de retour HTTP pour voir si la requête précédente a correctement fonctionné */
            switch ($this->TWoAuth->http_code) {

                case 200:
                    /* On construit l'URL de callback avec les tokens en params GET */
                    $url = $this->TWoAuth->getAuthorizeURL($token);
                    header('Location: ' .$url);
                    break;

                default:
                    echo $this->TWoAuth->http_code.': Impossible de se connecter à twitter ... Merci de renouveler votre demande plus tard.';
                    break;

            }
    }

    public function doTWhoot() {       
        if(isset($_GET["TW_action"]) && $_GET["TW_action"] == "log_SUCCESS")
            echo "<script type='text/javascript'>
                        if(typeof window.opener.TWonLogin == 'function')
                            window.opener.TWonLogin();
                            
                        window.close();
                  </script>";
        
        else  { ?>

            <script type="text/javascript">
                if(typeof openTwitterLogin  != 'function') {
                    var TWlogin;

                    function openTwitterLogin() {
                        TWlogin = window.open("?TW_action=log_TRY", "TWlogin", "width=800,height=430");
                        return false;
                    }
                    
                }
            </script>

        <?php }
    }


    public function doButton($srcImage = "http://a0.twimg.com/images/dev/buttons/sign-in-with-twitter-l.png") {        
        echo "<a href='?TW_action=log_TRY' onclick='javascript:return openTwitterLogin();' target='_blank'><img src='".$srcImage."' alt='S'identifier avec twitter' /></a>";
    }

    public function doPublish($message) {

        $this->TWoAuth = new TwitterOAuth($this->key, $this->secret, $_SESSION['access_token']['oauth_token'],  $_SESSION['access_token']['oauth_token_secret']);
        $this->twitterInfos = $this->TWoAuth->get('account/verify_credentials');

        if (200 == $this->TWoAuth->http_code) {
            $parameters = array('status' => $message);
            return $this->TWoAuth->post('statuses/update', $parameters);
        }
    }

    public function getUser() {
        // ifnos disponibles http://twitter.com/users/show.xml?screen_name=piroo
        $this->isConnected();
        return $this->twitterInfos;
    }


    /* @var $TWoAuth TwitterOAuth */
    protected  $TWoAuth;

    protected  $key;
    protected  $secret;
    protected  $callback_url;

    protected  $twitterInfos;
    protected  $isLoggedOnTwitter = null;
}
?>

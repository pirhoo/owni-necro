<?php

    /** init.share.php
    * @author Pierre Romera - pierre.romera@gmail.com
    * @version 1.0
    * @desc Charge les boutons de partage de l'application.
    */

    // Nous plaçons cette condition au début de chaque includes
    // elle garanti l'inclusion depuis les fichiers autorisés
    // (fichiers qui définissent la constante avec la bonne valeur)
    if(SAFE_PLACE != "f7039d22fa42daa3e57553db3807c933") die();
    
    define("DOC_URL"   , get_appinfo("doc_app") );
    define("DOC_TITLE" , "[APP] In memoriam: ".get_appinfo("nom_defunt"));
    define("DOC_TWUSER", "france24");
    define("FB_FAN", "http://www.facebook.com/france24");
?>

<script type="text/javascript">
    
    // Affiche le code d'embed pour les apps
    function doEmbed() {
        $("#mask").fadeIn(500);
        $(".inputEmbed").fadeIn(500);

        // cache l'embed au click sur le masque
        $("#mask").click(function () {
            $(".inputEmbed").fadeOut(500);
        });
    }


    function showFooter() {

        keepFooterOpen = true;

        if( RR_UTILS.isApple() ) {
            $(".showFooter").stop().css({marginTop:-100});
        } else
            $(".showFooter").stop().animate({marginTop:-100}, 500);
    
    }

    function hideFooter() {

        keepFooterOpen = false;
            
        if( RR_UTILS.isApple() )
            $(".showFooter").stop().css( {marginTop:-30} );
        else {
            setTimeout( function () {
                if(!keepFooterOpen)
                    $(".showFooter").stop().animate({marginTop:-30}, 500);
            }, 3000);
        }
    }

    var keepFooterOpen = false;

    $(".showFooter .trigger").mouseenter(function () { showFooter(); });
    $("#footer").mouseenter(function () { showFooter(); });
        
    $("#workspace").mouseenter(function () {  hideFooter(); });
    $("#app").mouseleave(function () {  hideFooter();  });


    if( RR_UTILS.isApple() )  {
        $(".showFooter .trigger").click(function () { showFooter(); });
        $("#footer").click(function () { showFooter(); });
        $("#workspace").click(function () {  hideFooter(); });
    }
    
 </script>


<!-- Logo de OWNI, idéalement toujours présent sur les app -->
<a href="<?php echo DOC_URL; ?>" class="powered"><img src="<?php echo THEME_DIR."img/logo.png"; ?>" alt="" /></a>


<div class="sharing">
    
    <a class="share mini-share-mail bg-white"
       target="_blank"
       title="<?php __(4); ?>"
       href='http://www.addtoany.com/email?linkurl=<?php echo  rawurlencode(DOC_URL);  ?>&linkname=<?php echo   rawurlencode(DOC_TITLE);  ?>&t=<?php echo rawurldecode(DOC_TITLE); ?>'>
        <img alt="share mail" src="<?php echo THEME_DIR."img/mini-email.png"; ?>" /> email
    </a>

    <a class="share mini-embed bg-white"
       href="#"
       title="<?php __(5); ?>"
       onclick="doEmbed()">
        &lt;integrer&gt;
    </a>
    
    <span class="share twitter"
          title="<?php __(2); ?>">
        <a href="http://twitter.com/share" 
           class="twitter-share-button"
           data-url="<?php echo DOC_URL; ?>"
           data-text="<?php echo DOC_TITLE; ?>"
           data-count="horizontal" 
           data-via="<?php echo DOC_TWUSER; ?>">Tweet</a>
        <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
    </span>
    
    <a class="share facebook" 
       title="<?php __(3); ?>"
       name="fb_share"
       type="button-count"
       share_url="<?php echo DOC_URL;  ?>"
       href="http://www.facebook.com/sharer.php">Partager</a>
    <script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>


    <div class="center inputEmbed">
        <label>
            <?php __(7); ?><br />
            <input value='<object src="<?php echo APP_URL; ?>" type="text/html" style="height:667px;990px;"></object>' class="codeEmbed text" id="codeEmbedFrame" />
        </label>
        <input onclick="RR_UTILS.copier( document.getElementById('codeEmbedFrame') )"
                   type="button"
                   value="Copier"
                   class="addTitle copier"
                   title="<?php __(9); ?>" />

        <br /><br /><br />

        <label>
            <?php __(8); ?><br />
            <input value='<a href="<?php echo APP_URL; ?>" target="_blank"><img src="<?php echo APP_URL; ?>includes/style/img/apercu_<?php echo LANG; ?>.jpg" alt="" /></a>' class="codeEmbed text" id="codeEmbed" />
        </label>
        <input onclick="RR_UTILS.copier( document.getElementById('codeEmbed') )"
                   type="button"
                   value="Copier"
                   class="addTitle copier"
                   title="<?php __(9); ?>" />
        <br /><br /><br />
    </div>

    <span class="share"  title="<?php __(6); ?>">
        <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo FB_FAN; ?>&amp;layout=button_count&amp;show_faces=true&amp;width=160&amp;action=recommend&amp;font=verdana&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:160px; height:21px;" allowTransparency="true"></iframe>
    </span>
    

</div>
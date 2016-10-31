function Necroapp(incDir, contentDir, xhrDir, category) {

    this.incDir = incDir;
    this.xhrDir = xhrDir;
    this.contentDir = contentDir;
    this.articles = null;
    this.category = category;
    this.slideIndex = 0;

    var app = this;
    // on reçoit les données
    $.getJSON(xhrDir+"ajax-switch-action.php?action=get_JSON_articles&category="+category, function (data) {

        app.articles = data;
        
        // on dessine l'app
        // ----------------

        // les conteners
        $("#content").html("<div class='article'></div>\n\
                            <div class='ctrls'>\n\
                                <div class='previous'></div>\n\
                                <div class='next'></div>\n\
                            </div>\n\
                            <div class='thumb-overflow'>\n\
                                <ul class='thumb-list'></ul>\n\
                            </div>")

        // la liste des miniatures
        for(var index in app.articles) {
            var content = "";

            if(app.articles[index].article_mediatype == "video")
                content += "<div class='thumb-yt-overflow'><img src='http://i1.ytimg.com/vi/" + app.articles[index].article_media + "/default.jpg' style='width:130px;height:97px;' /></div>";
            else if(app.articles[index].article_mediatype == "image" && app.articles[index].article_media != "")
                content += "<img src='" + app.incDir + "do.thumb.php?src=" + app.contentDir + "img/"+ app.articles[index].article_media + "&w=130&h=75' style='width:130px;' />";
            else if(app.articles[index].article_mediatype == "vf24" || app.articles[index].article_mediatype == "ina") {
                if (app.articles[index].media_preview == "1" && app.articles[index].ID != "" ) {
                    content += "<img src='" + app.contentDir + "/img/prev/"+app.articles[index].ID+".jpg' style='width:130px;' />";
                }
                else {
                    content += "<img src='" + app.incDir + "style/img/movie-thumb.png' style='width:130px;' />";
                }
            } else {
                content += "<img src='" + app.incDir + "style/img/movie-thumb.png' style='width:130px;' />";
            }       

            content += app.articles[index].article_name;
            
            $(".thumb-list").append("<li>"+content+"</li>");

        }


        // supprime tous les évènements existants sur les miniatures
         $(".thumb-list li").unbind("click").click(function(index) {
             if(!$(this).hasClass("actif"))
                app.drawArticle( $(this).index(), $(this) );
         });

         $(".ctrls .next").click(function () {
             $(".thumb-list li:eq("+(app.slideIndex+1)+")").trigger("click");
         });

         $(".ctrls .previous").click(function () {
             $(".thumb-list li:eq("+(app.slideIndex-1)+")").trigger("click");
         });
         
         $(".thumb-list li:eq(0)").trigger("click");



    });


    this.updateThumbs = function () {

        var app = this;

        // affiche tous les <li>
        $(".thumb-list li").stop().animate({visibility:"visible", opacity:1},400);

        // cache les <li> qu'on ne veut pas
        $(".thumb-list li").each(function (index) {

            if(index + 1 < app.slideIndex && index < $(".thumb-list li").length - 4)
                $(this).stop().animate({visibility:"hidden", opacity:0},400);
            
            if(index >= app.slideIndex + 3)
                $(this).stop().animate({visibility:"hidden", opacity:0},400);

            if(index < app.slideIndex - 1)
                $(this).stop().animate({visibility:"hidden", opacity:0},400);
        });
        

        // fait glisser les <li>
        $(".thumb-list").stop().animate({marginLeft:-145*(app.slideIndex-1)}, 500);

        // bloque le premier
        if(app.slideIndex == 0) {
            $(".thumb-list").stop().animate({marginLeft:0}, 400);
            $(".thumb-list li:eq(3)").stop().animate({visibility:"visible", opacity:1},400);
            $(".ctrls .previous").stop().addClass("gray");

            if( $(".thumb-list li").length == 0 )
                $(".ctrls .next").stop().addClass("gray");
            
        } else {
            $(".ctrls .previous").stop().removeClass("gray");

            if(app.slideIndex == $(".thumb-list li").length - 1) 
                $(".ctrls .next").stop().addClass("gray");
            else
                $(".ctrls .next").stop().removeClass("gray");

            
        }

    };
    
    this.drawArticle = function (index, toggler) {

        $(".thumb-list li").removeClass("actif");
        $(toggler).addClass("actif");
        
        var app = this;

        app.slideIndex = index;

        // réduit la liste des miniatures à seulement les 4 premières
        app.updateThumbs();
        
        // struture
        var content = "<div class='coll-l'></div>\n\
                       <div class='coll-r'>\n\
                            <h2></h2>\n\
                            <div class='subtitle'></div>\n\
                            <div class='brief'>\n\
                            </div>\n\
                            <a class='more' target='_blank'>"+_more+"</div>\n\
                       </div>\n\
                       <br style='clear:both' />";
        
        $("#content .article").html(content);

        // media
         if(app.articles[index].article_mediatype == "video")
            $("#content .article .coll-l").html('<iframe title="YouTube video player" class="youtube-player" type="text/html" width="260" height="215" src="http://www.youtube.com/embed/'+app.articles[index].article_media+'" frameborder="0"></iframe>');
        else if(app.articles[index].article_mediatype == "image")
            $("#content .article .coll-l").html("<img src='" + app.incDir + "do.thumb.php?src=" + app.contentDir + "img/"+ app.articles[index].article_media + "&w=260&h=215' style='width:260px;' />");
        else if(app.articles[index].article_mediatype == "vf24")
            $("#content .article .coll-l").html('<object height="215" width="260" style="visibility: visible;" data="http://www.france24.com/fr/sites/all/modules/maison/aef_player/flash/player.swf" name="player-node-4333749" id="player-node-4333749" type="application/x-shockwave-flash" > <param value="file='+ app.articles[index].article_media.replace('&quot;', '"') +'&image=Image&autostart=0&id=player-node-4333749&amp;skin=http://www.france24.com/fr/sites/france24.com.fr/modules/maison/france24_player/flash/skin_f24/skin_f24.swf" name="flashvars"/><param value="http://www.france24.com/fr/sites/all/modules/maison/aef_player/flash/player.swf" name="src"/><param value="true" name="allowfullscreen"/><param value="always" name="allowscriptaccess"/><param value="transparent" name="wmode"/></object>');
        else if(app.articles[index].article_mediatype == "ina")
            $("#content .article .coll-l").html('<object height="215" width="260" style="visibility: visible;" data="index.php?action=ina&id=' + app.articles[index].ID + '" /><param value="transparent" name="wmode" /></object>');

 
        var date_en = app.articles[index].article_date;
        date_en = date_en.replace(/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/, "$2/$1/$3");
        
        // description
         $("#content .article .coll-r h2").html(app.articles[index].article_name);
         $("#content .article .coll-r .author").html(app.articles[index].article_author);
         $("#content .article .coll-r .subtitle").html("<span class='date'>" + date_en + "</span>");
         if( app.articles[index].article_date != "" && app.articles[index].article_author != "")
             $("#content .article .coll-r .subtitle").append(" - ");
         $("#content .article .coll-r .subtitle").append("<span class='author'>" + app.articles[index].article_author + "</span>");
         $("#content .article .coll-r .brief").html(app.articles[index].article_content);
         $("#content .article .coll-r .more").attr("href", app.articles[index].article_source);



    };

}
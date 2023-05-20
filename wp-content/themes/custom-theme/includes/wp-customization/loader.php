<?php

add_action( 'after_body_open_tag', 'tu_show_loader' );
function tu_show_loader() {

    do_action('after_show_loader');

    if(is_home()){
        include_once(TEMPLATE_PATH.'/partials/loader.php');
    }

    ?>
    <?php /* ?>
    <div id="tu_loader">
        Loading...
    </div>
    <style>
        #tu_loader{
            background: rgba(0,0,0,0.8);
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 999999999;
            visibility: visible;
            opacity: 1;
            transition: visibility 0s, opacity 0.5s linear;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
    </style>
    <script type="text/javascript">
        setTimeout(function(){

            document.getElementById("tu_loader").style.visibility = "none";
            document.getElementById("tu_loader").style.opacity = 0;

            setTimeout(function(){
                document.getElementById("tu_loader").style.display = "none";
            }, 500);
        }, 2000);
    </script>
    <?php */ ?>
    <?php
}

add_action('after_show_loader', 'tu_after_show_loader');
function tu_after_show_loader(){

    $loader_src_json = json_encode($GLOBALS['loader_src']);

    ?>

    <noscript id="deferred-styles">
        <?php foreach($GLOBALS['loader_src']['stylesheets'] as $src): ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $src; ?>"/>
        <?php endforeach; ?>
    </noscript>

    <script type="text/javascript">

        var loader_src_json = JSON.parse('<?php echo $loader_src_json; ?>');

        for(var src_index in loader_src_json.scripts){

            var s = document.createElement("script");
            s.type = "text/javascript";
            s.src = loader_src_json.scripts[src_index];
            document.body.appendChild(s);
        }

        //Tham khảo: http://stackoverflow.com/questions/14644558/call-javascript-function-after-script-is-loaded
        var loadDeferredStyles = function() {
            var addStylesNode = document.getElementById("deferred-styles");
            var replacement = document.createElement("div");
            replacement.innerHTML = addStylesNode.textContent;
            document.body.appendChild(replacement)
            addStylesNode.parentElement.removeChild(addStylesNode);
        };
        var raf = requestAnimationFrame || mozRequestAnimationFrame ||
            webkitRequestAnimationFrame || msRequestAnimationFrame;
        if (raf) raf(function() { window.setTimeout(loadDeferredStyles, 0); });
        else window.addEventListener('load', loadDeferredStyles);

    </script>
    <?php
}

add_action('hide_loader', 'tu_hide_loader');
function tu_hide_loader(){
    ?>
    <script type="text/javascript">

        jQuery(document).ready(function($){
            $('#tu_loader').fadeOut(500);
        });
    </script>
    <?php
}

add_action('hide_loader', 'tu_enqueue_callback_functions');
function tu_enqueue_callback_functions(){

}

add_filter( 'script_loader_src', 'tu_script_loader_src' );
function tu_script_loader_src($url)
{
    if(strpos($url, 'jquery.js') > -1){
        return $url;
    }else{
        $GLOBALS['loader_src']['scripts'][] = $url;
    }
}

add_filter( 'style_loader_src', 'tu_style_loader_src' );
function tu_style_loader_src($url)
{
    $GLOBALS['loader_src']['stylesheets'][] = $url;
}

add_filter( 'clean_url', function( $url )
{
    if(strpos($url, 'jquery.js') == -1 && strpos($url, '.js') > -1){
        return "$url' async='async";
    }

    return $url;
}, 11, 1 );
<?php
/*
This file shall contain various miscellaneous functions
*/

/*
function for creating tweet button
*/
function childtheme_create_tweet_button($args){
    $default = array(
        'multiple'      => false,                               //if using more than one tweet buttons on same page
                                                                //not working yet
        'url'           => 'http://deadlinemedia.ca',           //URL of the page to share
        'text'          => 'An awesome post from ',             //The text of the tweet
        'via'           => 'deadlinemediaca',                   //from who?
        'related'       => array('charlesolaes',
                            'SpencercHart'),                    //an array of usernames
        'count'         => 'vertical',                          //count box position
        'lang'          => 'en',                                //language
        'counturl'      => '',                                  //The URL to which your shared URL resolves to
        'type'          => 'iframe'                             //using javascript or iframe
    );
    
    //merge em'
    $new_args = array_merge($default, $args);
    
    $new_args = array_merge($new_args, array( 'related' => implode(':',$new_args['related']) ));
    
    extract($new_args, EXTR_PREFIX_ALL, 'tw');
    
    $url_query = '';
    
    //set the width and height of the twitter button
    switch($tw_count){
        case 'none':
            $width = '55';
            $height = '20';
            break;
        case 'vertical' :
            $width = '55';
            $height = '62';
            break;
        case 'horizontal' :
            $width = '110';
            $height = '20';
            break;
    }
    
    //generating html code for twitter button
    if($tw_type != 'iframe'){
        $twit_btn = '<a href="http://twitter.com/share" class="twitter-share-button"';
        
        //@todo: fix multiple
        if($tw_multiple){
            $twit_btn .= 'rel="me" rel="canonical"';
        }else{
            $twit_btn .= 'data-via="'.$tw_via.'"';
            $twit_btn .= 'data-url="'.$tw_url.'"';
        }
        
        $twit_btn .= '
        data-text="'.$tw_text.'"
        data-related="'.$tw_related.'"
        data-count="'.$tw_count.'"
        data-lang="'.$tw_lang.'"
        data-counturl="'.$tw_counturl.'"
        ';
        
        $twit_btn .= '>Tweet</a>';
        add_action('thematic_after','childtheme_tweet_scripts');
    }else{
        
        foreach($new_args as $key => $value){
            $url_query .= $key.'='.$value.'&amp;';
        }
       
        $iframe_url = 'http://platform.twitter.com/widgets/tweet_button.html?'.$url_query;
        $twit_btn = '<iframe allowtransparency="true" frameborder="0" scrolling="no"
        src="'.$iframe_url.'"
        style="width:'.$width.'px; height:'.$height.'px;"></iframe>';
    }
    
    $scirpt_url = 'http://platform.twitter.com/widgets.js';
    
    
    return $twit_btn;
    
}

function childtheme_tweet_scripts(){
    $scirpt_url = 'http://platform.twitter.com/widgets.js';
    echo '<script src="'.$scirpt_url.'"></script>';
}

/*
function for embedding videos in youtube with link
*/
function childtheme_embed_video( $args = array() ){
    
    //set default values
    $default = array(
        'src'       => '',          //the youtube web address, using url shortners wont work
        'width'     => '640',       //sets the width
        'height'    => '349',       //sets the height
        'privacy'   => false,       //uses old code with youtube-nocookie url
        'https'     => false,       //uses the https schema
        'hd'        => false,       //sets hd=1 query in url
        'use_old'   => false,       //uses old embed code
        'echo'      => true,        //automatically displays, if false, returns a string of embeded code
        'related_videos' => true    //sets rel = 0 query in url if set to false
    );
    
    //nothing
    $embed = '';
    
    //nada
    $additional_query = array();
    
    //merge em'
    $new_args = array_merge($default, $args);
    
    //for slightly better readability, I always disliked writing arrays with []
    extract($new_args, EXTR_PREFIX_ALL, 'yt');
    
    //set https
    $scheme = 'http';
    if($yt_https){
        $scheme = 'https';
    }
    
    //the url query that was attached, I'm not sure if adding this to the new iframe version will break anything
    if($yt_privacy || $yt_use_old ){
        $additional_query = array_merge($additional_query, array('fs' => '1', 'hl'=>'en_US'));
    }
    
    //set hd
    if($yt_hd){
        $additional_query = array_merge($additional_query, array('hd' => '1'));
    }
    
    //set related videos
    if(!$yt_related_videos){
        $additional_query = array_merge($additional_query, array('rel' => '0'));
    }
    
    //the less I have to concatinate the better, love ya PHP
    $aquery = http_build_query($additional_query, '', '&amp;');
    
    //???
    if(!empty($aquery)){
        $aquery = '?'.$aquery;
    }
    
    //parse url
    //I could extract this but... >_<
    $link = parse_url($yt_src);
    
    //Just incase someone enters a url like so http://www.youtube.com/embed/xxxxxxxxx
    //Which I doubt will happen, but who knows...
    if( preg_match("/embed/", $yt_src, $matches) ){
        $v = explode('/', $link['path']);
        //I'd better find a work around for this
        $v = $v[2];
        $new_link = $scheme.'://'.$link['host'].$link['path'].$aquery;
    }else{
        $query = $link['query'];
        parse_str($query, $queries);
        $v = $queries['v'];
        $new_link = $scheme.'://www.youtube.com/embed/'.$v.$aquery;
    }
    
    
    //set the url and init the embed code
    //ugly contactinating time
    //... the 7th if statement, so ugly
    if( $yt_use_old ){
        //the old
        $new_link = $scheme.'://www.youtube.com/v/'.$v.$aquery;
        
        $embed = '<object width="'.$yt_width.'" height="'.$yt_height.'">';
        $embed .= '<param name="movie" value="'.$new_link.'"></param>';
        $embed .= '<param name="allowFullScreen" value="true"></param>
        <param name="allowscriptaccess" value="always"></param>';
        $embed .= '<embed src="'.$new_link.'" type="application/x-shockwave-flash"
        allowscriptaccess="always" allowfullscreen="true"
        width="'.$yt_width.'" height="'.$yt_height.'"></embed></object>';
        
    }elseif( $yt_privacy  ){
        //the bad
        $new_link = $scheme.'://www.youtube-nocookie.com/v/'.$v.$aquery;
        
        $embed = '<object width="'.$yt_width.'" height="'.$yt_height.'">';
        $embed .= '<param name="movie" value="'.$new_link.'"></param>';
        $embed .= '<param name="allowFullScreen" value="true"></param>
        <param name="allowscriptaccess" value="always"></param>';
        $embed .= '<embed src="'.$new_link.'" type="application/x-shockwave-flash"
        allowscriptaccess="always" allowfullscreen="true"
        width="'.$yt_width.'" height="'.$yt_height.'"></embed></object>';
    }else{
        //and the new?
        $embed = '<iframe title="YouTube video player" width="'.$yt_width.'" height="'.$yt_height.'" src="'.
        $new_link.'" frameborder="0" allowfullscreen></iframe>';
        
    }
    
    //to display or not to display, that is the condition
    if($yt_echo){
        echo $embed;
    }else{
        return $embed;
    }
    
}
?>
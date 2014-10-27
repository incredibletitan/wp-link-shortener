<?php

/**
 * @author Pretender
 */


function get_links_from_posts(array $links_filter = null, array $post_filter = null )
{

    //Global WP DB connection
    global $wpdb;

    //Get all or by filter posts
    $query =
        "SELECT $wpdb->posts.* " .
            " FROM $wpdb->posts " .
            " WHERE $wpdb->posts.post_type = 'post' " .
            " AND $wpdb->posts.post_status = 'publish' ";

    if (null != $links_filter) {
        $likeStatement = '';

        $linksCount = count($links_filter);
        $isTheFirstStatementAchieved = false;  
        
        for ($i = 0; $i < $linksCount; $i++) {
            $urlRegex = "/^(https?:\/\/)?(([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*)\/?$/";
            $matches = array();
           
            //checking is it valid url 
            if (preg_match($urlRegex, $links_filter[$i], $matches)) {
                $matchedLink = $matches[2];

                //if it first valid link add to statement 'AND' and '('
                if (!$isTheFirstStatementAchieved ) {
                    $likeStatement .= " AND ($wpdb->posts.post_content LIKE '%" . $matchedLink . "%'";
                    $isTheFirstStatementAchieved  = true;
                } 
                // add 'OR'
                else {
                    $likeStatement .= " OR $wpdb->posts.post_content LIKE '%" . $matchedLink . "%'";
                }
            }

            //If it is last statement and it is not empty query add ')'
            if (($linksCount - 1) == $i && $isTheFirstStatementAchieved) {
                $likeStatement .= ') ';
            }
        }
        $query .= $likeStatement;
    }

    //Filtering by post id
    if (null != $post_filter) {
        $inStatement = " AND $wpdb->posts.ID  IN (";
        $postFilterCount = count($post_filter);

        for ($i = 0; $i < $postFilterCount; $i++) {
            if ($i != ($postFilterCount - 1)) {
                $inStatement .= $post_filter[$i] . ',';
            } else {
                $inStatement .= $post_filter[$i];
            }
        }
        $inStatement .= ') ';
        $query .= $inStatement;
    }
    $posts = $wpdb->get_results($query, OBJECT);
    $data = array();
    $dom = new DOMDocument();
    $wpURL = get_bloginfo('wpurl');

    foreach ($posts as $post) {
        if (empty($post->post_content)) {
            continue;
        }
        @$dom->loadHTML($post->post_content);
        $aTags = $dom->getElementsByTagName('a');

        foreach ($aTags as $a) {

            if (!$a->hasAttribute('href')) {
                continue;
            }
            $link = $a->getAttribute('href');
           
            if (substr($link, 0, 1) == '/'
                || substr($link, 0, 1) == '#'
                || substr($link, 0, 6) == 'mailto'
                || substr($link, 0, strlen( $wpURL)) == $wpURL) {

                continue;
            }

            //Filtering link using filter list
            foreach ($links_filter as $fitered_link) {
                if (strpos($link, $fitered_link) !== false) {
                    $data[$post->ID]['links'][] =  $link;

                    break;
                }
            }


        }
    }

    return $data;
}

/**
 * replace all links specified in $replacingList in the post
 * @param  int $postId Id of the wp post
 * @param  array $replacingList associative multidimensional array which contains long and shorten urls. e.g array(array('long_link' => 'short_link'))
 */
function replace_long_by_shorten_links($postId, $replacingList) 
{
    global $wpdb;

    $post = get_post($postId);
    $post_content = $post->post_content;

    foreach ($replacingList as $replaceContent) {
        $post_content = str_replace($replaceContent['source_link'], $replaceContent['result_link'], $post_content);
    }    
    $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET post_content = %s WHERE ID = %d", $post_content, $postId ) );
}

function shorten_url($url)
{
    require_once('libs/GoogleUrlApi.php');
    $key = get_option('google_api_key_settings');
    $shortenerApi = new GoogleUrlApi($key);

    //stub for proxy
    if (strpos($_SERVER['SERVER_NAME'], 'fmt') !== false) {
        $shortenerApi->setProxy('pretender:KTq94LsLcX@192.168.5.111:3128');
    }

    if ($url) {
        return $shortenerApi->shorten($url);
    }

    return false;
}









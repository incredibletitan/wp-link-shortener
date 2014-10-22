<?php

/**
 * @author Pretender
 */


function get_links_from_posts()
{

    //Global WP DB connection
    global $wpdb;

    //Get all or by filter posts
    $query =
        "SELECT $wpdb->posts.* " .
            "FROM $wpdb->posts " .
            "WHERE $wpdb->posts.post_type = 'post'" .
            "AND $wpdb->posts.post_status = 'publish'";

    $posts = $wpdb->get_results($query, OBJECT);
    $data = array();
    $dom = new DOMDocument();
    $wpURL = get_bloginfo('wpurl');

    foreach ($posts as $post) {
        if (empty($post->post_content)) {
            continue;
        }
        $dom->loadHTML($post->post_content);
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
            $data[$post->ID]['links'][] =  $link;
            
        }
    }

    return $data;
}

function shorten_url($url)
{
    require_once('libs/GoogleUrlApi.php');
    $key = 'AIzaSyCDCVUrfj5LYpSSJEqyxFhJqfEQokIyw1E';

    $shortenerApi = new GoogleUrlApi($key);
    $shortenerApi->setProxy('pretender:KTq94LsLcX@192.168.5.111:3128');

    return $shortenerApi->shorten($url);
}









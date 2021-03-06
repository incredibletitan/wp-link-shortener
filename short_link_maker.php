<?php
/*
<<<<<<< HEAD
Plugin Name: Post urls shortener
=======
Description: Replaces specified external links by short links
Version: 1.0
Author: Pretender
Author URI: Pretender.fmt
License: GPL2

Copyright 2014 Yuriy Stos (email : 100sbsh@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function ajax_shortener()
{

    wp_register_script('shortener_script', WP_PLUGIN_URL . '/short_link_maker/js/ajax_shortener.js');
    wp_register_script('initial_script', WP_PLUGIN_URL . '/short_link_maker/js/init.js');
    wp_localize_script('initial_script', 'ajax_obj', array('ajaxurl' => plugins_url() . '/short_link_maker/ajax.php'));
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script('initial_script' );
}


function short_link_menu()
{
    if (function_exists('add_menu_page')) {
        add_menu_page('Replace link by it\'s short name', 'URL shortener', 'administrator', 'short_link_maker\view.php');
    	add_submenu_page('short_link_maker\view.php', 'Settings', 'Settings', 'administrator', 'short_link_maker\options.php');
    }
}


add_action('admin_menu', 'short_link_menu');
add_action('init', 'ajax_shortener');
?>

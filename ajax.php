<?php
//todo find way to load $wpdb more correctly
include_once('../../../wp-load.php');
include_once("replace.php");

if (isset($_POST['action'])) {
	switch($_POST['action']) {
		case 'get_links':
			echo json_encode(get_links_from_posts());

			break;
		case 'shorten':
			if (isset($_POST['link'])) {
				$link = trim($_POST['link']);
			
				echo json_encode(array(
					'source_link' => $link, 'result_link' => shorten_url($link)
					)
				);							
			}

			break;

	}
}

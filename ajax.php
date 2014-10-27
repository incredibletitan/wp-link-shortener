<?php
include_once('../../../wp-load.php');
include_once("replace.php");

if (isset($_POST['action'])) {
	switch($_POST['action']) {
		case 'get_links':
			
			if (isset($_POST['links_list'])) {
				$filterLinksList = $_POST['links_list'];
				
				echo json_encode(get_links_from_posts($filterLinksList));
			}

			break;
		case 'shorten':
			if (isset($_POST['link'])) {
				$link = trim($_POST['link']);
				$shortenedUrl = shorten_url($link);

				if (!$shortenedUrl) {
					echo 'Cannot shorten url : ' . $link;

					exit;
				}

				echo json_encode(array(
					'source_link' => $link, 'result_link' => shorten_url($link)
					)
				);							
			}

			break;
		case 'replace':
			if (isset($_POST['data'])) {
				$result = $_POST['data'];
				replace_long_by_shorten_links($result['postId'], $result['links']);
			}

	}
}

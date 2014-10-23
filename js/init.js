/**
 * User: Pretender
 * Date: 19.09.14
 * Time: 14:59
 */

jQuery(document).ready(function() {
	jQuery('#shortener-form > div > input.process').click(function(){

		//Get all links 
		jQuery.ajax({

			//url which passed withing wp_localize_script in short_link_maker.php file
			url: ajax_obj.ajaxurl,
			type: 'POST',
			data: {	action: 'get_links'	},

			//Shorten links
			success: function(data) {
				dataJson = jQuery.parseJSON(data);
				jQuery.each(dataJson, function(postId, postLinksData) {

				var processedLinks = {postId : postId};
				processedLinks.links = [];
				 

					jQuery.each(postLinksData.links, function(i, link){
						
						jQuery.ajax({
							async: false,
							url: ajax_obj.ajaxurl,
							type: 'POST',
							data: {
								action: 'shorten',
								link: link
							},
							
							success: function(shortenData) {
								var processedLink = jQuery.parseJSON(shortenData);
								processedLinks.links.push(processedLink);
							}
						});
					});

					//Send request for replace long links by shorten
					jQuery.ajax({
							url: ajax_obj.ajaxurl,
							type: 'POST',

							data: {
								action: 'replace',
								data: processedLinks
							},
							
							success: function(result) {
								console.log(result);
							}, 
						});
				});
			}
		});
	});
});

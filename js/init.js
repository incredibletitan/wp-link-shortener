/**
 * User: Pretender
 * Date: 19.09.14
 * Time: 14:59
 */

jQuery(document).ready(function() {
	jQuery('#shortener-form > div > input.process').click(function(){
		jQuery.ajax({
			url: ajax_obj.ajaxurl,
			type: 'POST',
			data: {	action: 'get_links'	},
			
			success: function(data) {
				dataJson = jQuery.parseJSON(data);

				jQuery.each(dataJson, function(postId, postLinksData) {
					processedLinks = {postId : postId, links : []};

					jQuery.each(postLinksData.links, function(i, link){
						
						jQuery.ajax({
							url: ajax_obj.ajaxurl,
							type: 'POST',
							data: {
								action: 'shorten',
								link: link
							},
							
							success: function(shortenData) {
								processedLink = jQuery.parseJSON(shortenData);
								processedLinks.links.push(processedLink);
							}
						});
					});

					console.log(processedLinks);
					processedLinks.links.slice(0);
				});
			}
		});
	});
});

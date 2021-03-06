/**
 * User: Pretender
 * Date: 19.09.14
 * Time: 14:59
 */

jQuery(document).ready(function() {
	filterLinksCounter = 1;

	jQuery('.delete').click(function(){

		if (filterLinksCounter <= 1) {
			return;
		}
		jQuery('.links-filter-container .filtered-link-container').last().remove();
		filterLinksCounter--;
	});

	jQuery('.duplicate').click(function(){
		clonedContainer = jQuery('.links-filter-container .filtered-link-container').first().clone();
		id = filterLinksCounter++;
		clonedInput = clonedContainer.find('.filter-link');
		clonedErrorSpan = clonedContainer.find('.error');
		clonedInput.val('');
		clonedInput.attr('id', 'filtered-link' + id);
		clonedErrorSpan.attr('id', 'filtered-link-error' + id);
		clonedErrorSpan.css('display', 'none');
		jQuery('.links-filter-container').append(clonedContainer);
	});

	jQuery('#shortener-form > div > input.process').click(function(){
		jQuery('.error').css('display', 'none');
		linksArr = [];
		regex = /^(https?:\/\/)?(([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*)\/?$/;
		isErrorExists = false;

		for (i = 0; i < filterLinksCounter; i++) {
			filterLink = jQuery('#filtered-link' + i);

			if (!regex.test(filterLink.val())) {
				jQuery('#filtered-link-error' + i).css('display', 'inline');
				isErrorExists = true;			
				continue;
			}
			linksArr.push(filterLink.val());
		}

		if (isErrorExists) {
			return;
		}
		jQuery('.preloader').css('display', 'block');
		jQuery('.shortener-container').css('display', 'none');

		//Get all links 
		jQuery.ajax({

			//url which passed withing wp_localize_script in short_link_maker.php file
			url: ajax_obj.ajaxurl,
			type: 'POST',
			data: {	action: 'get_links', links_list: linksArr},

			//Shorten links
			success: function(data) {

				try {
					dataJson = JSON.parse(data);
					
					if (dataJson.length < 1) {
						console.log('No links');
						jQuery('.preloader').css('display', 'none');
						jQuery('.shortener-container').css('display', 'block');		
						
						return;
					}
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

									try {
										var processedLink = jQuery.parseJSON(shortenData);
										processedLinks.links.push(processedLink);
									} catch (e) {
										jQuery('.preloader').css('display', 'none');
										jQuery('.shortener-container').css('display', 'block');
										console.log(e);
									}
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
									jQuery('.preloader').css('display', 'none');
									jQuery('.shortener-container').css('display', 'block');
								}, 
							});
					});	
				} catch (e) {
					jQuery('.preloader').css('display', 'none');
					jQuery('.shortener-container').css('display', 'block');
				}
			}
		});
	});
});

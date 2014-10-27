<?php if(get_option('google_api_key_settings') == ''):?>
	<h3 style="color:red">You need to specify Google API key in the settings to shorten URLs</h3>
<?php exit;?>
<?php endif;?>

<div class="wrap">
    <h2><?php echo $title; ?></h2>
</div><!--.wrap-->

<div class="preloader" style="display:none; margin-left:150px "><img style="background: none !important;" width="32px" height="32px" src="<?php echo plugins_url()?>/short_link_maker/images/preloader.gif"/></div>
<div class="shortener-container">
	<a href="#" class="duplicate">Add</a> <a href="#" class="delete">Remove</a>
	<br/>
	<form id="shortener-form" method="POST">
	    <div id="" style="float:left;width:100%;">
	        <div class="links-filter-container" style="margin-top:5px">
	            <div class="filtered-link-container" style="margin-bottom: 5px">
	                <input type="text" class="filter-link" id="filtered-link0" placeholder="link"><span id="filtered-link-error0" style="display:none; color:red; margin-left: 5px;" class="error">Incorrect URL format</span>
	            </div>
	        </div>
	        <input  type="button" class="button process" value="Process" />
	    </div>
	</form>
</div>

<!-- AIzaSyCDCVUrfj5LYpSSJEqyxFhJqfEQokIyw1E -->
<div class="wrap">
    <h2><?php echo $title; ?></h2>
</div><!--.wrap-->

<form id="shortener-settings-form" method="POST">
    <div id="" style="float:left;width:100%; margin-top: 5px; margin-bottom:10px;">
    	 <label for="google_api_key_settings">Google api key</label><br/>
    	 <input size="35" type="text" value="<?php echo (get_option('google_api_key_settings') !== false) ? get_option('google_api_key_settings') : ''; ?>" id="google_api_key_settings" name="google_api_key_settings" placeholder=""><br/>
    </div>
    <input type="submit" name="save_settings_btn" class="button process" value="Save" />	
</form>

<?php
	if (isset($_POST['save_settings_btn'])) {

		if (isset($_POST['google_api_key_settings'])) {
			if (get_option('google_api_key_settings') !== false) {
				update_option( 'google_api_key_settings', $_POST['google_api_key_settings'] );
			} else {
				add_option( 'google_api_key_settings', $_POST['google_api_key_settings'] );
			}
		}
	}
?>
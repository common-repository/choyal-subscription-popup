<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<div class="wrap">
	<h1>CSP Settings</h1>
	
	<hr>
	
	<form action="" method="post">
	
	<?php wp_nonce_field( 'csp-setting-security', 'csp-setting' ); ?>
	
	<h2 class="title">POPUP</h2>
		
		<table class="form-table">
		<tbody>
		
		<tr>
		<th scope="row"><label for="csp_popup_title">Popup Title</label></th>
		<td><input name="csp_popup_title" type="text" id="csp_popup_title" value="<?php echo esc_html( stripslashes( get_option('csp-popup-title') ) ); ?>" placeholder="Join our subscribers" class="regular-text code">
		</td>
		</tr>
		
		<tr>
		<th scope="row"><label for="csp_popup_text">Popup Text</label></th>
		<td>
			<textarea name="csp_popup_text" id="csp_popup_text" placeholder="Sign up here and we'll keep you in the loop on all things product" class="regular-text code" ><?php echo esc_textarea( stripslashes( get_option('csp-popup-text') ) ); ?></textarea>
		</td>
		</tr>
		
		<tr>
			<th scope="row"><label for="csp_popup_disable">Disable Popup</label></th>
			<td><input name="csp_popup_disable" type="checkbox" id="csp_popup_disable" value="yes" <?php if( sanitize_text_field( get_option('disable-popup') ) == 'yes' ){ echo 'checked'; } ?> ></td>
		</tr>
		
		<tr>
		<th scope="row"><label for="csp_popup_fname_lname">Disable First/Last Name field's in Subscription form</label></th>
		<td>
		<input name="csp_popup_fname_lname" type="checkbox" id="csp_popup_fname_lname" value="yes" <?php if( sanitize_text_field( get_option('disable-fname-lname') ) == 'yes' ){ echo 'checked'; } ?> >
		</td>
		</tr>
		
		<tr>
		<th scope="row"><label for="csp_popup_mailchimp_integration">Activate MailChimp Integration  </label></th>
		<td>
		<input name="csp_popup_mailchimp_integration" type="checkbox" id="csp_popup_mailchimp_integration" value="yes" <?php if( sanitize_text_field( get_option('mail-chimp-activate') ) == 'yes' ){ echo 'checked'; } ?>>
		<a href="https://mailchimp.com/" target="_blank" >
		<img src="<?php echo CSP_ASSETS_PATH. 'img/freddie_wink.svg'; ?>" width="20" height="20" alt="MailChimp logo" class="freddie-logo" style="margin: 0 0 -6px 0;" >
		</a>
		</td>
		</tr>
		
		</tbody></table>
	
	<hr>
	
	<div class="csp-mailchimp-setting"> 
	
	<h2 class="title">MAILCHIMP</h2>
	
		<p>To find mailchimp api key read <a href="http://kb.mailchimp.com/integrations/api-integrations/about-api-keys" target="_blank">Documentation mailchimp api key</a>.</p>

		<table class="form-table">
		<tbody><tr>
		<th scope="row"><label for="csp_mailchimp_api_key">Api Key</label></th>
		<td><input name="csp_mailchimp_api_key" type="text" id="csp_mailchimp_api_key" value="<?php echo trim( strip_tags( get_option('mail-chimp-api-key') ) ); ?>" placeholder="784f56edf1d36190750d5f617f22d917-us16" class="regular-text code">
		</td>
		</tr>
		<tr>
		<th scope="row"><label for="csp_mailchimp_list_id">List Id</label></th>
		<td>
		<input name="csp_mailchimp_list_id" type="text" id="csp_mailchimp_list_id" value="<?php echo trim( strip_tags( get_option('mail-chimp-list-id') ) ); ?>" placeholder="38beada2ad" class="regular-text code">
		</td>
		</tr>
		
		</tbody></table>
	
	<hr>
	
	</div>
	
	
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
	
	</form>
	
</div>

<script>
jQuery(document).ready(function(){
	
	if(jQuery('#csp_popup_mailchimp_integration').is(":checked")){   
		jQuery(".csp-mailchimp-setting").show();
	}else{
		jQuery(".csp-mailchimp-setting").hide();
	}
	
	jQuery('#csp_popup_mailchimp_integration').change(function(){
		
		if(jQuery('#csp_popup_mailchimp_integration').is(":checked")){   
			jQuery(".csp-mailchimp-setting").show();
		}else{
			jQuery(".csp-mailchimp-setting").hide();
		}
		
	});
	
});
</script>
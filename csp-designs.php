<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<div class="wrap">
	
	<h1 class="wp-heading-inline">Design Setting</h1>
	
	<hr class="wp-header-end">
	
	<form action="" method="post">
	
	<p class="submit"><input type="submit" name="submit" class="button button-primary" value="Save Changes"></p>
	
	<?php wp_nonce_field( 'csp-setting-security', 'csp-setting' ); ?>
	
	<table class="form-table">
		<tbody>
		
			<tr>
				<th scope="row"><label for="csp_popup_overlay">Show Background Overlay</label></th>
				<td><input name="csp_popup_overlay" type="checkbox" id="csp_popup_overlay" value="yes" <?php if( esc_html( get_option('csp-popup-overlay') ) == 'yes' ){ echo 'checked'; } ?> ></td>
				
				</td>
				<td></td>
			</tr>
			
			<tr>
				<th scope="row"><label for="csp_background_overlay_transparency">Background Overlay Transparency</label></th>
				<td style="width: 300px;" >Min 0.1 <input type="range" value="<?php if( floatval( get_option('csp-background-overlay-transparency') ) > 0 ){ echo floatval( get_option('csp-background-overlay-transparency') ); }else{ echo '0.4'; } ?>" id="csp_background_overlay_transparency" name="csp_background_overlay_transparency" min="0.01" max="1.0" step="0.01"> Max 1</td>
				</td>
				<td>
					<div class="csp-overlay-current-bk" >
						<div class="csp-overlay-current" ><?php if( floatval( get_option('csp-background-overlay-transparency') ) > 0 ){ echo floatval( get_option('csp-background-overlay-transparency') ); }else{ echo '0.4'; } ?></div>
					</div>
				</td>
			</tr>
			
		</tbody>
	</table>
	
	<hr>
	
	<table class="form-table">
		<tbody>
		
		<tr>
			<th scope="row"><label>Select Design for Popup Box </label></th>
		</tr>
		
		</tbody>
	</table>
	
	<div class="csp-success-alert">Design Selected - Pro Version Feature </div>
	<div class="csp-pro-alert">Pro Version Feature <a class="csp-btn-buy" href="https://google.com" target="_blank" ><img src="<?php echo CSP_ASSETS_PATH. 'img/button-pro.png'; ?>" ></a></div>

			
	<div class="csp-designs">
		
		<?php for( $i = 1; $i<10; $i++ ){ ?>
			
			<div class="csp-popup-design">
			
				<input type="radio" name="csp-popup-design" class="csp-popup-design" id="csp-popup-design-<?php echo $i; ?>" value="<?php echo $i; ?>" >
				<label for="csp-popup-design-<?php echo $i; ?>" ><img src="<?php echo CSP_ASSETS_PATH. 'img/popup-design.jpg'; ?>" ></label>
				
			</div>
			
		<?php } ?>
		
	</div>
	
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
	
	</form>
	
	<br class="clear">

</div>

<style>
.csp-overlay-current{
	
	background: black;
	padding: 17px;
	color:white;
	position:absolute;
	margin: 6px 0 0 6px;
	
	
}
.submit{
	
	text-align: right !important;
	margin: 0;
    padding: 0;
	
}
.csp-popup-design img{
	display: inline-block;
	width:100%;
}
.csp-popup-design {
	width: 33%;
    display: inline-block;
    margin: 2px 1px 0 1px;
}
.csp-designs{
	margin:5px 0;
}
.csp-pro-alert{
    background: #f77200;
    padding: 10px 15px;
    color: white;
	height: 33px;
	font-size: 22px;
    line-height: 28px;
}
.csp-btn-buy img{
	width: 220px;
	float: right;
}
input.csp-popup-design {
    margin: 0px;
    z-index: 99999999;
    position: absolute;
    border: 0;
    border-radius: 0;
    background: rgba(255, 255, 255, 0.15);
}
input.csp-popup-design:checked:before {
    width: 15px;
    height: 14px;
    margin: 1px;
	background-color: #03ff51;
}
.csp-success-alert{
	
	display: none;
	background: #3ee85b;
    padding: 10px 15px;
    color: white;
	margin:5px 0;
	font-size: 22px;
    line-height: 28px;
	
}
.csp-overlay-current-bk{
	
	height:70px;
	width:70px;
	background: url('<?php echo CSP_ASSETS_PATH. 'img/overlay-bk.jpg'; ?>');
	background-size: cover;
	
}
</style>

<script>
jQuery(document).ready(function(){
	
	jQuery('.csp-overlay-current').css('background', 'rgba(0,0,0,'+ jQuery('#csp_background_overlay_transparency').val() +')');
	
	jQuery("input[name=csp-popup-design]:radio").change(function () {
		
		jQuery('.csp-success-alert').fadeIn();
		
	});
	
	jQuery(document).on('input', '#csp_background_overlay_transparency', function() {
		
		jQuery('.csp-overlay-current').html( jQuery(this).val() );
		jQuery('.csp-overlay-current').css('background', 'rgba(0,0,0,'+ jQuery(this).val() +')');
		
	});
		
});
</script>
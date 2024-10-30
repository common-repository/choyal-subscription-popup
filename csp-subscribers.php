<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<div class="wrap">
	
	<h1 class="wp-heading-inline">Subscribers</h1>

	<a href="#" class="page-title-action" onclick="document.getElementById('id01').style.display='block'" >Add New</a>
	
	<hr class="wp-header-end">

	<form id="subscriber-search-form" method="post">
		
		<img style="height: 20px;display:none;" id="csp-search-loader" src="<?php echo CSP_ASSETS_PATH.'/img/search-loader.gif'; ?>" >
			
		<p class="search-box">
			<label class="screen-reader-text" for="post-search-input">Search Subscribers:</label>
			<input type="search" id="post-search-input" name="s" value="">
			<input type="submit" id="search-submit" class="button" value="Search Pages">
			<a href="#" id="resetSearchForm" >Reset Search</a>
		</p>
	
	</form>
	
	<form id="subscriber-bulk-delete-form" method="post">
	
		<input type="hidden" name="bulk-delete-mailchimp" id="bulk-delete-mailchimp"  value="no" >
		
		<div class="tablenav top">

			<div class="alignleft actions bulkactions">
				
				<button class="button bulk-delete-subscribers" >Delete</button>
				
			</div>
				
			<br class="clear">
			
		</div>
		
		<h2 class="screen-reader-text">Pages list</h2>
	
		<table class="wp-list-table widefat fixed striped pages">
		
			<thead>
				<tr>
					<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td>
					<th scope="col" id="Email Address" class="manage-column column-title column-primary desc"><span>Email</span></th>
					<th scope="col" id="First Name" class="manage-column column-title column-primary desc"><span>First Name</span></th>
					<th scope="col" id="Last Name" class="manage-column column-title column-primary desc"><span>Last  Name</span></th>
				</tr>
			</thead>

			<tbody id="the-list">
					
				<?php foreach ( $cspArySubscribers as $cspsubscriber ) { ?>
				
				<tr id="post-2" class="iedit author-self level-0 post-2 type-page status-publish hentry">
					
					<th scope="row" class="check-column">			
					
						<input class="subscriber-chk" type="checkbox" name="subscriber[]" value="<?php if ( is_email( $cspsubscriber->email ) ) { echo $cspsubscriber->email; } ?>">
						
					</th>
					
					<td class="has-row-actions column-primary">
					
						<span><?php if ( is_email( $cspsubscriber->email ) ) { echo $cspsubscriber->email; } ?></span>

						<div class="row-actions"> <span class="trash"><a href="#<?php echo intval( $cspsubscriber->id ); ?>" sub-id="<?php echo intval( $cspsubscriber->id ); ?>" class="csp-delete-sub" >Delete</a></span> </div>

					</td>
			 
					<td><span><?php echo sanitize_text_field( $cspsubscriber->fname ); ?></span></td>
					
					<td>		
						<span><?php echo sanitize_text_field( $cspsubscriber->lname ); ?></span>
					</td>
					
				</tr>
			
				<?php } ?>	
				
			</tbody>

			<tfoot id="csp-admin-subscriber-list-footer" total="<?php echo intval( $cspTotalSubscribers ); ?>" current-results="<?php echo count($cspArySubscribers); ?>" >
				<tr id="csp-hori-loader" >
					<td id="cb" class="manage-column column-cb check-column"></td>
					<th scope="col" id="Email Address" class="manage-column column-title column-primary desc"></th>
					<th scope="col" id="First Name" class="manage-column column-title column-primary desc">
						<img class="csp-hori-loader-img" src="<?php echo CSP_ASSETS_PATH. 'img/horizontal-loader.gif'; ?>" >
					</th>
					<th scope="col" id="Last Name" class="manage-column column-title column-primary desc"></th>
				</tr>
				<tr>
					<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td>
					<th scope="col" id="Email Address" class="manage-column column-title column-primary desc"><span>Email</span></th>
					<th scope="col" id="First Name" class="manage-column column-title column-primary desc"><span>First Name</span></th>
					<th scope="col" id="Last Name" class="manage-column column-title column-primary desc"><span>Last  Name</span></th>
				</tr>
			</tfoot>

		</table>

		<div class="tablenav bottom">

			<div class="alignleft actions bulkactions">
			
				<button class="button bulk-delete-subscribers" >Delete</button>
				
			</div>
			
			<div class="alignleft actions"></div>
			
			<br class="clear">
		
		</div>

	</form>

<br class="clear">
</div>

<style>
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    padding-top: 60px;
}

.modal-content {
    background-color: #fefefe;
    margin: 5px auto; /* 15% from the top and centered */
	border-top: 3px solid black;
    width: 50%; /* Could be more or less, depending on screen size */
	padding: 25px 35px;
}

.closeCSP{
	font-size: 25px;
    font-weight: bold;
	float: right;
	cursor: pointer;
}

.closeCSP:hover{
	
	color: red;
	
}

.csp_btn, .csp_btn :hover{
	border: 1px solid #333;
    background: black;
    color: white;
    font-weight: normal;
    text-transform: capitalize;
    border-radius: 3px;
    padding: 10px 15px;
    width: 100%;
    box-shadow: 0 0 5px grey;
}

.csp_btn img, .csp_btn img:hover{ 
	margin: -10px 5px;
    height: 30px;
    width: 30px;
    border: 0;
    box-shadow: none;
    padding: 0;
    background: transparent; 
}

.csp_loader{ display: none; }

.csp_loader:hover {
    margin: -10px 5px;
    height: 30px;
    width: 30px;
    border: 0;
    box-shadow: none;
    padding: 0;
    background: transparent;
}

.csp-popup-success-msg{
	background: rgb(71, 167, 53);
}

.csp-popup-error-msg{

    background: rgb(243, 51, 51);
	
}
.csp_api_msg p{
	
	padding: 5px 10px;
    color: white;
	
}
#csp-hori-loader{
	display:none;
}
.csp-hori-loader-img{
	height: 14px;
}
/* Add Zoom Animation */
.animate {
    -webkit-animation: animatezoom 0.3s;
    animation: animatezoom 0.3s
}

@-webkit-keyframes animatezoom {
    from {-webkit-transform: scale(0)} 
    to {-webkit-transform: scale(1)}
}

@keyframes animatezoom {
    from {transform: scale(0)} 
    to {transform: scale(1)}
}
</style>

<!-- The Modal -->
<div id="id01" class="modal">
  
	<span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal"></span>

	<!-- Modal Content -->
	<form class="modal-content animate" action="" method="post" id="csp-add-new-subscriber" >
		
		<span onclick="document.getElementById('id01').style.display='none'" class="closeCSP" title="Close Modal">&times;</span>

		<h1>Add New Subscriber</h1>
		
		<div class="csp_api_msg"></div>
		
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="csp_email">Email</label></th>
					<td><input name="csp_email" type="email" id="csp_email" value="" class="regular-text" required></td>
				</tr>
				
				<?php if( !$disableFnameLname || $disableFnameLname == 'no' ){ ?>
				
				<tr>
					<th scope="row"><label for="csp_fname">First Name</label></th>
					<td><input name="csp_fname" type="text" id="csp_fname" value="" class="regular-text"></td>
				</tr>
				
				<tr>
					<th scope="row"><label for="csp_lname">Last Name</label></th>
					<td><input name="csp_lname" type="text" id="csp_lname" value="" class="regular-text"></td>
				</tr>
				
				<?php } ?>
				
				<?php if( !$mailChimpActivate || $mailChimpActivate == 'yes' ){ //MailChimp Subscribe ?>
				
				<tr>
				
					<th scope="row">
						<label for="csp_mailchimp">Add to MailChimp List </label>
					</th>
					<td>
					
						<input name="csp_mailchimp" type="checkbox" id="csp_mailchimp" value="yes">
						<a href="https://mailchimp.com/" target="_blank" >
							<img src="<?php echo CSP_ASSETS_PATH. 'img/freddie_wink.svg'; ?>" width="20" height="20" alt="MailChimp logo" class="freddie-logo" style="margin: 0 0 -6px 0;" >
						</a>
						
					</td>
				</tr>
				
				<?php } ?>
				
			</tbody>
		</table>
		
		<p class="submit"><button type="submit" name="csp_submit" class="csp_btn" >Save <img class="csp_loader" src="<?php echo CSP_ASSETS_PATH.'/img/ripple.svg'; ?>" onerror="this.src='<?php echo CSP_ASSETS_PATH.'/img/ripple.gif'; ?>'"></button></p>
		
	</form>
	
</div>

<!-- The Modal Delete Confirmation -->
<div id="id02" class="modal">
  
	<span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close Modal"></span>

	<!-- Modal Content -->
	<form class="modal-content animate" action="" method="post" id="csp-delete-sub-popup" >
		
		<span onclick="document.getElementById('id02').style.display='none'" class="closeCSP" title="Close Modal">&times;</span>

		<h2>Are you sure want to delete ?</h2>
		
		<div class="csp_api_msg"></div>
		
		<table class="form-table">
			<tbody>
				<?php if( !$mailChimpActivate || $mailChimpActivate == 'yes' ){ //MailChimp Subscribe ?>
				
				<tr>
				
					<th scope="row">
						<label for="csp_delete_removefrom_mailchimp">Remove from MailChimp List </label>
					</th>
					<td>
					
						<input name="csp_delete_removefrom_mailchimp" type="checkbox" id="csp_delete_removefrom_mailchimp" value="yes">
						<a href="https://mailchimp.com/" target="_blank" >
							<img src="<?php echo CSP_ASSETS_PATH. 'img/freddie_wink.svg'; ?>" width="20" height="20" alt="MailChimp logo" class="freddie-logo" style="margin: 0 0 -6px 0;" >
						</a>
						
					</td>
				</tr>
				
				<?php } ?>
			</tbody>
		</table>
		
		<input type="hidden" value="" id="csp-delete-subid-input" name="csp-delete-subid-input">
		
		<p class="submit"><button style="background:#d84242;" type="submit" name="csp_submit" class="csp_btn" >Delete <img class="csp_loader" src="<?php echo CSP_ASSETS_PATH.'/img/ripple.svg'; ?>" onerror="this.src='<?php echo CSP_ASSETS_PATH.'/img/ripple.gif'; ?>'"></button></p>
		
	</form>
	
</div>

<!-- The Modal Bulk Delete Confirmation -->
<div id="id03" class="modal">
  
	<span onclick="document.getElementById('id03').style.display='none'" class="close" title="Close Modal"></span>

	<!-- Modal Content -->
	<form class="modal-content animate" action="" method="post" id="csp-bulkdelete-sub-popup" >
		
		<span onclick="document.getElementById('id03').style.display='none'" class="closeCSP" title="Close Modal">&times;</span>

		<h2>Are you sure want to delete ?</h2>
		
		<div class="csp_api_msg"></div>
		
		<table class="responsive table-bordered">
			<thead>
				<th><p>You have selected follwing subscriber's</p></th>
			</thead>
			<tbody id="confirm-subscribers-list">
			  
			</tbody>
		</table>
		
		<table class="form-table">
			<tbody>
			
				<?php if( !$mailChimpActivate || $mailChimpActivate == 'yes' ){ //MailChimp Subscribe ?>
				
				<tr>
				
					<th scope="row">
						<label for="csp_delete_removefrom_mailchimp">Remove from MailChimp list </label>
					</th>
					<td>
					
						<input name="csp_bulkdelete_confirm_mailchimp" type="checkbox" id="csp_bulkdelete_confirm_mailchimp" value="yes">
						
						<a href="https://mailchimp.com/" target="_blank" >
							<img src="<?php echo CSP_ASSETS_PATH. 'img/freddie_wink.svg'; ?>" width="20" height="20" alt="MailChimp logo" class="freddie-logo" style="margin: 0 0 -6px 0;" >
						</a>
						
					</td>
					
				</tr>
				
				<?php } ?>
				
			</tbody>
		</table>
		
		<p class="submit"><button style="background:#d84242;" type="submit" name="csp_submit" class="csp_btn" >Delete Selected<img class="csp_loader" src="<?php echo CSP_ASSETS_PATH.'/img/ripple.svg'; ?>" onerror="this.src='<?php echo CSP_ASSETS_PATH.'/img/ripple.gif'; ?>'"></button></p>
		
	</form>
	
</div>

<script>
// Get the modal
var modal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<script>
jQuery(document).ready(function(){
	
	var csp_ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
	var csp_ajax_nonce = '<?php echo wp_create_nonce('csp-security-subscribers'); ?>';
	
	jQuery('#csp-add-new-subscriber').submit(function(){
		
		jQuery('.csp_loader').show();
		
		var subscribeForm = jQuery('#csp-add-new-subscriber').serializeArray();
		
		var data = {
			'action': 'csp_admin_add_subscriber',
			'data': subscribeForm,
			'security' : csp_ajax_nonce
		};

		
		jQuery.ajax({
			
			type: "post",
			url: csp_ajax_url,
			data: data,
			success : function( response ) {
				
				if(response.operation == 'success'){
					
					jQuery('#csp-add-new-subscriber').find("input[type=text], input[type=email]").val("");
					jQuery('.csp_api_msg').html(response.msg);
					location.reload(true);
					
				}else{
					
					jQuery('.csp_api_msg').html(response.msg);
					
				}
				
				jQuery('.csp_loader').hide();
				
			}
			
		});
		
		return false;
		
	});
	
	//delete subscriber model
	
	jQuery(document).on( 'click', '.csp-delete-sub', function(){
		
		var subId = jQuery(this).attr('sub-id');
		
		jQuery('#csp-delete-subid-input').val( subId );
		
		jQuery('#id02').show();
		
		return false;
		
	});
	
	//delete subscriber popup form subumit

	jQuery('#csp-delete-sub-popup').submit(function(){
		
		jQuery('.csp_loader').show();
		
		var subscriberDeleteForm = jQuery('#csp-delete-subid-input').val();
		
		if ( jQuery('#csp_delete_removefrom_mailchimp').is(':checked') ) {
			
			var csp_delete_removefrom_mailchimp = 'yes';
		
		}else{
			
			var csp_delete_removefrom_mailchimp = 'no';
			
		}
		
		var data = {
			'action': 'csp_admin_delete_subscriber',
			'data': { 'subscriberDeleteForm':subscriberDeleteForm, 'csp_delete_removefrom_mailchimp':csp_delete_removefrom_mailchimp },
			'security' : csp_ajax_nonce
		};

		
		jQuery.ajax({
			
			type: "post",
			url: csp_ajax_url,
			data: data,
			success : function( response ) {
				
				jQuery('#csp-delete-sub-popup').find("input[type=text], input[type=email]").val("");
				jQuery('.csp_api_msg').html(response);
				
				jQuery('.csp_loader').hide();
				
				location.reload(true);
				
			}
			
		});
		
		return false;
		
	});
	
	jQuery('#subscriber-bulk-delete-form').submit(function(){
		
		jQuery('.csp_loader').show();
		
		var emails = jQuery('input:checkbox:checked.subscriber-chk').map(function () {
		  return this.value;
		}).get();
		
		var bulkdeleteMailChimpCheck = jQuery('#bulk-delete-mailchimp').val();
		
		var data = {
			'action': 'csp_admin_bulk_delete_subscribers',
			'data': { 'bulk-delete-mailchimp': bulkdeleteMailChimpCheck, 'emails':emails },
			'security' : csp_ajax_nonce
		};

		
		jQuery.ajax({
			
			type: "post",
			url: csp_ajax_url,
			data: data,
			success : function( response ) {
				
				jQuery('#csp-add-new-subscriber').find("input[type=text], input[type=email]").val("");
				jQuery('.csp_api_msg').html(response);
				location.reload(true);
				
				jQuery('.csp_loader').hide();
				
			}
			
		});
		
		return false;
		
	});
	
	jQuery('.bulk-delete-subscribers').click(function(){
		
		var values = jQuery('input:checkbox:checked.subscriber-chk').map(function () {
		  return this.value;
		}).get();

		if( values.length == 0 ){
			
			return false;
			
		}
		
		jQuery('#id03').show();
		
		var subsListHtmlPopup = '';
		
		jQuery.each( values, function( index, value ){
			subsListHtmlPopup += '<tr><td><li>'+value+'</li></td></tr>';
		});
		
		jQuery('#confirm-subscribers-list').html(subsListHtmlPopup);
		
		return false;
		
	});
	
	//Bulk Delete Confirmation
	jQuery('#csp-bulkdelete-sub-popup').submit(function(){
		
		if ( jQuery('#csp_bulkdelete_confirm_mailchimp').is(':checked') ) {
		
			jQuery('#bulk-delete-mailchimp').val('yes');
		
		}
		
		jQuery('#subscriber-bulk-delete-form').submit();
		
		return false;
		
	});
	
	//pagination 
	var jaxCallCheck = false;
	
	jQuery(window).scroll(function(){
		
		var total = jQuery('#csp-admin-subscriber-list-footer').attr('total');
		
		if (jQuery(document).height() - 400 <= jQuery(window).scrollTop() + jQuery(window).height()) { //145
			
			var cresults = parseInt( jQuery('#csp-admin-subscriber-list-footer').attr('current-results') );
			
			if( cresults < total ){
				
				jQuery('#csp-hori-loader').show();
				
				//Call ajax 
				
				var data = {
					'action': 'csp_subscribers_load_more',
					'data': { 'cresults': cresults },
					'security': csp_ajax_nonce
				};
				
				if( jaxCallCheck == false ){
					
					jaxCallCheck = true;
					
					jQuery.ajax({
						
						type: "post",
						url: csp_ajax_url,
						data: data,
						success : function( response ) {
							
							jQuery('#csp-admin-subscriber-list-footer').attr('current-results', (cresults+ parseInt( response.cresults )) );
							
							var subsListHtmlAppend = '';
			
							jQuery.each( response.sdata, function( index, value ){
								subsListHtmlAppend += '<tr id="post-'+value.id+'" class="iedit author-self level-0 post-2 type-page status-publish hentry"><th scope="row" class="check-column"><input class="subscriber-chk" type="checkbox" name="subscriber[]" value="'+value.email+'"></th><td class="has-row-actions column-primary"><span>'+value.email+'</span><div class="row-actions"> <span class="trash"><a href="#'+value.id+'" sub-id="'+value.id+'" class="csp-delete-sub" >Delete</a></span> </div></td><td><span>'+value.fname+'</span></td><td><span>'+value.lname+'</span></td></tr>';
							
							});
							
							jQuery('#the-list').append(subsListHtmlAppend);
							
							jQuery('#csp-hori-loader').hide();
							
							jaxCallCheck = false;
							
						}
						
					});
					
				}
				
			}
			
		}
		
	});
	
	
	//search 
	jQuery('#subscriber-search-form').submit(function(){
		
		jQuery('#csp-search-loader').show();
		
		var searchFormData = jQuery('#subscriber-search-form').serializeArray();
		
		//Call ajax 
		
		var data = {
			'action': 'csp_search_subscribers',
			'data': searchFormData,
			'security' : csp_ajax_nonce
		};
		
		jQuery.ajax({
						
			type: "post",
			url: csp_ajax_url,
			data: data,
			success : function( response ) {
				
				jQuery('#the-list').html(response);
				
				jQuery('#csp-search-loader').hide();
				
			}
			
		});
		
		return false;
		
	});
	
	jQuery('#resetSearchForm').click(function(){
		location.reload(true);
		return false;
	});
	
	
});
</script>
jQuery(document).ready(function(){
	
	jQuery('.csp_model').animate({'bottom': '0px'}, 300);
	
	jQuery('.csp_close').click(function(){
		
		jQuery('.csp_model').animate({'bottom': '-400px'}, 300);
		jQuery('.csp_overlay').hide();
		jQuery('.csp_subscribe_btn').show();
		return false;
		
	});
	
	jQuery('.csp_subscribe_btn').click(function(){
		
		jQuery('.csp_model').animate({'bottom': '0px'}, 300);
		jQuery('.csp_overlay').show();
		jQuery('.csp_subscribe_btn').hide();
		return false;
		
	});
	
	jQuery('#email-subscription-form').validate({
		
		submitHandler: function() {
			
			var subscribeForm = jQuery('#email-subscription-form').serializeArray();
			
			jQuery('.csp_loader').show();
			
			jQuery.ajax({
				url : csp_ajax.ajax_url,
				type : 'post',
				data : {
					action : 'csp_submit',
					data : subscribeForm,
					security : csp_ajax.ajax_nonce
				},
				success : function( response ) {
					
					if(response.operation == 'success'){
						
						jQuery('#email-subscription-form').find("input[type=text], input[type=email], textarea").val("");
						jQuery('.csp_api_msg').html(response.msg);
						
					}else{
						
						jQuery('.csp_api_msg').html(response.msg);
						
					}
					
					jQuery('.csp_loader').hide();
					
				}
			});
			
		}
		
	});
	
});
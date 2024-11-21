<?php
error_reporting(E_ALL);
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('header');
$this->load->model('general_model');
$enable_app = $store_configuration["enable_app"];
$enable_popup_message = $store_configuration["enable_popup_message"];
$showcase = explode(".",$store_configuration["showcase_url"]);
$showcase_url = $showcase[0].$showcase[1];
$shop = $store_configuration['shop'];
$appcharges = $this->general_model->getChargeData($shop);

?>

<div class="loading-mask gemfind-loading-mask">
  <div class="loader gemfind-loader"><p>Please wait...</p>
  </div>
</div>
<div id="search-diamonds">
<div class="alert alert-success" style="display:none;">
</div>
<div class="alert alert-danger fade in alert-dismissible" style="display:none;">
</div>
<?php 
	if($enable_app == "true")
	{ ?>

	<?php if ($appcharges->status != 'pending' || $appcharges->status == '' ) { ?>
		<iframe style="width: 1px; min-width: 100%;" scrolling="yes" width="100%" id="iframe" height="1650px" frameborder="0" src="<?php echo $store_configuration["showcase_url"];?>"></iframe>
	
		
		<script src="<?php echo $store_configuration["showcase_url"];?>/Scripts/build/lib/post-robot.js"></script>
		<script>
			var options = {
				window: document.getElementById('iframe').contentWindow, // Window reference for the iframe. Required.
				domain: '<?php echo $store_configuration["showcase_url"];?>'// FQDN of the hosted showcase. Required. 
			}
			postRobot.on('init', options, function(event) {
				console.log('init event live');
				console.log(event.data);
				// This event fires when the api becomes available to the hosting site
			})
			postRobot.on('addToCart', options, function(event) {
				console.log(event.data);
				//console.log("Item Id - "+ event.data.itemId);
				// This event will fire whenever a user adds an item to their shopping cart.
				var shop = '<?php echo $store_configuration["shop"];?>';
				var configurationId = "";
				if (event.data.itemId) {
					console.log("Item Id - "+ event.data.itemId);
					// TODO - process non-configurable item
					var productId = event.data.itemId;	
					if  (event.data.configurationId) {
						console.log("Configurable Id - "+ event.data.configurationId);
						var configurationId = event.data.configurationId;
						var type = 2;
						// TODO - process configured item
					} 
					else{
						var type = 1;
					}
				}
				else if  (event.data.configurationId) {
					console.log("Configurable Id - "+ event.data.configurationId);
					var configurationId = event.data.configurationId;
					var type = 2;
					// TODO - process configured item
				} 
				else if  (event.data.serialNumber) {
					var productId = event.data.serialNumber;
					var type = 3;
					//console.log("Item Id");
					console.log("SerialNumber Id  - "+ event.data.serialNumbers);
					// TODO - Process Diamond
				}
				//data: {productId:productId,shop:shop,type:type,quantity: event.data.quantity},
				console.log('eventdataqty--'+event.data.quantity);
				jQuery.ajax({
					url: '<?php echo base_url().'stuller/cartadd'?>',
					data: {productId:productId,configurationId:configurationId,shop:shop,type:type,quantity: event.data.quantity,price:event.data.price},
					type: 'POST',
					//dataType: 'json',
					cache: true,
				beforeSend: function(settings) {
					jQuery('.gemfind-loading-mask').show();
				},
				success: function(response) {
			    var response = jQuery.parseJSON(response);
			    if (response) {
			        if (response.outofstock) {
			            jQuery('.gemfind-loading-mask').hide();
			            jQuery(".alert-danger").html(response.outofstock_msg);
			            jQuery(".alert-danger").show();
			            jQuery([document.documentElement, document.body]).animate({
			                scrollTop: jQuery(".alert-danger").offset().top - 50
			            }, 800);
			            setTimeout(function() {
			                $(".alert-danger").fadeOut();
			            }, 5000);
			        } else {
			            jQuery('.gemfind-loading-mask').hide();

			            // Check enable_popup_message
			            var enablePopupMessage = '<?php echo $enable_popup_message; ?>';
			            console.log('enablePopupMessage');
			            console.log(enablePopupMessage);

			            if (enablePopupMessage === 'true') {
			                // Show popup
			                showPopup(response.custom_msg, response.AddToCart);
			            } else {
			                // Show success alert
			                jQuery(".alert-success").html(response.custom_msg);
			                jQuery(".alert-success").append('<span>You will be automatically redirected to cart in <b id="count_time_point">10</b></span>');
			                jQuery(".alert-success").show();
			                jQuery([document.documentElement, document.body]).animate({
			                    scrollTop: jQuery(".alert-success").offset().top - 50
			                }, 800);
			                count_time = 9;
			                var myfunc = setInterval(function() {
			                    jQuery("#count_time_point").text(count_time);
			                    if (count_time <= 1) {
			                        clearInterval(myfunc);
			                    }
			                    count_time--;
			                }, 1000);
			                var delay = 9000;
			                if (response.AddToCart) {
			                    setTimeout(function() {
			                        	window.location.href = response.AddToCart;
			                    	}, delay);
			                	}	
			            	}
			        	}
			    	}
				},

				error: function(xhr, status, errorThrown) {
					console.log('Error happens. Try again.');
					console.log(errorThrown);
					}
				});
			});

			function showPopup(message, cartUrl) {
			    var popupHtml = `
			        <div id="gfstuller-custom-popup-overlay" class="gfstuller-popup-overlay">
			            <div id="gfstuller-custom-popup" class="gfstuller-popup">
			                <div class="gfstuller-popup-content">
			                    <span class="gfstuller-popup-close">&times;</span>
			                    <p>${message}</p>
			                </div>
			            </div>
			        </div>

			        <style>
			        .gfstuller-popup-overlay {
			            position: fixed;
			            top: 0;
			            left: 0;
			            width: 100%;
			            height: 100%;
			            background: rgba(0, 0, 0, 0.7); /* Dark semi-transparent background */
			            display: flex; /* Use flexbox */
			            justify-content: center; /* Center horizontally */
			            align-items: center; /* Center vertically */
			            z-index: 9999; /* Ensure it's on top of other elements */
			        }

			       .gfstuller-popup {
					    background-color: #fff;
					    padding: 45px 25px;
					    border-radius: 8px;
					    max-width: 500px;
					    width: 100%;
					    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
					    position: relative;
					    text-align: center;
					    margin: 0 20px;
					}

			        .gfstuller-popup-content p {
			            margin-bottom: 10px;
			            font-size: 18px;	
			        }

			        .gfstuller-popup-close {
					   position: absolute;
					    top: -8px;
					    right: -8px;
					    cursor: pointer;
					    font-size: 30px;
					    color: #333;
					    background-color: #ddd;
					    width: 35px;
					    border-radius: 25px;
					    height: 35px;
					    line-height: 35px;
					    font-weight: bold;

					}
			        </style>
			    `;
			    jQuery('body').append(popupHtml);
			    jQuery('#gfstuller-custom-popup-overlay').fadeIn();

			    // Auto-redirect after a delay without showing countdown
			    var delay = 9000;
			    setTimeout(function() {
			        window.location.href = cartUrl;
			    }, delay);

			    // Close popup on click
			    jQuery('.gfstuller-popup-close').on('click', function() {
			        jQuery('#gfstuller-custom-popup-overlay').fadeOut(function() {
			            jQuery(this).remove();
			        });
			    });
			}

		</script>
	<?php } else {
		echo '<p class=stuller_disable >GemFind Stuller App is Disabled!! <br> <span style="font-size: 15px;"> Your GemFind-Stuller application subscription canceled because of no real transaction, kindly activate the application from admin section or write to us at <a href="mailto:support@gemfind.com"> support@gemfind.com </a> for more details. </span> </p> ';
		} ?>
<?php
	}
	else
	{
		echo "<p class=stuller_disable>Gemfind Stuller App is Disabled!!</p>";
	}
?>
</div>
<?php 
$this->load->view('footer'); 
?>
<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//page template creation 
if ( ! function_exists('putAdminPageTemplate')){
	function putAdminPageTemplate($adminOptionData) {
		$shop = $_REQUEST['shop'];
		$showcaseUrl = $adminOptionData["showcase_url"];
		$url = base_url().'stuller/cartadd';
		$customcss = base_url()."assets/css/custom.css";
	    $file_data = array (
		  'asset' => 
		  array (
			'key' => 'templates/page.gemfind-stuller.liquid',
			'value' => '<div class="alert alert-success" style="display:none;"></div><div class="alert alert-danger fade in alert-dismissible" style="display:none;"></div>{% assign showcases = page.content | split: "/" %}<link href="'.$customcss.'" rel="stylesheet"><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/><script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script><div class="loading-mask gemfind-loading-mask"><div class="loader gemfind-loader"><p>Please wait...</p></div></div><iframe style="width: 1px; min-width: 100%;" scrolling="no" width="100%" id="iframe" height="1600px" frameborder="0" src="{{page.content}}"></iframe>
			<script src="{{showcases[0]}}{{"//"}}{{showcases[1]}}{{showcases[2]}}/Scripts/build/lib/post-robot.js"></script>
			<script> 
			console.log("{{showcases[0]}}{{"//"}}{{showcases[1]}}{{showcases[2]}} ");
			var options = {
				window: document.getElementById(\'iframe\').contentWindow, // Window reference for the iframe. Required.
				domain: "{{showcases[0]}}{{"//"}}{{showcases[1]}}{{showcases[2]}}" // FQDN of the hosted showcase. Required. 
			}
			postRobot.on(\'init\', options, function(event) {
				// This event fires when the api becomes available to the hosting site
			})
			postRobot.on(\'addToCart\', options, function(event) {
				var shop = "'.$shop.'";
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
				jQuery.ajax({
					url: "'.$url.'",
					data: {productId:productId,configurationId:configurationId,shop:shop,type:type,quantity: event.data.quantity,price:event.data.price},
					type: \'POST\',
					//dataType: \'json\',
					cache: true,
				beforeSend: function(settings) {
					jQuery(\'.gemfind-loading-mask\').show();
				},
				success: function(response) {
					console.log(response);
					var response = jQuery.parseJSON(response);					
					if(response){
						jQuery(".gemfind-loading-mask").hide();
						if(response.outofstock){
							jQuery(".alert-danger").html(response.outofstock_msg);	
							jQuery(".alert-danger").show();
							jQuery([document.documentElement, document.body]).animate({
						        scrollTop: jQuery(".alert-success").offset().top - 50
						    }, 800);
						    setTimeout(function(){
								$(".alert-danger").fadeOut();
							},5000);
						}else{
							jQuery(".alert-success").html(response.custom_msg);
							jQuery(".alert-success").append("<span>You will be automatically redirect to cart in <b id=count_time_point>5</b></span>");
							jQuery(".alert-success").show();
							jQuery([document.documentElement, document.body]).animate({
						        scrollTop: jQuery(".alert-success").offset().top - 50
						    }, 800);
						    count_time = 4;
						    var myfunc = setInterval(function() {
						    	jQuery("#count_time_point").text(count_time);
						    	if(count_time < 1){
						    		clearInterval(myfunc);
						    	}
						    	count_time--
						    }, 1000);
							var delay = 9000; 
							//console.log(response.AddToCart);
							if(response.AddToCart){
								setTimeout(function(){ 
									window.location.href = response.AddToCart;
								}, delay);
							}
						}
					}
				},
				error: function(xhr, status, errorThrown) {
					console.log(\'Error happens. Try again.\');
					console.log(errorThrown);
					}
				});
			})
		</script>'
		  ),
		);	
		return $file_data;
	}
}



if ( ! function_exists('is_404')){
	function is_404($url) {
	    $handle = curl_init($url);
	    curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
	    /* Get the HTML or whatever is linked in $url. */
	    $response = curl_exec($handle);
	    /* Check for 404 (file not found). */
	    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	    curl_close($handle);
	    /* If the document has loaded successfully without any redirection or error */
	    if ($httpCode >= 200 && $httpCode < 300) {
	        return false;
	    } else {
	        return true;
	    }
	}
}

if ( ! function_exists('getDiamondSkuByPath')){
	function getDiamondSkuByPath($path) {
	    $urlstring = $path;    	
		$urlarray = explode('-sku-', $urlstring);
		return $urlarray[1];
	}
}

if ( ! function_exists('getCurlData')){
	function getCurlData($url,$headers) {
	    $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1');
        $response = curl_exec($curl);
        return $results = json_decode($response);
	}
}

if ( ! function_exists('getShopToken')){
	function getShopToken($access_token_url,$query) {
	    $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	  	curl_setopt($curl, CURLOPT_URL, $access_token_url);
	  	curl_setopt($curl, CURLOPT_POST, count($query));
	  	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($query));
	  	$result = curl_exec($curl);
	  	
	  	curl_close($curl);
        $result = json_decode($result, true);

        return $result['access_token'];
	}
}

if ( ! function_exists('shopify_call')){
	function shopify_call($token, $shop,$shopify_json_endpoint,$data,$method) {
		$shopifycallurl = 'https://'.$shop.$shopify_json_endpoint;
		$request_headers = array(
                    "X-Shopify-Access-Token:" . $token,
                    "Content-Type:application/json"
                );
	    $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $shopifycallurl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 100,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $request_headers
        ));
        $response = curl_exec($curl);
        return $results = json_decode($response);
	}
}



if ( ! function_exists('postCurlData')){
	function postCurlData($url,$headers,$data,$method) {
	    $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 100,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers
        ));
        $response = curl_exec($curl);
        return $results = json_decode($response);
	}
}


?>
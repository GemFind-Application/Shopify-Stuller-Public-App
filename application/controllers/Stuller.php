<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors', 0);

class Stuller extends CI_Controller {
	
	function __construct()
	{  	
		parent::__construct();
		$this->load->library('stuller_lib');
		$this->load->model('general_model');
		if($this->input->get('path_prefix')){
			header('Content-Type: application/liquid');			
		}
	}

	/*
	* View Stuller List 
	*/
	public function index()
	{	
		//redirect("https://gemfind-demo-store-3.myshopify.com/cart/add?id=32832279052393&quantity=1");
		$shop = $this->input->get('shop');
		$store_configuration = $this->general_model->getStoreConfiguration($shop);
		$data['store_configuration'] = json_decode(json_encode($store_configuration), true);
		$this->load->view('list_stuller', $data);		
	}
    

	public function product()
	{	
		$shop = $this->input->get('shop');
		$access_token = $this->stuller_lib->getShopAccessToken($shop);
		$data['access_token'] = $access_token;
		$shop_base_url = "https://".$shop;
		$get_shop_data_endpoint = "/admin/shop.json";
		$getShopDataRequestURL = $shop_base_url.$get_shop_data_endpoint;

		$request_headers = array(
		                    "X-Shopify-Access-Token:" . $access_token,
		                    "Content-Type:application/json"
		                );
		      
		$resultShop = getCurlData($getShopDataRequestURL,$request_headers);

		$data['shop_main_domain'] = $resultShop->shop->domain;

		$data['diamond_path'] = $this->uri->segment(3);
		$this->load->view('view_diamonds',$data);
	}
 	public function cartadd(){
		$username = $this->config->item('stuller_username'); // Authenticated API User
		$password = $this->config->item('stuller_password'); // Authenticated API Password
		$apiversion = $this->config->item('shopify_api_version');
	    $product_id = $_REQUEST['productId'];

		$configurationId = $_REQUEST['configurationId'];
		$type = $_REQUEST['type'];
		$shop = $_REQUEST['shop'];	
		$lineProperties = "";		
		if($type == 1)
		{
			
			$lead_time_msg = false;
			$URL = "https://api.stuller.com/v2/products?ProductId=".$product_id;			
			$flag = true;
			//$type = 2;
		}
		else if ($type == 2 || $type == 3) {

			$flag = false;
			if ($type == 2) { //Type = 2 Configurable
				$URL = "https://api.stuller.com/v2/products/configuredproduct?ConfigurationId=".$configurationId;
				$flag = true;
			}
			else if ($type == 3) { //Type = 3 SerialNumbers
				$URL = "https://api.stuller.com/v2/gem/diamonds?SerialNumbers=".$product_id;
				$flag = true;
			}
		}
		if($flag == 1){
			
			if ($type == 2 || $type == 3) 
			{
					/*$product_id = 6635090;
					$URL = "https://api.stuller.com/v2/products/configuredproduct?ConfigurationId=".$product_id;
					*/
					//echo $URL."<br/>";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL,$URL);
					curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
					curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
					curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
					$result = curl_exec ($ch);
					$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
					curl_close ($ch);
					$stuller_data = "";
					$stuller_data = json_decode($result, true);	

					$url=$URL;
					$txt = $result;

					$myfile = file_put_contents('logs1.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
					$new_myfile = file_put_contents('url_logs1.txt', $url.PHP_EOL, FILE_APPEND | LOCK_EX);
							
					$product = $stuller_data["Product"];
					//echo "test<pre>";print_r($stuller_data); 
					$stuller_sku = (string)$product["SKU"];
					$stuller_desc = $product["Description"];
					$stuller_short_desc = $product["ShortDescription"];
					/* Stone Data */
					if(!empty($stuller_data["Stones"]))
					{
						$stuller_stone_desc = $stuller_data["Stones"][0]["Product"]["ShortDescription"];
						$stuller_stone_price = $stuller_data["Stones"][0]["Product"]["Price"]["Value"];
					}
					/* Engravings */
					if(!empty($stuller_data["Engravings"]))
					{
						$stuller_engravings = $stuller_data["Engravings"][0]["Text"];
					}
					
					/* Properties */
					$comma = ",";
					$configmodel = $product["ConfigurationModel"]["SettingOptions"][0];
					$stullerProduct = "";
					$stullerProduct .= $stuller_data["EstimatedShipDate"];
					if($stuller_desc != "")
					{
						$stullerProduct .= $comma.$stuller_desc;
					}
					
					if(isset($stuller_data["ConfiguredRingSize"]) && $stuller_data["ConfiguredRingSize"] != 0 && $stuller_data["ConfiguredRingSize"] != '')
					{
						$stullerProduct .= $comma."Ring Size: ".$stuller_data["ConfiguredRingSize"];
					} else {
						$stullerProduct .= $comma."Ring Size: ".$product["RingSize"];
					}					
					
					if($stuller_short_desc != "")
					{
						$stullerProduct .= $comma.$stuller_short_desc;
					}
					if($configmodel["LocationNumber"] != "")
					{
						$stullerProduct .= $comma.$configmodel["LocationNumber"];
					}
					if($configmodel["SettingType"] != "")
					{
						$stullerProduct .= $comma.$configmodel["SettingType"];
					}
					if($configmodel["Description"] != "")
					{
						$stullerProduct .= $comma.$configmodel["Description"];
					}
					if($configmodel["SizeMM"] != "")
					{
						$stullerProduct .= $comma.$configmodel["SizeMM"];
					}
					if($configmodel["Shape"] != "")
					{
						$stullerProduct .= $comma.$configmodel["Shape"];
					}
					if($configmodel["Dimension1"] != "")
					{
						$stullerProduct .= $comma.$configmodel["Dimension1"];
					}
					if($product["CenterStoneShape"] != "")
					{
						$stullerProduct .= $comma.$product["CenterStoneShape"];
					}
					$stone = $stuller_data["Stones"][0]["Product"];
					/* Stone Data */
					if(!empty($stuller_data["Stones"]))
					{
						$stoneData = "";
						$stoneDataArr = array();
						
						foreach($stuller_data["Stones"] as $key => $val)
						{
							//$stoneData .= "Stones".($key+1).":-";
							if (isset($val["Product"]))
							{
								$stoneData = "";
								if($val["Product"]["Id"] != ""){
									$stoneData .= "Id:".$val["Product"]["Id"];
								}
								if($val["Product"]["SKU"] != ""){
									$stoneData .= ",SKU:".$val["Product"]["SKU"];
								}
								if($val["Product"]["Description"] != ""){
									$stoneData .= ",Description:".$val["Product"]["Description"];
								}
								if($val["Product"]["ShortDescription"] != ""){
									$stoneData .= ",ShortDescription:".$val["Product"]["ShortDescription"];
								}
								$stoneDataArr[] = $stoneData;
							}
						}
						if (isset($val["Diamond"]))
						{
							$stoneData = "";
							if ($val["Diamond"]["SerialNumber"] != "") {
								$stoneData .= "Id:" . $val["Diamond"]["SerialNumber"];
							}
							if ($val["Diamond"]["SerialNumber"] != "") {
								$stoneData .= ",SKU:" . $val["Diamond"]["SerialNumber"];
							}
							if ($val["Diamond"]["CaratWeight"] != "") {
								$stoneData .= ",Description:" . $val["Diamond"]["CaratWeight"]." / ".$val["Diamond"]["Shape"]." / ".$val["Diamond"]["Clarity"]." / ".$val["Diamond"]["Color"]." / ".$val["Diamond"]["Cut"]." / ".$val["Diamond"]["StoneType"];
							}
							if ($val["Diamond"]["CaratWeight"] != "") {
								$stoneData .= ",ShortDescription:" . $val["Diamond"]["CaratWeight"]." / ".$val["Diamond"]["Shape"]." / ".$val["Diamond"]["Clarity"]." / ".$val["Diamond"]["Color"]." / ".$val["Diamond"]["Cut"]." / ".$val["Diamond"]["StoneType"];
							}
							$stoneDataArr[] = $stoneData;
						}
						$stoneDataArr = array_unique($stoneDataArr);
					}
					$engravings = $stuller_data["Engravings"][0];
					$stullerEngravings = "";
					/*if($engravings["LocationNumber"] != "")
					{
						$stullerEngravings .= $engravings["LocationNumber"];
					}
					if($engravings["Text"] != "")
					{
						$stullerEngravings .= $comma.$engravings["Text"];
					}
					if($engravings["FontFace"] != "")
					{
						$stullerEngravings .= $comma.$engravings["FontFace"];
					}*/
					//echo "<pre>";print_r($stuller_data["Engravings"]);
					foreach($stuller_data["Engravings"] as $key => $val)
					{
						if($key == 0)
						{
							$stullerEngravings .= "Inside Band: ".$val["Text"].", LocationNumber: ".$val["LocationNumber"].", FontFace: ".$val["FontFace"].",Font Color: ".$val["FillColor"];
						}
						else
						{
							$stullerEngravings .= ",Outside Band: ".$val["Text"].", LocationNumber: ".$val["LocationNumber"].", FontFace: ".$val["FontFace"].",Font Color: ".$val["FillColor"];
						}
					}
					
					$lineProperties = "";
					if($stullerProduct != "")
					{
						$lineProperties .= "&properties[Product]=".$stullerProduct;
					}
					if(!empty($stoneDataArr))
					{
						$i = 1;
						foreach($stoneDataArr as $key => $val)
						{
								$lineProperties .= "&properties[Stone".$i."]=".$val;
								$i++;
						}
					}
					if($stullerEngravings != "")
					{
						$lineProperties .= "&properties[Engravings]=".$stullerEngravings;
					}
					/* Properties */
					
					$stuller_short_desc .= "/ Stone Description:".$stuller_stone_desc." / Stone Price:".$stuller_stone_price." / Stuller Engravings:".$stuller_engravings;
					$stuller_price = number_format($_REQUEST['price'],2);
					$stuller_weight = $product["Weight"];
					$stuller_images_arr = $stuller_data["Images"];
					$stuller_quantity = 1;
					
					//Images
					if(isset($stuller_images_arr) && count($stuller_images_arr) > 0)
					{
						foreach($stuller_images_arr as $key => $value)
						{
							$arrImages[] = array(
								"src" => $value["FullUrl"]
							);
						}
					} 


					
					$arrMainImages = array($arrImages);

					//Collection 
					for ($x = 1; $x <= 10; $x++) {
					  $key = "MerchandisingCategory".$x;
					  if(isset($product[$key]))
					  {
						  $arrCollection[] = $product[$key];
					  }
					  else
					  {
						  break;
					  }
					}

					// exit();

					$tags = implode(",",$arrCollection);
					$stock_weight = $product["Weight"];
					

					
					//Stock Start	
					$qty = 1;
					if (isset($_POST["quantity"]) && !empty($_POST["quantity"]) && $_POST["quantity"] > "0") {
						$qty = $_POST["quantity"] ? $_POST["quantity"] : 1;
					}
					$onhand = $product["OnHand"];
					if($qty <= $onhand)
					{
						$qty = $qty;
					}
					else
					{
						$qty = 1;
					}
					
					$lead_time = 0;	
					if ($product["LeadTime"]) {
						$lead_time = $product["LeadTime"];
					}
					$custom_msg = "<strong>".$stuller_desc."</strong> has been added to your cart. ";							
					$stuller_stock = 1;
					$stuller_stock_status = "";
					/*$stuller_stock = $stuller_data["Products"][0]["OnHand"];
					$stuller_stock_status = $stuller_data["Products"][0]["Status"];*/		
	
					$stuller_stock = $stuller_data["Product"]["OnHand"];
					$stuller_stock_status = $stuller_data["Product"]["Status"];			
					
					$outofstock = false;
					$backorder = false;
					$custom_msg = "<strong>".$stuller_desc."</strong> has been added to your cart. ";
					
					if($stuller_stock_status == "In Stock" || $stuller_stock_status == "Made To Order" || $stuller_stock_status == "While supplies last"){
						$lead_time_msg = true;
						if($stuller_stock > 0){
							$stuller_stock = $stuller_stock;
						}else{
							$stuller_stock = 1;
						}
					}else {
						if($shop == 'monacojewelers.myshopify.com'){
							$backorder = true;
						}else{
							$outofstock = true;
							//$outofstock_msg = 'The product is out of stock. Please try again later.';
							$outofstock_msg = 'We are happy to help you choose the right diamond or gemstone. For stone availability, please contact us directly. Thank you!';
						}
						$stuller_stock = 0;
					}
			}
			else
			{
				

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$URL);
				curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
				$result = curl_exec ($ch);
				$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
				curl_close ($ch);

				// $ch = curl_init();
			
				// // Set cURL options
				// curl_setopt($ch, CURLOPT_URL, $URL);
				// curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout after 30 seconds
				// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				// curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				// curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
				// curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				//     'Content-Type: application/json'
				// ));

				// $result = curl_exec($ch);

				// if ($result === false) {
				//     $error = curl_error($ch);
				//     echo "cURL error: " . $error;
				// } else {
				//     $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get status code
				//     if ($status_code === 200) {
				//         $stuller_data = json_decode($result, true);
				//         // Process the retrieved data
				//         // ...
				//     } else {
				//         echo "Request failed with status code: " . $status_code;
				//     }
				// }

				// curl_close($ch);


				$stuller_data = "";
				$stuller_data = json_decode($result, true);	

				//echo "<pre>"; print_r($result); exit();

				$url=$URL;
				$txt = $result;
				
				$myfile = file_put_contents('logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
				$new_myfile = file_put_contents('url_logs.txt', $url.PHP_EOL, FILE_APPEND | LOCK_EX);
				

				$stuller_sku = (string)$stuller_data["Products"][0]["SKU"];
				$stuller_desc = $stuller_data["Products"][0]["Description"];
				$stuller_short_desc = $stuller_data["Products"][0]["ShortDescription"];
				$stuller_price = number_format($_REQUEST['price'],2);
				$stuller_weight = $stuller_data["Products"][0]["Weight"];
				$stuller_images_arr = $stuller_data["Products"][0]["Images"];
				$stuller_quantity = 1;
				$product = $stuller_data["Products"][0];
				/* Properties */
				$comma = ",";
				$stullerProduct = "&properties[Product]=";
				if($product["Description"] != "")
				{
					$stullerProduct .= $product["Description"].$comma;
				}
				if($product["ShortDescription"] != "")
				{
					$stullerProduct .= $product["ShortDescription"].$comma;
				}
				
				if(isset($stuller_data["ConfiguredRingSize"]) && $stuller_data["ConfiguredRingSize"] != 0 && $stuller_data["ConfiguredRingSize"] != '')
				{
					$stullerProduct .= "Ring Size: ".$stuller_data["ConfiguredRingSize"];
				} else {
					
					$stullerProduct .= "Ring Size: ".$product["RingSize"];
				}
				
				$lineProperties = $stullerProduct;
				/* Properties */
				
				

				if(isset($stuller_images_arr) && count($stuller_images_arr) > 0)
				{
					

					foreach($stuller_images_arr as $key => $value)
					{
						$arrImages[] = array(
							"src" => $value["FullUrl"]
						);
					}
				} 


				$arrMainImages = array($arrImages);

				

				//Collection
				// for ($x = 1; $x <= 10; $x++) {
				//   $key = "MerchandisingCategory".$x;

				 

				//   if(array_key_exists($key,$stuller_data["Products"][0]))
				//   {

				// 	  $arrCollection[] = $stuller_data["Products"][0][$key];
				//   }
				//   else
				//   {
				// 	  break;
				//   }
				// }

			
				for ($x = 1; $x <= 10; $x++) {
				    $key = "MerchandisingCategory" . $x;
				    
				    if (isset($stuller_data["Products"][0][$key])) {
				        $arrCollection[] = $stuller_data["Products"][0][$key];
				    } else {
				        break;
				    }
				   
				}	

				//Stock Start	
				$qty = 1;
				if (isset($_POST["quantity"]) && !empty($_POST["quantity"]) && $_POST["quantity"] > "0") {
					$qty = $_POST["quantity"] ? $_POST["quantity"] : 1;
				}
				$onhand = $stuller_data["Products"][0]["OnHand"];
				if($qty <= $onhand)
				{
					$qty = $qty;
				}
				else
				{
					$qty = 1;
				}
				
				$lead_time = 0;	
				if ($stuller_data["Products"][0]["LeadTime"]) {
					$lead_time = $stuller_data["Products"][0]["LeadTime"];
				}
				
				$stuller_stock = 1;
				$stuller_stock_status = "";
				$stuller_stock = $stuller_data["Products"][0]["OnHand"];
				$stuller_stock_status = $stuller_data["Products"][0]["Status"];			
				$outofstock = false;
				$backorder = false;
				$custom_msg = "<strong>".$stuller_desc."</strong> has been added to your cart. ";
				
				if($stuller_stock_status == "In Stock" || $stuller_stock_status == "Made To Order" || $stuller_stock_status == "While supplies last"){
					$lead_time_msg = true;
					if($stuller_stock > 0){
						$stuller_stock = $stuller_stock;
					}else{
						$stuller_stock = 1;
					}
				}else {
					if($shop == 'monacojewelers.myshopify.com'){
						$backorder = true;
					}else{
						$outofstock = true;
						//$outofstock_msg = 'The product is out of stock. Please try again later.';
						$outofstock_msg = 'We are happy to help you choose the right diamond or gemstone. For stone availability, please contact us directly. Thank you!';
					}
					$stuller_stock = 0;
				}
			}


				    
			
			/*echo "lead lead_time_msg".$lead_time_msg;
			echo "<br>lead time".$lead_time;*/
			if ($lead_time_msg && $lead_time != "") {
				$custom_msg .= "<strong>".$stuller_sku." </strong> will require $lead_time day(s) to ship, but this is only an estimate. Please contact us for more information regarding estimated shipping or delivery times. ";

			}

			//Stock End
			try{
				if($outofstock){
					echo json_encode(array("outofstock" => $outofstock,"outofstock_msg" => $outofstock_msg));
					exit;
				}else{
					$access_token = $this->stuller_lib->getShopAccessToken($shop);
					$shop_base_url = "https://".$shop;
					$request_headers = array(
						"X-Shopify-Access-Token:" . $access_token,
						"Content-Type:application/json"
					);
					$filename_log1='accestoken.txt';
						file_put_contents($filename_log1, $access_token);

					if($stuller_desc){
						$product_title = $stuller_desc;
					}else{
						$product_title = $stuller_short_desc;
					}
					$sanitize_string_prodid = str_replace( array( '\'', '"', ',' , ';', '<', '>',')','(','.','/' ), '', $product_title); 
					$sanitize_string_sku = str_replace( array( '\'', '"', ',' , ';', '<', '>',')','(','.',':' ), '', $stuller_sku); 
					$product_url = str_replace(' ', '-', strtolower($sanitize_string_prodid));
					$product_url = str_replace('--', '-', $product_url);
					$product_sku = str_replace(' ', '-', strtolower($sanitize_string_sku));
					$product_handle = $product_sku."-".$product_url;

					//API Call
					$get_product_endpoint = "/admin/api/".$apiversion."/products.json?handle=".$product_handle;
					$add_product_endpoint = "/admin/api/".$apiversion."/products.json";
					$get_locations_endpoint = "/admin/api/".$apiversion."/locations.json";
					$update_inventory_endpoint = "/admin/api/".$apiversion."/inventory_levels/set.json";
					$get_cart_endpoint = "/cart.json";
					
					$getProductRequestUrl = $shop_base_url.$get_product_endpoint;
					$addProductRequestUrl = $shop_base_url.$add_product_endpoint;
					$getProductLocationUrl = $shop_base_url.$get_locations_endpoint;
					$updateInvUrl = $shop_base_url.$update_inventory_endpoint;
					
					//CURL Call
					$resultProducts = getCurlData($getProductRequestUrl,$request_headers);
					$resultLocation = getCurlData($getProductLocationUrl,$request_headers);
					$locations = $resultLocation->locations;
					/*echo "<pre>";
					print_r($locations);*/
					foreach($locations as $key => $value)
					{
						if($value->active)
						{
							$location_id = $value->id;
						}
					}
		            

					
					//$resultProd = json_decode(json_encode($resultProducts), true);
		            
					//GetSKUs of All Products
					$arrSKUs = array();
					$arrVarIds = array();
					$in_shopify = false;

					if($resultProducts){
						foreach ($resultProducts->products as $main_key => $main_value) {
							
							foreach ($main_value->variants as $var_key => $var_value) {
								if($var_value->sku == $stuller_sku){
									$in_shopify = true;
									$prod_id = $main_value->id;
									$variation_id = $var_value->id;
									$inventory_item_id = $var_value->inventory_item_id;
								}
							}
						}
					}
					
					
					
					if(!$in_shopify)
					{
						if($backorder)
						{
							$inventory_policy = "continue";
						}
						else{
							$inventory_policy = "deny";
						}

						$productVendor = "GemFindStuller";
						$productType = "Rings";
						$arrProduct = array(
							"product" => array(
								"title" => $product_title,
								"body_html" => $stuller_short_desc,
								"handle" => $product_handle,
								"vendor" => $productVendor,
								"product_type" => $productType,
								// "tags" => $tags,
								"images" => $arrImages,
								"published_scope" => "web",
								"variants" => array(array(
									"price" => $stuller_price,
									"sku" => $stuller_sku,
									"weight" => $stock_weight,
									"inventory_management" => "shopify",
									"inventory_quantity" => $stuller_stock,
									"inventory_policy" => $inventory_policy
								)),
                                "metafields" => array(array(
                                    "namespace" => "seo",
                                    "key" => "hidden",
                                    "value" => 1,
                                    "type" => "integer"
                                )),
                                "sales_channels" => ["online"]
							)
						);
						$product_add_post_data = json_encode($arrProduct);

						// create product in shopify
						$resultProd = postCurlData($addProductRequestUrl,$request_headers,$product_add_post_data,"POST"); 
						$filename_log='testlog.txt';
						file_put_contents($filename_log, $product_add_post_data.$addProductRequestUrl.json_encode($request_headers));
						// product add to cart
						$resultProd = json_decode(json_encode($resultProd), true);
						$variants_id = $resultProd["product"]["variants"][0]["id"];		

						 $referer = $_SERVER['HTTP_REFERER'] ?? '';

				        // Parse the URL to extract only the scheme and host
				        $parsedUrl = parse_url($referer);
				        $shop_base_url = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];

						// echo "<pre>";print_r($resultProd);exit;
						$chekcout_url = $shop_base_url."/cart/add?id=".$variants_id."&quantity=".$qty.$lineProperties;
						echo json_encode(array("VariantId" => $variants_id,"Quantity" => $qty,"AddToCart" => $chekcout_url,"custom_msg" => $custom_msg,"onhand" => $stuller_data["Products"][0]["OnHand"],"leadtime" => $stuller_data["Products"][0]["LeadTime"],"In shopify"=> "No", "outofstock" => $outofstock));
					}
					else
					{

						 $referer = $_SERVER['HTTP_REFERER'] ?? '';

				        // Parse the URL to extract only the scheme and host
				        $parsedUrl = parse_url($referer);
				        $shop_base_url = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        
						$update_variant_url = $shop_base_url."/admin/api/".$apiversion."/variants/".$variation_id.".json";
						$price_post_data = '{"variant": {"id":'.$variation_id.', "price": "'.$stuller_price.'"}}';
						$resultPriceUpdate = postCurlData($update_variant_url,$request_headers,$price_post_data,"PUT");
						$inv_post_data = '{"location_id": '.$location_id.',"inventory_item_id": '.$inventory_item_id.',"available": '.$stuller_stock.'}';
						$resultInvUpdate = postCurlData($updateInvUrl,$request_headers,$inv_post_data,"POST");
						/*echo "<pre>";
						print_r($resultInvUpdate);*/
						$chekcout_url = $shop_base_url."/cart/add?id=".$variation_id."&quantity=".$qty.$lineProperties;
						echo json_encode(array("VariantId" => $variation_id,"Quantity" => $qty,"AddToCart" => $chekcout_url,"custom_msg" => $custom_msg,"onhand" => $stuller_data["Products"][0]["OnHand"],"leadtime" => $stuller_data["Products"][0]["LeadTime"],"In shopify"=> "Yes", "outofstock" => $outofstock));
					}
				}

			
			} catch (Exception $e) {
				redirect($this->agent->referrer().'/error');
			}
		}
 	}
}
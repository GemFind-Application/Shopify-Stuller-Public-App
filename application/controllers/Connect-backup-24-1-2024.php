<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Connect extends CI_Controller {

	function __construct()
	{  	
		parent::__construct();
		$this->load->model('general_model');
		$this->load->library('stuller_lib');
		$this->load->helper('url');
		$this->load->library('email');
		$config = array(
		    'protocol' =>PROTOCOL, 
		    'smtp_host' => SMTP_HOST, 
		    'smtp_port' => SMTP_PORT,
		    'smtp_user' => SMTP_USER,
		    'smtp_pass' => SMTP_PASSWORD,
		    'smtp_crypto' => 'tls', 
		    'mailtype' => 'html', 
		    'smtp_timeout' => '4', 
		    'charset' => 'utf-8',
		    'wordwrap' => TRUE,
		    'newline' => "\r\n"
		);
		$this->email->initialize($config);
		if($this->input->get('path_prefix')){
			header('Content-Type: application/liquid');
			//header('X-Frame-Options: allow-from https://alltime.fit');
			
		}
		//header('Set-Cookie: cross-site-cookie=name; SameSite=None; Secure');
	}

	public function index()
	{		
		$params = $_GET; // Retrieve all request parameters
		$hmac = $_GET['hmac']; // Retrieve HMAC request parameter

		$shop = $params['shop'];
		$api_key = $this->config->item('api_key');
		$shared_secret = $this->config->item('shared_secret');

		$params = array_diff_key($params, array('hmac' => '')); // Remove hmac from params
		ksort($params); // Sort params lexographically
		$computed_hmac = hash_hmac('sha256', http_build_query($params), $shared_secret);
		
		// Create Page Template At Admin Side
		$query = array(
			    "client_id" => $api_key, // Your API key
			    "client_secret" => $shared_secret, // Your app credentials (secret key)
			    "code" => $params['code'] // Grab the access key from the URL
			  );
		$access_token_url = "https://" . $params['shop'] . "/admin/oauth/access_token";
		$access_token = getShopToken($access_token_url,$query); 
		$shop_base_url = "https://".$shop;
		$shopify_api_version = $this->config->item('shopify_api_version');
		if(isset($_REQUEST['access_token']))
		{
			$request_headers = array(
				"X-Shopify-Access-Token:" . $_REQUEST['access_token'],
				"Content-Type:application/json"
			);
		}
		else{
			$request_headers = array(
				"X-Shopify-Access-Token:" . $access_token,
				"Content-Type:application/json"
			);
		}
		
		if($_SERVER['HTTP_SEC_FETCH_DEST'] == 'iframe'){
			// Use hmac data to check that the response is from Shopify or not
			if (hash_equals($hmac, $computed_hmac)) {
			  // Set variables for our request
			  $query = array(
			    "client_id" => $api_key, // Your API key
			    "client_secret" => $shared_secret, // Your app credentials (secret key)
			    "code" => $params['code'] // Grab the access key from the URL
			  );
			  $access_token_url = "https://" . $params['shop'] . "/admin/oauth/access_token";
			  $shop_access_token = getShopToken($access_token_url,$query); 

			  $data['access_key'] = $params['code'];
			  $data['access_token'] = $access_token;
			  $data['shop_url'] = $shop;
			  $data['stullerconfigdata'] = $this->general_model->getDiamondConfig($shop);
			  $data['recurring_charges_data'] = $this->general_model->generalGetData($shop,'shop','app_charges');


			  $redirect_base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
			  $redirect_base_url .= "://". @$_SERVER['HTTP_HOST'];
			  $redirect_base_url .= @$_SERVER['REQUEST_URI'];
			  if($this->input->post('SubmitThemeSetting') == "Publish")
			  {
				//AddPageTemplate
				// $shop_base_url = "https://".$shop;
				// $themeId = $_REQUEST['theme_integration'];
				// $shopify_api_version = $this->config->item('shopify_api_version');
				// $add_product_endpoint = "/admin/api/".$shopify_api_version."/themes/".$themeId."/assets.json";
				// $addProductRequestUrl = $shop_base_url.$add_product_endpoint;
				// $request_headers = array(
				// 		"X-Shopify-Access-Token:" . $_REQUEST["access_token"],
				// 		"Content-Type:application/json"
				// 	);				
				// $file_data = putAdminPageTemplate();	
				// $product_add_post_data = json_encode($file_data);
				// $resultProd = postCurlData($addProductRequestUrl,$request_headers,$product_add_post_data,"PUT");
				// $this->session->set_flashdata('SystemOptionMSG', "Data Saved");
				// redirect($redirect_base_url, 'refresh'); 
			  }
			  if(isset($_REQUEST['unpublish_theme']))
			  {
				//unpublish_theme
				// $shop_base_url = "https://".$shop;
				// $themeId = $_REQUEST['theme_integration'];
				// $shopify_api_version = $this->config->item('shopify_api_version');
				// $add_product_endpoint = "/admin/api/".$shopify_api_version."/themes/".$themeId."/assets.json?asset[key]=templates/page.gemfind-stuller.liquid";
				// $addProductRequestUrl = $shop_base_url.$add_product_endpoint;
				// $request_headers = array(
				// 		"X-Shopify-Access-Token:" . $_REQUEST["access_token"],
				// 		"Content-Type:application/json"
				// 	);				
				// $file_data = putAdminPageTemplate();	
				// $product_add_post_data = json_encode($file_data);
				// $resultProd = postCurlData($addProductRequestUrl,$request_headers,$product_add_post_data,"PUT");
				// $this->session->set_flashdata('SystemOptionMSG', "Data Saved");
				// //GetStoreTheme
				// $get_product_endpoint = "/admin/api/".$shopify_api_version."/themes.json";
				// $getProductRequestUrl = $shop_base_url.$get_product_endpoint;
				// $resultThemes = getCurlData($getProductRequestUrl,$request_headers);
				// $data['resultThemes'] = $resultThemes;
				// redirect($redirect_base_url, 'refresh'); 
			  }
			  if(isset($_REQUEST['SubmitStullerSetting']))
			  {
					// Generate access token URL
					$access_token_url = "https://" . $params['shop'] . "/admin/oauth/access_token";
					// Configure curl client and execute request
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_URL, $access_token_url);
					curl_setopt($ch, CURLOPT_POST, count($query));
					curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
					$result = curl_exec($ch);
					curl_close($ch);
					// Store the access token
					$result = json_decode($result, true);
					if($result['access_token']){
						$access_token = $result['access_token']; 
					}elseif($shop_access_token){
						$access_token = $shop_access_token; 
					}else{
						$access_token = $this->input->post('sp_access_token');
					}
					
					$adminOptionData = array();
					$adminOptionData = array(
					  'enable_app'  => $this->input->post('enable_app'),
					  'showcase_url'  => $this->input->post('showcase_url'),
					  'api_url'    => $this->input->post('api_url'),
					  'api_username'    => $this->input->post('api_username'),
					  'api_password'    => $this->input->post('api_pwd'),
					  'shop_access_token' => $this->input->post('access_token')
					  );
					  $adminOptionData['shop'] = $shop;
					if($data['stullerconfigdata']){	

						//Update Setting
						$adminOptionData['updated_date'] = date('Y-m-d h:i:s');
						if($access_token){
							$adminOptionData['shop_access_token'] = $access_token;
						}
						
						$this->general_model->updateData($adminOptionData,$shop);

						redirect($redirect_base_url, 'refresh'); 
						//echo '<pre>'; print_r($data1); exit;

						//GetStoreTheme
						// $get_product_endpoint = "/admin/api/".$shopify_api_version."/themes.json";
						// $getProductRequestUrl = $shop_base_url.$get_product_endpoint;
						// $resultThemes = getCurlData($getProductRequestUrl,$request_headers);
						// foreach ($resultThemes->themes as $main_key => $main_value) {
						// 	if($main_value->role == "main")
						// 	{
						// 		//AddPageTemplate
						// 		$shop_base_url = "https://".$shop;
						// 		$themeId = $main_value->id;
						// 		$shopify_api_version = $this->config->item('shopify_api_version');
								
						// 		//unpublish_theme
						// 		$theme_endpoint = "/admin/api/".$shopify_api_version."/themes/".$themeId."/assets.json?asset[key]=templates/page.gemfind-stuller.liquid";
						// 		$ThemeRequestUrl = $shop_base_url.$theme_endpoint;
						// 		$request_headers = array(
						// 				"X-Shopify-Access-Token:" . $_REQUEST["access_token"],
						// 				"Content-Type:application/json"
						// 			);				
						// 		$file_data = putAdminPageTemplate($adminOptionData);	
						// 		$theme_update_post_data = json_encode($file_data);
						// 		$resultProd = postCurlData($ThemeRequestUrl,$request_headers,$theme_update_post_data,"PUT");
								
						// 		$publish_theme_endpoint = "/admin/api/".$shopify_api_version."/themes/".$themeId."/assets.json";
						// 		$pThemeRequestUrl = $shop_base_url.$publish_theme_endpoint;
						// 		$request_headers = array(
						// 				"X-Shopify-Access-Token:" . $_REQUEST["access_token"],
						// 				"Content-Type:application/json"
						// 			);				
						// 		$file_data = putAdminPageTemplate($adminOptionData);	
						// 		$theme_update_post_data = json_encode($file_data);
						// 		$resultProd = postCurlData($pThemeRequestUrl,$request_headers,$theme_update_post_data,"PUT");
						// 	}
						// }
					}else{						
						$adminOptionData['shop'] = $shop;
						if($access_token){
							$adminOptionData['shop_access_token'] = $access_token;
						}
						$this->general_model->addData($adminOptionData);
						//GetStoreTheme
						// $get_product_endpoint = "/admin/api/".$shopify_api_version."/themes.json";
						// $getProductRequestUrl = $shop_base_url.$get_product_endpoint;
						// $resultThemes = getCurlData($getProductRequestUrl,$request_headers);
						// foreach ($resultThemes->themes as $main_key => $main_value) {
						// 	if($main_value->role == "main")
						// 	{
						// 		//AddPageTemplate
						// 		$shop_base_url = "https://".$shop;
						// 		$themeId = $main_value->id;
						// 		$shopify_api_version = $this->config->item('shopify_api_version');
						// 		$add_product_endpoint = "/admin/api/".$shopify_api_version."/themes/".$themeId."/assets.json";
						// 		$addProductRequestUrl = $shop_base_url.$add_product_endpoint;
						// 		$request_headers = array(
						// 				"X-Shopify-Access-Token:" . $_REQUEST["access_token"],
						// 				"Content-Type:application/json"
						// 			);				
						// 		$file_data = putAdminPageTemplate($adminOptionData);	
						// 		$product_add_post_data = json_encode($file_data);
						// 		$resultProd = postCurlData($addProductRequestUrl,$request_headers,$product_add_post_data,"PUT");
						// 	}
						// }
					}
					$this->session->set_flashdata('SystemOptionMSG', "Data Saved");
					redirect($redirect_base_url, 'refresh'); 
			  }
			  //GetStoreTheme
				// $get_product_endpoint = "/admin/api/".$shopify_api_version."/themes.json";
				// $getProductRequestUrl = $shop_base_url.$get_product_endpoint;
				// $resultThemes = getCurlData($getProductRequestUrl,$request_headers);
				// $data['resultThemes'] = $resultThemes;
				$dappcharges = $this->general_model->getAppChargesData($shop);
				//echo "<pre>";print_r($dappcharges);echo "</pre>";
				$dconfig = $this->general_model->getDiamondConfigData($shop);
				//echo "<pre>";print_r($dconfig);echo "</pre>";
				$customerData = $this->general_model->getCustomerData($shop);
				//echo "<pre>";print_r($customerData);echo "</pre>";
				if($dappcharges != "")
				{
					if($dconfig == "" && $customerData == "")
					{
					  $data["customer"] = "";
					}
					else
					{
					  $data["customer"] = "existence";
					}
				}
				else if($dconfig != ""){
					if($customerData != "")
					{
					  $data["customer"] = "existence";
					}
					else
					{
					  $data["customer"] = "";
					}
				}		
				else
				{
					if($customerData != "")
					{
					  $data["customer"] = "existence";
					}
					else
					{
					  $data["customer"] = "";
					}
				}
				$data["currentshop"] = $shop;
				//echo '<pre>'; print_r($data);
			    $this->load->view('admin_form', $data);

			} else {
			  // Someone is trying to be shady!
			  die('This request is NOT from Shopify!');
			}
		}else{
			$this->installnewhubspotStuller($shop,$access_token);
		    $redirectURL = 'https://'.$shop.'/admin/apps/gemfind-stuller';
		    $this->registerShopifyAppUninstallWebhookStuller($shop,$access_token);
			redirect($redirectURL, 'refresh');
			exit;
		}
	}

	public function registerShopifyAppUninstallWebhookStuller($shop_domain,$access_token){
		 $API_KEY =$this->config->item('api_key');
		 $SECRET = $this->config->item('shared_secret');
		 $STORE_URL = $shop_domain;
		 $params = $_GET;
		
		$TOKEN = $access_token; 
		$url = 'https://'. $API_KEY . ':' . md5($SECRET . $TOKEN) . '@' . $STORE_URL . '/admin/webhooks.json';
		$paramshook = array("webhook" => array( "topic"=>"app/uninstalled",
			"address"=> base_url()."webhook/setShopifyUninstall",
			"format"=> "json"));

        $file='api_token_log.txt';
        $result_log = $API_KEY .' '.$SECRET .' '.$STORE_URL .' '.$params .' '.$query .' '.$access_token_url .' '.$shop_access_token .' '.$TOKEN .' '.$url .' '.$paramshook;
        file_put_contents($file, $result_log);

		$session = curl_init();

		curl_setopt($session, CURLOPT_URL, $url);
		curl_setopt($session, CURLOPT_POST, 1);
		curl_setopt($session, CURLOPT_POSTFIELDS, stripslashes(json_encode($paramshook)));
		curl_setopt($session, CURLOPT_HEADER, false);
		curl_setopt($session, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json', 'X-Shopify-Access-Token: '.$TOKEN));
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($session);

		curl_close($session);
	  	// Store the access token
		$result = json_decode($result, true);
		// $this->installnewhubspotStuller($shop_domain,$access_token);
	}


	public function  installnewhubspotStuller($shop,$access_token)
    {
    
    	 $request_headers = array(
					"X-Shopify-Access-Token:" . $access_token,
					"Content-Type:application/json"
				);


	$shop_detail_api_url = "https://" . $shop . "/admin/shop.json";
	$resultShop = getCurlData($shop_detail_api_url,$request_headers);

	//$resultShop = json_encode($shop_access_token, true);
	$file = "result_log11.txt";
     	file_put_contents($file, $resultShop );

  	$now = new DateTime();
	$now->setTimezone(new DateTimezone('Asia/Kolkata'));
	$current_date = $now->format('Y-m-d H:i:s');
	$domain = $resultShop->shop->domain;

	$arr = array(
	    'filters' => array(
	     array(
	        'propertyName' => 'email',
	        'operator' => 'EQ',
	        'value' => $resultShop->shop->email
	      )
	    )
	);
	$post_json = json_encode($arr);

	$file = "newarray_log1.txt";
    file_put_contents($file, $post_json);

    $email_id=$resultShop->shop->email;
    $endpoint ='https://api.hubapi.com/contacts/v1/contact/email/'.$email_id.'/profile';
    $ch = curl_init();
    $headers = [
	    'Content-Type: application/json',	
	    'Authorization: Bearer ' . YOUR_ACCESS_TOKEN,
	];
    //curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_errors = curl_error($ch);
    curl_close($ch);

    $file = $domain."install_status_log".time().".txt";
    file_put_contents($file, $status_code);

    $file = $domain."install_response_log".time().".txt";
    file_put_contents($file, $response);

    if ($status_code == 200) {
		$arr1 = array(
            'properties' => array(
                array(
                    'property' => 'email',
                    'value' => $resultShop->shop->email
                ),
                array(
                    'property' => 'Install_Date',
                    'value' => $current_date
            	),
               array(
                    'property' => 'app_status',
                    'value' => 'INSTALL-STULLER'
                )
            )
        );
		$post_json1 = json_encode($arr1);
        $email_id1=$resultShop->shop->email;
        $endpoint1 ='https://api.hubapi.com/contacts/v1/contact/email/'.$email_id1.'/profile';


       $ch1 = curl_init();
        $headers = [
		    'Content-Type: application/json',	
		    'Authorization: Bearer ' . YOUR_ACCESS_TOKEN,
		];
        curl_setopt($ch1, CURLOPT_POST, true);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $post_json1);
        curl_setopt($ch1, CURLOPT_URL, $endpoint1);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);

		// Execute the request and get the response
		$response = curl_exec($ch);

        $response1 = curl_exec($ch1);
        $status_code1 = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
        $curl_errors1 = curl_error($ch1);
        curl_close($ch1);

        $file = $domain."reinstall_status_log".time().".txt";
        file_put_contents($file, $status_code1);

        $file = $domain."reinstall_response_log".time().".txt";
        file_put_contents($file, $response1);
    }  else{
		$arr = array(
        'properties' => array(
            array(
                'property' => 'email',
                'value' => $resultShop->shop->email
            ),
            array(
                'property' => 'shop_name',
                'value' => $resultShop->shop->name
            ),
            array(
                'property' => 'domain_name',
                'value' => $resultShop->shop->domain
            ),
            array(
                'property' => 'phone',
                'value' => $resultShop->shop->phone
            ),
            array(
                'property' => 'state',
                'value' => $resultShop->shop->province
            ),
            array(
                'property' => 'country',
                'value' => $resultShop->shop->country
            ),
            array(
                'property' => 'address',
                'value' => $resultShop->shop->address1
            ),
            array(
                'property' => 'city',
                'value' => $resultShop->shop->city
            ),
             array(
                    'property' => 'Install_Date',
                    'value' => $current_date
            	),
            array(
                'property' => 'app_status',
                'value' => 'INSTALL-STULLER'
            )
        )
    );
    $post_json = json_encode($arr);
    // $file = "post_data_log.txt";
    // file_put_contents($file, $post_json);
    $endpoint = 'https://api.hubapi.com/contacts/v1/contact';

    $ch = curl_init();
    $headers = [
	    'Content-Type: application/json',	
	    'Authorization: Bearer ' . YOUR_ACCESS_TOKEN,
	];
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_errors = curl_error($ch);
    curl_close($ch);
    $file = $domain."fresh_install_response_log".time().".txt";
    file_put_contents($file, $response);
    $file = $domain."fresh_install_status_log".time().".txt";
    file_put_contents($file, $status_code);
    return;
	}

    }

	public function SubmitCustomerInfo()
	{
		$customerData = array(
		  'business'  => $this->input->post('business'),
		  'name'  => $this->input->post('name'),
		  'address'  => $this->input->post('address'),
		  'state'  => $this->input->post('state'),
		  'city'  => $this->input->post('city'),
		  'zip_code'  => $this->input->post('zip_code'),
		  'telephone'  => $this->input->post('telephone'),
		  'website'    => $this->input->post('website_url'),
		  'email'    => $this->input->post('email'),
		  'shop'    => $this->input->post('shop'),
		  'notes'    => $this->input->post('notes')
		);
		$this->general_model->addCustomerData($customerData);
		// Send Email 					
		$templateValueReplacement = array(
			'{{shopurl}}' => $this->input->post('shop'), 
			'{{business}}' => $this->input->post('business'),
			'{{name}}' => $this->input->post('name'),
			'{{address}}' => $this->input->post('address'),
			'{{state}}' => $this->input->post('state'),
			'{{city}}' => $this->input->post('city'),
			'{{zip_code}}' => $this->input->post('zip_code'),
			'{{telephone}}' => $this->input->post('telephone'),
			'{{website}}' => $this->input->post('website_url'),
			'{{email}}' => $this->input->post('email'),
			'{{notes}}' => $this->input->post('notes')
		);
		// Send Email to customer
		$customer_template = $this->load->view('emails/customer_customer_info_template.html','',true);                
		$customer_email_body = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $customer_template);	
		$customer_subject = "Customer Information Form";
		$customer_toEmail = $this->input->post('email');
		$this->email->from('smtp@gemfind.com', 'GemFind Stuller');
		$this->email->to($customer_toEmail);
		$this->email->subject($customer_subject);
		$this->email->message($customer_email_body);
		$this->email->send();
							
		// Send Email to Admin
		$admin_template = $this->load->view('emails/admin_customer_info_template.html','',true);
		$admin_email_body = str_replace(array_keys($templateValueReplacement), array_values($templateValueReplacement), $admin_template);	
		$admin_subject = ucfirst($this->input->post('business')).": New Shopify Stuller";
		$admin_toEmail = "appinstall@gemfind.com";
		//$admin_toEmail = "rahul.evdpl@gmail.com";
		$this->email->from('smtp@gemfind.com', 'GemFind Stuller');
		$this->email->to($admin_toEmail);
		$this->email->subject($admin_subject);
		$this->email->message($admin_email_body);
		$this->email->send();
		
		//$this->session->set_flashdata('SystemOptionMSG', "Data has been saved successfully!!");
		echo json_encode($this->session->flashdata('SystemOptionMSG'));
		//redirect($redirect_base_url, 'refresh'); 
		$this->installhubspotStuller($templateValueReplacement);
	}

	public function installhubspotStuller($templateValueReplacement){
	

		$shop_access_token = $this->input->post('access_token'); 
	    $domain = $this->input->post('website_url');


	     $request_headers = array(
					"X-Shopify-Access-Token:" . $shop_access_token,
					"Content-Type:application/json"
				);


		$shop_detail_api_url = "https://" . $domain . "/admin/shop.json";
		$resultShop = getCurlData($shop_detail_api_url,$request_headers);

		
		$now = new DateTime();
		$now->setTimezone(new DateTimezone('Asia/Kolkata'));
		$current_date = $now->format('Y-m-d H:i:s');

		

		$arr = array(
		    'filters' => array(
		     array(
		        'propertyName' => 'email',
		        'operator' => 'EQ',
		        'value' => $resultShop->shop->email
		      )
		    )
		);
		$post_json = json_encode($arr);

		$file = "array_log.txt";
        file_put_contents($file, $post_json);



        $email_id=$resultShop->shop->email;
        $endpoint ='https://api.hubapi.com/contacts/v1/contact/email/'.$email_id.'/profile';
        $ch = curl_init();
        $headers = [
		    'Content-Type: application/json',	
		    'Authorization: Bearer ' . YOUR_ACCESS_TOKEN,
		];
        //curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errors = curl_error($ch);
        curl_close($ch);

        $file = $domain."customer_status_log".time().".txt";
        file_put_contents($file, $status_code);

        $file = $domain."customer_response_log".time().".txt";
        file_put_contents($file, $response);

        if ($status_code == 200) {
			$arr1 = array(
	            'properties' => array(
	                array(
	                    'property' => 'email',
	                    'value' => $this->input->post('email')
	                ),
	                array(
	                    'property' => 'Install_Date',
	                    'value' => $current_date
                	),
                	
	               array(
	                    'property' => 'app_status',
	                    'value' => 'REGISTER-STULLER'
	                )
	            )
	        );
			$post_json1 = json_encode($arr1);
			file_put_contents('post_data_dia.txt',$post_json1);
	        $email_id1=$resultShop->shop->email;
	        $endpoint1 ='https://api.hubapi.com/contacts/v1/contact/email/'.$email_id1.'/profile';
	        
	        $ch1 = curl_init();
	        $headers = [
			    'Content-Type: application/json',	
			    'Authorization: Bearer ' . YOUR_ACCESS_TOKEN,
			];
	        curl_setopt($ch1, CURLOPT_POST, true);
	        curl_setopt($ch1, CURLOPT_POSTFIELDS, $post_json1);
	        curl_setopt($ch1, CURLOPT_URL, $endpoint1);
	        curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
	        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
	        $response1 = curl_exec($ch1);
	        $status_code1 = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
	        $curl_errors1 = curl_error($ch1);
	        curl_close($ch1);

	        $file = $domain."customer_re_register_status_log".time().".txt";
	        file_put_contents($file, $status_code1);

	        $file = $domain."customer_re_register_response_log".time().".txt";
	        file_put_contents($file, $response1);
        }  else{

        	 //echo "<pre>"; print_r($response); exit();

        	$arr2 = array(
            'properties' => array(
                array(
                    'property' => 'email',
                    'value' => $this->input->post('email')
                ),
                array(
                    'property' => 'shop_name',
                    'value' => $this->input->post('shop')
                ),
                array(
                    'property' => 'domain_name',
                    'value' => $this->input->post('website_url')
                ),
                array(
                    'property' => 'phone',
                    'value' => $this->input->post('telephone')
                ),
                array(
                    'property' => 'state',
                    'value' => $this->input->post('state')
                ),
                array(
                    'property' => 'address',
                    'value' => $this->input->post('address')
                ),
                array(
                    'property' => 'city',
                    'value' => $this->input->post('city')
                ),
               
                array(
	                    'property' => 'Install_Date',
	                    'value' => $current_date
                ),
                array(
                    'property' => 'app_status',
                    'value' => 'REGISTER-STULLER'
                )
            )
        );
        $post_json2 = json_encode($arr2);
        $file = "post_data_log3.txt";
        file_put_contents($file, $post_json2);
        $endpoint2 = 'https://api.hubapi.com/contacts/v1/contact';
        $ch2 = curl_init();
        $headers = [
		    'Content-Type: application/json',	
		    'Authorization: Bearer ' . YOUR_ACCESS_TOKEN,
		];
        curl_setopt($ch2, CURLOPT_POST, true);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $post_json2);
        curl_setopt($ch2, CURLOPT_URL, $endpoint2);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        $response2 = curl_exec($ch2);
        $status_code2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
        $curl_errors2 = curl_error($ch2);
        curl_close($ch2);
       // echo "<pre>"; print_r($response2); exit();
        $file = $domain."customer_register_response_log".time().".txt";
        file_put_contents($file, $response2);
        $file = $domain."customer_register_status_log".time().".txt";
        file_put_contents($file, $status_code2);
        }
	}
}
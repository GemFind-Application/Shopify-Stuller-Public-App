<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Charge extends CI_Controller {

	function __construct()
	{  	
		parent::__construct();
		$this->load->model('general_model');
		//header('X-Frame-Options: allow-from https://gemfind-demo-store-8.myshopify.com');
		//header('Set-Cookie: cross-site-cookie=name; SameSite=None; Secure');
	}

	public function index()
	{
		$api_version = '2023-04';
		$params = $_GET; // Retrieve all request parameters
		$shop = $params['shop'];
		if( isset($params['charge_id']) && $params['charge_id'] != '' ) {
		  	$access_tk_session = $this->session->userdata($shop.'_access_tk');
			$charge_id = $params['charge_id'];

			/*$activation_array = array(
				'recurring_application_charge' => array(
					"id" => $charge_id,
				    "name" => "GemFind Basic Plan",
				    "api_client_id" => rand(1000000, 9999999),
				    "price" => "89.00",
				    "status" => "accepted",
				    "return_url" => "https://".$shop."/admin/apps/gemfind-stuller/",
				    "billing_on" => date('Y-m-d'),
				    "test" => null,
				    "activated_on" => null,
				    "trial_ends_on" => null,
				    "cancelled_on" => null,
				    "trial_days" => 0,
				    "decorated_return_url" => "https://".$shop."/admin/apps/gemfind-stuller?charge_id=" . $charge_id
				)
			);
			$activation_data = json_encode($activation_array);
			$response_activate = shopify_call($access_tk_session, $shop, "/admin/api/2020-07/recurring_application_charges/".$charge_id."/activate.json", $activation_data, 'POST');*/
			$request_headers = array(
		        "X-Shopify-Access-Token:" . $access_tk_session,
		        "Content-Type:application/json"
		    );
		    $charge_detail_endpoint = "https://".$shop."/admin/api/".$api_version."/recurring_application_charges/".$charge_id.".json";
			$response_activate = getCurlData($charge_detail_endpoint,$request_headers);
			
			$result_recurring_charge = $this->general_model->generalGetData($shop,'shop','app_charges');

			$recurring_charges_data = array(
	                  'charge_id'  => $charge_id,
	                  'plan'  => "GemFind Basic Plan",
	                  'api_client_id'  => $response_activate->recurring_application_charge->api_client_id,
	                  'status'    => $response_activate->recurring_application_charge->status,
	                  'price' => $response_activate->recurring_application_charge->price,
	                  'shop' => $shop,
	                  'billing_on' => date('Y-m-d')
	              );
			if($result_recurring_charge){
				$recurring_charges_update_data = array(
	                  'charge_id'  => $charge_id,
	                  'plan'  => "GemFind Basic Plan",
	                  'api_client_id'  => $response_activate->recurring_application_charge->api_client_id,
	                  'status'    => $response_activate->recurring_application_charge->status,
	                  'price' => $response_activate->recurring_application_charge->price,
	                  'shop' => $shop,
	                  'billing_on' => date('Y-m-d')
	              );
				$this->general_model->generalUpdateData($recurring_charges_update_data,'shop',$shop,'app_charges');
			}else{
				$this->general_model->generalAddData($recurring_charges_data,'app_charges');
			}
			$redirect_to_app = "https://".$shop."/admin/apps/gemfind-stuller?charge_id=" . $charge_id;
			header("Location: ".$redirect_to_app);
			exit;
		}else{
			$access_token = $params['code_access'];
			$this->session->set_userdata($shop.'_access_tk', $access_token);
			unset($params['code_access']);
			unset($params['host']);
			$query_string = http_build_query($params); 
			$recurring_charges = array(
				"recurring_application_charge" => array(
					"name" => "Transactional Stuller Showcase Powered By GemFind",
					"test" => null, 
					"trial_days"=> "7",
      				"trial_ends_on"=>date('Y-m-d', strtotime('+7 days')),
					"price" => "189.00",
					"return_url" => base_url()."charge?" . $query_string
				)
			);
			$recurring_charge_data = json_encode($recurring_charges);
			$charge = shopify_call($access_token, $shop, "/admin/api/".$api_version."/recurring_application_charges.json", $recurring_charge_data, "POST");
			header("Location: " . $charge->recurring_application_charge->confirmation_url);
			exit;
		}
	}
}
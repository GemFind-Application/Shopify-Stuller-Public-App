<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('header');

$store_token = $access_token; 
$footer_data['shop_access_token'] = $store_token;

if (empty($access_token)) {
	$store_token = $stullerconfigdata ? $stullerconfigdata->shop_access_token : '';
}

$charge_status = $charge_id = $enable_app = '';
if($recurring_charges_data){
	$charge_id = $recurring_charges_data->charge_id;
	$charge_status = $recurring_charges_data->status;
}
// print '<pre>';
 // print_r($stullerconfigdata); 
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://unpkg.com/@shopify/polaris@4.5.0/styles.min.css" />

<style type="text/css">
	body{background-color:#fff;}
.view-in-front, .view-in-front:active, .view-in-front:focus{
    box-sizing: border-box;
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 3.6rem;
    min-width: 3.6rem;
    margin: 0;
    padding: .7rem 1.6rem;
    border: .1rem solid var(--p-border,#c4cdd5);
    box-shadow: 0 1px 0 0 rgba(22,29,37,.05);
    border-radius: 3px;
    line-height: 1;
    color: #212b36;
    text-align: center;
    cursor: pointer;
    user-select: none;
    text-decoration: none;
    background: linear-gradient(180deg,#f9fafb,#f4f6f8);
    border-color: #c4cdd5;
}
.view-in-front:hover{box-shadow: 0 0 0 0 transparent, inset 0 1px 1px 0 rgba(99,115,129,.1), inset 0 1px 4px 0 rgba(99,115,129,.2); text-decoration: none;}
.dearlercode-form, .upgrade_message{padding: 12px 30px}
.alert {position: relative;padding: .75rem 1.25rem;margin-bottom: 1rem;border: 1px solid transparent;border-radius: .25rem;}
.alert-success {color: #155724;background-color: #d4edda;border-color: #c3e6cb;}
.form-body {padding: 2rem;background-color: #f4f4f4;}
form#SystemOption {
    margin-bottom: 45px;
}
.form-actions.right {
    padding: 0 2rem;
}
#SubmitDiamondSetting{
background: linear-gradient(180deg,#6371c7,#5563c1);
border-color: #3f4eae;
box-shadow: inset 0 1px 0 0 #6774c8, 0 1px 0 0 rgba(22,29,37,.05), 0 0 0 0 transparent;
color: #fff;
padding: 6px 25px;
}
#SubmitDiamondSetting:hover{background: linear-gradient(180deg,#5c6ac4,#4959bd);border-color: #3f4eae;color: #fff;text-decoration: none;}
.getting-started-banner{display: flex;}
.getting-started-banner .getting-started-banner--image {
    width: 240px;
    height: auto;
    display: inline-flex;
    padding: 60px 30px;
    background-color: #F9FAFB;
    border: 1px solid #DFE3E8;
}
.getting-started-banner .getting-started-banner--image img {
    max-width: 100%;
    width: 100%;
    object-fit: contain;
}
.getting-started-banner .getting-started-banner--content {
    width: 80%;
    padding: 30px;
    background-color: #F9FAFB;
	border: 1px solid #DFE3E8;
}
.getting-started-banner .getting-started-banner--content .banner__title {
    font-size: 16px;
    line-height: 24px;
    color: #31373D;
    font-weight: 500;
}
.getting-started-banner .getting-started-banner--content .banner__content {
    font-size: 14px;
    line-height: 20px;
    color: #212B36;
    margin: 20px 0;
}
.Polaris-Button a, .Polaris-Banner__PrimaryAction a{text-decoration: none;color: #212b36;}
.Polaris-Button a:hover,  .Polaris-Banner__PrimaryAction a:hover{text-decoration: none;}
.Polaris-Button:hover {
    background: linear-gradient(180deg,#f9fafb,#d7dadc);
    border-color: #c4cdd5;
}
#getting_started .Polaris-Layout__Section {
    padding-top: 35px;
    margin: 0;
    max-width: 100%;
}
#knowledge .Polaris-Layout__Section{margin: 0px;}
.Polaris-Layout{
	margin: 0;
	    padding-bottom: 40px;
}
#myTab {
    border: none;
}
.nav-tabs>li.nav-item>a.nav-link{border: none;border-radius: 4px 4px 4px 4px;}
.nav-tabs>li.nav-item.active>a.nav-link, .nav-tabs>li.nav-item.active>a.nav-link:focus, .nav-tabs>li.nav-item.active>a.nav-link:hover{border: none; background: linear-gradient(180deg,#6371c7,#5563c1);border-color: #3f4eae;box-shadow: inset 0 1px 0 0 #6774c8, 0 1px 0 0 rgba(22,29,37,.05), 0 0 0 0 transparent;color: #fff;}
.nav-tabs>li.nav-item>a.nav-link:hover{border: none; background: linear-gradient(180deg,#6371c7,#5563c1);border-color: #3f4eae;box-shadow: inset 0 1px 0 0 #6774c8, 0 1px 0 0 rgba(22,29,37,.05), 0 0 0 0 transparent;color: #fff;}
.help-text{
	    font-size: 29px;
    position: relative;
    display: inline-table;
    padding-top: 25px;
    padding-bottom: 25px;
    font-weight: 600;
}
li.Polaris-ResourceList__ItemWrapper{
	padding: 15px;
    border: 1px solid #ccc;
}
.Polaris-Layout__Section.help_center {
    padding-bottom: 35px;
}
.tab-content>.tab-pane{padding-top: 20px;}
.quick_links{float: right !important;margin-right: 5px;}
li.quick_links > a:active{margin-right: 2px;}
.nav-tabs>li.quick_links>a{border-radius: 4px 4px 4px 4px;padding: .7rem 1.6rem; border: 1px solid #c4cdd5;}
.card-header{font-size: 1rem;line-height: 1.5;color: #212529;text-align: left;font-family: "Roboto",sans-serif;font-weight: 400;box-sizing: border-box;margin-bottom: .25rem !important;box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16),0 2px 10px 0 rgba(0,0,0,0.12) !important;border-radius: 0;padding: 1rem 1.5rem;background: transparent;border: 0;}
.card-header .mb-0 .btn.btn-link{width: 100%;text-align: left;font-weight: 600;color:#212b36;font-size: 16px;}
.card-header .mb-0 .btn.btn-link:hover{text-decoration: none;}
#accordion .card-body{
	text-align: left;
	font-family: "Roboto",sans-serif;
	box-sizing: border-box;
	flex: 1 1 auto;
	min-height: 1px;
	padding: 1.25rem;
	margin-bottom: .25rem !important;
	background-color:rgb(189 189 189 / 30%);
	padding-top: 1.5rem;
	padding-bottom: 1.5rem;
	border-radius: 0 !important;
	font-size: 1em;
    font-weight: 400;
	line-height: 1.7;
	border: 0;
}
.card-header .mb-0 .btn.btn-link:focus {
    outline: none;
    text-decoration: none;
}
.rotate-icon{float: right;font-size: 23px;}
#accordion .card .card-header button:not(.collapsed) .rotate-icon {
    -webkit-transform: rotate(180deg);
    transform: rotate(180deg);
}
.field_usage_section {
    padding: 10px;
    line-height: 25px;
    color: black;
    border: 1px solid #ccc;
    transition: box-shadow .2s cubic-bezier(.64,0,.35,1);
    transition-delay: .1s;
    box-shadow: inset 0 3px 0 0 #47c1bf, inset 0 0 0 0 transparent, 0 0 0 1px rgba(63,63,68,.05), 0 1px 3px 0 rgba(63,63,68,.15);
    background-color: #eef9f9;
}
.field_item h3{font-weight: 600;}
#SubmitCustomerInfo{	
background: linear-gradient(180deg,#6371c7,#5563c1);	
border-color: #3f4eae;	
box-shadow: inset 0 1px 0 0 #6774c8, 0 1px 0 0 rgba(22,29,37,.05), 0 0 0 0 transparent;	
color: #fff;	
padding: 6px 25px;	
}	
#SubmitCustomerInfo{margin:15px;}	
.form-actions.right{padding:0 !important;}	
#SubmitDiamondSetting:hover,#SubmitCustomerInfo:hover{background: linear-gradient(180deg,#5c6ac4,#4959bd);border-color: #3f4eae;color: #fff;text-decoration: none;}	
.heading-customerform {	
    font-size: 29px;	
    position: relative;	
    display: inline-table;	
    padding-top: 25px;	
    padding-bottom: 25px;	
    font-weight: 600;	
}
.ji-certified{
		height: 20px;
}
.col-ji-certified{
	display:flex;
	margin:20px 0;
}
</style>

<script type="text/javascript">
	setTimeout(function(){
		$(".alert").fadeOut();
	},5000);
	$(function() {	
	  $('#loader').hide();	
	  $('.frmcustomer').submit(function(e) {	
		$('#loader').show();	
		e.preventDefault();	
			
		$.ajax({	
			url: "<?php echo site_url('Connect/SubmitCustomerInfo');?>",	
			method: "POST",	
			data: $(this).serialize(),	
		    dataType: 'JSON',	
			success: function(data){	
				$('#loader').hide();	
				$('.frmcustomer')[0].reset();	
				$('.frmcustomer').hide().delay(1000).slideUp();	
				$(".form-submitmsg").show().delay(5000).fadeOut();	
				setTimeout(function () { 	
					location.reload(true); 	
				}, 5000); 	
			}	
		});			
	  });	
	});
</script>
	<?php 
	$shop = $shop_logo = '';

	if($stullerconfigdata){
		if($stullerconfigdata->enable_app){
			$enable_app = $stullerconfigdata->enable_app;
			$enable_popup_message = $stullerconfigdata->enable_popup_message;
		}
	}
	?>
<div class="dearlercode-form">
	<?php 
	if($this->session->flashdata('SystemOptionMSG') != null){?>
	<div class="alert alert-success">
		<strong>Success!</strong> <?php echo $this->session->flashdata('SystemOptionMSG');?>
	</div>
	<?php } ?>
	<ul class="nav nav-tabs" id="myTab" role="tablist">
	  <li class="nav-item hide">
		<a class="nav-link hide" id="started-tab" data-toggle="tab" href="#getting_started" role="tab" aria-controls="getting_started" aria-selected="true">Getting Started</a>
	  </li>
	  <li class="nav-item active">
		<a class="nav-link active" id="setting-tab" data-toggle="tab" href="#setting" role="tab" aria-controls="setting" aria-selected="false">Settings</a>
	  </li>
	  <li class="nav-item">
		<a class="nav-link" id="knowledge-tab" data-toggle="tab" href="#knowledge" role="tab" aria-controls="knowledge" aria-selected="false">Knowledge Base</a>
	  </li>
	  <li class="quick_links">
		<a href="<?php echo $this->config->item('final_shop_url')."/admin/apps/"; ?>" target="_top" rel="noopener noreferrer" class="view-in-front">Back to Apps Listing</a>
	  </li>
	  <?php if($charge_id !='' &&  $charge_status == 'active'){?>
	  		<?php if($enable_app == 'true' && $stullerconfigdata->showcase_url){?>
			  <li class="quick_links">
				<a href="<?php echo $this->config->item('final_shop_url')."/apps/stuller/"; ?>" target="_blank" class="view-in-front">View in Frontend</a>
			  </li>
			<?php }?>
	   <?php }?>
	</ul>
<div class="tab-content" id="myTabContent">

<div class="tab-pane" id="knowledge" role="tabpanel" aria-labelledby="knowledge-tab">
	
	<div class="Polaris-Layout">
		<div class="Polaris-Layout__Section help_center">
			<span class="help-text">Help Center</span>
			 <div class="Polaris-Banner Polaris-Banner--statusInfo Polaris-Banner--withinPage" tabindex="0" role="status" aria-live="polite" aria-labelledby="Banner28Heading" aria-describedby="Banner28Content"><div class="Polaris-Banner__Ribbon"><span class="Polaris-Icon Polaris-Icon--colorTealDark Polaris-Icon--isColored Polaris-Icon--hasBackdrop"><svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path d="M10 0C4.486 0 0 4.486 0 10s4.486 10 10 10 10-4.486 10-10S15.514 0 10 0m0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8m0-4.1a1.1 1.1 0 1 0 .001 2.201A1.1 1.1 0 0 0 10 13.9M10 4C8.625 4 7.425 5.161 7.293 5.293A1.001 1.001 0 0 0 8.704 6.71C8.995 6.424 9.608 6 10 6a1.001 1.001 0 0 1 .591 1.808C9.58 8.548 9 9.616 9 10.737V11a1 1 0 1 0 2 0v-.263c0-.653.484-1.105.773-1.317A3.013 3.013 0 0 0 13 7c0-1.654-1.346-3-3-3"></path></svg></span></div><div class="Polaris-Banner__ContentWrapper"><div class="Polaris-Banner__Heading" id="Banner28Heading"><p class="Polaris-Heading">We’d love to hear from you</p></div><div class="Polaris-Banner__Content" id="Banner28Content"><p>Need help? Schedule a Free Consultation by clicking below link</p><div class="Polaris-Banner__Actions"><div class="Polaris-ButtonGroup"><div class="Polaris-ButtonGroup__Item"><div class="Polaris-Banner__PrimaryAction"><a target="_blank" class="Polaris-Button Polaris-Button--outline" href="https://gemfind.com/free-consultation/" rel="noopener noreferrer" data-polaris-unstyled="true"><span class="Polaris-Button__Content"><span class="Polaris-Button__Text">Free Consultation</span></span></a></div></div></div></div></div></div></div>
			</div>
			<div class="Polaris-Layout__Section">
		</div>
	</div>

</div>
  <div class="tab-pane active" id="setting" role="tabpanel" aria-labelledby="setting-tab">
<?php if(isset($customer) && $customer != "") { 
  		// echo '<pre>'; print_r($customer); exit();
  		if($charge_id =='' ||  $charge_status != 'active'){?>
	<div class="Polaris-Card__Section">
			<div class="Polaris-Banner Polaris-Banner--statusInfo Polaris-Banner--withinPage" id="Banner30Content">
							<div class="Polaris-Banner__Ribbon">
								<span class="Polaris-Icon Polaris-Icon--colorTealDark Polaris-Icon--isColored Polaris-Icon--hasBackdrop">
								<svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
										<path d="M10 0C4.486 0 0 4.486 0 10s4.486 10 10 10 10-4.486 10-10S15.514 0 10 0m0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8m0-4.1a1.1 1.1 0 1 0 .001 2.201A1.1 1.1 0 0 0 10 13.9M10 4C8.625 4 7.425 5.161 7.293 5.293A1.001 1.001 0 0 0 8.704 6.71C8.995 6.424 9.608 6 10 6a1.001 1.001 0 0 1 .591 1.808C9.58 8.548 9 9.616 9 10.737V11a1 1 0 1 0 2 0v-.263c0-.653.484-1.105.773-1.317A3.013 3.013 0 0 0 13 7c0-1.654-1.346-3-3-3">
										</path>
								</svg>
								</span>
							</div>
							<div class="Polaris-Banner__ContentWrapper">
								<div class="Polaris-Banner__Heading">
									<p class="Polaris-Heading">Disclaimer</p>
								</div>
								<div class="Polaris-Banner__Content">
									<p>Please check the tool online to make sure it works for your online jewelry store. Once you have installed and activated, there will be NO refunds, since the account has to be setup and training needs to be provided. Here is a <a target=_blank href="https://gemfind-product-demo-site.myshopify.com/apps/stuller"> link </a> to our demo store, please check and make sure it meets your expectations. Contact us if you have any questions prior to activation at <a href="mailto:support@gemfind.com"> support@gemfind.com </a> or  call <a href="tel:+19497527710"> +19497527710 </a>.</p>
									
								</div>
							</div>
						</div>
		<div class="Polaris-Banner Polaris-Banner--statusInfo Polaris-Banner--withinContentContainer" tabindex="0" role="status" aria-live="polite" aria-labelledby="Banner8Heading" aria-describedby="Banner8Content">
			<div class="Polaris-Banner__Ribbon">
				<span class="Polaris-Icon Polaris-Icon--colorTealDark Polaris-Icon--isColored Polaris-Icon--hasBackdrop">
					<svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><circle cx="10" cy="10" r="9" fill="currentColor"></circle><path d="M10 0C4.486 0 0 4.486 0 10s4.486 10 10 10 10-4.486 10-10S15.514 0 10 0m0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8m1-5v-3a1 1 0 0 0-1-1H9a1 1 0 1 0 0 2v3a1 1 0 0 0 1 1h1a1 1 0 1 0 0-2m-1-5.9a1.1 1.1 0 1 0 0-2.2 1.1 1.1 0 0 0 0 2.2"></path></svg>
				</span>
			</div>
			<div>
				<div class="Polaris-Banner__Heading" id="Banner8Heading">
					<p class="Polaris-Heading">Transactional Stuller Showcase Powered By GemFind</p>
				</div>
				<div class="Polaris-Banner__Content" id="Banner8Content">
					<p>You have to subscribe to the Transactional Stuller Showcase Powered By GemFind before using the app. Click on below link to activate.</p>
					<p><a href="<?php echo base_url();?>charge?<?php echo $_SERVER['QUERY_STRING']."&code_access=".$access_token; ?>" target="_top">Activate</a></p>
				</div>
			</div>
		</div>
	</div>
<?php } } ?>
	<?php 	
	if(isset($customer) && $customer != "") { 	
		if($charge_id !='' &&  $charge_status == 'active'){	
	?>
	  	<div class="tab-pane" id="getting_started" role="tabpanel" aria-labelledby="started-tab">
		<div class="Polaris-Layout">
		   	<div class="Polaris-Layout__Section">
		   		<div class="getting-started-banner">
		   			<div class="getting-started-banner--image">
		   				<img src="<?php echo base_url();?>/assets/images/getting-started.png">
		   			</div>
		   			<div class="getting-started-banner--content">
		   				<h3 class="banner__title"><strong>Installation Steps</strong></h3>
						<p>&nbsp;</p>
						<ul>
							<li>Ensure the Stuller Showcase is embedded on all the pages you want it and make sure your product markup has been set which is done through Stuller. 
Please find following helpful links:  <p>Personalization Settings – <a href="https://www.stuller.com/myaccount/showcasesettings/" target="_blank">https://www.stuller.com/myaccount/showcasesettings/</a></p><p>Markup Settings – <a href="https://www.stuller.com/showcasemarkups/" target="_blank">https://www.stuller.com/showcasemarkups/</a></p><p>Both pages of settings must be completed for a Showcase to be up and running.</p> </li>
							<li>Activate the Stuller Add to Cart app plan under the Setting tab located above.</li>
							<li>Download Installation Guide - <a href="https://gfstuller.com/Stuller_Showcse_Shopify_Installation_Guide.pdf" target="_blank">Click Here</a>.</li>
						</ul>
						<p>&nbsp;</p>
						<!--<p>Please note that due to technical limitations the following types of products are not able to be added to cart.</p>
						<ul>
							<li>Certified Natural Diamonds</li>
							<li>Certified Lab Grown Diamonds</li>
							<li>Certified Gemstones (known as Notable Gems)</li>
						</ul>
						<p>&nbsp;</p>-->
						<p>Got a questions? Contact us at <a href="mailto:support@gemfind.com" target="_blank">support@gemfind.com</a> or <a href="tel:800-373-4373" target="_blank">1-949-752-7710</a></p>
						<p>&nbsp;</p>
		   			</div>
		   		</div>
			</div>
			</div>
	   	
	</div>
	
  	<div class="field_usage_section">
  		<div class="Polaris-Banner__Heading" id="Banner28Heading">
	 		<p class="Polaris-Heading">Need help? Below are the description of each field using in the configuration.</p>
	 	</div>
  		<ul>
  			<li class="field_item">
  				<h3>Enable</h3>
  				<p>To active or de-active the app on front-end.</p>
  			</li>
			<li class="field_item">
  				<h3>ShowCase URL</h3>
  				<p>It is a Stuller Showcase URL received from Stuller.</p>
  			</li>
			
  		</ul>
  	</div>

  	  	<form action="" method="post" id="SystemOption" name="SystemOption" class="form-horizontal">
  		<input type="hidden" name="sp_access_token" value="<?php echo $store_token; ?>">
		<div class="form-body">	
			<div class="form-group">
				<div class="col-md-6">
					<label class="control-label">Enable</label>										
					<select name="enable_app" id="enable_app" class="form-control">
						<option value="true" <?php if($stullerconfigdata) { echo ($enable_app == "true") ? "selected" : ""; }?> >Yes</option>
						<option value="false" <?php if($stullerconfigdata){ echo ($enable_app == "false") ? "selected" : "";}?> >No</option>
					</select>
				</div>
				<div class="col-md-6">
					<input type="hidden" name="shop" value="<?php if($stullerconfigdata){ echo $stullerconfigdata->shop;}?>" readonly class="form-control" maxlength="255">	
					<input type="hidden" name="access_token" value="<?php if($access_token){ echo $access_token;}?>" readonly class="form-control">	
				</div>
				<div class="col-md-6">	
					<label class="control-label">Showcase URL</label>										
					<input type="text" name="showcase_url" value="<?php if($stullerconfigdata){ echo $stullerconfigdata->showcase_url;}?>" class="form-control" maxlength="255">
				</div>

				<div class="col-md-6">
					<label class="control-label">Display Message In Pop-Up?</label>										
					<select name="enable_popup_message" id="enable_popup_message" class="form-control">
						<option value="true" <?php if($stullerconfigdata) { echo ($enable_popup_message == "true") ? "selected" : ""; }?> >Yes</option>
						<option value="false" <?php if($stullerconfigdata){ echo ($enable_popup_message == "false") ? "selected" : "";}?> >No</option>
					</select>
				</div>

				<div class="col-md-6 hide">	
					<label class="control-label">API URL</label>										
					<input type="text" name="api_url" value="<?php if($stullerconfigdata){ echo ($stullerconfigdata->api_url ? $stullerconfigdata->api_url : "https://api.stuller.com");}?>" class="form-control" maxlength="255">
					
				</div>
				<div class="col-md-6 hide">	
					<label class="control-label">API Username</label>										
					<input type="text" name="api_username" value="<?php if($stullerconfigdata){  echo ($stullerconfigdata->api_username ? $stullerconfigdata->api_username : "Gemfinddev");}?>" class="form-control" maxlength="255">
				</div>
				<div class="col-md-6 hide">	
					<label class="control-label">API Password</label>										
					<input type="text" name="api_pwd" value="<?php if($stullerconfigdata){  echo ($stullerconfigdata->api_password ? $stullerconfigdata->api_password : "UnvWYg4ZKSfXHip");}?>" class="form-control" maxlength="255">
				</div>
			</div>
			<div class="form-actions right">
				<input type="submit" name="SubmitStullerSetting" id="SubmitDiamondSetting" class="btn green" value="Save Changes">
			</div>
		</div>
	</form>
	<?php 
		} 
	}
	else
	{
		// if($charge_status == 'active') {
		?>
		
		<span class="heading-customerform">Customer Registration</span>
		<form action="" method="post" name="frmcustomer" class="frmcustomer">
				<div class="form-body">
					<div class="form-group form-horizontal">
						<div class="col-md-6">
							<label for="business">Name of Business<span class="gf-required">*</span></label>
							<input type="hidden" name="shop" value="<?php echo $shop_url; ?>" />
							<input type="text" class="form-control" id="business" name="business" required />
						</div>
						<div class="col-md-6">
							<label for="name">First and Last name of Contact<span class="gf-required">*</span></label>
							<input type="text" class="form-control" id="name" name="name" required />
						</div>
							<div class="col-md-6">
							<label for="email">Email Address<span class="gf-required">*</span></label>
							<input type="text" class="form-control" id="email" name="email" required />								
						</div>
						
						<div class="col-md-6">
							<label for="telephone">Telephone<span class="gf-required">*</span></label>
							<input type="text" class="form-control" id="telephone" name="telephone" required />
						</div>

						<div class="col-md-6">
							<label for="address">Address<span class="gf-required">*</span></label>
							<input type="text" class="form-control" id="address" name="address" required />
						</div>

						<div class="col-md-6">
							<label for="address">City<span class="gf-required">*</span></label>
							<input type="text" class="form-control" id="city" name="city" required />
						</div>	

						<div class="col-md-6">
							<label for="address">State<span class="gf-required">*</span></label>
							<input type="text" class="form-control" id="state" name="state" required />
						</div>

						<div class="col-md-6">
							<label for="address">Country<span class="gf-required">*</span></label>
							<input type="text" class="form-control" id="country" name="country" required />
						</div>
						
						<div class="col-md-6">
							<label for="address">Zip code<span class="gf-required">*</span></label>
							<input type="text" class="form-control" id="zip_code" name="zip_code" required />

							<div class="col-ji-certified">
							<label for="ji-certified">Are you in the Jewelry Industry with a business license?<span class="gf-required">*</span></label>
							    <input type="checkbox" class="form-control ji-certified" id="ji-certified" name="ji-certified" value="no" onchange="setCertifiedValue(this)" required />
							</div>

						</div>
						
						<div class="col-md-6">
							<label for="website_url">Website url</label>
							<input type="text" class="form-control" id="website_url" name="website_url"  />						
						</div>							
													
						<div class="col-md-6">
							<label for="notes">Notes <span style="font-size: 10px;font-weight:normal;">(Max 1000 Character)<span></label>
							<textarea class="form-control" id="notes" name="notes" maxlength="1000"  style="height: 160px; resize: none;"></textarea>
						</div> 

						<div class="col-md-6 ">								
						</div>
					
						<div class="form-actions right">
							<input type="submit" name="SubmitCustomerInfo" id="SubmitCustomerInfo" class="btn green" value="Submit">
							<input type="hidden" name="price" id="price" class="btn green" value="<?php echo $recurring_charges_data ? $recurring_charges_data->price : '189'; ?>">
							<input type="hidden" name="sp_access_token" value="<?php echo $store_token; ?>">
						</div>
					</div>
				</div>
		</form>
		<div class="alert alert-success form-submitmsg" style="display:none;">
		  <strong>Success!</strong> <span>Thanks for registration. GemFind support will connect you soon. Reloading your the configuration now...</span>
		</div>
		<?php
	}
	?>
  </div>
</div> 
</div>
<script type="text/javascript">
	
	 function setCertifiedValue(checkbox) {
        if (checkbox.checked) {
            checkbox.value = "Yes";
        } else {
            checkbox.value = "No";
        }
    }

</script>
<?php $this->load->view('footer', $footer_data); ?>
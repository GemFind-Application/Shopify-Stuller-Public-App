<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(E_ALL);
ini_set('display_errors', 'On');
?>
<meta charset="utf-8">
<title>Gemfind Stuller</title>
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="<?php echo base_url()?>assets/js/jquery-3.4.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <link href="<?php echo base_url()?>assets/css/custom.css" rel="stylesheet">

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	/* body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	} */

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
	
	<script type="text/javascript">
		jQuery('body').addClass('gemfind-tool');
		
		// ken dana website search fix
		jQuery('.search-hover .icon-search').on('click',function(){
			 jQuery('form#search .search-field').keydown(function(event) {
			    // enter has keyCode = 13, change it if you want to use another button
			    if (event.keyCode == 13) {
			      jQuery('form#search').submit();
			      return false;
			    }
			  });
			if(jQuery(this).hasClass('icon-arrow-right')){
				jQuery('form#search').submit();
			}
			jQuery('.search-hover > a.icon').removeClass('icon-search');
			jQuery('.search-hover > a.icon').addClass('icon-arrow-right');
			jQuery('.search-hover .search-wrapper').addClass('is-active');
		});
		jQuery('.search-hover .search-wrapper .icon-close').on('click',function(){
			jQuery('.search-hover .search-wrapper').removeClass('is-active');	
			jQuery('.search-hover > a.icon').removeClass('icon-arrow-right');
			jQuery('.search-hover > a.icon').addClass('icon-search');
		});
		jQuery('.js-show-mobile-nav').on('click',function(){
			if(jQuery('nav.primary.primary-nav').hasClass('is-active')){
				jQuery('nav.primary.primary-nav').removeClass('is-active');
				jQuery('body').removeClass('no-scroll');
			}else{
				jQuery('nav.primary.primary-nav').addClass('is-active');
				jQuery('body').addClass('no-scroll');
			}
		});
		jQuery('.primary-nav-item .js-show-subnav').on('click',function(e){
			e.preventDefault();
			if(jQuery(this).hasClass('is-active')){
				jQuery(this).removeClass('is-active');
			}else{
				jQuery(this).addClass('is-active');
			}
		});		
	</script>
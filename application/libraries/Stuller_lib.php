<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Code Igniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		Rick Ellis
 * @copyright	Copyright (c) 2006, pMachine, Inc.
 * @license		http://www.codeignitor.com/user_guide/license.html
 * @link		http://www.codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * PayPal_Lib Controller Class (Paypal IPN Class)
 *
 * This CI library is based on the Paypal PHP class by Micah Carrick
 * See www.micahcarrick.com for the most recent version of this class
 * along with any applicable sample files and other documentaion.
 *
 * This file provides a neat and simple method to interface with paypal and
 * The paypal Instant Payment Notification (IPN) interface.  This file is
 * NOT intended to make the paypal integration "plug 'n' play". It still
 * requires the developer (that should be you) to understand the paypal
 * process and know the variables you want/need to pass to paypal to
 * achieve what you want.  
 *
 * This class handles the submission of an order to paypal as well as the
 * processing an Instant Payment Notification.
 * This class enables you to mark points and calculate the time difference
 * between them.  Memory consumption can also be displayed.
 *
 * The class requires the use of the PayPal_Lib config file.
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Commerce
 * @author      Ran Aroussi <ran@aroussi.com>
 * @copyright   Copyright (c) 2006, http://aroussi.com/ci/
 *
 */

// ------------------------------------------------------------------------

class stuller_lib {

	var $CI;
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->helper('url');
		$this->CI->load->helper('form');
        $this->CI->load->helper('common');
		$this->CI->load->model('general_model');
        $requestData = $this->CI->input->get(NULL, true);
		

	}

    /**
     * @return int
     */
    function getResultPerPage(){
        return 20;
    }

	/**
     * @param $shop
     * @return mixed
     */
	function getDiamondFilters($shop)
	{   
	    parse_str($this->CI->input->post('searchformdata'), $request);
	    $resultUsername = $this->CI->general_model->getUsername($shop);
	    $dealerID = $resultUsername->dealerid;
        
	    if($dealerID){
		    if($request['filtermode'] == 'navstandard' || $request['filtermode'] == 'navlabgrown'){
		        $requestUrl = $this->CI->general_model->getFilterApi($shop).'DealerID='.$dealerID;
		    } else if($request['filtermode'] == 'navfancycolored'){
		        $requestUrl = $this->CI->general_model->getFilterApiFancy($shop).'DealerID='.$dealerID;
		    } else {
		        $requestUrl = $this->CI->general_model->getFilterApi($shop).'DealerID='.$dealerID;
		    }
		}else{
			return;
		}
	    $curl = curl_init();
        //echo $requestUrl;
	    curl_setopt($curl, CURLOPT_URL, $requestUrl);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_HEADER, false);
	    $responce = curl_exec($curl);
	    $results = (array) json_decode($responce);
	    if(sizeof($results) > 1 && $results[0]->message == 'Success'){
	        foreach ($results[1] as $value) {
	            return $value = (array) $value;
	        }
	    }
	    curl_close($curl);
	}

    /**
     * @return mixed
     */
	function getActiveNavigation($shop)
    {
        if($shop == ''){
            $shop = $this->CI->input->get('shop');
        }

    	$resultUsername = $this->CI->general_model->getUsername($shop);

	    $DealerID = "DealerID=".$resultUsername->dealerid;
	    $resultNavigationAPI = $this->CI->general_model->getNavigationapi($shop);
	    $navigation_api = $resultNavigationAPI->navigationapi;


        $requestUrl = $navigation_api.$DealerID;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $requestUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $responce = curl_exec($curl);
        $results = (array) json_decode($responce);
        if(isset($results[0])){
        $results = (array) $results[0];
        
        if (curl_errno($curl)) {
            return $returnData = ['navigation' => [], 'total' => 0];
        }

        if(sizeof($results) == 0){
            return $returnData = ['navigation' => [], 'total' => 0];
        }
        
        if(sizeof($results) > 0){
            foreach ($results as $name => $value) {
                if($name != '$id' && $name != 'navAdvanced' && $name != 'navRequest'){
                    $navigation[$name] = $value;
                }
            }
            $returnData = ['navigation' => $navigation, 'total' => sizeof($navigation)];
            return $returnData; 
        }
        } else {
            return $returnData = ['navigation' => [], 'total' => 0];
        }    

    }

    /**
     * @param $shop
     * @return mixed
     */
    public function getStyleSettings($shop)
    {

    	$resultUsername = $this->CI->general_model->getUsername($shop);
        $DealerID = 'DealerID='.$resultUsername->dealerid.'&ToolName=DL';
        $query_string = $DealerID;
        $resultStyleAPI = $this->CI->general_model->getStyleSettingapi($shop);
        $stylesettingapi = $resultStyleAPI->stylesettingapi;
        $requestUrl = $stylesettingapi.$query_string;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $requestUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response = curl_exec($curl);
        $results = (array) json_decode($response);
       if (curl_errno($curl)) {
            return $returnData = ['settings' => [],];
        }
        if(isset($results[0][0])){
            $settings = (array) $results[0][0];
            $returnData = ['settings' => $settings,];
        return $returnData; 
        }    

    }

    /**
     * @return mixed
     */
    function getJCOptions($shop)
    {
        if($shop == ''){
            $shop = $this->CI->input->get('shop');
        }

        $resultUsername = $this->CI->general_model->getUsername($shop);

        $DealerID = "DealerID=".$resultUsername->dealerid;
        $jc_options_api = $this->CI->general_model->getJCOptionsapi($shop);
        


        $requestUrl = $jc_options_api.$DealerID;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $requestUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $responce = curl_exec($curl);
        $results = (array) json_decode($responce);
        if(isset($results[0])){
        $results = (array) $results[0];
        
        
        if (curl_errno($curl)) {
            return $returnData = ['jc_options' => []];
        }

        if(sizeof($results) == 0){
            return $returnData = ['jc_options' => []];
        }
        
        if(sizeof($results) > 0){
            $returnData = ['jc_options' => $results[0]];
            return $returnData; 
        }
        } else {
            return $returnData = ['jc_options' => []];
        }    

    }

    /**
     * @param $shop
     * @return int
     */    
    function getUsername($shop){
        $resultUsername = $this->CI->general_model->getUsername($shop);
        if($resultUsername){
            return $resultUsername->dealerid;
        }else{
            return '';
        }
    }

    /**
     * @param $request
     * @return array
     */
    function getDiamonds($request)
    {   
        if ($request == null) {
            $diamond = [
                'meta' => ['code' => 400, 'message' => __('No arguments supplied.')],
                'data' => [],
                'pagination' => [],
                'perpage'       => $this->getResultPerPage()
            ];
            return $diamond;
        }
        if (!is_array($request)) {
            $diamond = [
                'meta' => ['code' => 400, 'message' => $request],
                'data' => [],
                'pagination' => [],
                'perpage'       => $this->getResultPerPage()
            ];
            return $diamond;
        }
        $shapeValue =  $certificate = $fluorescence = $fancycolor = $colorcontent = $claritycontent = 
        $cutcontent = $polishcontent = $symmetrycontent = $fancycolorcontent = $intintensitycontent = [];
        $shapesContent = $symmetrycontentContent = $certificatesContent = $fluorescenceContent = 
        $fancycolorContent = $colorcontentContent = $claritycontentContent = $cutcontentContent = 
        $polishcontentContent = $symmetrycontentContent = $fancycolorcontentContent = 
        $intintensitycontentContent = $itemperpage = '';
        $hasvideo = 'Yes' ;
        // Convert the Shapes list into gemfind form

        if (array_key_exists('diamond_shape', $request)) {
            foreach ($request["diamond_shape"] as $value) {
                $shapeValue[] = strtolower($value);
            }
            $shapesContent = implode(',', $shapeValue);
        }

        // Convert the Certificate array into gemfind form

        if (array_key_exists('diamond_certificates', $request)) {
                foreach ($request["diamond_certificates"] as $values) {
                        $certificate[] = str_replace(' ', '%20', $values);
                }
                $certificatesContent = implode(',', $certificate);
        }



        // Convert the Fluorescence list into gemfind form

        if (array_key_exists('diamond_fluorescence', $request)) {
            foreach ($request["diamond_fluorescence"] as $value) {
                $fluorescence[] = strtolower($value);
            }
            $fluorescenceContent = implode(',', $fluorescence);
        }

        // Convert the color list into gemfind form

        if (array_key_exists('diamond_color', $request)) {
            foreach ($request["diamond_color"] as $value) {
                $colorcontent[] = strtolower($value);
            }
            $colorcontentContent = implode(',', $colorcontent);
        }

        // Convert the clarity list into gemfind form

        if (array_key_exists('diamond_clarity', $request)) {
            foreach ($request["diamond_clarity"] as $value) {
                $claritycontent[] = strtolower($value);
            }
            $claritycontentContent = implode(',', $claritycontent);
        }

        // Convert the Cut list into gemfind form

        if (array_key_exists('diamond_cut', $request)) {
            foreach ($request["diamond_cut"] as $value) {
                $cutcontent[] = strtolower($value);
            }
            $cutcontentContent = implode(',', $cutcontent);
        }

        // Convert the Polish list into gemfind form

        if (array_key_exists('diamond_polish', $request)) {
            foreach ($request["diamond_polish"] as $value) {
                $polishcontent[] = strtolower($value);
            }
            $polishcontentContent = implode(',', $polishcontent);
        }

        // Convert the Symmetry list into gemfind form

        if (array_key_exists('diamond_symmetry', $request)) {
            foreach ($request["diamond_symmetry"] as $value) {
                $symmetrycontent[] = strtolower($value);
            }
            $symmetrycontentContent = implode(',', $symmetrycontent);
        }

        // Convert the DiamondId list into gemfind form

        if(isset($request['did'])){
            $did = $request['did'];
        } else {
            $did = '';
        }
		
        // Create the request array to sumbit to gemfind
        $requestData = [
            'shapes' => $shapesContent,
            'fluorescence_intensities' => $fluorescenceContent,
            'size_from' => ($request["diamond_carats"]["from"]) ? $request["diamond_carats"]["from"] : '',
            'size_to' => ($request["diamond_carats"]["to"]) ? $request["diamond_carats"]["to"] : '',
            'color' => $colorcontentContent,
            'clarity' => $claritycontentContent,
            'cut' => $cutcontentContent,
            'polish' => $polishcontentContent,
            'symmetry' => $symmetrycontentContent,
			'price_from' => (intval(preg_replace('/[^\d.]/', '', $request["price"]["from"]))) ? intval(preg_replace('/[^\d.]/', '', $request["price"]["from"])) : 0,
            'price_to' => (intval(preg_replace('/[^\d.]/', '', $request["price"]["to"]))) ? intval(preg_replace('/[^\d.]/', '', $request["price"]["to"])) : '',
			/*'price_from' => $request["price"]["from"] ? $request["price"]["from"] : '',
            'price_to' => $request["price"]["to"] ? $request["price"]["to"] : '',
            'price_from' => (intval($request["price"]["from"])) ? intval($request["price"]["from"]) : '',
            'price_to' => (intval($request["price"]["to"])) ? intval($request["price"]["to"]) : '',*/
            'diamond_table_from' => (intval($request["diamond_table"]["from"])) ? intval($request["diamond_table"]["from"]) : '',
            'diamond_table_to' => (intval($request["diamond_table"]["to"])) ? intval($request["diamond_table"]["to"]) : '',
            'depth_percent_from' => (intval($request["diamond_depth"]["from"])) ? intval($request["diamond_depth"]["from"]) : '',
            'depth_percent_to' => (intval($request["diamond_depth"]["to"])) ? intval($request["diamond_depth"]["to"]) : '',
            'labs' => $certificatesContent,
            'origin' => ($request["gemfind_diamond_origin"]) ? $request["gemfind_diamond_origin"] : '',
            'page_number' => ($request['currentpage']) ? $request['currentpage'] : '',
            'page_size' => ($request['itemperpage']) ? $request['itemperpage'] : $this->getResultPerPage(),
            'sort_by' => ($request['orderby']) ? $request['orderby'] : '',
            'sort_direction' => ($request['direction']) ? $request['direction'] : '',
            'did' => $did,
            'hasvideo' => $hasvideo,
            'Filtermode' => ($request['filtermode'])? $request['filtermode'] : 'navstandard'
        ];


        if(isset($request['filtermode'])){
            if($request['filtermode'] != 'navstandard' && $request['filtermode'] != 'navlabgrown'){
                // Convert the Symmetry list into gemfind form
                
                if (array_key_exists('diamond_fancycolor', $request)) {
                    foreach ($request["diamond_fancycolor"] as $value) {
                        $fancycolorcontent[] = strtolower($value);
                    }
                    $fancycolorcontentContent = implode(',', $fancycolorcontent);
                }

                // Convert the Symmetry list into gemfind form
                
                if (array_key_exists('diamond_intintensity', $request)) {
                    foreach ($request["diamond_intintensity"] as $value) {
                        $intintensitycontent[] = strtolower($value);
                    }
                    $intintensitycontentContent = implode(',', $intintensitycontent);
                }

                $fancyData = ['FancyColor' =>$fancycolorcontentContent,'intIntensity' =>
                $intintensitycontentContent];

                $requestData = array_merge($requestData,$fancyData);
            }
        }

        $result = $this->sendRequest($requestData, $request['shopurl']);

        $num = ceil($result['total'] / $this->getResultPerPage());
        
        if ($result['diamonds'] != null || $result['total'] != 0) {
            $count = 0;
            if ($request['currentpage'] > 1) {
                $count = ($request['itemperpage']) ? $request['itemperpage'] : $this->getResultPerPage() * ($request['currentpage'] - 1);
            }

            $diamond = [
                'meta' => ['code' => 200],
                'data' => $result['diamonds'],
                'pagination' => [
                    'currentpage' => $request['currentpage'],
                    'count'     => $count,
                    'limit'     => count($result['diamonds']),
                    'total'     => $result['total']
                ],
                'perpage'       => ($request['itemperpage']) ? $request['itemperpage'] : $this->getResultPerPage()    
            ];
        } else {
            $diamond = [
                'meta' =>['code' => 404, 'message' => "No Product Found"],
                'data' => [],
                'pagination' =>['total' => $result['total']],
                'perpage'       => $this->getResultPerPage()  
            ];
        }

        return $diamond;
    }

    /**
     * @param $requestParam,$shop
     * @return array
     */
    public function sendRequest($requestParam,$shop)
    {      

        $Shape = $CaratMin = $CaratMax = $PriceMin = $PriceMax = $ColorId = $ClarityId = $CutGradeId = $TableMin = $TableMax = $DepthMin = $DepthMax = $SymmetryId = $PolishId = $FluorescenceId = $Certificate = $OrderBy = $OrderType = $PageNumber = $PageSize = $InHouseOnly = $SOrigin = $query_string = $DID = $FancyColor = $intIntensity = $HasVideo = '';
           if($requestParam) {
                
                $DealerID = 'DealerID='.$this->getUsername($shop).'&';
                
                if (array_key_exists('shapes', $requestParam)) {
                    if($requestParam['shapes']){
                    $Shape = 'Shape='.$requestParam['shapes'].'&';
                    }
                }
                if (array_key_exists('size_from', $requestParam)) {
                    if($requestParam['size_from']){
                    $CaratMin = 'CaratMin='.$requestParam['size_from'].'&';
                    }
                }
                if (array_key_exists('size_to', $requestParam)) {
                    if($requestParam['size_to']){
                    $CaratMax = 'CaratMax='.$requestParam['size_to'].'&';
                    }
                }
                if (array_key_exists('price_from', $requestParam)) {
                    if($requestParam['price_from']){
                    $PriceMin = 'PriceMin='.$requestParam['price_from'].'&';
                    } else {
                    $PriceMin = 'PriceMin=0&';    
                    }
                }
                if (array_key_exists('price_to', $requestParam)) {
                    if($requestParam['price_to']){
                    $PriceMax = 'PriceMax='.$requestParam['price_to'].'&';
                    }
                }
                if (array_key_exists('depth_percent_from', $requestParam)) {
                    if($requestParam['depth_percent_from']){
                    $DepthMin = 'DepthMin='.$requestParam['depth_percent_from'].'&';
                    } else {
                    $DepthMin = 'DepthMin=0&';    
                    }
                }
                if (array_key_exists('depth_percent_to', $requestParam)) {
                    if($requestParam['depth_percent_to']){
                    $DepthMax = 'DepthMax='.$requestParam['depth_percent_to'].'&';
                    }
                }                                                                                
                if (array_key_exists('diamond_table_from', $requestParam)) {
                    if($requestParam['diamond_table_from']){
                    $TableMin = 'TableMin='.$requestParam['diamond_table_from'].'&';
                    } else {
                    $TableMin = 'TableMin=0&';    
                    }
                }
                if (array_key_exists('diamond_table_to', $requestParam)) {
                    if($requestParam['diamond_table_to']){
                    $TableMax = 'TableMax='.$requestParam['diamond_table_to'].'&';
                    }
                }
                if (array_key_exists('color', $requestParam)) {
                    if($requestParam['color']){
                    $ColorId = 'ColorId='.$requestParam['color'].'&';
                    }
                }
                if (array_key_exists('clarity', $requestParam)) {
                    if($requestParam['clarity']){
                    $ClarityId = 'ClarityId='.$requestParam['clarity'].'&';
                    }
                }
                if (array_key_exists('cut', $requestParam)) {
                    if($requestParam['cut']){
                    $CutGradeId = 'CutGradeId='.$requestParam['cut'].'&';
                    }
                }                                                                                
                if (array_key_exists('symmetry', $requestParam)) {
                    if($requestParam['symmetry']){
                    $SymmetryId = 'SymmetryId='.$requestParam['symmetry'].'&';
                    }
                }
                if (array_key_exists('polish', $requestParam)) {
                    if($requestParam['polish']){
                    $PolishId = 'PolishId='.$requestParam['polish'].'&';
                    }
                }
                if (array_key_exists('fluorescence_intensities', $requestParam)) {
                    if($requestParam['fluorescence_intensities']){
                    $FluorescenceId = 'FluorescenceId='.$requestParam['fluorescence_intensities'].'&';
                    }
                }
                
                if (array_key_exists('labs', $requestParam)) {
                    if($requestParam['labs']){
                    $Certificate = 'Certificate='.$requestParam['labs'].'&';
                    }
                }
                if (array_key_exists('sort_by', $requestParam)) {
                    if($requestParam['sort_by']){
                    $OrderBy = 'OrderBy='.$requestParam['sort_by'].'&';
                    }
                }                                                                                
                if (array_key_exists('sort_direction', $requestParam)) {
                    if($requestParam['sort_direction']){
                    $OrderType = 'OrderType='.$requestParam['sort_direction'].'&';
                    }
                }
                if (array_key_exists('page_number', $requestParam)) {
                    if($requestParam['page_number']){
                    $PageNumber = 'PageNumber='.$requestParam['page_number'].'&';
                    }
                }
                if (array_key_exists('page_size', $requestParam)) {
                    if($requestParam['page_size']){
                    $PageSize = 'PageSize='.$requestParam['page_size'];
                    }
                }
                if (array_key_exists('inhouse', $requestParam)) {
                    if($requestParam['inhouse']){
                    $InHouseOnly = '&InHouseOnly='.$requestParam['InHouseOnly'];
                    }
                }

                if($requestParam['sort_by'] == 'Inhouse'){
                    $showinhousefirst = 'ShowInhouseFirst=true&';
                }

                if (array_key_exists('origin', $requestParam)) {
                    if($requestParam['origin']){
                    $SOrigin = '&SOrigin='.$requestParam['origin'].'&';
                    }
                }

                if (array_key_exists('did', $requestParam)) {
                    if($requestParam['did']){
                    $DID = 'DID='.$requestParam['did'].'&';
                    }
                }


                if (array_key_exists('hasvideo', $requestParam)) {
                    if($requestParam['hasvideo']){
                    $HasVideo = 'HasVideo='.$requestParam['hasvideo'].'&';
                    }
                }                
                

                if (array_key_exists('Filtermode', $requestParam)) {
                    if($requestParam['Filtermode'] != 'navstandard' && $requestParam['Filtermode'] != 'navlabgrown'){
                        if(array_key_exists('FancyColor', $requestParam)){
                            if($requestParam['FancyColor']){
                                $FancyColor = 'FancyColor='.$requestParam['FancyColor'].'&';
                            }
                        }
                        if(array_key_exists('intIntensity', $requestParam)){
                            if($requestParam['intIntensity']){
                                $requestParam['intIntensity'] = str_replace(' ', '+', $requestParam['intIntensity']);
                                $intIntensity = 'intIntensity='.$requestParam['intIntensity'].'&';

                            }    
                        }
                        $IsLabGrown = '&IsLabGrown=false';
                        $query_string = $DealerID.$Shape.$CaratMin.$CaratMax.$PriceMin.$PriceMax.$ClarityId.$CutGradeId.$TableMin.$TableMax.$DepthMin.$DepthMax.$SymmetryId.$PolishId.$FluorescenceId.$FancyColor.$intIntensity.$Certificate.$SOrigin.$DID.$showinhousefirst.$OrderBy.$OrderType.$PageNumber.$PageSize.$InHouseOnly.$IsLabGrown;
                        $requestUrl = $this->CI->general_model->getdiamondlistapifancy($shop).$query_string;
                    } else {
                        if($requestParam['Filtermode'] == 'navlabgrown'){
                            $IsLabGrown = '&IsLabGrown=true';
                        } else {
                            $IsLabGrown = '&IsLabGrown=false';
                        }
                        $query_string = $DealerID.$Shape.$CaratMin.$CaratMax.$PriceMin.$PriceMax.$ColorId.$ClarityId.$CutGradeId.$TableMin.$TableMax.$DepthMin.$DepthMax.$SymmetryId.$PolishId.$FluorescenceId.$Certificate.$SOrigin.$DID.$showinhousefirst.$OrderBy.$OrderType.$PageNumber.$PageSize.$InHouseOnly.$IsLabGrown;
                        $requestUrl = $this->CI->general_model->getdiamondlistapi($shop).$query_string;
                    }
                }

            }
        $curl = curl_init();
        //echo $requestUrl;
        curl_setopt($curl, CURLOPT_URL, $requestUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $responce = curl_exec($curl);
        $results = json_decode($responce);
        
        if (curl_errno($curl)) {
            return $returnData = ['diamonds' => [], 'total' => 0, 'message' => 'Gemfind: An error has occurred.'];
        }
       if(isset($results->message)){
            return $returnData = ['diamonds' => [], 'total' => 0, 'message' => 'Gemfind: An error has occurred.'];
        }
        curl_close($curl);

        if($results->diamondList != "" && $results->count > 0){
            $returnData = ['diamonds' => $results->diamondList, 'total' => $results->count];
        } else {
            $returnData = ['diamonds' => [], 'total' => 0];   
        }

        return $returnData;
    }

    /**
     * @return int
     */
    public function getResultsPerPageOptions(){
        return $this->CI->general_model->getAllOptions();
    }

    /**
     * @return int
     */
    public function getResultsPerPage(){
        return $this->CI->general_model->getResultsPerPage();
    }

    /**
     * @param $param,$type,$shopurl,$pathprefixshop
     * @return string
     */
    public function getDiamondViewUrl($param,$type,$shopurl,$pathprefixshop)
    {
        $route = "https://".$shopurl.$pathprefixshop."/product/";
        return $this->getUrl($route, ['path' => $param, 'type' => $type, '_secure' => true]);
    }

    /**
     * @param $route,$params
     * @return string
     */
    public function getUrl($route = '', $params = []){
        if($params['path']){
            return $route.$params['path']."/".$params['type'];    
        }else{
            return $route.$params['id']."/".$params['type'];    
        }
        
    }

    /**
     * @param $shop
     * @return string
     */
    public function getCurrencySymbol($shop) {
        $dealerID = $this->getUsername($shop);
        $requestUrl = $this->CI->general_model->getFilterApi($shop).'DealerID='.$dealerID;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $requestUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $responce = curl_exec($curl);
        $results = (array) json_decode($responce);
        if(sizeof($results) > 1 && $results[0]->message == 'Success'){
            foreach ($results[1] as $value) {
                if($value->currencyFrom == 'USD'){
                    return "$";
                }else{
                    return $value->currencyFrom.$value->currencySymbol;
                }
            }
        }
        curl_close($curl);
    }

    /**
     * @return array
     */
    public function getDiamondAttributes(){
        return $this->CI->general_model->getDiamondAttribute();
    }

    /**
     * @param $color,$shop
     * @return array
     */
    public function getShapeByColor($color,$shop)
    {
        $DealerID = 'DealerID='.$this->getUsername($shop).'&';
        $Color = 'Color='.$color;
        $query_string = $DealerID.$Color;
        $requestUrl = $this->CI->general_model->getdiamondshapeapi($shop).$query_string;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $requestUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $responce = curl_exec($curl);
        $results = (array) json_decode($responce);
        
       if (curl_errno($curl)) {
            return $returnData = ['shapes' => [], 'total' => 0];
        }
        if(($results[0]->status == 0)){
            return $returnData = ['shapes' => [], 'total' => 0];
        }
        
        if(($results[0]->status > 0) && ($results[0]->message == 'Success')){
            foreach ($results[1][0]->shapes as $value) {
                $value = (array) $value;
                $shapes[] = strtolower($value['shapeName']);
            }
            $returnData = ['shapes' => $shapes, 'total' => sizeof($shapes)];
        return $returnData; 
        }    

    }

    /**
     * @return array|product
     */
    public function getProduct()
    {   
        $diamond_path = $this->CI->uri->segment(3);
        $id = getDiamondSkuByPath($diamond_path);
        $shop = $this->CI->input->get('shop');
        $type = $this->CI->uri->segment(4); 

        if (!$this->product) {
            /*if($type == 'labcreated'){
                $this->product = (array)$this->getDiamondByIdtype($id,$type,$shop);
            } else {*/
                $this->product = (array)$this->getDiamondById($id,$type,$shop);    
            /*}*/
            
        }

        //$this->triggerClick($this->product);

        return $this->product;
    }

    /**
     * @param $id
     * @param $type
     * @param $shop
     * @return array
     */
    public function getDiamondByIdtype($id,$type,$shop)
    {   
        $IslabGrown = '';
        if($type == 'labcreated'){
            $IslabGrown = '&IslabGrown=true';    
        } else {
            $IslabGrown = "";
        }
        $DealerID = 'DealerID='.$this->getUsername($shop).'&';
        $DID = 'DID='.$id;
        $query_string = $DealerID.$DID.$IslabGrown;
        $requestUrl = $this->getdiamonddetailapi($shop).$query_string;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $requestUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $responce = curl_exec($curl);
        $results = json_decode($responce);
        
        if (curl_errno($curl)) {
            return $returnData = ['diamondData' => [], 'total' => 0, 'message' => 'Gemfind: An error has occurred.' ];
        }
       if(isset($results->message)){
            return $returnData = ['diamondData' => [], 'total' => 0, 'message' => 'Gemfind: An error has occurred.' ];
        }
        curl_close($curl);
        if($results->diamondId != "" && $results->diamondId > 0){
            $diamondData = (array) $results;
            $returnData = ['diamondData' => $diamondData];
        } else {
            $returnData = ['diamondData' => []];   
        }
        return $returnData;
    }

    /**
     * @param $id
     * @param $shop
     * @return array
     */
    public function getDiamondById($id,$type,$shop)
    {
        $IslabGrown = '';
        if($type && $type == 'labcreated'){
            $diamond_type = '&IslabGrown=true';    
        } elseif($type == 'fancydiamonds') {
            $diamond_type = '&IsFancy=true'; 
        }else{
            $diamond_type = ''; 
        }

        $DealerID = 'DealerID='.$this->getUsername($shop).'&';
        $DID = 'DID='.$id;
        $query_string = $DealerID.$DID.$diamond_type;
        $requestUrl = $this->CI->general_model->getdiamonddetailapi($shop).$query_string;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $requestUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response = curl_exec($curl);
        $results = json_decode($response);
        
        if (curl_errno($curl)) {
            return $returnData = ['diamondData' => [], 'total' => 0, 'message' => 'Gemfind: An error has occurred.' ];
        }
       if(isset($results->message)){
            return $returnData = ['diamondData' => [], 'total' => 0, 'message' => 'Gemfind: An error has occurred.' ];
        }
        curl_close($curl);
        if($results->diamondId != "" && $results->diamondId > 0){
            $diamondData = (array) $results;
            $returnData = ['diamondData' => $diamondData];
        } else {
            $returnData = ['diamondData' => []];   
        }
        return $returnData;
    }

    /**
     * @return int
    */
    public function isHintEnabled($shop) {
        return $this->CI->general_model->isHintEnabled($shop);
    }

    /**
     * @return int
    */
    public function isMoreInfoEnabled($shop) {
        return $this->CI->general_model->isMoreInfoEnabled($shop);
    }

    /**
     * @return int
    */
    public function isEmailtoFriendEnabled($shop) {
        return $this->CI->general_model->isEmailtoFriendEnabled($shop);
    }
    
    /**
     * @return int
    */
    public function isPrintDetailEnabled($shop) {
        return $this->CI->general_model->isPrintDetailEnabled($shop);
    }

    /**
     * @return int
    */
    public function isScheduleViewingEnabled($shop) {
        return $this->CI->general_model->isScheduleViewingEnabled($shop);
    }

    /**
     * @return string
    */
    public function isAdminNotify($shop) {
        return $this->CI->general_model->isAdminNotify($shop);
    }

    /**
     * @return string
    */
    public function showFilterInfo($shop) {
        return $this->CI->general_model->showFilterInfo($shop);
    }

    /**
     * @return string
    */
    public function showPoweredBy($shop) {
        return $this->CI->general_model->showPoweredBy($shop);
    }

    /**
     * @return string
    */
    public function getDefaultViewmode($shop) {
        return $this->CI->general_model->getDefaultViewmode($shop);
    }
    
    /**
     * @return string
     */
    public function getSubmitUrl($diamondid,$shop,$pathprefixshop,$diamondtype)
    {
        $route = "https://".$shop.$pathprefixshop."/cartadd/";
        return $this->getUrl($route, ['id'=>$diamondid,'type'=> $diamondtype,'_secure' => true]);
    }

    /**
     * @return mix
     */
    public function getEmailSender($shop)
    {
        return $this->CI->general_model->getFromEmailAddress($shop);
    }

	public function getAdminEmail($shop)
    {
        return $this->CI->general_model->getAdminEmailAddress($shop);
    }

    /**
     * @return json
     */
    public function authenticateDealer($shop,$password)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->CI->general_model->dealerAuthapi($shop),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{"DealerID": "'.$this->getUsername($shop).'", "DealerPass": "'.$password.'"}',
            CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "cache-control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $data = array('status' => 0, 'msg' => $err );
            $result = json_encode(array('output' => $data));
            return $result;
        } else {
          if($response == '"User successfully authenticated."'){
            $data = array('status' => 1, 'msg' => 'User successfully authenticated.' );
            $result = json_encode(array('output' => $data));
            return $result;
          }
          if($response == '"User not authenticated."'){
            $data = array('status' => 2, 'msg' => 'User not authenticated.' );
            $result = json_encode(array('output' => $data));
            return $result;
          }
          if($response == '"User not found!"'){
            $data = array('status' => 2, 'msg' => 'User not found!' );
            $result = json_encode(array('output' => $data));
            return $result;
          }          
        }
    }

    /**
     * @return string
     */
    public function getShopAccessToken($shop)
    {
        return $this->CI->general_model->getAccessToken($shop);
    }  

    /**
     * @return string
     */
    public function getShapedefaultfilter(){
        $shapeRequest =  $this->CI->input->get('shape');
        if(isset($shapeRequest)){
            return $shapeRequest;
        }
    }

}

?>
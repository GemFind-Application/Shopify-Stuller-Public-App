<?php
class General_model extends CI_Model {
	
	public function getDiamondConfig($shop)
    {
        $this->db->where('shop',$shop);
        return $this->db->get('stuller_config')->first_row();
    }
	
	public function addData($Data)
	{   
		$this->db->insert('stuller_config',$Data);            
		return $this->db->insert_id();
	}

	// update data common function
	public function updateData($Data,$shop)
	{        
		$this->db->where('shop',$shop);
		$this->db->update('stuller_config',$Data);            
		return $this->db->affected_rows();
	}

	public function getStoreConfiguration($shop){
		$this->db->where('shop',$shop);
		$this->db->select('*');
		$resultDetail = $this->db->get('stuller_config')->first_row();
		return $resultDetail;
	}
    
    public function getAccessToken($shop){
        $this->db->where('shop',$shop);
        $this->db->select('shop_access_token');
        $resultDetailApi = $this->db->get('stuller_config')->first_row();
        return $resultDetailApi->shop_access_token;
    }

    public function generalAddData($Data,$tablename)
    {   
        $this->db->insert('app_charges',$Data);            
        return $this->db->insert_id();
    }

     public function generalGetData($wherevalue,$wherefieldname,$tablename)
    {   
        $this->db->where($wherefieldname,$wherevalue);           
        return $this->db->get($tablename)->first_row();
    }

    // update data common function
    public function generalUpdateData($updatedata,$wherefieldname,$wherevalue,$tablename)
    {        
        $this->db->where($wherefieldname,$wherevalue);
        $this->db->update($tablename,$updatedata);            
        return $this->db->affected_rows();
    }

    public function getChargeData($shop)
    {
        $this->db->where('shop',$shop);
        $this->db->select('*');
        $appCharges = $this->db->get('app_charges')->first_row();
        return $appCharges;
    }
	
	public function getAppChargesData($shop)
	{
		$this->db->where('shop',$shop);
        $this->db->select('*');
        $resultCharges = $this->db->get('app_charges')->first_row();
        return $resultCharges->cid;
	}

	public function modifyAppStatus($shop,$data)
    {
        $this->db->set('status', 'Inactive');
        $this->db->where('shop',$shop);
        $this->db->update('app_charges',$data);            
        return $this->db->affected_rows();
    }
	
	public function getCustomerData($shop){
        $this->db->where('shop',$shop);
        $this->db->select('*');
        $resultCustomer = $this->db->get('customer')->first_row();
        return $resultCustomer->id;
    }
    
    public function getCustomerDetail($shop)

    {

        $this->db->where('shop',$shop);

        return $this->db->get('customer')->first_row();

    }
	
	public function getDiamondConfigData($shop)
	{
		$this->db->where('shop',$shop);
        $this->db->select('*');
        $resultConfig = $this->db->get('stuller_config')->first_row();
        return $resultConfig->id;
	}
	
	public function addCustomerData($Data)
    {   
        $this->db->insert('customer',$Data);            
        return $this->db->insert_id();
    }
}
?>
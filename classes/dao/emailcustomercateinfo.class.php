<?php
/*************************************************************************
Code : PhanTom
**************************************************************************/
class EmailCustomerCateInfo {
	var $id;		    # Item ID
	var $customer;		
	var $group;		
	var $campaign;	

	# Constructor
	function EmailCustomerCateInfo($customer, $group , $campaign, $id= 0)
	{
		$this->id = $id;
		$this->customer = $customer;
		$this->group = $group;
		$this->campaign = $campaign;
	}
	function getId() {
		return $this->id;
	}	
	function setId($nValue) {
		$this->id=$nValue;
	}
	function getGroup() {
		return $this->group;
	}	
	function setGroup($nValue) {
		$this->group=$nValue;
	}
	function getCampaign() {
		return $this->campaign;
	}	
	function setCampaign($nValue) {
		$this->campaign=$nValue;
	}
	function getCustomer() {
		return $this->customer;
	}	
	function setCustomer($nValue) {
		$this->customer=$nValue;
	}
	
}	
?>
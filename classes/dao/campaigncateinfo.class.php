<?php
/*************************************************************************
Code : PhanTom
**************************************************************************/
class CampaignCateInfo {
	var $id;		    # Item ID
	var $campaign;		
	var $group;		

	# Constructor
	function CampaignCateInfo($campaign, $group , $id= 0)
	{
		$this->id = $id;
		$this->campaign = $campaign;
		$this->group = $group;
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
	
}	
?>
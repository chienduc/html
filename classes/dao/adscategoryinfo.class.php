
<?php
/*************************************************************************
Class AdsCategoryInfo
----------------------------------------------------------------
DeraCMS Project
Company: Derasoft Co., Ltd                                  
Name: Mai Minh                                   
Last updated: 18/09/2011
**************************************************************************/	

class  AdsCategoryInfo {
	var $id;			# Primary key
	var $store_id;		
	var $name;		# Vietnamese name
	var $status;		# Status
	var $properties;
	
	function AdsCategoryInfo($store_id=0, $name='', $status='',$properties='', $acId=0) {
		$this->id = $acId;
		$this->store_id=$store_id;
		$this->name = stripslashes(htmlspecialchars($name));
		$this->status = $status;
		$this->properties = unserialize($properties);
	}
	
	function getId() {
		return $this->id;
	}	
	
	function setId($nValue) {
		$this->id=$nValue;
	}
	
	function getStoreId() {
		return $this->store_id;
	}	
	
	function setStoreId($nValue) {
		$this->store_id=$nValue;
	}
	
	function getActiveAds() {
		include_once(ROOT_PATH."classes/dao/ads.class.php");
		$ads = new Ads($this->store_id);
		$rowsPages = $ads->getNumItems('id', "`gid` = '".$this->id."' AND `status` = '1'");
		return $rowsPages['rows'];
	}	
	
	function getNumAds() {
		include_once(ROOT_PATH."classes/dao/ads.class.php");
		$ads = new Ads($this->store_id);
		$rowsPages = $ads->getNumItems('id', "`gid` = '".$this->id."'");
		return $rowsPages['rows'];
	}
	
	function getProperty($id,$key)
	{
		if(isset($this->properties[$id][$key])) return $this->properties[$id][$key];
		return '';
	}
	
	function getProperties()
	{
		return $this->properties;
	}
	
	function setProperties($nValue)
	{
		$this->properties=$nValue;
	}

	function getName() {
		return $this->name;		
	}
	function setName($nValue) {
		$this->name=stripslashes($nValue);
	}
	
	function getStatus() {
		return $this->status;
	}
	
	function getStatusTextBackend() {
		global $amessages;
		return $amessages['status'][$this->status];
	}
	
	function isEnabled() {
		return ($this->status == 1?1:0);
	}
	
	function isDeleted() {
		return ($this->status == 2?1:0);
	}
	
	function isDisabled() {
		return ($this->status == 0?1:0);
	}
}
?>
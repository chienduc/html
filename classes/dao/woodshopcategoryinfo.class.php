
<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/	

class  WoodshopCategoryInfo {
	var $id;			# Primary key
	var $store_id;		
	var $name;		    # Vietnamese name
	var $works;
	var $date_complete;
	var $status;		# Status
	var $properties;
	
	function WoodshopCategoryInfo($store_id=0, $name='', $works, $date_complete, $status='',$properties='', $acId=0) {
		$this->id = $acId;
		$this->store_id=$store_id;
		$this->name = stripslashes(htmlspecialchars($name));
		$this->works = stripslashes(htmlspecialchars($works));
		$this->date_complete = $date_complete;
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
	
	
	function getNumWoodshop() {
		include_once(ROOT_PATH."classes/dao/woodshop.class.php");
		$woodshop = new Woodshop($this->store_id);
		$rowsPages = $woodshop->getNumItems('id', "`wid` = '".$this->id."'");
		return $rowsPages['rows'];
	}
	
	function getProperty($key)
	{
		if(isset($this->properties[$key])) return $this->properties[$key];
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

	function getName($lang='vn') {
		if($lang=='vn') return $this->name;	
		elseif(isset($this->properties['custom_'.$lang.'_name'])) return $this->properties['custom_'.$lang.'_name'];	
	}
	function setName($nValue,$lang='vn') {
		if($lang=='vn') $this->name=stripslashes($nValue);
		else $this->properties['custom_'.$lang.'_name']=stripslashes($nValue);
	}
	function getWorks($lang='vn') {
		if($lang=='vn') return $this->works;	
		elseif(isset($this->properties['custom_'.$lang.'_works'])) return $this->properties['custom_'.$lang.'_works'];	 	
	}
	function setWorks($nValue,$lang='vn') {
		if($lang=='vn') $this->works=stripslashes($nValue);
		else $this->properties['custom_'.$lang.'_works']=stripslashes($nValue);
	}
	function getDateComplete() {
		return $this->date_complete;		
	}
	function setDateComplete($nValue) {
		$this->date_complete=$nValue;
	}
	function getDateCompletes() {
		return Changeundate($this->date_complete);		
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
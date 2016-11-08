<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
class StaffInfo {
	var $id;			# Primary key
	var $store_id;		# Estore id
	var $cat_id;		# Category id
	var $name;			# Staff name
	var $office;
	var $viewed;		# Number of views
	var $date_created;	# Date created
	var $updated;		# Date update
	var $position;
	var $properties;	# Properties
	var $status;		# 0-Disabled, 1-Active, 2-Deleted, 3-Unpublished
	# Constructor
	function StaffInfo($name, $office, $viewed, $date_created, $updated, $position, $properties, $status, $cat_id = 0, $store_id = 0, $id = 0)
	{
		$this->id = $id;
		$this->store_id = $store_id;
		$this->cat_id = $cat_id;
		$this->name = stripslashes(htmlspecialchars($name));
		$this->office = stripslashes(htmlspecialchars($office));
		$this->viewed = $viewed;
		$this->date_created = $date_created;
		$this->updated = $updated;
		$this->position = $position;
		$this->properties = unserialize($properties);
		$this->status = $status;
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
	function getCatId() {
		return $this->cat_id;
	}
	function setCatId($nValue) {
		$this->cat_id=$nValue;
	}
	function getCatSlug() {
		include_once(ROOT_PATH."classes/dao/staffcategories.class.php");
		$staffCategories = new StaffCategories($this->store_id);
		return $staffCategories->getSlugFromId($this->cat_id);
	}
	function getCatName() {
		include_once(ROOT_PATH."classes/dao/staffcategories.class.php");
		$staffCategories = new StaffCategories($this->store_id);
		return $staffCategories->getNameFromId($this->cat_id);
	}

	function getName($lang='vn') {
		if($lang == 'vn')	return $this->name;
		else return $this->properties['custom_'.$lang.'_name'];	
	}
	function setName($nValue,$lang='vn') {
		if($lang == 'vn')	$this->name=stripslashes($nValue);
		else	$this->properties['custom_'.$lang.'_name']=stripslashes($nValue);
	}
    function getOffice($lang='vn') {
		if($lang == 'vn')	return $this->office;
		else return $this->properties['custom_'.$lang.'_office'];	
	}
	function setOffice($nValue,$lang='vn') {
		if($lang == 'vn')	$this->office=stripslashes($nValue);
		else	$this->properties['custom_'.$lang.'_office']=stripslashes($nValue);
	}

	
	function getViewed() {
		return $this->viewed;
	}	
	function setViewed($nValue) {
		$this->viewed=$nValue;
	}
	function getDateCreated()
	{
		return $this->date_created;
	}
	function setDateCreated($nValue)
	{
		$this->date_created=$nValue;
	}
	function getUpdated()
	{
		return $this->updated;
	}
	function setUpdated($nValue)
	{
		$this->updated=$nValue;
	}
	
	function getProperty($key)
	{
		if(isset($this->properties[$key])) return $this->properties[$key];
		return '';
	}
	function setProperty($key,$nValue)
	{
		$this->properties[$key]=$nValue;
	}
	function getPosition() {
		return $this->position;
	}
	function setPosition($nValue) {
		$this->position = $nValue;
	}
	function getProperties()
	{
		return $this->properties;
	}
	function setProperties($nValue)
	{
		$this->properties=$nValue;
	}
	function getStatus() {
		return $this->status;
	}
	function setStatus($nValue) {
		$this->status = $nValue;
	}

	function getStatusTextBackend() {
		global $amessages;
		return $amessages['status_product'][$this->status];
	}
	
	
}	
?>
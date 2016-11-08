<?php
/*************************************************************************
Class MenuInfo
----------------------------------------------------------------
DeraCMS Project
Company: Derasoft Co., Ltd                                  
Name: Nguyen Anh Ngoc                        
Last updated: 07/10/2009
Checked by: Mai Minh (29/09/2011)
**************************************************************************/
class MenuInfo {
	var $id;			# Primary key
	var $parent_id;		# Parent menu
	var $store_id;
	var $cId;			# Menu category ID
	var $name;			# Vietnamese name
	var $url;			# Vietnamese url
	var $status;		# Status
	var $position;		# Order position
	var $properties;
	
	function MenuInfo ($name, $url,$status, $position,$properties='',$mcId,$store_id, $parent_id = 0, $mId = 0)
	{
		$this->id = $mId;
		$this->parent_id = $parent_id;
		$this->store_id = $store_id;
		$this->cId= $mcId;
		$this->name = stripslashes(htmlspecialchars($name));
		$this->url = stripslashes(htmlspecialchars($url));
		$this->status = $status;
		$this->position = $position;
		$this->properties = unserialize($properties);
	}

	function getId() {
		return $this->id;
	}	
	function setId($nValue){
		$this->id=$nValue;
	}
	function getParentId() {
		return $this->parent_id;
	}
	function setParentId($nValue) {
		$this->parent_id=$nValue;
	}
	function getStoreId() {
		return $this->store_id;
	}
	function setStoreId($nValue) {
		$this->store_id=$nValue;
	}
	function getName($lang = 'vn') {
		if($lang == 'vn')	return $this->name;
		else return $this->properties['custom_'.$lang.'_name'];
	}
	function setName($lang = 'vn', $nValue) {
		if($lang == 'vn')	$this->name=stripslashes($nValue);
		else	$this->properties['custom_'.$lang.'_name']=stripslashes($nValue);
	}
	function getCId() {
		return $this->cId;
	}
	function setCId($nValue) {
		$this->cId = $nValue;
	}
	function getUrl($lang = 'vn') {
		if($lang == 'vn')	return $this->url;
		else	return $this->properties['custom_'.$lang.'_url'];
	}
	function setUrl($lang = 'vn', $nVlaue) {
		if($lang == 'vn')	$this->url=stripslashes($nValue);
		else	$this->properties['custom_'.$lang.'_url']=stripslashes($nValue);
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
	function getPosition() {
		return $this->position;
	}
	function setPosition($nValue) {
		$this->position = $nValue;
	}
	
	function getStatusTextBackend() {
		global $amessages;
		return $amessages['status'][$this->status];
	}
	function getCatName() {
		include_once(ROOT_PATH."classes/dao/menucategories.class.php");
		$menuCategories = new MenuCategories();
		return $menuCategories->getNameFromId($this->cId);
	}
	function getParentName() {
		include_once(ROOT_PATH."classes/dao/menus.class.php");
		$menus = new Menus($this->store_id);
		return $menus->getNameFromId($this->parent_id);
	}
	function getChildren($page = 1, $condition = "`status` = '1'", $sort = array('position' => 'asc'), $items_per_page = 100) {
		include_once(ROOT_PATH."classes/dao/menus.class.php");
		$menus = new Menus($this->store_id);
		$Items = $menus->getObjects($page,"`parent_id` = '".$this->id."' AND $condition",$sort,items_per_page);
		return $Items;
	}

}	

?>
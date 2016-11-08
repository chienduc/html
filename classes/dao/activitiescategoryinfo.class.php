
<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/	

class  ActivitiesCategoryInfo {
	var $id;			# Primary key
	var $store_id;		
	var $name;		    # Vietnamese name
	var $content;
	var $status;		# Status
	var $properties;
	
	function ActivitiesCategoryInfo($store_id=0, $name='', $content, $status='',$properties='', $id=0) {
		$this->id = $id;
		$this->store_id=$store_id;
		$this->name = stripslashes(htmlspecialchars($name));
		$this->content = stripslashes(htmlspecialchars($content));
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
	
	
	function getNumActivities() {
		include_once(ROOT_PATH."classes/dao/activities.class.php");
		$activities = new Activities($this->store_id);
		$rowsPages = $activities->getNumItems('id', "`wid` = '".$this->id."'");
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
		if($lang == 'vn')	return $this->name;
		else return $this->properties['custom_'.$lang.'_name'];	
	}
	function setName($nValue,$lang='vn') {
		if($lang == 'vn')	$this->name=stripslashes($nValue);
		else $this->properties['custom_'.$lang.'_name']=stripslashes($nValue);
	}
	function getContent($lang='vn') {
		if($lang=='vn') return $this->content;	
		else return $this->properties['custom_'.$lang.'_content'];		
	}
	function setContent($nValue,$lang='vn') {
		if($lang=='vn') $this->content=stripslashes($nValue);
		else $this->properties['custom_'.$lang.'_content']=stripslashes($nValue);
	}
	function getStatus() {
		return $this->status;
	}
	
	function getStatusTextBackend() {
		global $amessages;
		return $amessages['status'][$this->status];
	}
}
?>
<?php
/*************************************************************************
Class Ad
----------------------------------------------------------------
Derasoft CMS Project
Company: Derasoft Co., Ltd                                  
Last updated: 21/09/2011
Coder: Tran Thi My Xuyen
Checked by: Mai Minh (26/09/2011)
**************************************************************************/
class AdsInfo {
	var $aId;			# Ad code (primary key)
	var $store_id;				
	var $gid;			# Group of the ad
	var $logo_url;		# Ad logo URL
	var $url;			# Ad URL
	var $status;		# 0-Disabled, 1-Active, 2-Deleted
	var $position;		# Display order
	var $viewed;		# Number of views
	var $date_created;	# Date created
	var $properties;	# Properties
	var $content;
	
	# Constructor
	function AdsInfo($logo_url, $url, $status=0, $position=0, $viewed=0,$date_created='',$properties='',$content='',$gid = 0, $store_id=0,$aId = 0)
	{
		$this->aId = $aId;
		$this->store_id=$store_id;
		$this->gid = $gid;
		$this->logo_url = $logo_url;
		$this->url = $url;
		$this->status = $status;
		$this->position = $position;
		$this->viewed = $viewed;
		$this->date_created = $date_created;
		$this->properties = unserialize($properties);
		$this->content=stripslashes(htmlspecialchars($content));
	}

	function getId() {
		return $this->aId;
	}	
	function setId($nValue) {
		$this->aId=$nValue;
	}	
	
	function getContent($lang='vn') {
		if($lang=='vn') return $this->content;		
		else return $this->properties['custom'.$lang.'_content'];
	}
	function setContent($nValue,$lang='vn') {
		if($lang=='vn') $this->content=stripslashes($nValue);
		else $this->properties['custom'.$lang.'_content']=stripslashes($nValue);
	}
	function getGId() {
		return $this->gid;		
	}
	function setGId($nValue) {
		$this->gid=$nValue;
	}
	function getCatName() {
		include_once(ROOT_PATH."classes/dao/adscategories.class.php");
		$adsCategories = new AdsCategories($this->store_id);
		return $adsCategories->getNameFromId($this->gid);
	}
	function getLogoUrl() {
		if($this->logo_url) return $this->logo_url;
	}
	function setLogoUrl($nValue) {
		$this->logo_url=$nValue;
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
	function getUrl() {
		return $this->url;		
	}
	function setUrl($nValue) {
		$this->url=$nValue;
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
	function getStatusText() {
		global $amessages;
		return $amessages['status_text'][$this->status];
	}
	function getStatusTextBackend() {
		global $amessages;
		return $amessages['status'][$this->status];
	}
}	
?>
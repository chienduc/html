<?php
/*************************************************************************
Class Song
----------------------------------------------------------------
Music Project
Company: Derasoft Co., Ltd
Last updated: 08/17/2013
Author: Cong Hoan (hoan052@gmail.com)
**************************************************************************/
class SongInfo {
	var $id;			# Primary key
	var $store_id;		# Song id
	var $cat_id;		# Category id	
	var $slug;			# Slug
	var $name;			# song name	
	var $composer;      # song writer	
	var $date_created;	# Date created
	var $updated;		# Date update
	var $position;
	var $properties;	# Properties
	var $status;		# 0-Disabled, 1-Active, 2-Deleted, 3-Unpublished
	
	#Constructor
	function SongInfo($id = 0, $store_id = 0, $cat_id = 0, $name, $slug, $composer, $date_created, $updated, $position, $properties, $status)
	{
		$this->id = $id;
		$this->store_id = $store_id;
		$this->cat_id = $cat_id;		
		$this->slug = stripslashes(htmlspecialchars($slug));
		$this->name = stripslashes(htmlspecialchars($name));
		$this->composer = stripslashes(htmlspecialchars($composer));	
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
	function getSongId() {
		return $this->song_id;
	}
	function setSongId($nValue) {
		$this->store_id=$nValue;
	}
	function getCatId() {
		return $this->cat_id;
	}
	function setCatId($nValue) {
		$this->cat_id=$nValue;
	}
	function getCatSlug() {
		include_once(ROOT_PATH."classes/dao/songcategories.class.php");
		$songCategories = new SongCategories($this->store_id);
		return $songCategories->getSlugFromId($this->cat_id);
	}
	function getCatName() {
		include_once(ROOT_PATH."classes/dao/songcategories.class.php");
		$songCategories = new SongCategories($this->store_id);
		return $songCategories->getNameFromId($this->cat_id);
	}
	
	function getSlug() {
		return $this->slug;		
	}
	
	function setSlug($nValue) {
		$this->slug=stripslashes($nValue);
	}
	
	function getName($lang='vn') {
		if($lang == 'vn')	return $this->name;
		else return $this->properties['custom_'.$lang.'_name'];	
	}
	
	function setName($nValue,$lang='vn') {
		if($lang == 'vn')	$this->name=stripslashes($nValue);
		else	$this->properties['custom_'.$lang.'_name']=stripslashes($nValue);
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
		return $amessages['status_song'][$this->status];
	}
	# Return 1 if File is not null
	function getNullFile($n) {
		for($i=1;$i<=$n;$i++){
		$key = "file".$i;
		if($this->$key!='')
			return 1;
		}
		return '';
	}
	
	function getUrl($page = 1, $keywords = '', $sort_key = 'position', $sort_direction = 'asc') {
		$url = '';
		if(URL_TYPE == 1 || $page > 1) {	# Query string
			$url = '/'.SCRIPT.'?act=song&id='.$this->id;
			return $url;
		} elseif(URL_TYPE == 2) {	# SEO
			$url = '/'.$this->getCatSlug().'/'.$this->slug.'-p'.$this->id.'.html';
			return $url;
		} else return '';	
	}
	
}	
?>
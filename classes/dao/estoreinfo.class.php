<?php
/*************************************************************************
Class EStore Info
----------------------------------------------------------------
BiDo.vn Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
Last updated: 06/20/2010
**************************************************************************/
class EStoreInfo {
	var $id;
	var $owner_id;
	var $area_id;
	var $cat_id;
	var $subdomain;
	var $domain;
	var $name;
	var $keywords;
	var $description;
	var $company;
	var $address;
	var $tel;
	var $cell;
	var $email;
	var $date_created;
	var $date_expire;
	var $properties;
	var $status;
				
	function EStoreInfo($owner_id, $area_id, $cat_id, $subdomain, $domain, $name, $keywords, $description, $company, $address, $tel, $cell, $email, $date_created, $date_expire, $properties, $status, $id = '0')
	{
		$this->id = $id;
		$this->owner_id = $owner_id;
		$this->area_id = $area_id;
		$this->cat_id = $cat_id;
		$this->subdomain = stripslashes(htmlspecialchars($subdomain));
		$this->domain = stripslashes(htmlspecialchars($domain));
		$this->name = stripslashes(htmlspecialchars($name));
		$this->keywords = stripslashes(htmlspecialchars($keywords));
		$this->description = stripslashes(htmlspecialchars($description));
		$this->company = stripslashes(htmlspecialchars($company));
		$this->address = stripslashes(htmlspecialchars($address));
		$this->tel = stripslashes(htmlspecialchars($tel));
		$this->cell = stripslashes(htmlspecialchars($cell));
		$this->email = stripslashes(htmlspecialchars($email));
		$this->date_created = $date_created;
		$this->date_expire = $date_expire;
		$this->properties = unserialize($properties);
		$this->status = $status;
	}
	function getId()
	{
		return $this->id;
	}
	function setId($nValue)
	{
		$this->id=$nValue;
	}
	function getOwnerId()
	{
		return $this->owner_id;
	}
	function setOwnerId($nValue)
	{
		$this->owner_id=$nValue;
	}
	function getOwnerUsername() {
		include_once(ROOT_PATH."classes/dao/users.class.php");
		$users = new Users($this->id);
		return $users->getUsername("`id` = '".$this->owner_id."'");	
	}
	function getAreaId()
	{
		return $this->area_id;
	}
	function setAreaId($nValue)
	{
		$this->area_id=$nValue;
	}
	function getCatId()
	{
		return $this->cat_id;
	}
	function setCatId($nValue)
	{
		$this->cat_id=$nValue;
	}
	function getCatName() {
		include_once(ROOT_PATH."classes/dao/estorecategories.class.php");
		$estoreCategories = new EstoreCategories();
		return $estoreCategories->getNameFromId($this->cat_id);
	}
	function getSubdomain()
	{
		return $this->subdomain;
	}
	function setSubdomain($nValue)
	{
		$this->subdomain=$nValue;
	}
	function getDomain()
	{
		return $this->domain;
	}
	function setDomain($nValue)
	{
		$this->domain=$nValue;
	}
	function getName($lang='vn')
	{
		if($lang=='vn')return $this->name;
		else return $this->properties['custom_'.$lang.'_name'];
	}
	function setName($nValue,$lang='vn')
	{
		if($lang=='vn') $this->name=$nValue;
		else  $this->properties['custom_'.$lang.'_name'] = $nValue;
	}
	function getKeywords($lang='vn')
	{
		if($lang=='vn')return $this->keywords;
		else return $this->properties['custom_'.$lang.'_keyword'];
	}
	function setKeywords($nValue,$lang='vn')
	{
		if($lang=='vn') $this->keyword=$nValue;
		else  $this->properties['custom_'.$lang.'_keyword']=$nValue;
	}
	function getDescription($lang='vn')
	{
		if($lang=='vn')return $this->description;
		else return $this->properties['custom_'.$lang.'_site_description'];
	}
	function setDescription($nValue,$lang='vn')
	{
		if($lang=='vn') $this->description=$nValue;
		else  $this->properties['custom_'.$lang.'_site_description']=$nValue;
	}
	function getCompany($lang='vn')
	{
		if($lang=='vn')return $this->company;
		else return $this->properties['custom_'.$lang.'_company'];
	}
	function setCompany($nValue,$lang='vn')
	{
		if($lang=='vn') $this->company=$nValue;
		else  $this->properties['custom_'.$lang.'_company']=$nValue;
	}
	function getAddress($lang='vn')
	{
		if($lang=='vn')return $this->address;
		else return $this->properties['custom_'.$lang.'_address'];
	}
	function setAddress($nValue,$lang='vn')
	{
		if($lang=='vn') $this->address=$nValue;
		else  $this->properties['custom_'.$lang.'_address']=$nValue;
	}
	function getTel()
	{
		return $this->tel;
	}
	function setTel($nValue)
	{
		$this->tel=$nValue;
	}
	function getCell()
	{
		return $this->cell;
	}
	function setCell($nValue)
	{
		$this->cell=$nValue;
	}
	function getEmail()
	{
		return $this->email;
	}
	function setEmail($nValue)
	{
		$this->email=$nValue;
	}
	function getDateCreated()
	{
		return $this->date_created;
	}
	function setDateCreated($nValue)
	{
		$this->date_created=$nValue;
	}
	function getDateExpire()
	{
		return $this->date_expire;
	}
	function setDateExpire($nValue)
	{
		$this->date_expire=$nValue;
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
	function getStatus()
	{
		return $this->status;
	}
	function setStatus($nValue)
	{
		$this->status=$nValue;
	}
	function getStatusTextBackend() {
		global $amessages;
		return $amessages['status'][$this->status];
	}
	function getAdsCategoryInfo($cId = 0) {
		$catItems = $this->getProperty('ads_category');
		if(isset($catItems[$cId]['rows'])) {
			return $catItems[$cId];
		} else {
			include_once(ROOT_PATH.'classes/dao/adscategories.class.php');
			$adsCategories = new AdsCategories();
			$adsCategoryInfo = $adsCategories->getObject($cId);
			if($adsCategoryInfo) {
				return $adsCategoryInfo->getProperties();
			}
		}
	}
	function getCurrency() {
		include_once(ROOT_PATH.'classes/dao/currencies.class.php');
		$currencies = new Currencies($this->id);
		$currency = $currencies->getPrimaryCurrency();
		return $currency->getName();
	}
}
?>
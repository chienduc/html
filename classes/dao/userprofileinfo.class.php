<?php
/*************************************************************************
Class User Info
----------------------------------------------------------------
DeraCMS Project
Company: Derasoft Co., Ltd                                  
Author: Mai Minh
Email: info@derasoft.com                                    
Last updated: 29/09/2009
**************************************************************************/
class UserProfileInfo {
	var $id;
	var $store_id;
	var $user_id;
	var $type;
	var $education_level;
	var $job_category;	# 1-Store staff, 2-Store admin, 3-Store boss, 7-Site staff, 8-Site admin, 9-Site boss
	var $years_experience;
	var $select_category;
	var $place_work;
	var $mode_work;
	var $salary;
	var $position;
	var $status;
	var $date_created;
	var $properties;

	function UserProfileInfo($type, $education_level, $job_category, $years_experience, $select_category, $place_work, $mode_work, $salary, $position, $status,  $date_created, $properties, $user_id=0, $store_id = 0, $id = 0 )
	{
		$this->id = $id;
		$this->store_id = $store_id;
		$this->user_id = $user_id;
		$this->type = $type;
		$this->education_level = $education_level;
		$this->job_category = $job_category;
		$this->years_experience = $years_experience;
		$this->select_category = $select_category;	
		$this->place_work= $place_work;
		$this->mode_work = $mode_work;
		$this->salary = $salary;		
		$this->position = $position;
		$this->status = $status;
		$this->date_created = $date_created;
		$this->properties = unserialize($properties);
	}
	function getId()
	{
		return $this->id;
	}
	function setId($nValue) {
		$this->id = $nValue;
	}
	function getStoreid()
	{
		return $this->store_id;
	}
	function setStoreid($nValue) {
		$this->store_id = $nValue;
	}
	function getUserId()
	{
		return $this->user_id;
	}
	function setUserId($nValue) {
		$this->user_id = $nValue;
	}
	function getType()	{
		return $this->type;
	}
	function setType($nValue) {
		$this->type = $nValue;
	}
	function getEducationLevel()
	{
		return $this->education_level;
	}
	function setEducationLevel($nValue) {
		$this->education_level = $nValue;
	}
	function getJobCategory()
	{
		return $this->job_category;
	}
	function setJobCategory($nValue) {
		$this->job_category = $nValue;
	}
	function getYearsExperience()
	{
		return $this->years_experience;
	}
	function setYearsExperience($nValue) 
	{
		$this->years_experience = $nValue;
	}
	function getSelectCategory()
	{
	  return $this->select_category;
	}
	function setSelectCategory($nValue) {
		$this->select_category = $Value;
	}
	function getPlaceWork()
	{
		return $this->place_work;
	}
	function setPlaceWork($nValue)
	{
		$this->place_work = $nValue;
	}
	function getModeWork() 
	{
		return $this->mode_work;
	}
	function setModeWork($nValue) {
		$this->mode_work = $nValue;
	}
	function getSalary() 
	{
		return $this->salary;
	}
	function setSalary($nValue) 
	{
		$this->salary = $nValue;
	}
	function getPosition() 
	{
		return $this->position;
	}
	function setPosition($nValue) 
	{
		$this->position = $nValue;
	}
	function getStatus()
	{
		return( $this->status );
	}
	function setStatus($nValue) 
	{
		$this->status = $nValue;
	}
	function getDateCreated()
	{
		return $this->date_created;
	}
	function setDateCreated($nValue) 
	{
		$this->date_created = $nValue;
	}
	function getProperties()
	{
		return $this->properties;
	}
	function setProperties($nValue)
	{
		$this->properties=$nValue;
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
	function getStatusText() {
		global $messages;
		return $messages['status_user'][$this->status];
	}
	function getStatusTextBackend() {
		global $amessages;
		return $amessages['status_user'][$this->status];
	}
	function getTypeTextBackend() {
		global $amessages;
		return $amessages['type_user'][$this->type];
	}
	function getInfo(){
		include_once(ROOT_PATH."classes/dao/customers.class.php");
		$customers = new Customers($this->store_id);
		$customerInfo = $customers->getObject($this->user_id);
		if($customerInfo){
			$infoCustomer = array();
			if($customerInfo->getType() == 1){
			
				$infoCustomer = array('avatar' => $customerInfo->getProperty('avatar'), 'name' => $customerInfo->getFullName(), 'address' => $customerInfo->getAddress(), 'email' => $customerInfo->getEmail(), 'tel' => $customerInfo->getTel(), 'date_born' => $customerInfo->getProperty('date_born'));
			}else{
				$infoCustomer = array('avatar' => $customerInfo->getProperty('logo'), 'name' => $customerInfo->getFullName(), 'address' => $customerInfo->getAddress(), 'email' => $customerInfo->getEmail(), 'tel' => $customerInfo->getTel(), 'website' => $customerInfo->getProperty('website_company'));
			}
			return $infoCustomer;
		}else return '';
	}
	function getUrl($lang='vn') {
		$url = '';
		if(URL_TYPE == 1 || $page > 1) {	# Query string
			$url = "/$lang/".SCRIPT.'?act=employ&id='.$this->id;
			return $url;
		} elseif(URL_TYPE == 2) {	# SEO
			 $url = "/employment/u-".$this->id.'.html';
			return $url;
		} else return '';	
	}
}
?>
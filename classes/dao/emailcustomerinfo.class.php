<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
class EmailCustomerInfo {
	var $id;			
	var $store_id;		
	var $email;		
	var $name;	
	var $sex;			
	var $company;			
	var $website;		
	var $province;			
	var $send;		
	var $open;		
	var $details;	
	var $date_update;	
	var $date_created;
	var $status;		# 0-Disabled, 1-Active, 2-Deleted, 3-Unpublished
	var $feedback;		
	var $campaign;		

	# Constructor
	function EmailCustomerInfo($email, $name, $sex, $company, $website, $province,$send, $open, $details, $date_created, $date_update, $status, $feedback, $campaign, $store_id = 0, $id = 0)
	{
		$this->id = $id;
		$this->store_id = $store_id;
		$this->email = $email;
		$this->name = stripslashes($name);
		$this->sex = stripslashes($sex);
		$this->company = stripslashes($company);
		$this->website = $website;
		$this->province = $province;
		$this->send = $send;
		$this->details = $details;
		$this->open = $open;
		$this->date_created = $date_created;
		$this->date_update = $date_update;
		$this->status = $status;
		$this->feedback = $feedback;
		$this->campaign = $campaign;
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
	function getEmail() {
		return $this->email;
	}
	function setEmail($nValue) {
		$this->email=$nValue;
	}
	
	function getName() {
		return $this->name;		
	}
	function setName($nValue) {
		$this->name=stripslashes($nValue);
	}
    function getCompany() {
		return $this->company;		
	}
	function setCompany($nValue) {
		$this->company=stripslashes($nValue);
	}
	function getWebsite() {
		return $this->website;		
	}
	function setWebsite($nValue) {
		$this->website=stripslashes($nValue);
	}
	function getProvince() {
		return $this->province;		
	}
	function setProvince($nValue) {
		$this->province=stripslashes($nValue);
	}
	function getProvinceName() {
		include_once(ROOT_PATH."classes/dao/areas.class.php");
		$areas = new Areas($this->store_id);
		return $areas->getNameFromId($this->province);
	}
	function getSend() {
		return $this->send;
	}	
	function setSend($nValue) {
		$this->send=$nValue;
	}
	function getOpen() {
		return $this->open;
	}	
	function setOpen($nValue) {
		$this->open=$nValue;
	}
	function getSex() {
		return $this->sex;
	}	
	function setSex($nValue) {
		$this->sex=$nValue;
	}
	function getDetails() {
		return $this->details;
	}	
	function setDetails($nValue) {
		$this->details=$nValue;
	}
	function getDateCreated()
	{
		return $this->date_created;
	}
	function setDateCreated($nValue)
	{
		$this->date_created=$nValue;
	}
	function getDateUpdate()
	{
		return $this->date_update;
	}
	function setDateUpdate($nValue)
	{
		$this->date_update=$nValue;
	}
	
	function getStatus() {
		return $this->status;
	}
	function setStatus($nValue) {
		$this->status = $nValue;
	}
	function getFeedback() {
		return $this->feedback;
	}
	function setFeedback($nValue) {
		$this->feedback = $nValue;
	}
	function getCampaign() {
		return $this->campaign;
	}
	function setCampaign($nValue) {
		$this->campaign = $nValue;
	}
	function getStatusTextBackend() {
		global $amessages;
		return $amessages['customers'][$this->status];
	}
	
}	
?>
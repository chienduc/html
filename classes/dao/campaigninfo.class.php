<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
class CampaignInfo {
	var $id;			
	var $store_id;	
	var $name;	
	var $name_display;
	var $email;		
	var $id_email;			
	var $appointment;		
	var $send;		
	var $open;		
	var $date_created;	
	var $status;		
	# Constructor
	function CampaignInfo($name, $email, $name_display, $appointment, $send, $open, $date_created, $status=0, $id_email=1,$store_id=0, $id = 0)
	{
		$this->id = $id;
		$this->store_id=$store_id;
		$this->name = $name;
		$this->email = $email;
		$this->name_display = $name_display;
		$this->id_email = $id_email;
		$this->appointment = $appointment;
		$this->send = $send;
		$this->open = $open;
		$this->date_created = $date_created;
		$this->status = $status;
	}

	function getId() {
		return $this->id;
	}	
	function setId($nValue) {
		$this->id=$nValue;
	}	
	function getName() {
		return $this->name;
	}	
	function setName($nValue) {
		$this->name=$nValue;
	}
	
	function getEmail() {
		return $this->email;
	}	
	function setEmail($nValue) {
		$this->email=$nValue;
	}
	
	function getNameDisplay() {
		return $this->name_display;
	}	
	function setNameDisplay($nValue) {
		$this->name_display=$nValue;
	}
	function getMailName() {
		include_once(ROOT_PATH."classes/dao/emailtemplate.class.php");
		$emailTemplate = new EmailTemplate($this->store_id);
		return $emailTemplate->getNameFromId($this->id_email);
	}
	function getIdEmail() {
		return $this->id_email;
	}	
	function setIdEmail($nValue) {
		$this->id_email=$nValue;
	}
	function getAppointment() {
		return $this->appointment;
	}
	function getAppointments() {
		return Changeundate($this->appointment);
	}
	function setAppointment($nValue) {
		$this->appointment=$nValue;
	}
	function getSend() {
		return $this->send;
	}
	function setSend($nValue) {
		$this->send=$nValue;
	}
	function getStatus() {
		return $this->status;
	}
	function setStatus($nValue) {
		$this->status = $nValue;
	}
	function getOpen() {
		return $this->open;
	}
	function setOpen($nValue) {
		$this->open = $nValue;
	}
	function getDateCreated() {
		return $this->date_created;
	}
	
	function setDateCreated($nValue) {
		$this->date_created = $nValue;
	}
	function getStatusText() {
		global $amessages;
		return $amessages['status_text'][$this->status];
	}
	function getStatusTextBackend() {
		global $amessages;
		return $amessages['campaign'][$this->status];
	}
	
}	
?>
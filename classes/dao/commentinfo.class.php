<?php
/*************************************************************************
Class Staticinfo
----------------------------------------------------------------
DeraCMS Project
Company: Derasoft Co., Ltd                                  
Name: Tran Thi Kim Que                                  
Last updated: 15/10/2009                                  
**************************************************************************/
class CommentInfo {
	var $id;
	var $pid;
	var $fullname;
	var $email;
	var $tel;
	var $address;
	var $details;
	var $type;
	var $id_type;
	var $like;
	var $user_id;
	var $user_type;
	var $created;
	var $store_id;
	var $status;
	var $avatar;
	function CommentInfo ($fullname='', $email='',$tel,$address, $details='',$created, $type, $id_type, $like, $user_id, $user_type, $avatar,$store_id, $status = '',$pid='', $id = 0)
	{
		$this->id = $id;
		$this->pid = $pid;
		$this->fullname = $fullname;
		$this->email = $email;
		$this->tel = $tel;
		$this->address = $address;
		$this->details = $details;
		$this->type = $type;
		$this->id_type = $id_type;
		$this->like = $like;
		$this->user_id = $user_id;
		$this->user_type = $user_type;
		$this->avatar = $avatar;
		$this->created = $created;
		$this->store_id = $store_id;
		$this->status = $status;
	}

	function getId() {
		return $this->id;
	}	
	function setId($nValue) {
		$this->id=$nValue;
	}
	function getPId() {
		return $this->pid;
	}	
	function setPId($nValue) {
		$this->user_id=$nValue;
	}
	function getUserId() {
		return $this->user_id;
	}	
	function setUserId($nValue) {
		$this->pid=$nValue;
	}
	function getUserType() {
		return $this->user_type;
	}	
	function setUserType($nValue) {
		$this->user_type=$nValue;
	}
	function getAvatar() {
		return $this->avatar;
	}	
	function setAvatar($nValue) {
		$this->avatar=$nValue;
	}
	
	function getFullname() {
		return $this->fullname;
	}	
	function setFullname($nValue) {
		$this->fullname=stripslashes($nValue);
	}
	function getEmail() {
		return $this->email;
	}	
	function setEmail($nValue) {
		$this->email=$nValue;
	}
	function getTel() {
		return $this->tel;
	}	
	function setTel($nValue) {
		$this->tel=$nValue;
	}
	function getAddress() {
		return $this->address;
	}	
	function setAddress($nValue) {
		$this->address=$nValue;
	}
	function getDetails() {
		return $this->details;
	}	
	function setDetails($nValue) {
		$this->details=$nValue;
	}
	function getDateCreated() {
		return $this->created;
	}	
	function setDateCreated($nValue) {
		$this->created=$nValue;
	}
	function getStoreId() {
		return $this->store_id;
	}
	function setStoreId($nValue) {
		$this->store_id=$nValue;
	}
	function getStatus() {
		return $this->status;
	}	
	function setStatus($nValue) {
		$this->status=$nValue;
	}
	
	function getType() {
		return $this->type;
	}	
	function setType($nValue) {
		$this->type=$nValue;
	}
	
	function getIdType() {
		return $this->id_type;
	}	
	function setIdType($nValue) {
		$this->id_type=$nValue;
	}
	function getLike() {
		return $this->like;
	}	
	function setLike($nValue) {
		$this->like=$nValue;
	}
	function getStatusTextBackend() {
		global $amessages;
		return $amessages['status'][$this->status];
	}
	function getProname() {
		return 'Du an';
	}
    function getChildren() {
		include_once(ROOT_PATH."classes/dao/comments.class.php");
		$comments = new Comments($this->store_id);
		$Items = $comments->getObjects(1,"`pid` = '".$this->id."'",array('created'=>'ASC'),100);
		return $Items;
	}
	
}	

?>
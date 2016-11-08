<?php
/*************************************************************************
Class Saleoff Info
----------------------------------------------------------------
BiDo.vn Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
Last updated: 06/20/2010
**************************************************************************/
class SaleoffInfo {
	var $id;
	var $store_id;
	var $name;
	var $description;
	var $obj;		# 1 cho sản phẩm, 2 cho đơn hàng
	var $type;		# 1 giảm theo %, 2 giảm theo tiền
	var $amount;	# Số lượng giảm (% hoặc tiền)
	var $date_created;
	var $date_started;
	var $date_ended;
	var $status;
				
	function SaleoffInfo($store_id, $name, $description, $object, $type, $amount, $date_created, $date_started, $date_ended, $status, $id = '0')
	{
		$this->id = $id;
		$this->store_id = $store_id;
		$this->name = stripslashes(htmlspecialchars($name));
		$this->description = stripslashes(htmlspecialchars($description));
		$this->obj = $object;
		$this->type = $type;
		$this->amount = $amount;
		$this->date_created = $date_created;
		$this->date_started = $date_started;
		$this->date_ended = $date_ended;
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
	function getStoreId()
	{
		return $this->store_id;
	}
	function setStoreId($nValue)
	{
		$this->store_id=$nValue;
	}
	function getName()
	{
		return $this->name;
	}
	function setName($nValue)
	{
		$this->name=$nValue;
	}
	function getDescription()
	{
		return $this->description;
	}
	function setDescription($nValue)
	{
		$this->description=$nValue;
	}
	function getObject()
	{
		return $this->obj;
	}
	function setObject($nValue)
	{
		$this->obj=$nValue;
	}
	function getType()
	{
		return $this->type;
	}
	function setType($nValue)
	{
		$this->type=$nValue;
	}
	function getAmount()
	{
		return $this->amount;
	}
	function setAmount($nValue)
	{
		$this->amount=$amount;
	}
	function getDateCreated()
	{
		return $this->date_created;
	}
	function setDateCreated($nValue)
	{
		$this->date_created=$nValue;
	}
	function getDateStarted()
	{
		return $this->date_started;
	}
	function setDateStarted($nValue)
	{
		$this->date_started=$nValue;
	}
	function getDateEnded()
	{
		return $this->date_ended;
	}
	function setDateEnded($nValue)
	{
		$this->date_ended=$nValue;
	}		
	function getStatus()
	{
		return $this->status;
	}
	function setStatus($nValue)
	{
		$this->status=$nValue;
	}
}
?>
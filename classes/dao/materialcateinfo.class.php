<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
class MaterialCateInfo {
	var $id;			# Primary key
	var $id_cate;		# Position
	var $id_business;	# Properties
	# Constructor
	function MaterialCateInfo($id_cate, $id_business, $id = 0)
	{
		$this->id = $id;
		$this->id_cate = $id_cate;
		$this->id_business = $id_business;
	}
	function getId() {
		return $this->id;
	}	
	function setId($nValue) {
		$this->id=$nValue;
	}
	function getIdCate() {
		return $this->id_cate;
	}	
	function setIdCate($nValue) {
		$this->id_cate=$nValue;
	}
	
	function getIdBusiness() {
		return $this->id_business;
	}	
	function setIdBusiness($nValue) {
		$this->id_business=$nValue;
	}
	
		
	
	
	
}	
?>
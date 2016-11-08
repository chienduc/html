<?php
/*************************************************************************
Code : PhanTom
**************************************************************************/
class TourCateInfo {
	var $id;		    # Item ID
	var $id_article;		
	var $tour;	
	var $type;	

	# Constructor
	function ProductCateInfo($id_product, $cat_id , $id= 0)
	{
		$this->id = $id;
		$this->id_product = $id_product;
		$this->cat_id = $cat_id;
	}
	function getId() {
		return $this->id;
	}	
	function setId($nValue) {
		$this->id=$nValue;
	}

	function getIdProduct() {
		return $this->id_product;
	}	
	function setIdProduct($nValue) {
		$this->id_product=$nValue;
	}
	
	function getCatId() {
		return $this->cat_id;
	}	
	function setCatId($nValue) {
		$this->cat_id=$nValue;
	}
	
}	
?>
<?php
/*************************************************************************
Class CartItems
----------------------------------------------------------------
BiDo.vn Project
Last updated: 07/05/2010
Author: Mai Minh (http://maiminh.vnweblogs.com)
**************************************************************************/
include_once(ROOT_PATH."classes/dao/mysql.class.php");
include_once(ROOT_PATH."classes/dao/cartitem.class.php");

class Carts extends Model {
	var $table;
	var $_db;
	var $store_id;
	var $sess_id;
	
	function Carts($store_id = 0, $sess_id = 0, $database = '') {
		if(!$database) {
			global $db;
			$this->_db = $db;
		} else $this->_db = $database;
		$this->table = DB_PREFIX."cart_items";
		$this->store_id = $store_id;
		$this->sess_id = $sess_id;
	}

/* Common methods
/*-----------------------------------------------------------------------*
* Function: getObject
* Parameter: key
* Return: Info object
*-----------------------------------------------------------------------*/
	function getObject($value = '0', $key = 'id', $condition = '1>0') {
		if(!$key || !$value) return '';
		$result = $this->select('*', "`store_id` = '".$this->store_id."' AND `session_id` = '".$this->sess_id."' AND `$key` = '$value' AND ($condition)");
		if($result) {
			$object = new CartItem
						(	$result[0]['product_id'],
							$result[0]['quantity'],
							$result[0]['properties'],
							$result[0]['time_stamp'],
							$result[0]['session_id'],
							$result[0]['store_id'],
							$result[0]['id']
						);
			return $object;
		}
		return 0;
	}
/*-----------------------------------------------------------------------*
* Function: getObjects
* Parameter: WHERE condition
* Return: Array of Info objects
*-----------------------------------------------------------------------*/
	function getObjects($page = 1, $condition = '1>0', $sort = array(), $items_per_page = DEFAULT_ADMIN_ROWS_PER_PAGE) {
		if(!$page) $page = 1;
		$start = ($page -1) * $items_per_page;
		$results = $this->select('*', "`store_id` = '".$this->store_id."' AND `session_id` = '".$this->sess_id."' AND $condition", $sort, $start, $items_per_page);
		if($results) {
			$objects = array();
			foreach($results as $key => $result) {
				$objects[] = new CartItem
						(	$result['product_id'],
							$result['quantity'],
							$result['properties'],
							$result['time_stamp'],
							$result['session_id'],
							$result['store_id'],
							$result['id']
						);
			}
			return $objects;
		}
		return 0;
	}

	# Add record
	function addData($fields,$key = 'id') {
		$result = $this->add($fields,'$key','NULL');
		if($result) return $result;
		return 0;
	}

	# Update record
	function updateData($fields, $value = '', $key = 'id') {
		$result = $this->update($fields,"`store_id` = '".$this->store_id."' AND `session_id` = '".$this->sess_id."' AND `$key` = '$value'");
		if($result)
			return $result;
		return 0;
	}

	# Delete record
	function deleteData($value = '', $key = 'id') {
		$result = $this->delete("`store_id` = '".$this->store_id."' AND `session_id` = '".$this->sess_id."' AND `$key` = '$value'");
		if($result) return 1;
		return 0;
	}
	
		# Delete record
	function emptyCart() {
		$result = $this->delete("`store_id` = '".$this->store_id."' AND `session_id` = '".$this->sess_id."'");
		if($result) return 1;
		return 0;
	}
		
	# Check if a product already exists in cart
	function isProductExists($product_id = 0) {
		$rows = $this->countItems('id',"`store_id` = '".$this->store_id."' AND `session_id` = '".$this->sess_id."'".($product_id?" AND `product_id` = '$product_id'":''));
		return $rows;
	}
	
	function getNumItemsInCart() {
		return $this->countItems('id', "`store_id` = '".$this->store_id."' AND `session_id` = '".$this->sess_id."'");
	}
	function getSumQuantity($sessionId='') {
		$result = $this->select("sum(`quantity`)", "`store_id` = '".$this->store_id."' AND session_id='$sessionId'");
		if($result)
			return $result[0][0];
		return '';
	}
	// Total price of carts 
	function getCartCost($storeId,$rprovince,$sessId) {
		include_once(ROOT_PATH."classes/dao/estores.class.php");
		$estore = new EStores();
		$estoreInfo = $estore->getObject($storeId);
		$vat = $estoreInfo->getProperty('order_vat');	// order vat 
		
		include_once(ROOT_PATH."classes/dao/areas.class.php");
		$areas = new Areas($storeId);
		$areaObject = $areas->getObject($rprovince,'id');
		if($areaObject) $price = $areaObject->getShipPrice();	// shipping price in area
		
		include_once(ROOT_PATH."classes/dao/carts.class.php");
		$carts = new Carts($storeId,$sessId);
		$cartItems = $carts->getObjects(1,"1=1",array('id'=>'ASC'),'');
		$subWeight = 0;
		$grandTotal = 0;
		if($cartItems){
		foreach($cartItems as $cartItem){
			$weight = $cartItem->getProWeight($storeId) * $cartItem->getQuantity();
			$subWeight = $subWeight + $weight;	// total weight of product on order 
			$priceTotal = $cartItem->getProPrice() * $cartItem->getQuantity();
			$grandTotal = $grandTotal + $priceTotal;	// total price of product on order
		}
		}
		$vatFrice = ($grandTotal*$vat)/100;	// vat on order 
		$shippingPrice = $price*$subWeight;	// shipping price on order 
		$subgrandTotal = $grandTotal + $shippingPrice + $vatFrice;	// total price of order 
		$costs = array('shipping' => $shippingPrice, 'grandTotal' => $grandTotal, 'vatFrice' => $vatFrice, 'subgrandTotal' => $subgrandTotal);
		return $costs;
	}
}
?>
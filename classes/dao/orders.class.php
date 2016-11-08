<?php
/*************************************************************************
Class Orders
----------------------------------------------------------------
BiDo.vn Project
Last updated: 13/11/2010
Author: Mai Minh (http://maiminh.vnweblogs.com)
Coder: Tran Thi My Xuyen
**************************************************************************/
include_once(ROOT_PATH."classes/dao/mysql.class.php");
include_once(ROOT_PATH."classes/dao/orderinfo.class.php");

class Orders extends Model {
	var $table;
	var $_db;
	var $store_id;
	
	function Orders($store_id = 0, $database = '') {
		if(!$database) {
			global $db;
			$this->_db = $db;
		} else $this->_db = $database;
		$this->table = DB_PREFIX."orders";
		$this->store_id = $store_id;
	}

/* Common methods
/*-----------------------------------------------------------------------*
* Function: getObject
* Parameter: key
* Return: Info object
*-----------------------------------------------------------------------*/
	function getObject($value = '0', $key = 'id', $condition = '1>0') {
		if(!$key || !$value) return '';
		$result = $this->select('*', "`store_id` = '".$this->store_id."' AND `$key` = '$value' AND ($condition)");
		if($result) {
			$object = new OrderInfo
						(	$result[0]['code'],
							$result[0]['name'],
							$result[0]['email'],
							$result[0]['address'],
							$result[0]['province'],
							$result[0]['tel'],
							$result[0]['cell'],
							$result[0]['rname'],
							$result[0]['raddress'],
							$result[0]['rprovince'],
							$result[0]['rtel'],
							$result[0]['rcell'],
							$result[0]['rdate'],
							$result[0]['rnote'],
							$result[0]['created'],
							$result[0]['updated'],
							$result[0]['properties'],
							$result[0]['status'],
							$result[0]['u_type'],
							$result[0]['user_id'],
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
		$results = $this->select('*', "`store_id` = '".$this->store_id."' AND $condition", $sort, $start, $items_per_page);
		if($results) {
			$objects = array();
			foreach($results as $key => $result) {
				$objects[] = new OrderInfo
								(	$result['code'],
									$result['name'],
									$result['email'],
									$result['address'],
									$result['province'],
									$result['tel'],
									$result['cell'],
									$result['rname'],
									$result['raddress'],
									$result['rprovince'],
									$result['rtel'],
									$result['rcell'],
									$result['rdate'],
									$result['rnote'],
									$result['created'],
									$result['updated'],
									$result['properties'],
									$result['status'],
									$result['u_type'],
									$result['user_id'],
									$result['store_id'],
									$result['id']
								);
			}
			return $objects;
		}
		return 0;
	}

/*-----------------------------------------------------------------------*
* Function: updateData
* Parameter: Info object
* Return: 1 if success, 0 if fail
*-----------------------------------------------------------------------*/	
	# Add record
	function addData($fields,$key = 'id') {
		$result = $this->add($fields,'$key','NULL');
		if($result) return $result;
		return 0;
	}

	# Update record
	function updateData($fields, $value = '', $key = 'id') {
		$result = $this->update($fields,"`store_id` = '".$this->store_id."' AND `$key` = '$value'");
		if($result)
			return $result;
		return 0;
	}

	# Change status
	function changeStatus($id = 0, $status = '') {
		if(!$id) return 0;
		if($this->update(array('status' => $status), "`store_id` = '".$this->store_id."' AND `id` = '$id'")) return 1;
		return 0;
	}

	# Clean trash
	function cleanTrash() {
		include_once(ROOT_PATH.'classes/dao/orderitems.class.php');
		$listOrder = $this->select('id', "`store_id` = '".$this->store_id."' AND `status` = ".S_DELETED);
		if($listOrder){
			foreach($listOrder as $order){
				$orderObject = new OrderItems($order['id']);
				$orderObject->deleteData($order['id'],'order_id');
			}
		}
		$result = $this->delete("`store_id` = '".$this->store_id."' AND `status` = ".S_DELETED);
		if($result) return 1;
		return 0;
	}	

	# Check duplicate
	function duplicateSlug($slug, $id = 0) {
		$rows = $this->countItems('id',"`store_id` = '".$this->store_id."' AND `slug` = '$slug'".($id?" AND `id` <> '$id'":''));
		if($rows) return 1;
		return 0;		
	}
	function getStatusPayment($status) {
		global $amessages;
		return $amessages['status_payment'][$status];
	}
	
	function getTopStoreOrder(){
		$results = $this->select('*,count(`id`)',"1=1 group by `store_id`",array('id'=>'asc'),10);
		$objects = array();
		if($results) {
			foreach($results as $key => $result) {
				$objects[]= $result['store_id'];
			}
			return implode(",",$objects);
		}
		else return 0;	
	}
		
}
?>
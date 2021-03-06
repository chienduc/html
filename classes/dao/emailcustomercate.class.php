<?php
/*************************************************************************
Coder : PhanTom
**************************************************************************/
include_once(ROOT_PATH."classes/database/model.class.php");
include_once(ROOT_PATH."classes/dao/emailcustomercateinfo.class.php");

class EmailCustomerCate extends Model {
	var $table;
	var $_db;
	
	function EmailCustomerCate($database = '') {
		if(!$database) {
			global $db;
			$this->_db = $db;
		} else $this->_db = $database;
		$this->table = DB_PREFIX."emailcustomer_cate";
	}

/* Common methods
/*-----------------------------------------------------------------------*
* Function: getObject
* Parameter: key
* Return: Info object
*-----------------------------------------------------------------------*/
	function getObject($value = '0', $key = 'id', $condition = '1>0') {
		if(!$key || !$value) return '';
		$result = $this->select('*', "`$key` = '$value' AND ($condition)");
		if($result) {
			$object = new EmailCustomerCateInfo
						(	$result[0]['id'],
							$result[0]['customer'],
							$result[0]['group'],
							$result[0]['campaign']
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
		$results = $this->select('*', "$condition", $sort, $start, $items_per_page);
                if($results) { 
			$objects = array();
			foreach($results as $key => $result) {
				$objects[] = new EmailCustomerCateInfo
						(	$result['id'],
							$result['customer'],
							$result['group'],
							$result['campaign']
						);
			}   
			return $objects;
		}
		return 0;
	}

	function getGroupFromCampaign($customer='') {
		if(!$customer) return 0;
		$results = $this->select('*', "`customer`=$customer",array('id'=>'asc'),'');
		if($results) { 
			$objects = array();
			foreach($results as $key => $result) {
				$objects[] = $result['group'];		
			}
			return $objects;
			
		}
		return 0;
	}
		function getCustomerFromGroup($group='') {
		if(!$group) return 0;
		$results = $this->select('*', "`group`=$group",array('id'=>'asc'),'');
		if($results) { 
			$objects = array();
			foreach($results as $key => $result) {
				$objects[] = $result['customer'];		
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
		$result = $this->update($fields,"`$key` = '$value'");
		if($result)
			return $result;
		return 0;
	}

	# Delete record
	function deleteData($value = '', $key = 'id') {
		$result = $this->delete("`$key` = '$value'");
		if($result) return 1;
		return 0;
	}	

}
?>
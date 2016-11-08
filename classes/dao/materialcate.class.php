<?php
/*************************************************************************
Coder: PhanTOm
**************************************************************************/
include_once(ROOT_PATH."classes/dao/mysql.class.php");
include_once(ROOT_PATH."classes/dao/materialinfo.class.php");

class MaterialCate extends Model {
	var $table;
	var $_db;

	function MaterialCate($database = '') {
		if(!$database) {
			global $db;
			$this->_db = $db;
		} else $this->_db = $database;
		$this->table = DB_PREFIX."material_cate";
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
			$object = new MaterialCateInfo
						(  
						    $result[0]['id_cate'],
							$result[0]['id_business'],
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
		$results = $this->select('*', "$condition", $sort, $start, $items_per_page);
		if($results) {
			$objects = array();
			foreach($results as $key => $result) {
				$objects[] = new MaterialCateInfo
								( 
								     $result['id_cate'],
							         $result['id_business'],
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
		$result = $this->update($fields,"`$key` = '$value'");
		if($result)
			return $result;
		return 0;
	}
	# Update record
	function _updateData($fields,$condition = 0) {
		if(!$condition) return 0;
		$result = $this->update($fields,$condition);
		if($result)
			return $result;
		return 0;
	}
	function deleteData($id =0) {
		$result = $this->delete("`id_cate` = '".$id."'");
	}
	function getAllId($id =0, $type=0 , $sort = array('id' => 'ASC')) {
		$all=array();
		$results = $this->select("id_business", "`id_cate` = '".$id."'", $sort, 0, 500);
		if($results) {
			foreach($results as $key => $result)
				$all[]= $result['id_business'];
		}
				
	return $all;
			 
	}	
	
	
}
?> 
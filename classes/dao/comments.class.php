<?php
/*************************************************************************
ClassStaticpage
----------------------------------------------------------------
DeraCMS Project
Company: Derasoft Co., Ltd                                  
Name: Tran Thi Kim Que                                  
Last updated: 15/10/2009    
**************************************************************************/

include_once(ROOT_PATH.'classes/dao/model.class.php');
include_once(ROOT_PATH.'classes/dao/commentinfo.class.php');

class Comments extends Model {
	var $table;
	var $_db;
	var $store_id;
	
	function Comments($store_id = 0, $database = '') {
		if(!$database) {
			global $db;
			$this->_db = $db;
		} else $this->_db = $database;
		$this->table = DB_PREFIX."comment";	
		$this->store_id = $store_id;
	}

/*-----------------------------------------------------------------------*
* Function: getObject
* Parameter: key
* Return: Info object
*-----------------------------------------------------------------------*/
	function getObject($value = '0',$key = 'id', $condition = '1>0') {
		if(!$key || !$value) return '';
		$result = $this->select('*', "`store_id` = '".$this->store_id."' AND `$key` = '$value' AND ($condition)");
		if($result) {
			$object = new CommentInfo (
										$result[0]['fullname'],
										$result[0]['email'],
										$result[0]['tel'],
										$result[0]['address'],
										$result[0]['details'],
										$result[0]['created'],
										$result[0]['type'],
										$result[0]['id_type'],
										$result[0]['like'],
										$result[0]['user_id'],
										$result[0]['user_type'],
										$result[0]['avatar'],
										$result[0]['store_id'],
										$result[0]['status'],
										$result[0]['pid'],
										$key
										);
			return $object;
		}
		return '';
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
				$objects[] = new CommentInfo (
													$result['fullname'],
													$result['email'],
													$result['tel'],
													$result['address'],
													$result['details'],
													$result['created'],
													$result['type'],
													$result['id_type'],
													$result['like'],
													$result['user_id'],
													$result['user_type'],
													$result['avatar'],
													$result['store_id'],
													$result['status'],
													$result['pid'],
													$result['id']);
			}
			return $objects;
		}
		return '';
	}
 
/*-----------------------------------------------------------------------*
* Function: addData
* Parameter: Info object
* Return: 1 if key already exists, 0 if not exists
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
	
	# under price
	function getNumComment($id_type =0) {
	$results = $this->select('id', "`store_id` = '".$this->store_id."' AND status =1 AND `id_type` = $id_type");
		if($results) 
		return count($results);
		else
		return 0;
	}
	function getNumAllComment($idType =0) {
	$results = $this->select('id', "`store_id` = '".$this->store_id."' AND status =1 AND `id_type` in ('$idType')");
		if($results) 
		return count($results);
		else
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
		$result = $this->delete("`store_id` = '".$this->store_id."' AND `status` = ".S_DELETED);
		if($result) return 1;
		return 0;
	}	
}
?>
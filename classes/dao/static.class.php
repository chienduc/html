<?php
/*************************************************************************
ClassStaticpage
----------------------------------------------------------------
Bido.vn Project
Company: Derasoft Co., Ltd                                  
Last updated: 15/09/2011
Coder: Tran Thi My Xuyen
Checked by: Mai Minh (21/09/2011)
**************************************************************************/
include_once(ROOT_PATH.'classes/dao/model.class.php');
include_once(ROOT_PATH.'classes/dao/staticinfo.class.php');

class StaticPage extends Model {
	var $table;
	var $_db;
	var $store_id;
	
	function StaticPage($store_id = 0, $database = '') {
		if(!$database) {
			global $db;
			$this->_db = $db;
		} else $this->_db = $database;
		$this->table = DB_PREFIX."static";
		$this->store_id = $store_id;
	}

/*-----------------------------------------------------------------------*
* Function: getObject
* Parameter: key
* Return: Info object
*-----------------------------------------------------------------------*/

	function getObject($key = '0') {
		$result = $this->select('*',"`store_id` = '".$this->store_id."' AND `id` = '$key'");
		if($result) {
			$object = new StaticInfo (
										$result[0]['slug'],
										$result[0]['block'],
										$result[0]['title'],
										$result[0]['keywords'],
										$result[0]['sapo'],
										$result[0]['details'],
										$result[0]['date_created'],
										$result[0]['status'],
										$result[0]['position'],
										$result[0]['properties'],
										$result[0]['store_id'],
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
		$results = $this->select('*',"`store_id` = '".$this->store_id."' AND $condition", $sort, $start, $items_per_page);
		if($results) {
			$objects = array();
			foreach($results as $key => $result) {
				$objects[] = new StaticInfo (
												$result['slug'],
												$result['block'],
												$result['title'],
												$result['keywords'],
												$result['sapo'],
												$result['details'],
												$result['date_created'],
												$result['status'],
												$result['position'],
												$result['properties'],
												$result['store_id'],
												$result['id']
												);
			}
			return $objects;
		}
		return '';
	}
/*-----------------------------------------------------------------------*
* Function: getRecord
* Parameter: WHERE condition
* Return: 1 if id already exists, 0 if not exists
*-----------------------------------------------------------------------*/
	function getRecord($id) {
		$result = $this->select('id',"`store_id` = '".$this->store_id."' AND `id` = '$id'");
		if($result) return 1;
		return '';
	} 
/*-----------------------------------------------------------------------*
* Function: addData
* Parameter: Info object
* Return: 1 if key already exists, 0 if not exists
*-----------------------------------------------------------------------*/	
	function addData($object) {
		$result = $this->add($object,'id','NULL');
		if($result)
			return $result;
		return "";
	}
/*-----------------------------------------------------------------------*
* Function: updateData
* Parameter: Info object
* Return: 1 if key already exists, 0 if not exists
*-----------------------------------------------------------------------*/	
	function updateData($fields,$cId) {
		$result = $this->update($fields,"`store_id` = '".$this->store_id."' AND `id` =$cId ");
		if($result)
			return $result;
		return "";
	}
	
	function changePosition($oId = 0, $position = 0) {
		if(!$oId) return 0;
		if($this->update(array('position' => $position), "`store_id` = '".$this->store_id."' AND `id` = '$oId'")) return 1;
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
	#Return a Static Title from provided ID
	function getTitleFromId($id='') {
		if(!$id) return '';
		$result = $this->select('title',"`store_id` = '".$this->store_id."' AND id = '$id'");
		if($result) return $result[0]['title'];
		return '';
	}
/*-----------------------------------------------------------------------*
* Function: getObjects
* Parameter: WHERE condition
* Return: Array of Info objects
*-----------------------------------------------------------------------*/
	function getObjectFromSlug($slug) {
		if(!$slug) return "";
		$result = $this->select('*', "`store_id` = '".$this->store_id."' AND `slug`='$slug' AND `status` = 1");
		if($result) {
			$object = new StaticInfo (
										$result[0]['slug'],
										$result[0]['block'],
										$result[0]['title'],
										$result[0]['keywords'],
										$result[0]['sapo'],
										$result[0]['details'],
										$result[0]['date_created'],
										$result[0]['status'],
										$result[0]['position'],
										$result[0]['properties'],
										$result[0]['store_id'],
										$result[0]['id']
									);
			return $object;
		}
		return "";
	}
/*-----------------------------------------------------------------------*
* Function: CheckDuplicate
* Parameter: Info object
* Return: 1 if key already exists, 0 if not exists
*------------------------------------------------------------------------*/
	function checkDuplicate($value = '', $key = 'slug', $condition = '') {
		$result = $this->select("`$key`","`store_id` = '".$this->store_id."' AND `$key` = '$value'".($condition?" AND $condition":''));
		if($result) return 1;
		return 0;
	}	
}
?>
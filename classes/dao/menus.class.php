<?php
/*************************************************************************
Class Menus
----------------------------------------------------------------
Derasoft CMS Project
Company: Derasoft Co., Ltd                                  
Name: Nguyen Anh Ngoc                                    
Last updated: 07/10/2009
Checked by: Mai Minh (29/09/2011)
**************************************************************************/
include_once(ROOT_PATH.'classes/dao/model.class.php');
include_once(ROOT_PATH.'classes/dao/menuinfo.class.php');

class Menus extends Model {
	var $table;
	var $_db;
	var $store_id;
	function Menus($store_id = 0, $database = '') {
		if(!$database) {
			global $db;
			$this->_db = $db;
		} else $this->_db = $database;
		$this->table = DB_PREFIX."menus";
		$this->store_id = $store_id;	
	}
/*-----------------------------------------------------------------------*
* Function: getObject
* Parameter: key
* Return: Info object
*-----------------------------------------------------------------------*/
	function getObject($value = '0', $key = 'id', $condition = '1>0') {
		if(!$key || !$value) return '';
		$result = $this->select('*', "`store_id` = '".$this->store_id."' AND `$key` = '$value' AND ($condition)");
		if($result) {
			$object = new MenuInfo ($result[0]['name'],
									$result[0]['url'],
									$result[0]['status'],
									$result[0]['position'],
									$result[0]['properties'],
									$result[0]['mc_id'],
									$result[0]['store_id'],
									$result[0]['parent_id'],
									$result[0]['id']
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
	function getObjects($page = 1, $condition = '`pid` = 0', $sort = array(), $items_per_page = DEFAULT_ADMIN_ROWS_PER_PAGE) {
		if(!$page) $page = 1;
		$start = ($page -1) * $items_per_page;
		$results = $this->select('*', "`store_id` = '".$this->store_id."' AND $condition", $sort, $start, $items_per_page);
		if($results) {
			$objects = array();
			foreach($results as $key => $result) {
				$objects[] = new MenuInfo (	$result['name'],
											$result['url'],
											$result['status'],
											$result['position'],
											$result['properties'],
											$result['mc_id'],
											$result['store_id'],
											$result['parent_id'],
											$result['id']
											);
			}
			return $objects;
		}
		return '';
	}

/*-----------------------------------------------------------------------*
* Function: getMenuFromPid
* Parameter: WHERE condition
* Return: Array of Info objects
*-----------------------------------------------------------------------*/
	function getMenuFromPid($store_id,$mc_id=1,$parent_id=0) {
		$results = $this->select('*', "status =1 AND store_id=$store_id AND mc_id=$mc_id AND parent_id = '$parent_id'",array('position'=>'ASC'),'');
		if($results) {
			$menuInfos = array();
			foreach($results as $key => $result) {
				$menuInfos[] = new MenuInfo ($result['name'],
											$result['url'],
											$result['status'],
											$result['position'],
											$result['properties'],
											$result['mc_id'],
											$result['store_id'],
											$result['parent_id'],
											$result['id']
											);
			}
			return $menuInfos;
		}
		return '';
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
	# Change product category
	function changeCId($id = 0, $cId = 0) {
		if(!$id) return 0;
		if($this->update(array('mc_id' => $cId), "`store_id` = '".$this->store_id."' AND `id` = '$id'")) return 1;
		return 0;
	}
	# Change product position
	function changePosition($id = 0, $position = 0) {
		if(!$id) return 0;
		if($this->update(array('position' => $position), "`store_id` = '".$this->store_id."' AND `id` = '$id'")) return 1;
		return 0;
	}

	# Clean trash
	function cleanTrash() {	
		$result = $this->delete("`store_id` = '".$this->store_id."' AND `status` = ".S_DELETED);
		if($result) return 1;
		return 0;
	}	
		
	# Return a Product name from provided ID
	function getNameFromId($id='') {
		if(!$id) return '';
		$result = $this->select('name',"`store_id` = '".$this->store_id."' AND id = '$id'");
		if($result) return $result[0]['name'];
		return '';
	}
	# Return a Product name from provided ID
	function getParentIdFromId($id='') {
		if(!$id) return '';
		$result = $this->select('parent_id',"`store_id` = '".$this->store_id."' AND id = '$id'");
		if($result) return $result[0]['parent_id'];
		return '';
	}

	# Return a Product name from provided ID
	function getCIdFromId($id='') {
		if(!$id) return '';
		$result = $this->select('cat_id',"`store_id` = '".$this->store_id."' AND id = '$id'");
		if($result) return $result[0]['cat_id'];
		return '';
	}
	# Return a Product name from provided ID
	function getIdFromUrl($url='') {
		if(!$url) return '';
		$url2='/vn'.$url;
		$result = $this->select('id',"`store_id` = '".$this->store_id."' AND (`url` = '$url' OR `url`='$url2')");
		if($result) return $result[count($result)-1]['id'];
		return '';
	}
	function generateCombo($value='',$noroot = 0) {
		global $amessages;
		$combo = '';
		if(!$noroot) $combo = '<option value="0"'.($value=='0'?" selected":"").'>'.$amessages['root'].'</option>';
		$results = $this->select('id,name',"`store_id` = '".$this->store_id."' AND parent_id = '0'");
		if($results) {
			foreach($results as $key => $result) {
				$combo .= "<option value='".$result['id']."'".($value==$result['id']?" selected":"").">&nbsp;&nbsp;&nbsp;l--".$result['name']."</option>";	
				$s1results = $this->select('id,name',"`store_id` = '".$this->store_id."' AND parent_id = '".$result['id']."'");
				if($s1results) {
					foreach($s1results as $key1 => $result1) {
						$combo .= "<option value='".$result1['id']."'".($value==$result1['id']?" selected":"").">&nbsp;&nbsp;&nbsp;l&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;l--".$result1['name']."</option>";
					}
				}			
			}
		}
		return $combo;
	}
}
?>
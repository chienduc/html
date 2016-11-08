<?php
/*************************************************************************
Class Services
----------------------------------------------------------------
DeraCMS 3.0 Project
Company: Derasoft Co., Ltd
Last updated: 09/09/2011
Coder: Mai Minh
Checked by: Mai Minh (10/05/2012)
**************************************************************************/
include_once(ROOT_PATH."classes/dao/model.class.php");
include_once(ROOT_PATH."classes/dao/serviceinfo.class.php");

class Services extends Model {
	var $table;
	var $_db;
	var $store_id;
	
	function Services($store_id = 0, $database = '') {
		if(!$database) {
			global $db;
			$this->_db = $db;
		} else $this->_db = $database;
		$this->table = DB_PREFIX."services";
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
			$object = new ServiceInfo
						(	$result[0]['slug'],
							$result[0]['title'],
							$result[0]['keyword'],
							$result[0]['sapo'],
							$result[0]['detail'],
							$result[0]['template'],
							$result[0]['viewed'],
							$result[0]['date_created'],
							$result[0]['date_update'],
							$result[0]['position'],
							$result[0]['properties'],
							$result[0]['status'],
							$result[0]['home'],
							$result[0]['cat_id'],
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
				$objects[] = new ServiceInfo
								(	$result['slug'],
									$result['title'],
									$result['keyword'],
									$result['sapo'],
									$result['detail'],
									$result['template'],
									$result['viewed'],
									$result['date_created'],
									$result['date_update'],
									$result['position'],
									$result['properties'],
									$result['status'],
									$result['home'],
									$result['cat_id'],
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
	# Change home
	function changeHome($id = 0, $home = '') {
		if(!$id) return 0;
		if($this->update(array('home' => $home), "`store_id` = '".$this->store_id."' AND `id` = '$id'")) return 1;
		return 0;
	}
	# Change Service category
	function changeCatId($id = 0, $catId = 0) {
		if(!$id) return 0;
		if($this->update(array('cat_id' => $catId), "`store_id` = '".$this->store_id."' AND `id` = '$id'")) return 1;
		return 0;
	}
	# Change Service position
	function changePosition($id = 0, $position = 0) {
		if(!$id) return 0;
		if($this->update(array('position' => $position), "`store_id` = '".$this->store_id."' AND `id` = '$id'")) return 1;
		return 0;
	}

	# Clean trash
	function cleanTrash() {
		$results = $this->select('*', "`store_id` = '".$this->store_id."' AND `status` = ".S_DELETED);
		if($results) {
			$objects = array();
			foreach($results as $key => $result) {
				$properties = unserialize($result['properties']);
				$avalue = $properties['avatar'];
				if($properties['avatar']) unlink(ROOT_PATH."upload/".$this->store_id."/Services/a_".$properties['avatar']);
				foreach($properties['photos'] as $pkey => $pvalue) {
					unlink(ROOT_PATH."upload/".$this->store_id."/Services/l_".$pvalue);
					unlink(ROOT_PATH."upload/".$this->store_id."/Services/a_".preg_replace("/(png$|bmp$|gif$)/","jpg",$pvalue));					
				}
				foreach($properties['videos'] as $pkey => $pvalue) {
					unlink(ROOT_PATH."upload/".$this->store_id."/Services/".$pvalue);					
				}
				foreach($properties['files'] as $pkey => $pvalue) {
					unlink(ROOT_PATH."upload/".$this->store_id."/Services/".$pvalue);					
				}
			}
		}	
		$result = $this->delete("`store_id` = '".$this->store_id."' AND `status` = ".S_DELETED);
		if($result) return 1;
		return 0;
	}	
		
	# Return a Service Id from provided ID
	function getIdFromSlug($slug='') {
		if(!$slug) return 0;
		$result = $this->select('id',"`store_id` = '".$this->store_id."' AND slug = '$slug'");
		if($result) return $result[0]['id'];
		return 0;
	}

	# Return a Service Name from provided slug
	function getNameFromSlug($slug='') {
		if(!$slug) return '';
		$result = $this->select('title',"`store_id` = '".$this->store_id."' AND slug = '$slug'");
		if($result) return $result[0]['name'];
		return '';
	}
	
	
	# Return a Service Name from provided slug
	function getAllId($cat_id='') {
		if(!$cat_id) return '';
		$result = $this->select('id',"`store_id` = '".$this->store_id."' AND cat_id = '$cat_id'");
		if($result) return $result;
		return '';
	}

	# Return a Service slug from provided ID
	function getSlugFromId($id='') {
		if(!$id) return '';
		$result = $this->select('slug',"`store_id` = '".$this->store_id."' AND id = '$id'"); 
		if($result) return $result[0]['slug'];
		return '';
	}

	# Return a Service name from provided ID
	function getNameFromId($id='') {
		if(!$id) return '';
		$result = $this->select('title',"id = '$id'");
		if($result) return $result[0]['title'];
		return '';
	}/*-----------------------------------------------------------------------*
* Function: updateData
* Parameter: Info object
* Return: 1 if success, 0 if fail
*-----------------------------------------------------------------------*/	

/*-----------------------------------------------------------------------*
* Function: CheckDuplicate
* Parameter: Info object
* Return: 1 if key already exists, 0 if not exists
*------------------------------------------------------------------------*/
	function checkDuplicate($value = '', $key = 'title', $condition = '') {
		$result = $this->select("`$key`","`store_id` = '".$this->store_id."' AND `$key` = '$value'".($condition?" AND $condition":''));
		if($result) return 1;
		return 0;
	}

	# Return a Service name from provided ID
	function getCatIdFromId($id='') {
		if(!$id) return '';
		$result = $this->select('cat_id',"`store_id` = '".$this->store_id."' AND id = '$id'");
		if($result) return $result[0]['cat_id'];
		return '';
	}
	
	function generateCombo($value='', $noroot = 0) { 
		global $amessages;
		$combo = '';
		if(!$noroot) $combo = '<option value="0"'.($value=='0'?" selected":"").'>'.$amessages['service_other'].'</option>';
		$results = $this->select('id,title',"`store_id` = '".$this->store_id."'");
		if($results) {
			foreach($results as $key => $result) {
				$combo .= "<option value='".$result['id']."'".($value==$result['id']?" selected":"").">".$result['title']."</option>";	
			}
		}
		return $combo;
	}
	
	function getAllSubCate($pId) {
		$results = $this->select("id", "status =1 AND `cat_id` in ('$pId')",array('position'=>'ASC'));
		if($results) {
			$categoryInfos = array();
			foreach($results as $key => $result) {
				$a= $result['id'];
				$categoryInfos[]=$result['id'];
			
			}
			return implode(",",$categoryInfos).",$pId";
			
			
		}
		return 0;
	}

	// function root parent id Servicecategory
	function getParentCategory($id){
		$result = 0;
		if($id){
			include_once(ROOT_PATH."classes/dao/servicecategories.class.php");
			$ServiceCates = new ServiceCategories($this->store_id);
			$ServiceCateItem = $ServiceCates->getObject($id);
			if($ServiceCateItem){
				if($ServiceCateItem->getParentId() == 0)	$result = $id;
				else{
						$ServiceCateItem2 = $ServiceCates->getObject($ServiceCateItem->getParentId());
						if($ServiceCateItem2){
							if($ServiceCateItem2->getParentId() == 0)	$result = $ServiceCateItem2->getId();
							else{
									$ServiceCateItem3 = $ServiceCates->getObject($ServiceCateItem2->getParentId());
									if($ServiceCateItem3){
										if($ServiceCateItem3->getParentId() == 0)	$result = $ServiceCateItem3->getId();
									}
							}
						}
				}
			}
		}
		return $result;
	}
}
?>
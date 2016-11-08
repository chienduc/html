<?php
/*************************************************************************
Class Songs
----------------------------------------------------------------
Music Project
Last updated: 08/17/2013
Author: Cong Hoan (hoan052@gmail.com)
**************************************************************************/
include_once(ROOT_PATH."classes/dao/model.class.php");
include_once(ROOT_PATH."classes/dao/songinfo.class.php");

class Songs extends Model {
	var $table;
	var $_db;
	var $store_id;
	
	function Songs($store_id = 0, $database = '') {
		if(!$database) {
			global $db;
			$this->_db = $db;
		} else $this->_db = $database;
		$this->table = DB_PREFIX."songs";
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
			$object = new SongInfo
						(	$result[0]['id'],
							$result[0]['store_id'],
							$result[0]['cat_id'],
							$result[0]['slug'],
							$result[0]['name'],
							$result[0]['composer'],
							$result[0]['date_created'],
							$result[0]['update'],
							$result[0]['position'],
							$result[0]['properties'],
							$result[0]['status']						
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
				$objects[] = new SongInfo
								(	$result['id'],
									$result['store_id'],
									$result['cat_id'],
									$result['slug'],
									$result['name'],
									$result['composer'],
									$result['date_created'],
									$result['update'],
									$result['position'],
									$result['properties'],
									$result['status']			
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
	
	# Change song category
	function changeSongId($id = 0, $catId = 0) {
		if(!$id) return 0;
		if($this->update(array('cat_id' => $catId), "`store_id` = '".$this->store_id."' AND `id` = '$id'")) return 1;
		return 0;
	}
	
	# Change song position
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
				if($properties['avatar']){
					unlink(ROOT_PATH."upload/".$this->store_id."/products/l_".$properties['avatar']);
					unlink(ROOT_PATH."upload/".$this->store_id."/products/m_".$properties['avatar']);
					unlink(ROOT_PATH."upload/".$this->store_id."/products/t_".$properties['avatar']);
					unlink(ROOT_PATH."upload/".$this->store_id."/products/a_".$properties['avatar']);
				}
				foreach($properties['photos'] as $pkey => $pvalue) {
					unlink(ROOT_PATH."upload/".$this->store_id."/products/l_".$pvalue);
					unlink(ROOT_PATH."upload/".$this->store_id."/products/m_".$pvalue);
					unlink(ROOT_PATH."upload/".$this->store_id."/products/t_".$pvalue);
					unlink(ROOT_PATH."upload/".$this->store_id."/products/a_".$pvalue);					
				}
				foreach($properties['videos'] as $pkey => $pvalue) {
					unlink(ROOT_PATH."upload/".$this->store_id."/products/".$pvalue);					
				}
				foreach($properties['files'] as $pkey => $pvalue) {
					unlink(ROOT_PATH."upload/".$this->store_id."/products/".$pvalue);					
				}
			}
		}
		$result = $this->delete("`store_id` = '".$this->store_id."' AND `status` = ".S_DELETED);
		if($result) return 1;
		return 0;
	}
	
		
	# Return a slug from provided ID
	function getIdFromSlug($slug='') {
		if(!$slug) return 0;
		$result = $this->select('id',"`store_id` = '".$this->store_id."' AND slug = '$slug'");
		if($result) return $result[0]['id'];
		return 0;
	}

	# Return a song name from provided slug
	function getNameFromSlug($slug='') {
		if(!$slug) return '';
		$result = $this->select('name',"`store_id` = '".$this->store_id."' AND slug = '$slug'");
		if($result) return $result[0]['name'];
		return '';
	}

	# Return a song slug from provided ID
	function getSlugFromId($id='') {
		if(!$id) return '';
		$result = $this->select('slug',"`store_id` = '".$this->store_id."' AND id = '$id'");
		if($result) return $result[0]['slug'];
		return '';
	}

	# Return a song name from provided ID
	function getNameFromId($id='') {
		if(!$id) return '';
		$result = $this->select('name',"`store_id` = '".$this->store_id."' AND id = '$id'");
		if($result) return $result[0]['name'];
		return '';
	}

	

/*-----------------------------------------------------------------------*
* Function: CheckDuplicate
* Parameter: Info object
* Return: 1 if key already exists, 0 if not exists
*------------------------------------------------------------------------*/
	function checkDuplicate($value = '', $key = 'name', $condition = '') {
		$result = $this->select("`$key`","`store_id` = '".$this->store_id."' AND `$key` = '$value'".($condition?" AND $condition":''));
		if($result) return 1;
		return 0;
	}

	# Return a category name from provided ID
	function getCatIdFromId($id='') {
		if(!$id) return '';
		$result = $this->select('cat_id',"`store_id` = '".$this->store_id."' AND id = '$id'");
		if($result) return $result[0]['cat_id'];
		return '';
	}
	function getSongFromPid($pId) {
		$results = $this->select('*', "`store_id` = '".$this->store_id."' AND status =1 AND `cat_id`=$pId", array('created' => 'DESC'),  $start, '');
		if($results) {
			$songInfos = array();
			foreach($results as $key => $result) {
				$songInfos[] = new SongInfo ($result['id'],
									$result['store_id'],
									$result['cat_id'],
									$result['slug'],
									$result['name'],
									$result['composer'],
									$result['date_created'],
									$result['update'],
									$result['position'],
									$result['properties'],
									$result['status']);
			}
			return $songInfos;
		}
		return '';
	}
	
}
?>
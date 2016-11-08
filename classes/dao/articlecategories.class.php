<?php
/*************************************************************************
Class articleCategories
----------------------------------------------------------------
BiDo.vn Project
Company: Derasoft Co., Ltd
Last updated: 09/09/2011
Coder: Tran Thi My Xuyen
Checked by: Mai Minh (22/09/2011)
**************************************************************************/
include_once(ROOT_PATH."classes/dao/model.class.php");
include_once(ROOT_PATH."classes/dao/articlecategoryinfo.class.php");

class ArticleCategories extends Model {
	var $table;
	var $_db;
	var $store_id;
	
	function ArticleCategories($store_id = 0, $database = '') {
		if(!$database) {
			global $db;
			$this->_db = $db;
		} else $this->_db = $database;
		$this->table = DB_PREFIX."article_categories";
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
			$object = new ArticleCategoryInfo
						(	$result[0]['slug'],
							$result[0]['name'],
							$result[0]['keyword'],
							$result[0]['sapo'],
							$result[0]['position'],
							$result[0]['viewed'],
							$result[0]['properties'],
							$result[0]['status'],
							$result[0]['store_id'],
							$result[0]['parent_id'],
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
				$objects[] = new ArticleCategoryInfo
								(	$result['slug'],
									$result['name'],
									$result['keyword'],
									$result['sapo'],
									$result['position'],
									$result['viewed'],
									$result['properties'],
									$result['status'],
									$result['store_id'],
									$result['parent_id'],
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

	# Change parent category
	function changePId($id = 0, $pId = 0) {
		if(!$id) return 0;
		if($this->update(array('parent_id' => $pId), "`store_id` = '".$this->store_id."' AND `id` = '$id'")) return 1;
		return 0;
	}
	
	# Change position category
	function changePosition($id = 0, $position = 0) {
		if(!$id) return 0;
		if($this->update(array('position' => $position), "`store_id` = '".$this->store_id."' AND `id` = '$id'")) return 1;
		return 0;
	}

	# Clean trash
	function cleanTrash() {
		$results = $this->select('id',"`store_id` = '".$this->store_id."' AND `status` = ".S_DELETED);
		if($results) {
			include_once(ROOT_PATH."classes/dao/articles.class.php");
			$articles = new Articles($this->store_id);
			# Loop all DELETED categories
			foreach($results as $key => $result) {
				# Change status of all articles in each category to DELETED too
				$articles->update(array('status' => S_DELETED),"`store_id` = '".$this->store_id."' AND `cat_id` = '".$result['id']."'");
			}	
		}
		$result = $this->delete("`store_id` = '".$this->store_id."' AND `status` = ".S_DELETED);
		if($result) return 1;
		return 0;
	}	
		
	function getParentObject($parent_id) {
		return $this->getObject($parent_id,'parent_id');
	}

	# Return a ArticleCategory Id from provided ID
	function getIdFromSlug($slug='') {
		if(!$slug) return 0;
		$result = $this->select('id',"`store_id` = '".$this->store_id."' AND slug = '$slug'");
		if($result) return $result[0]['id'];
		return 0;
	}

	# Return a ArticleCategory Name from provided slug
	function getNameFromSlug($slug='') {
		if(!$slug) return '';
		$result = $this->select('name',"`store_id` = '".$this->store_id."' AND slug = '$slug'");
		if($result) return $result[0]['name'];
		return '';
	}

	# Return a ArticleCategory slug from provided ID
	function getSlugFromId($id='') {
		if(!$id) return '';
		$result = $this->select('slug',"`store_id` = '".$this->store_id."' AND id = '$id'");
		if($result) return $result[0]['slug'];
		return '';
	}
	function getParentIdFromId($id='') {
		if(!$id) return '';
		$result = $this->select('parent_id',"`store_id` = '".$this->store_id."' AND id = '$id'");
		if($result) return $result[0]['parent_id'];
		return '';
	}

	# Return a ArticleCategory name from provided ID
	function getNameFromId($id='0') {
		global $amessages;
		if(!$id) return $amessages['root'];
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
	
	function generateCombo($value='', $noroot = 0) {
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
						$combo .= "<option value='".$result1['id']."'".($value==$result1['id']?" selected":"").">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;l--".$result1['name']."</option>";
					$s2results = $this->select('id,name',"`store_id` = '".$this->store_id."' AND parent_id = '".$result1['id']."'");
					if($s2results){
						foreach($s2results as $key2 => $result2) {
						$combo .= "<option value='".$result2['id']."'".($value==$result2['id']?" selected":"").">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;l--".$result2['name']."</option>";
							}
						
						}
					}
				}			
			}
		}
		return $combo;
	}
	
	// function root parent id articlecategory
	function getParentCategory($id){
		$result = 0;
		if($id){
			include_once(ROOT_PATH."classes/dao/articlecategories.class.php");
			$articleCates = new ArticleCategories($this->store_id);
			$articleCateItem = $articleCates->getObject($id);
			if($articleCateItem){
				if($articleCateItem->getParentId() == 0)	$result = $id;
				else{
						$articleCateItem2 = $articleCates->getObject($articleCateItem->getParentId());
						if($articleCateItem2){
							if($articleCateItem2->getParentId() == 0)	$result = $articleCateItem2->getId();
							else{
									$articleCateItem3 = $articleCates->getObject($articleCateItem2->getParentId());
									if($articleCateItem3){
										if($articleCateItem3->getParentId() == 0)	$result = $articleCateItem3->getId();
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
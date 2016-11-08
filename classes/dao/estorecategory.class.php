<?php
/*************************************************************************
Class OrderItems
----------------------------------------------------------------
BiDo.vn Project
Name: Tran Thi My Xuyen
Last Update: 19/04/2012
**************************************************************************/
include_once(ROOT_PATH."classes/database/model.class.php");
include_once(ROOT_PATH."classes/dao/estorecateinfo.class.php");

class EstoreCategory extends Model {
	var $table;
	var $_db;
	
	function EstoreCategory($database = '') {
		if(!$database) {
			global $db;
			$this->_db = $db;
		} else $this->_db = $database;
		$this->table = DB_PREFIX."estore_category";
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
			$object = new EstoreCateInfo
						(	$result[0]['sid'],
							$result[0]['cat_id'],
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
				$objects[] = new EstoreCateInfo
						(	$result['sid'],
							$result['cat_id'],
							$result['id']
						);
			}
			return $objects;
		}
		return 0;
	}
	function getCatId($page = 1, $condition = '1>0', $sort = array(), $items_per_page = DEFAULT_ADMIN_ROWS_PER_PAGE) {
		if(!$page) $page = 1;
		$start = ($page -1) * $items_per_page;
		$results = $this->select('cat_id', "$condition", $sort, $start, $items_per_page);
		if($results) { 
			$objects = array();
			foreach($results as $key => $result) {
				$objects[] = new EstoreCateInfo
						(	
							$result['cat_id']
						);
			}
			return $objects;
		}
		return 0;
	}
	#get all cat_id from sid
	function getCatIdFromSid($sId) {
		$results = $this->select('*', "`sid`=$sId ",array('cat_id'=>'asc') ,'');
		if($results) { 
			$objects = array();
			foreach($results as $key => $result) {
				$objects[] = new EstoreCateInfo
						(	$result['sid'],
							$result['cat_id'],
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
		
	function getAllSubCategory($pId = 0,$aid=0,$new='') {
	   if($aid==0){
	       $query = "SELECT DISTINCT(sid) FROM ".DB_PREFIX."estore_category WHERE cat_id='$pId'";
           if($new=='cac-nha-hang-moi-tham-gia-hotmeal'||$new=='newly-resgitered-restaurant-at-hotmeal')
           {
                $one_month = date("Y-m-d H:i:s",time()-86400*30);
                $query="SELECT DISTINCT(sid) FROM ".DB_PREFIX."estore_category ec,".DB_PREFIX."estores e WHERE ec.sid=e.id AND ec.cat_id='$pId' AND `created` >= '$one_month'";
           }
           else if($new=='danh-sach-nha-hang-dat-nhieu-nhat'||$new=='top-online-ordered-restaurants'){
                $query="SELECT DISTINCT(ec.sid),e.top,e.top_position,COUNT( o.id ) AS so FROM ".DB_PREFIX."estores e,".DB_PREFIX."orders o,".DB_PREFIX."estore_category ec WHERE e.id=o.store_id AND ec.sid=e.id AND ec.cat_id='$pId'  GROUP BY e.id";
           }
	   }
       else{
	       $query = "SELECT DISTINCT(e.sid) FROM ".DB_PREFIX."order_address o,".DB_PREFIX."estore_category e WHERE e.sid=o.sid AND cat_id='$pId' AND address='$aid'";
            if($new=='cac-nha-hang-moi-tham-gia-hotmeal'||$new=='newly-resgitered-restaurant-at-hotmeal'){
                $one_month = date("Y-m-d H:i:s",time()-86400*30);
                $query="SELECT DISTINCT(ec.sid) FROM ".DB_PREFIX."estore_category ec,".DB_PREFIX."estores e,".DB_PREFIX."order_address o WHERE ec.sid=e.id AND e.id=o.sid AND ec.cat_id='$pId' AND o.address='$aid' AND `created` >= '$one_month'";
            }
            else if($new=='danh-sach-nha-hang-dat-nhieu-nhat'||$new=='top-online-ordered-restaurants'){
               $query="SELECT DISTINCT(ec.sid),e.top,e.top_position,COUNT( o.id ) AS so FROM ".DB_PREFIX."estores e,".DB_PREFIX."orders o,".DB_PREFIX."estore_category ec,".DB_PREFIX."order_address oa WHERE e.id=o.store_id AND oa.sid=e.id AND oa.address='$aid'  AND ec.sid=e.id AND ec.cat_id='$pId'  GROUP BY e.id"; 
            }
            
            
       }
        $results=$this->_db->query($query);
        if($results){
            $data = array();
			while($row = $this->_db->fetchArray($results)) {
				$data[] = $row;
			}	
            	
			$categoryInfos = array();
			foreach($data as $key => $result) {
				$categoryInfos[]=$result['sid'];
			}
			return implode(",",$categoryInfos);
            
        }
        # End Change getAllSubCategory By Quoc Minh
	}
}
?>
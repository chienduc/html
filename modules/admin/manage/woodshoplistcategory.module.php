<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$templateFile = 'managewoodshop.tpl.html';
include_once(ROOT_PATH.'classes/dao/woodshopcategories.class.php');
$woodshopCategories = new WoodshopCategories($storeId);

$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['list_woodshop_category'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=woodshop';
$listTabs = array(
				$amessages['list_woodshop_category'] => $tabLink.'&mod=listcategory',
				$amessages['add_woodshop_category'] => $tabLink.'&mod=addcategory',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',1);

# Get parameters
$items_per_page = $request->element('ipp')?$request->element('ipp'):DEFAULT_ADMIN_ROWS_PER_PAGE;
if($items_per_page) $template->assign('ipp',$items_per_page);
$page = $request->element('pg')?$request->element('pg'):1;
if($page) $template->assign('pg',$page);
$sort_key = "store_id";
if($sort_key) $template->assign('sk',$sort_key);
$sort_direction ='ASC';
if($sort_direction) $template->assign('sd',$sort_direction);
$do = $request->element('doo')?$request->element('doo'):'';
if($do) $template->assign('do',$do);
$kw = $request->element('kw')?$request->element('kw'):'';
if($kw) $template->assign('kw',$kw);
$pId = $request->element('pId')?$request->element('pId'):0;

# Build WHERE condition
$condition = "1>0 ";
if($kw) $condition = "(`id`='$kw' OR `name` LIKE '%$kw%')";
$pages_condition = "(`store_id` = '$storeId' or `store_id`=0)  AND ($condition)";
$sort = array($sort_key => $sort_direction);

# Page navigation
$rowsPages = $woodshopCategories->getNumItems('id', $pages_condition,$items_per_page);
$template->assign('rowsPages',$rowsPages);
if($page < 1) $page = 1;
if($page > $rowsPages['pages']) $page = $rowsPages['pages'];
$start_num = ($page-1)*$items_per_page+1;
$template->assign('startNum',$start_num);
$url = '/'.ADMIN_SCRIPT."?op=manage&act=woodshop&mod=listcategory&doo=$do&kw=$kw&lang=$lang&ipp=$items_per_page&sk=$sort_key&sd=$sort_direction&pId=$pId&pg=%d";
$pager = Url::genPager($url,$rowsPages['pages'],$page);
$template->assign('pager',$pager);

# Get objects
$listItems = $woodshopCategories->getObjects($page,$condition,$sort,$items_per_page);
if($listItems) $template->assign('listItems',$listItems);
# Result code
$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);
$error_code = $request->element('ecode');
if($error_code) $template->assign('error_code',$error_code);

# Link
$link = '/'.ADMIN_SCRIPT."?op=manage&act=woodshop&mod=listcategory&kw=$kw&lang=$lang&ipp=$items_per_page&sk=$sort_key&sd=$sort_direction&pId=$pId&pg=$page";
$template->assign('link',$link);

if($_POST) {
	switch($do) {
		case 'enable':
			$id = $request->element('id');
			if($id) {
				$woodshopCategories->changeStatus($id,S_ENABLED);
				$result_code = 1;
				# Operation tracking
				$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['enable_product_category'],$woodshopCategories->getNameFromId($id)),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			} else {		
				$ids = $request->element('ids');
				if($ids) {
					$listCategory = '';
					foreach ($ids as $id) {
						$woodshopCategories->changeStatus($id,S_ENABLED);
						$listCategory .= ($listCategory?',&nbsp;':'').$woodshopCategories->getNameFromId($id);
					}
					$result_code = 1;
					# Operation tracking
					$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['enable_product_category'],$listCategory),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
				} else $error_code = 5;
			}
			break;
		case 'updateIpp':
				$ids = $request->element('ids');
				$ipps = $request->element('ipps');
				if($ids) {
					$listCategory = '';
					foreach ($ids as $id) {
						$winfo=$woodshopCategories->getObject($id);
						if($winfo){
							$properties=$winfo->getProperties();
							$properties['ipp']=$ipps[$id];
							$woodshopCategories->updateData(array('properties' => serialize($properties)),$id);
							
						}
						
					}
					$result_code = 2;
				} else $error_code = 5;
			
			break;
		case 'disable':
			$userInfo->checkPermission('pro_cat','edit');
			$id = $request->element('id');
			if($id) {
				$woodshopCategories->changeStatus($id,S_DISABLED);
				$result_code = 2;
				# Operation tracking
				$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['disable_product_category'],$woodshopCategories->getNameFromId($id)),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			} else {
				$ids = $request->element('ids');
				if($ids) {
					$listCategory = '';
					foreach ($ids as $id) {
						$woodshopCategories->changeStatus($id,S_DISABLED);
						$listCategory .= ($listCategory?',&nbsp;':'').$woodshopCategories->getNameFromId($id);
					}
					$result_code = 2;
					# Operation tracking
					$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['disable_product_category'],$listCategory),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
				} else $error_code = 5;
			}
			break;
		case 'delete':
			$userInfo->checkPermission('pro_cat','delete');
			$id = $request->element('id');
			if($id) {
				$rowsPages = $woodshopCategories->getNumItems('id', "`parent_id`='$id' AND `status` <> '".S_DELETED."'",1);
				if($rowsPages['rows']) {
					$error_code = 10;
				} else {
					$woodshopCategories->changeStatus($id,S_DELETED);
					$result_code = 3;
					# Operation tracking
					$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['delete_product_category'],$woodshopCategories->getNameFromId($id)),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
				}
			} else {
				$ids = $request->element('ids');
				if($ids) {
					$listCategory = '';
					$warning = 0;
					foreach ($ids as $id) {
						$rowsPages = $woodshopCategories->getNumItems('id', "`parent_id`='$id' AND `status` <> '".S_DELETED."'",1);
						if(!$rowsPages['rows']) {
							$woodshopCategories->changeStatus($id,S_DELETED);
							$listCategory .= ($listCategory?',&nbsp;':'').$woodshopCategories->getNameFromId($id);
						} else $warning = 1;
					}
					if($warning) $error_code = 10;
					else $result_code = 3;
					# Operation tracking
					$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['delete_product_category'],$listCategory),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
				} else $error_code = 5;
			}
			break;
		case 'changegroup':
			$userInfo->checkPermission('pro_cat','edit');
			$ids = $request->element('ids');
			$parent_id = $request->element('parent_id');
			if($ids) {
				$listCategory = '';
				foreach ($ids as $id) {
					$woodshopCategories->changePId($id,$parent_id);
					$listCategory .= ($listCategory?',&nbsp;':'').$woodshopCategories->getNameFromId($id);
				}
				$result_code = 4;
				$pId = $parent_id;
				# Operation tracking
				$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['change_parent_product_category'],$listCategory,$woodshopCategories->getNameFromId($parent_id)),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			} else $error_code = 5;
			break;
		case 'changeposition':
			$userInfo->checkPermission('pro_cat','edit');
			$positions = $request->element('positions');
			if($positions) {
				foreach ($positions as $key=>$value) {
					$woodshopCategories->changePosition($key,$value);
				}
				$result_code = 4;
				# Operation tracking
				$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>$amessages['tracking']['change_position_product_category'],'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			} else $error_code = 5;
			break;
		case 'cancel':		
			header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=product&mod=listcategory&lang=$lang&ecode=7&pId=$pId");
			exit;
			break;
	}
	header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=woodshop&mod=listcategory&doo=$do&kw=$kw&lang=$lang&ipp=$items_per_page&sk=$sort_key&sd=$sort_direction&pg=$page&ecode=$error_code&rcode=$result_code&pId=$pId");
} else {

}
?>
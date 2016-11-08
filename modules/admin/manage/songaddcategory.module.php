<?php
/*************************************************************************
Adding song category module
----------------------------------------------------------------
DeraCMS 3.0 Project
Company: Derasoft Co., Ltd
Coder: Cong Hoan
Email: hoan052@gmail.com
Last updated: 08/17/2013
**************************************************************************/

$userInfo->checkPermission('pro_cat','add');
$templateFile = 'managesong.tpl.html';
include_once(ROOT_PATH.'classes/dao/songcategories.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$songCategories = new SongCategories($storeId);
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_product'] => '/'.ADMIN_SCRIPT.'?op=manage&act=song',
				$amessages['add_product_category'] => '');

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=song';
$listTabs = array($amessages['list_song_item'] => $tabLink.'&mod=list',
				$amessages['add_new'] => $tabLink.'&mod=add',
				$amessages['list_song_category'] => $tabLink.'&mod=listcategory',
				$amessages['add_song_category'] => $tabLink.'&mod=addcategory',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');
							
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',4);

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);

# Category combo box
$songCombo = $songCategories->generateCombo($request->element('pId'));
if($songCombo) $template->assign('songCombo',$songCombo);

# Allow some javascript
$template->assign('ckEditor',1);
	
if($_POST && $request->element('doo') == 'submit') { # if form is submitted
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);
	} else { # Valid data input
		# check duplicate category name
		if($productCategories->checkDuplicate($request->element('name'))) {
			$validate['INPUT']['name']['message'] = $amessages['name_duplicated'];
			$validate['INPUT']['name']['error'] = 1;
			$validate['invalid'] = 1;
			$template->assign('error',$validate);
		}
		
		# Check if duplicate slug
		$slug = TextFilter::urlize($request->element('name'),false,'-');
		$i = 0;
		$dup = 1;
		while($dup) {
			$dup = $productCategories->checkDuplicate($slug.($i?'-'.$i:''),'slug');
			if($dup) $i++;
		}
		$slug .= $i?'-'.$i:'';
		
		# Everything is ok. Add data to DB
		if(!$validate['invalid']) {		
			$properties = array('');
			$properties['sort_type'] = $request->element('sort_type');
			$properties['sort_dir'] = $request->element('sort_dir');
			$properties['display'] = $request->element('display');
			$properties['ipp'] = $request->element('ipp');
			$properties['content'] = Filter($request->element('content'));
			$properties['keyword'] =  Filter($request->element('keyword'));
		    $properties['sumary'] = Filter($request->element('sumary'));			
			$data = array('store_id' => $storeId,
						  'parent_id' => $request->element('parent_id'),
						  'slug' => $request->element('slug'),						  
						  'name' => Filter($request->element('name')),					 					  
						  'position' => $request->element('position'),
						  'status' => $request->element('status'),
						  'properties' => serialize($properties));
			$songCategories->addData($data);
			
			
			# Operation tracking
			$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['add_product_category'],$request->element('name')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=song&mod=listcategory&pId=".$request->element('parent_id')."&rcode=6");
		}
	}
}

?>
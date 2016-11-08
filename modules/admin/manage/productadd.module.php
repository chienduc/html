<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$userInfo->checkPermission('product','add');
$templateFile = 'managesong.tpl.html';
include_once(ROOT_PATH.'classes/dao/productcategories.class.php');

include_once(ROOT_PATH.'classes/dao/services.class.php');
include_once(ROOT_PATH.'classes/dao/servicecategories.class.php');
include_once(ROOT_PATH.'classes/dao/products.class.php');
include_once(ROOT_PATH.'classes/dao/areas.class.php');
include_once(ROOT_PATH.'classes/dao/fields.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
include_once(ROOT_PATH.'classes/dao/productcate.class.php');
include_once(ROOT_PATH.'classes/dao/typetours.class.php');
include_once(ROOT_PATH.'classes/dao/typedates.class.php');
$typedates = new Typedates();
$typetours = new Typetours();
$areas = new Areas($storeId);
$serviceCategories = new ServiceCategories($storeId);

$productCategories = new ProductCategories($storeId);
$services = new Services($storeId);
$products = new Products($storeId);
$fields = new Fields($storeId);
$productCate = new ProductCate();
$gallery_root = ROOT_PATH."upload/$storeId/";
$gallery_path = $gallery_root."products/";

$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_product'] => '/'.ADMIN_SCRIPT.'?op=manage&act=product',
				$amessages['add_new_product'] => '');
$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=product';

$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['add_new'] => '#',
				$amessages['list_product_category'] => $tabLink.'&mod=listcategory',
				$amessages['add_product_category'] => $tabLink.'&mod=addcategory',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);
$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);


# Category combo box
$categoryBrand = $productCategories->generateCombo($request->element('catid'),1);
if($categoryBrand) $template->assign('categoryBrand',$categoryBrand);


$categoryCombo = $serviceCategories->generateListbox($request->element('cat_id'),1);
if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);

# Category combo box

$categoryCombos = $services->generateCombo($request->element('cat_ids'),1);
if($categoryCombos) $template->assign('categoryCombos',$categoryCombos);


$dateCombos = $typedates->generateCombo($request->element('typedate'));
if($dateCombos) $template->assign('dateCombos',$dateCombos);

#
$tourCombos = $typetours->generateCombo($request->element('typetour'));
if($tourCombos) $template->assign('tourCombos',$tourCombos);

# Get list of custom fields
$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='product'",array('position' => 'ASC'));
if($fieldList) $template->assign('fieldList',$fieldList);

# Allow some javascript
$template->assign('ckEditor',1);

if($_POST && $request->element('doo') == 'submit') { # if form is submitted
	# Validate the data input
	$validate = validateData($request);
	if($validate['invalid']) {	# data input is not in valid form
		$template->assign('error',$validate);	
		# Category combo box


$categoryCombo = $productCategories->generateListbox($request->element('cat_id'));
if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
	} else { # Valid data input
		# check duplicate product name
		if($estore->getProperty('check_duplicate_product_name')) {
			if($products->checkDuplicate($request->element('name'),'name',"cat_id = '".$request->element('cat_id')."'")) {
				$validate['INPUT']['name']['message'] = $amessages['name_duplicated'];
				$validate['INPUT']['name']['error'] = 1;
				$validate['invalid'] = 1;
				$template->assign('error',$validate);
			}
		}
			
		# Check if duplicate slug
		$slug = TextFilter::urlize($request->element('name'),false,'-');
		$i = 0;
		$dup = 1;
		while($dup) {
			$dup = $products->checkDuplicate($slug.($i?'-'.$i:''),'slug',"cat_id = '".$request->element('cat_id')."'");
			if($dup) $i++;
		}
		$slug .= $i?'-'.$i:'';
		
		# Everything is ok. Add data to DB
		if(!$validate['invalid']) {
			$properties = array('');

			# Check if gallery folder is exists
			if(!file_exists($gallery_root)) mkdir("$gallery_root");
			if(!file_exists($gallery_path)) mkdir("$gallery_path");
			# User upload
			 $userUpload = $userInfo->getUsername();
		
			#File Avatar
			$fileAvatr = isset($_FILES['avatar'])?$_FILES['avatar']:'';
			if($fileAvatr) {
				$img = addslashes(Filter(rand()."_".$fileAvatr['name']));
				$tmp_img = $fileAvatr['tmp_name'];
				$size = $fileAvatr['size'];
				$type=strtolower(substr($img,-3));
				if(preg_match("/".ALLOW_FILE_TYPES."/",strtolower($img))) {
					# Upload
					if(isImage($img)) {
						$new_img = $img;
						move_uploaded_file($tmp_img,$gallery_path.'l_'.$img);
						if(!isJpeg($img)) $new_img = preg_replace("/(png$|bmp$|gif$)/","jpg",$img);
						resize($gallery_path,$gallery_path,'l_'.$img,'l_'.$new_img,DEFAULT_LARGE_SIZE,DEFAULT_LARGE_SQUARE,DEFAULT_PHOTO_QUALITY);
						resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$new_img,DEFAULT_AVATAR_SIZE,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);
						resizes($gallery_path,$gallery_path,'l_'.$img,'m_'.$new_img,DEFAULT_WIDTH_PROJECT,DEFAULT_HIGHT_PROJECT,DEFAULT_PHOTO_QUALITY);
						
						if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
						$avatar = $new_img;
					} 
				} #/if (preg_match
			}
			
			
			#File Avatar
			$fileColor = isset($_FILES['color_board'])?$_FILES['color_board']:'';
			if($fileColor) {
				$img = addslashes(Filter(rand()."_".$fileColor['name']));
				$tmp_img = $fileColor['tmp_name'];
				$size = $fileColor['size'];
				$type=strtolower(substr($img,-3));
				if(preg_match("/".ALLOW_FILE_TYPES."/",strtolower($img))) {
					# Upload
					if(isImage($img)) {
						$new_img = $img;
						move_uploaded_file($tmp_img,$gallery_path.'l_'.$img);
						if(!isJpeg($img)) $new_img = preg_replace("/(png$|bmp$|gif$)/","jpg",$img);
						resize($gallery_path,$gallery_path,'l_'.$img,'l_'.$new_img,DEFAULT_LARGE_SIZE,DEFAULT_LARGE_SQUARE,DEFAULT_PHOTO_QUALITY);
						resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$new_img,DEFAULT_AVATAR_SIZE,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);
						
						
						if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
						$color_board = $new_img;
					} 
				} #/if (preg_match
			}
			# Files upload
			$files = isset($_FILES['files'])?$_FILES['files']:'';
			if($files) {
				$uphotos = array();
				$uvideos = array();
				$ufiles = array();
				for($i=0; $i<count($files['name']);$i++) {
					$img = addslashes(Filter(rand()."_".$files['name'][$i]));
					$tmp_img = $files['tmp_name'][$i];
					$size = $files['size'][$i];
					$type=strtolower(substr($img,-3));
					if(preg_match("/".ALLOW_FILE_TYPES."/",strtolower($img))) {
						# Upload
						if(isImage($img)) {
							$new_img = $img;
							move_uploaded_file($tmp_img,$gallery_path.'l_'.$img);
							if(!isJpeg($img)) $new_img = preg_replace("/(png$|bmp$|gif$)/","jpg",$img);
							resize($gallery_path,$gallery_path,'l_'.$img,'l_'.$new_img,DEFAULT_LARGE_SIZE,DEFAULT_LARGE_SQUARE,DEFAULT_PHOTO_QUALITY);
							resize($gallery_path,$gallery_path,'l_'.$img,'a_'.$new_img,DEFAULT_AVATAR_PR_SIZE,DEFAULT_AVATAR_SQUARE,DEFAULT_PHOTO_QUALITY);
							#if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
							$uphotos[] = $new_img;
						} elseif(isVideo($img)) {
							move_uploaded_file($tmp_img,$gallery_path.$img);
							$uvideos[] = $img;
						} else {
							move_uploaded_file($tmp_img,$gallery_path.$img);
							$ufiles[] = $img;
						}
					} #/if (preg_match
				} #/for($i=0;
			}
			$properties = array('avatar' => $avatar,
			                     'color_board' => $color_board,
								'photos' => $uphotos,
								'videos' => $uvideos,
								'files' => $ufiles,
								'user_upload' => $userUpload,
								'weight' => $request->element('weight',1),
								'root_price' => $request->element('root_price'));
			
			# Custom fields
			foreach($fieldList as $field) {
				$properties[$field->getName()] = stripslashes($request->element($field->getName()));
			}

			$data = array('store_id' => $storeId,
						  'cat_id' => $request->element('catid'),
						  'area' => $request->element('area'),
						  'areah' => $request->element('areas'),
						  'price' => $request->element('price'),
						  'market_price' => $request->element('market_price'),
						  'sku' => $request->element('sku'),
						  'prices' => $request->element('prices'),
						  'acreage' => $request->element('acreage'),
						  'acreages' => $request->element('acreages'),
						  'slug' => $slug,
						  'name' => Filter($request->element('name')),
						  'keyword' => Filter($request->element('keyword')),
						  'position' => $request->element('position'),
						  'status' => $request->element('status'),
						  'address' => $request->element('address'),
						  'client' => $request->element('client'),
						  'completed' => Changedate($request->element('completed')),
						  'description' => $request->element('description'),
						  'detail' => $request->element('detail'),
						  'banggia' => $request->element('banggia'),
						  'properties' => serialize($properties),
						  'created' => date("Y-m-d H:i:s"));
			$newId = $products->addData($data);
			
			
			#Add 
			foreach($request->element("cat_id") as $catid){
				      $data = array('id_product' => $newId,
						             'cat_id' => $catid);
				$productCate->addData($data);
			}
					
			# Operation tracking
			$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['add_product'],$request->element('name')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
			header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=product&mod=list&pId=".$request->element('cat_id')."&rcode=6");
		}
	}
}

# Ham kiem tra du lieu nguoi dung nhap vao
function validateData($request) {
	global $amessages;
	include_once(ROOT_PATH.'classes/data/validate.class.php');
	$error = array();
	$validate = new Validate();
	$error['INPUT']['cat_id'] = $validate->pasteString($request->element('cat_id'));
	$error['INPUT']['name'] = $validate->validString($request->element('name'),$amessages['name']);
	$error['INPUT']['address'] = $validate->validString($request->element('address'),$amessages['address']);
	$error['INPUT']['client'] = $validate->validString($request->element('client'),$amessages['client']);
	$error['INPUT']['keyword'] = $validate->validString($request->element('keyword'),$amessages['keyword']);
	$error['INPUT']['detail'] = $validate->validString($request->element('detail'),$amessages['detail']);
	$error['INPUT']['banggia'] = $validate->validString($request->element('banggia'),$amessages['detail']);
	$error['INPUT']['position'] = $validate->pasteString($request->element('position'));
	$error['INPUT']['status'] = $validate->pasteString($request->element('status'));

	
	$error['INPUT']['description'] = $validate->validString($request->element('description'));

	# Paste value of custom fields
	global $fieldList;
	foreach($fieldList as $field) {
		$error['INPUT'][$field->getName()] = $validate->pasteString($request->element($field->getName()));
	}
	
	if($error['INPUT']['name']['error'] || $error['INPUT']['keyword']['error'] || $error['INPUT']['description']['error'] || $error['INPUT']['detail']['error'] ) {
		$error['invalid'] = 1;
		return $error;
	}
	$error['invalid'] = 0;
	return $error;
}
?>
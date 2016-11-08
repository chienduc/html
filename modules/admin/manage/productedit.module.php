<?php
/*************************************************************************
Coder: PhanTom
**************************************************************************/
$userInfo->checkPermission('product','edit');
$templateFile = 'manageproduct.tpl.html';
include_once(ROOT_PATH.'classes/dao/productcategories.class.php');
include_once(ROOT_PATH.'classes/dao/services.class.php');
include_once(ROOT_PATH.'classes/dao/servicecategories.class.php');
include_once(ROOT_PATH.'classes/dao/products.class.php');
include_once(ROOT_PATH.'classes/dao/fields.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
include_once(ROOT_PATH.'classes/dao/typetours.class.php');
include_once(ROOT_PATH.'classes/dao/areas.class.php');
include_once(ROOT_PATH.'classes/dao/typedates.class.php');
include_once(ROOT_PATH.'classes/dao/productcate.class.php');
$serviceCategories = new ServiceCategories($storeId);
$typedates = new Typedates();
$typetours = new Typetours();
$productCate = new ProductCate();
$productCategories = new ProductCategories($storeId);
$products = new Products($storeId);
$areas = new Areas($storeId);
$services = new Services($storeId);
$fields = new Fields($storeId);
$gallery_root = ROOT_PATH."upload/$storeId/";
$gallery_path = $gallery_root."products/";
$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_product'] => '/'.ADMIN_SCRIPT.'?op=manage&act=product',
				$amessages['edit_product'] => '');
				

$tabLink = '/'.ADMIN_SCRIPT.'?op=manage&act=product';
$listTabs = array($amessages['list_item'] => $tabLink.'&mod=list',
				$amessages['edit_product'] => '#',
				$amessages['list_category'] => $tabLink.'&mod=listcategory',
				$amessages['add_product_category'] => $tabLink.'&mod=addcategory',
				$amessages['clean_trash'] => $tabLink.'&mod=cleantrash');			
$template->assign('listTabs',$listTabs);
$template->assign('currentTab',2);

$result_code = $request->element('rcode');
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
$productInfo = $products->getObject($id);


if(!$productInfo) {
	$template->assign('validItem',0); 
} else {
	$template->assign('validItem',1);
	if($request->element('doo') == 'delPhoto') {
		$properties = $productInfo->getProperties();
		foreach($properties['photos'] as $key => $value) {
			if($value == $request->element('photo')) {
				unlink($gallery_path."l_".$properties['photos'][$key]);
				unlink($gallery_path."a_".$properties['photos'][$key]);
				unset($properties['photos'][$key]);
				$data = array('properties' => serialize($properties));
				$products->updateData($data,$id);
				$productInfo = $products->getObject($id);
				break;
			}
		}
	}
	if($request->element('doo') == 'delAvatar') {
		$productInfo = $products->getObject($id);
		$properties = $productInfo->getProperties();
		if($productInfo->getProperty('avatar')) {
			unlink($gallery_path.'a_'.$productInfo->getProperty('avatar'));
			unlink($gallery_path.'l_'.$productInfo->getProperty('avatar'));
			unlink($gallery_path.'m_'.$productInfo->getProperty('avatar'));
			unset($properties['avatar']);
			$data = array('properties' => serialize($properties));
			$products->updateData($data,$id);
			$productInfo = $products->getObject($id);
		}
	}
	
	if($request->element('doo') == 'delColor') {
		$productInfo = $products->getObject($id);
		$properties = $productInfo->getProperties();
		if($productInfo->getProperty('color_board')) {
			unlink($gallery_path.'a_'.$productInfo->getProperty('color_board'));
			unlink($gallery_path.'l_'.$productInfo->getProperty('color_board'));
			unset($properties['color_board']);
			$data = array('properties' => serialize($properties));
			$products->updateData($data,$id);
			$productInfo = $products->getObject($id);
		}
	}
	
		
	if($request->element('doo') == 'delMovie') {
		$properties = $productInfo->getProperties();
		foreach($properties['movies'] as $key => $value) {
			if($value == $request->element('movie')) {
				unlink($gallery_path.$properties['movies'][$key]);
				unset($properties['movies'][$key]);
				$data = array('properties' => serialize($properties));
				$products->updateData($data,$id);
				$productInfo = $products->getObject($id);
				break;
			}
		}
	}
	if($request->element('doo') == 'delFile') {
		$properties = $productInfo->getProperties();
		foreach($properties['files'] as $key => $value) {
			if($value == $request->element('file')) {
				unlink($gallery_path.$properties['files'][$key]);
				unset($properties['files'][$key]);
				$data = array('properties' => serialize($properties));
				$products->updateData($data,$id);
				$productInfo = $products->getObject($id);
				break;
			}
		}
	}

	# Allow some javascript
	$template->assign('ckEditor',1);

	if($_POST && $request->element('doo') == 'submit') { # if form is submitted
		# Get list of custom fields
		$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='product'",array('position' => 'ASC'));
		if($fieldList) $template->assign('fieldList',$fieldList);
		
		# Validate the data input
		$validate = validateData($request);
		if($validate['invalid']) {	# data input is not in valid form
			$template->assign('error',$validate);
			$productInfo = $products->getObject($id);
			$template->assign('itemInfo',$productInfo);
			
			# Category combo box
			$categoryCombos = $services->generateCombo($request->element('cat_ids'),1);
			if($categoryCombos) $template->assign('categoryCombos',$categoryCombos);
			
			# Category combo box
			$departCombo = $areas->generateComboss($request->element('area'));
			if($departCombo) $template->assign('departCombo',$departCombo);
			
			# Category combo box
			$departComboH = $areas->generateComboH($request->element('areas'),$request->element('area'));
			if($departComboH) $template->assign('departComboH',$departComboH);
			
			
			$dateCombos = $typedates->generateCombo($request->element('typedate'));
            if($dateCombos) $template->assign('dateCombos',$dateCombos);
			
			#
			$tourCombos = $typetours->generateCombo($request->element('typetour'));
			if($tourCombos) $template->assign('tourCombos',$tourCombos);
			
			$categoryBrand = $productCategories->generateCombo($request->element('catid',0));
			if($categoryBrand) $template->assign('categoryBrand',$categoryBrand);

			# Category combo box
			$categoryCombo = $serviceCategories->generateListbox($request->element('cat_id'),0);
			if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
		} else { # Valid data input
		
		# Category combo box
			$categoryCombos = $services->generateCombo($request->element('cat_ids'),1);
			if($categoryCombos) $template->assign('categoryCombos',$categoryCombos);
			
			
			$categoryBrand = $productCategories->generateCombo($request->element('catid',0));
			if($categoryBrand) $template->assign('categoryBrand',$categoryBrand);
						# Category combo box
			$categoryCombo = $productCategories->generateCombo($request->element('cat_id',0));
			if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
			
			# Category combo box
			$departCombo = $areas->generateComboss($request->element('area'));
			if($departCombo) $template->assign('departCombo',$departCombo);
			
			# Category combo box
			$departComboH = $areas->generateComboH($request->element('areas'),$request->element('area'));
			if($departComboH) $template->assign('departComboH',$departComboH);
			
			$dateCombos = $typedates->generateCombo($request->element('typedate'));
            if($dateCombos) $template->assign('dateCombos',$dateCombos);
			
			#
			$tourCombos = $typetours->generateCombo($request->element('typetour'));
			if($tourCombos) $template->assign('tourCombos',$tourCombos);
			
			# check duplicate category name
			if($estore->getProperty('check_duplicate_product_name')) {
				if($products->checkDuplicate($request->element('name'),'name',"`id` <> '$id' AND `cat_id` = '".$request->element('cat_id')."'")) {
					$validate['INPUT']['name']['message'] = $amessages['name_duplicated'];
					$validate['INPUT']['name']['error'] = 1;
					$validate['invalid'] = 1;
					$template->assign('error',$validate);
				}
			}
			
			# Check if duplicate slug
			$slug = TextFilter::urlize($request->element('slug'),false,'-');
			$i = 0;
			$dup = 1;
			while($dup) {
				$dup = $products->checkDuplicate($slug.($i?'-'.$i:''),'slug',"`id` <> '$id' AND `cat_id` = '".$request->element('cat_id')."'");
				if($dup) $i++;
			}
			$slug .= $i?'-'.$i:'';
					
			# Everything is ok. Update data to DB
			if(!$validate['invalid']) {
				$productInfo = $products->getObject($id);
				if($productInfo) {			
					$properties = $productInfo->getProperties();
					
					# Check if gallery folder is exists
					if(!file_exists($gallery_root)) mkdir("$gallery_root");
					if(!file_exists($gallery_path)) mkdir("$gallery_path");
										
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
								if($productInfo->getProperty('avatar')) {
									unlink($gallery_path.'a_'.$productInfo->getProperty('avatar'));
									unlink($gallery_path.'t_'.$productInfo->getProperty('avatar'));
									unlink($gallery_path.'m_'.$productInfo->getProperty('avatar'));
									unlink($gallery_path.'l_'.$productInfo->getProperty('avatar'));
								}
								$properties['avatar'] = $new_img;
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
								if($productInfo->getProperty('color_board')) {
									unlink($gallery_path.'a_'.$productInfo->getProperty('color_board'));
									unlink($gallery_path.'l_'.$productInfo->getProperty('color_board'));
								}
								$properties['color_board'] = $new_img;
							} 
						} #/if (preg_match
					}
					
					
					# Files upload
					$files = isset($_FILES['files'])?$_FILES['files']:'';
					if($files) {
						if(!isset($properties['photos'])) $properties['photos'] = array();
						if(!isset($properties['videos'])) $properties['videos'] = array();
						if(!isset($properties['files'])) $properties['files'] = array();
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
									if($img != $new_img) unlink($gallery_path,'l_'.$img);	# Delete file if it's not a JPEG
									$properties['photos'][] = $new_img;
								} elseif(isVideo($img)) {
									move_uploaded_file($tmp_img,$gallery_path.$img);
									$properties['videos'][] = $img;
								} else {
									move_uploaded_file($tmp_img,$gallery_path.$img);
									$properties['files'][] = $img;
								}
							} #/if (preg_match
						} #/for($i=0;
					}
					# End File upload
					#User update
					$properties['user_update'] = $userInfo->getUsername();
					$properties['weight'] =	$request->element('weight',1);

					# Custom fields
					foreach($fieldList as $field) {
						$properties[$field->getName()] = stripslashes($request->element($field->getName()));
					}
				
					$data = array('store_id' => $storeId,
								  'cat_id' => $request->element('catid'),
								  'area' => $request->element('area'),
								  'areah' => $request->element('areas'),
								   'sku' => $request->element('sku'),
								  'price' => $request->element('price'),
								   'market_price' => $request->element('market_price'),
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
								  'updated' => date("Y-m-d H:i:s"));
					$products->updateData($data,$id);
					
					 $productCate->deleteData($id,'id_product');
			 #Add style restaurant
			 foreach($request->element("cat_id") as $catid){
				      $data = array('id_product' => $id,
						            'cat_id' => $catid);
				    $productCate->addData($data);
		  	}
					
					# Operation tracking
					$trackings->addData(array('store_id'=>$storeId,'username'=>$userInfo->getUsername(),'action'=>sprintf($amessages['tracking']['edit_product'],$request->element('name')),'date_created'=>date("Y-m-d H:i:s"),'ip'=>$_SERVER['REMOTE_ADDR']));
					
					# Redirect to editing page
					header('location:'.'/'.ADMIN_SCRIPT."?op=manage&act=product&mod=edit&lang=$lang&id=$id&rcode=7");
				}
			}
		}
	} else { # Load product information to edit
			$template->assign('item',$productInfo);
			
			
			 $allId=$productCate->getGroupFromProduct($id);
		     $categoryCombo = $serviceCategories->generateListbox($allId);
			 if($categoryCombo) $template->assign('categoryCombo',$categoryCombo);
		 
		    $categoryBrand = $productCategories->generateCombo($productInfo->getCatId(),1);
			if($categoryBrand) $template->assign('categoryBrand',$categoryBrand);
			
			
			
			# Category combo box

			$categoryCombos = $services->generateCombo($productInfo->getIdService(),1);
			if($categoryCombos) $template->assign('categoryCombos',$categoryCombos);
			
			$dateCombos = $typedates->generateCombo($request->element('typedate'));
            if($dateCombos) $template->assign('dateCombos',$dateCombos);
			
			#
			$tourCombos = $typetours->generateCombo($request->element('typetour'));
			if($tourCombos) $template->assign('tourCombos',$tourCombos);
			
			# Category combo box
			$departCombo = $areas->generateComboss($productInfo->getArea());
			if($departCombo) $template->assign('departCombo',$departCombo);
			
			# Category combo box
			$departComboH = $areas->generateComboH($productInfo->getAreas(),$productInfo->getArea());
			if($departComboH) $template->assign('departComboH',$departComboH);
			
	
			
			# Get list of custom fields
			$fieldList = $fields->getObjects(1,"`status`='1' AND `module`='product'",array('position' => 'ASC'));
			if($fieldList) $template->assign('fieldList',$fieldList);
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
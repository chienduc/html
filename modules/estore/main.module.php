<?php
/*************************************************************************
Estore main module
----------------------------------------------------------------
DeraCMS 3.0 Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
Last updated: 25/03/2013
Coder: Mai Minh (http://maiminh.vnweblogs.com)
**************************************************************************/

include_once(ROOT_PATH.'classes/dao/templates.class.php');
include_once(ROOT_PATH.'classes/dao/productcategories.class.php');
include_once(ROOT_PATH.'classes/dao/products.class.php');
include_once(ROOT_PATH.'classes/dao/comments.class.php');
include_once(ROOT_PATH.'classes/dao/areas.class.php');
include_once(ROOT_PATH.'classes/dao/supports.class.php');

$estores = new EStores();
$templates = new Templates();

# Checking if this estore is enable
if($estore->getStatus() == S_EXPIRED) $act = 'suspended';
elseif($estore->getStatus() != S_ENABLED) $act = 'disabled';

#Get user template
$templateId = $estore->getProperty('domain_template_id');
$userTemplate = $templates->getTemplateFolderFromId($templateId);
if(isset($_SESSION['template']))
$userTemplate=$_SESSION['template'];
if($request->element('template'))
$_SESSION['template']=$request->element('template');
if(!$userTemplate) $userTemplate = STANDARD_TEMPLATE;
//$userTemplate = 'tiffanyhotel';
# Add the template path of this estore to the template paths array
$template_dir[] = ROOT_PATH.TEMPLATE_PATH.'/'.$userTemplate.'/';

# Lang
$lang=$request->element('lang');
if(!$lang) $lang=DEFAULT_LANGUAGE;
#Allow order
$orderOn = $estore->getProperty('order_on');
$template->assign('orderOn',$orderOn);

include_once(ROOT_PATH.'classes/dao/servicecategories.class.php');
$serviceCategories = new ServiceCategories($sId);






$supports = new Supports($sId);

$supportRight = $supports->getObjects(1,"status=1",array("position"=>"ASC"),'');
$template->assign('supportRight',$supportRight);



# Comments
$comments = new Comments($sId);
# Get the list Menus 
include_once(ROOT_PATH.'classes/dao/menus.class.php');
include_once(ROOT_PATH.'classes/dao/menucategories.class.php');
$menus = new Menus($storeId);

$menucategories = new MenuCategories();
$menuCateItems = $menucategories->getObjects(1,"status = 1",array('id'=>'ASC'),'');

if($menuCateItems){
	foreach($menuCateItems as $menuCates){
		$mcId = $menuCates->getId();
		$listMenus = $menus->getMenuFromPid($storeId,$mcId);
		$template->assign('listMenus_'.$mcId,$listMenus);
	}
}
# Get the list Ads
include_once(ROOT_PATH.'classes/dao/ads.class.php');
include_once(ROOT_PATH.'classes/dao/adscategories.class.php');
$ads = new Ads($storeId);
$adCategories = new AdsCategories($storeId);
$adcateItems = $adCategories->getObjects(1,"status = 1",array('id'=>'asc'));
if($adcateItems){
	foreach($adcateItems as $adCates){
		$gId = $adCates->getId();
		$listAds = $ads->getAdsFromGId($storeId,$gId);
		$template->assign('listAd_'.$gId,$listAds);
		$template->assign('listCount',$listAds);
	}
}

include_once(ROOT_PATH.'classes/dao/articlecategories.class.php');
$articleCategories= new ArticleCategories($sId);


include_once(ROOT_PATH.'classes/dao/articles.class.php');
$articles= new Articles($sId);
$listNew = $articles->getObjects(1,"status=1 and `home` = 1",array("position"=>"DESC"),10);
$template->assign('leftNews',$listNew);

$listBanggia = $articles->getObjects(1,"status=1 and cat_id =2",array("date_created"=>"DESC"),10);
$template->assign('listBanggia',$listBanggia);





# Get the product list
$estore->setProperty('products_per_typify', 6);
$productCategories = new ProductCategories($sId);
$products = new Products($sId);

$productRight = $products->getObjects(1,"status = 1 AND `home`=1",array('created'=>'DESC'),$estore->getProperty('products_per_typify'));
$template->assign('productRight',$productRight);

$proCateHome = $productCategories->getObjects(1,"status = 1 and `home` = 1",array('position'=>'DESC'));
$template->assign('proCateHome',$proCateHome);



$comboLeft2 = $productCategories->getObjects(1,"status = 1 and parent_id = 0",array('position'=>'asc'),100);
$template->assign('comboLeft2',$comboLeft2);

 
# Poll
include_once(ROOT_PATH."/modules/estore/plugin/left.module.php");
# Shopping Cart
$carts = new Carts($sId, $sessId);

#CartItems
$count_item = $carts->getSumQuantity($sessId);
$template->assign("count_item",$count_item);
$template->assign("sessId",$sessId);
# Include the appropriated action module
if ($act) include_once(ROOT_PATH.'modules/estore/'.strtolower($act).'.module.php');
# Assign the template variables
$template->assign("carts",$carts);
$template->assign('userTemplate',$userTemplate);
$template->assign('estore',$estore);
$estore->setProperty('currency',1);
$estore->setProperty('rate',19000);

?>


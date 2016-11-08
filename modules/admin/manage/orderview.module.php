<?php
/*************************************************************************
View order module
----------------------------------------------------------------
BiDo Project
Company: Derasoft Co., Ltd
Last updated: 16/09/2011
Coder: Tran Thi My Xuyen
**************************************************************************/
$userInfo->checkPermission('order','view');
$templateFile = 'manageorderview.tpl.html';
include_once(ROOT_PATH.'classes/dao/orders.class.php');
include_once(ROOT_PATH.'classes/dao/orderitems.class.php');
include_once(ROOT_PATH."classes/data/textfilter.class.php");
$orders = new Orders($storeId);

$topNav = array($amessages['dash_board'] => '/'.ADMIN_SCRIPT.'?op=dashboard',
				$amessages['manage_website'] => '/'.ADMIN_SCRIPT.'?op=manage',
				$amessages['manage_order'] => '/'.ADMIN_SCRIPT.'?op=manage&act=order',
				$amessages['view_order'] => '');
				
$result_code = $request->element('rcode'); 
if($result_code) $template->assign('result_code',$result_code);
$id = $request->element('id');
if($id) $template->assign('id',$id);
if($id){
	$orderItems = new OrderItems($id);
	$orderInfo = $orders->getObject($id);
	$template->assign('item',$orderInfo); 
	$listProducs = $orderItems->getObjects(1,"1>0",array('id' => 'DESC'),'');
	$template->assign('listProducs',$listProducs); 

}

?>
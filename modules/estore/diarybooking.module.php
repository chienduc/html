<?php
/*************************************************************************
Estore category module
----------------------------------------------------------------
BiDo.vn Project
Company: Derasoft Co., Ltd
Email: info@derasoft.com
Last updated: 06/19/2010
**************************************************************************/
include_once(ROOT_PATH."classes/dao/orders.class.php");
include_once(ROOT_PATH."classes/dao/orderitems.class.php");
$orders = new Orders($sId);
$templateFile = "diarybooking.tpl.html";
if(isset($_SESSION['store_customerId'])){
	$Id = $_SESSION['store_customerId'];
	$estore->setProperty('orders_per_page', 20);
	$page = $request->element('pg');
	if(!$page) $page = 1;
	$rowsPages = $orders->getNumItems('id', "user_id = '$Id'",$estore->getProperty('orders_per_page'));
	$template->assign('rowsPages',$rowsPages);
	/// start page 
	$end = $page * $estore->getProperty('orders_per_page');
	if($end > $estore->getProperty('orders_per_page')) $start  = $end -  $estore->getProperty('orders_per_page') +1;
	else $start = 1;
	if($end > $rowsPages['rows']) $end = $rowsPages['rows'];
	$template->assign('end',$end);
	$template->assign('start',$start);
	/// end page
	if($page < 1) $page = 1;
	if($page > $rowsPages['pages']) $page = $rowsPages['pages'];

	$listorder = $orders->getObjects($page,"user_id = '$Id'",array('created'=>'DESC'),$estore->getProperty('orders_per_page'));
	$template->assign("items",$listorder);
	
	# Generate pager
	if(isset($rowsPages)){
	$url = "/listorder/page-%d.html";
	$pager = LinkPage($url,$rowsPages['pages'],$page,10,'/'.TEMPLATE_PATH.'/'.$userTemplate.'/images/');
	$template->assign('pager',$pager);
	}
}
# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' => $messages['reservation'], 'url' => '', 'current' => '1');
$template->assign('navigationItems',$navigationItems);
$pageTitle=$messages['diary_booking'];
if($request->element('plus')=='detail')
{
$templateFile="viewreservation.tpl.html";	
 $orderCode = $request->element('orderCode');
 $cell = $request->element('cell');
$order = $orders->getObject($orderCode,'id',"cell = '$cell'");
if ($order){
	$oId = $order->getId();
	$orderItems = new OrderItems($oId);
	$template->assign('order',$order);
}
}
?>
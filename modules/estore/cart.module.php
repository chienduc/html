<?php
/*************************************************************************
Estore category module
----------------------------------------------------------------
DerCMS 3.0 Project
Company: Derasoft Co., Ltd                                  
Coder: Tran Thi My Xuyen
Email: info@derasoft.com                                    
Last updated: 31/05/2012
**************************************************************************/
if($orderOn){
$templateFile = 'cart.tpl.html';
$plus = $request->element('plus','');
if($plus == 'quickAdd') {
	$pId = $request->element('pId');
	if($pId) {
		if(!$carts->isProductExists($pId)) {
			$fields = array('store_id' => $sId,
							'session_id'=> $sessId,
							'product_id' => $pId,
							'quantity' => 1,
							'properties' => '',
							'time_stamp' => date()
							);
			$carts->addData($fields);
			$_SESSION['lastProduct'] = $pId;
		}
	}
	header("location: ".$_SERVER['HTTP_REFERER']);
}

if($plus == 'deleteCart') {
	$cId = $request->element('id');
	if($cId) {
		$carts->deleteData($cId,'id');
	}
	header("location: ".$_SERVER['HTTP_REFERER']);
}

# Actions
if($_POST) {
	switch ($plus) {
		case 'updateCart':
			$dIds =  $request->element('dIds');
			if($dIds) {
				foreach ($dIds as $dId) {
					$carts->deleteData($dId);
				}		
			} else {
				$cIds =  $request->element('cIds');
				if($cIds) {
					$quantity = $request->element('quantity');
					$i = 0;
					foreach ($cIds as $cId) {
						if($quantity[$i] <= 0) $carts->deleteData($cId);
						else $carts->updateData(array('quantity' => $quantity[$i]),$cId);
						$i++;
					}
				}
			}
			header("location: /cart.html");
			break;
		case 'addItem':
			$pId = $request->element('pId');
			if($pId) {
				if(!$carts->isProductExists($pId)) {
					$quantity = $request->element('quantity',1);
					$fields = array('store_id' => $sId,
									'session_id'=> $sessId,
									'product_id' => $pId,
									'quantity' => $quantity,
									'properties' => '',
									'time_stamp' => date()
									);
					$carts->addData($fields);
					$_SESSION['lastProduct'] = $pId;
				}
			}
			header("location: /cart.html");
			break;
		case 'continue':
			if($_SESSION['lastProduct']) {
				$lastCatId = $products->getCatIdFromId($_SESSION['lastProduct']);
				if($lastCatId) {
					$lastCat = $productCategories->getObject($lastCatId);
					if($lastCat) {
						header("location: ".$lastCat->getUrl());
						break;
					}
				}				
			}
			header("location: /products.html");
			break;
	}
}
# Get items in cart
$cartItems = $carts->getObjects(1, '1>0', $sort = array('time_stamp' => 'desc'), 100);
if($cartItems) $template->assign("cartItems",$cartItems);
}else $templateFile = 'note.tpl.html';
# Generate the navigation bar
$navigationItems[] = array('name' => $estore->getName(), 'url' => '/', 'current' => '0');
$navigationItems[] = array('name' => $messages['cart'], 'url' => '', 'current' => '1');
$template->assign('navigationItems',$navigationItems);

# Page title, keywords, description
$pageTitle = $messages['cart'];
?>
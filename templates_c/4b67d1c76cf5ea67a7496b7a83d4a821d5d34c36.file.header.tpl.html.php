<?php /* Smarty version Smarty-3.0-RC2, created on 2016-11-04 09:28:43
         compiled from "C:\xampp\htdocs\projects\shop/templates/son//header.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:32344581bf25b2995f1-92765571%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4b67d1c76cf5ea67a7496b7a83d4a821d5d34c36' => 
    array (
      0 => 'C:\\xampp\\htdocs\\projects\\shop/templates/son//header.tpl.html',
      1 => 1466273236,
    ),
  ),
  'nocache_hash' => '32344581bf25b2995f1-92765571',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_strip_tags')) include 'C:\xampp\htdocs\projects\shop\classes\template\plugins\modifier.strip_tags.php';
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title><?php if ($_smarty_tpl->getVariable('pageTitle')->value){?><?php echo smarty_modifier_strip_tags($_smarty_tpl->getVariable('pageTitle')->value);?>
 - <?php }?><?php echo $_smarty_tpl->getVariable('estore')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</title>
	<link rel="shortcut icon" href="<?php echo $_smarty_tpl->getVariable('estore')->value->getProperty('store_logo');?>
" type="image/x-icon"/>
    <meta name="keywords" content="<?php if ($_smarty_tpl->getVariable('pageKeywords')->value){?><?php echo smarty_modifier_strip_tags($_smarty_tpl->getVariable('pageKeywords')->value);?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('estore')->value->getKeywords($_smarty_tpl->getVariable('lang')->value);?>
<?php }?>" />
<meta name="description" content="<?php if ($_smarty_tpl->getVariable('pageDescription')->value){?><?php echo smarty_modifier_strip_tags($_smarty_tpl->getVariable('pageDescription')->value);?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('estore')->value->getDescription($_smarty_tpl->getVariable('lang')->value);?>
<?php }?>" />
    <meta name="robots" content="index, follow" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0"/>
    <meta name="msvalidate.01" content="EBC36AED0CBA30318BA97C11B36D728B" />
    <meta name="p:domain_verify" content="fa3ad1fa3eb71b7ec3c3bbacd5a07be0"/>
    
    <!-- Bootstrap -->
    <link href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/css/bootstrap.min.css" rel="stylesheet">
	<link href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/css/cssresponsive.css" rel="stylesheet">
    <link href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/css/menu-respons.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- slider -->
	<link rel="stylesheet" href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/css/nivo-slider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/css/styles.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/css/customecssthem.css" type="text/css" media="screen" />
    <link rel="author" href="https://plus.google.com/114791908042608533500"/>
    
    <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.6";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	
  </head>
  <body>
    <div class="container-fluid">
        <div class="row">
            <!-- slider va menu top -->
                 <!-- bgin top menu  sub-->
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<div class="row" id="headermobile" style="margin-top:10px;">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="mobile_text" style="display:none">
    	<p>Mai Thiên Phúc - nhà phân phối sơn số 1 việt nam</p>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 cusmenu-click" style="padding:0px;">
    	<div id="menu_mobile_ne" class="glyphicon glyphicon-align-justify" style="font-size:17px;margin:18px 20px 0px;color:#F96302"></div>
    	<div id="menu_mobile_ne_show" class="swipe ne_show_menu" style="z-index:9999;">
      <?php if ($_smarty_tpl->getVariable('listMenus_2')->value){?>
	     <div class="swipe-menu">
             <ul class="links" style="padding-left:0px;">
            <?php  $_smarty_tpl->tpl_vars['menuMain'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('listMenus_2')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['menuMain']->key => $_smarty_tpl->tpl_vars['menuMain']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['menuMain']->key;
?> 
            <?php $_smarty_tpl->assign('menuChildren',$_smarty_tpl->getVariable('menuMain')->value->getChildren(),null,null);?>
               <li><a href="<?php echo $_smarty_tpl->getVariable('menuMain')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('menuMain')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><?php echo $_smarty_tpl->getVariable('menuMain')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</a>
                 <?php if ($_smarty_tpl->getVariable('menuChildren')->value){?>
                    	<ul style="padding-left:10px !important">
                        <?php  $_smarty_tpl->tpl_vars['menuchildren'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['j'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('menuChildren')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['menuchildren']->key => $_smarty_tpl->tpl_vars['menuchildren']->value){
 $_smarty_tpl->tpl_vars['j']->value = $_smarty_tpl->tpl_vars['menuchildren']->key;
?> 
                        	<li><a href="<?php echo $_smarty_tpl->getVariable('menuchildren')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('menuchildren')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><?php echo $_smarty_tpl->getVariable('menuchildren')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</a></li>
                          <?php }} ?>
                        </ul>
                     <?php }?>
                    </li>
                 <?php }} ?>
                </ul>
        	</div>
            <?php }?>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6" style="margin-top:10px;padding:0px">
    	<p class="logomobile" style="text-align:center"><img src="<?php echo $_smarty_tpl->getVariable('estore')->value->getProperty('store_logo');?>
" title="<?php echo $_smarty_tpl->getVariable('estore')->value->getName();?>
" alt="<?php echo $_smarty_tpl->getVariable('estore')->value->getName();?>
"/></p>
    </div>
   
</div>
<div class="row" id="headernone">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
       <a href="\" title="<?php echo $_smarty_tpl->getVariable('estore')->value->getName();?>
"> <img class="img-thumbnail" style="border:none !important" src="<?php echo $_smarty_tpl->getVariable('estore')->value->getProperty('store_logo');?>
" alt="<?php echo $_smarty_tpl->getVariable('estore')->value->getName();?>
" /></a>
    </div>
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                     <form action="" method="post" novalidate>
                        <div class="input-group" style="margin-top:40px">
                       
                          <input type="text" name="keywords" <?php if ($_smarty_tpl->getVariable('keywords')->value){?> value="<?php echo $_smarty_tpl->getVariable('keywords')->value;?>
"<?php }?> class="form-control" placeholder="Tìm kiếm sản phẩm" style="border:1px solid #F96302">
                          <span class="input-group-btn">
                             <input type="hidden" name="act" id="act" value="search">
                           
                            <input type="submit" id="MainContent_ctl00_btnLogin" class="btn btn-default cus_logo_search" value="Tìm kiếm">
                         </span>
                        
                      
                        </div><!-- /input-group -->
                         </form>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><img class="img-thumbnail" style="border:none !important;" src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/slogan-dailyson247.png"/></div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:5px">
                <div class="row">
         <?php if ($_smarty_tpl->getVariable('listMenus_2')->value){?>
          <div id='cssmenu'>
              <ul>
              <?php  $_smarty_tpl->tpl_vars['menuMain'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('listMenus_2')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['menuMain']->key => $_smarty_tpl->tpl_vars['menuMain']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['menuMain']->key;
?> 
               <?php $_smarty_tpl->assign('menuChildren',$_smarty_tpl->getVariable('menuMain')->value->getChildren(),null,null);?>
              <li><a href="<?php echo $_smarty_tpl->getVariable('menuMain')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('menuMain')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><span><?php echo $_smarty_tpl->getVariable('menuMain')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</span></a>
                  <?php if ($_smarty_tpl->getVariable('menuChildren')->value){?>
                       <ul> 
                       <?php  $_smarty_tpl->tpl_vars['menuchildren'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['j'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('menuChildren')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['menuchildren']->key => $_smarty_tpl->tpl_vars['menuchildren']->value){
 $_smarty_tpl->tpl_vars['j']->value = $_smarty_tpl->tpl_vars['menuchildren']->key;
?> 
                       <li> <a title="<?php echo $_smarty_tpl->getVariable('menuchildren')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
" href="<?php echo $_smarty_tpl->getVariable('menuchildren')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" > <?php echo $_smarty_tpl->getVariable('menuchildren')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</a></li>
                       <?php }} ?>
                       </ul>
                    <?php }?>
                 </li>
                <?php }} ?>
              </ul>
          </div>
        <?php }?>
                </div>
            </div>
        </div>
    </div>
                 <ul>
    <!-- submenu -->
    <!-- end -->
</ul>
</div></div>
<!-- end submenu_top -->
            <!-- end menu top -->
            <div id="leftcontent" class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            	<div class="row">

                    <!-- shop category -->
  <?php if ($_smarty_tpl->getVariable('comboLeft2')->value){?>
<div class="panel panel-primary cuscate customemobile">
    <div class="panel-heading">
        <h3 class="panel-title" id="panel-title">Danh mục sản phẩm</h3>
    </div>
    <div id="cusnsxmenu" class="panel-body active-mobile">
        <div id='cssmenuleft' class="panel-body">
            <ul>
             <?php  $_smarty_tpl->tpl_vars['combo'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('comboLeft2')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['combo']->key => $_smarty_tpl->tpl_vars['combo']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['combo']->key;
?> 
               <?php $_smarty_tpl->assign('comboChildren',$_smarty_tpl->getVariable('combo')->value->getChildren(),null,null);?>
               <li <?php if ($_smarty_tpl->tpl_vars['i']->value>3){?>class="has-sub"<?php }?>><a href="<?php echo $_smarty_tpl->getVariable('combo')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('combo')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><span class="chinh"><?php echo $_smarty_tpl->getVariable('combo')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</span></a>
                <?php if ($_smarty_tpl->getVariable('comboChildren')->value){?>
                    <ul>
                     <?php  $_smarty_tpl->tpl_vars['combochild'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['j'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('comboChildren')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['combochild']->key => $_smarty_tpl->tpl_vars['combochild']->value){
 $_smarty_tpl->tpl_vars['j']->value = $_smarty_tpl->tpl_vars['combochild']->key;
?> 
                     	<li><a href="<?php echo $_smarty_tpl->getVariable('combochild')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('combochild')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><span class="phu"><?php echo $_smarty_tpl->getVariable('combochild')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</span></a></li>
                      <?php }} ?>
                  	</ul>
                  <?php }?>
               </li>
            <?php }} ?>
            </ul>
        </div>
    </div>
</div>
<?php }?>
  </div>
            </div>
            <div id="centercontent" class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <!-- banner flash quan cao-->
   <?php if ($_smarty_tpl->getVariable('listAd_1')->value){?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row" id="cusrow">
		<ul class="rslides" id="slider1">
         <?php  $_smarty_tpl->tpl_vars['ads'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('listAd_1')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['ads']->key => $_smarty_tpl->tpl_vars['ads']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['ads']->key;
?>
             <?php $_smarty_tpl->assign('banner',$_smarty_tpl->getVariable('ads')->value->getProperty('logo'),null,null);?>
             <?php $_smarty_tpl->assign('url',$_smarty_tpl->getVariable('ads')->value->getProperty('url'),null,null);?> 
            <li><img src="/upload/1/resources/l_<?php echo $_smarty_tpl->getVariable('banner')->value;?>
" alt="<?php echo $_smarty_tpl->getVariable('ads')->value->getContent($_smarty_tpl->getVariable('lang')->value);?>
" /></li>
           <?php }} ?>
		</ul>
    </div>
</div>
<?php }?>
<div class="clearfix"></div>
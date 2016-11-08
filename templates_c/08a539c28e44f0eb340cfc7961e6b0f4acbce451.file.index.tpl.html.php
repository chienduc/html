<?php /* Smarty version Smarty-3.0-RC2, created on 2016-11-04 09:28:42
         compiled from "C:\xampp\htdocs\projects\shop/templates/son/index.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:18235581bf25a852147-57422988%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '08a539c28e44f0eb340cfc7961e6b0f4acbce451' => 
    array (
      0 => 'C:\\xampp\\htdocs\\projects\\shop/templates/son/index.tpl.html',
      1 => 1466273446,
    ),
  ),
  'nocache_hash' => '18235581bf25a852147-57422988',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
 <?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('usertemplate')->value)."/header.tpl.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>	
 <!-- shop product setting -->
 <?php if ($_smarty_tpl->getVariable('productSethome')->value){?>
<div class="panel panel-primary customemobile" id="cusshopproduct">
    <div class="panel-heading cushead">
        <h1 class="panel-title custitle" id="panel-title">Sản Phẩm Nổi Bật</h1>
    </div>
    <div class="panel-body">
        <div class="row">
         <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('productSethome')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
         <?php $_smarty_tpl->assign('avatar',$_smarty_tpl->getVariable('item')->value->getProperty('avatar'),null,null);?>
          <div class="col-san-pham cussp">
                <div class="thumbnail custhumnail">
                    <a href="<?php echo $_smarty_tpl->getVariable('item')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><h4 style=" text-align:center"><img class="img-thumbnail cusimg" src="/upload/1/products/a_<?php echo $_smarty_tpl->getVariable('avatar')->value;?>
" alt="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
" /></h4></a>
                    <div class="caption cuscap">
                    	<p class=""><a id="bangmauan" href="<?php echo $_smarty_tpl->getVariable('item')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" class="btn btn-primary cusimg" role="button" title="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"></a></p>
                        <h2 class="cush2"><a href="<?php echo $_smarty_tpl->getVariable('item')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</a></h2>
                        <p style="margin-bottom:0px;font-size:80%; margin-top:10px; text-align:left">Mã SP: <span style=" font-weight:bold"><?php echo $_smarty_tpl->getVariable('item')->value->getSku();?>
</span></p>
                        
                       <?php if ($_smarty_tpl->getVariable('item')->value->getMarketPrice()>0){?><p style="color:#00507c;font-size:80%; text-align:left; margin-bottom:0px"><b>Giá cũ: <span style=" font-weight:bold; color:#666; font-size:14px; text-decoration:line-through"><?php echo number_format($_smarty_tpl->getVariable('item')->value->getMarketPrice());?>
  vnđ</span></b></p><?php }?>
                        
                        <p style="color:#00507c;font-size:80%; text-align:left"><b>Giá: <span style=" font-weight:bold; color:red; font-size:14px"><?php if ($_smarty_tpl->getVariable('item')->value->getPrice()>0){?><?php echo number_format($_smarty_tpl->getVariable('item')->value->getPrice());?>
  vnđ<?php }else{ ?>Liên hệ<?php }?></span></b></p>
                       
                    </div>
                     <p class="p-product1"><a id="bangmauan" href="<?php echo $_smarty_tpl->getVariable('item')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" class="btn btn-primary cusimg" role="button" title="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
">Xem chi tiết</a></p>
                </div>
            </div>
          <?php }} ?>
        
        </div>
    </div>
</div>
<?php }?>

<?php if ($_smarty_tpl->getVariable('listAd_3')->value){?>
<?php  $_smarty_tpl->tpl_vars['ads'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('listAd_3')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['ads']->key => $_smarty_tpl->tpl_vars['ads']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['ads']->key;
?>
<?php if ($_smarty_tpl->tpl_vars['i']->value==0){?>
  <?php $_smarty_tpl->assign('banner',$_smarty_tpl->getVariable('ads')->value->getProperty('logo'),null,null);?>
  <?php $_smarty_tpl->assign('url',$_smarty_tpl->getVariable('ads')->value->getProperty('url'),null,null);?> 
<div class="panel panel-primary customemobile" id="cusshopproduct">
 <a href="<?php echo $_smarty_tpl->getVariable('url')->value;?>
" title="<?php echo $_smarty_tpl->getVariable('ads')->value->getContent($_smarty_tpl->getVariable('lang')->value);?>
"><img class="addimages" src="/upload/1/resources/l_<?php echo $_smarty_tpl->getVariable('banner')->value;?>
" alt="<?php echo $_smarty_tpl->getVariable('ads')->value->getContent($_smarty_tpl->getVariable('lang')->value);?>
"   /></a>  
</div>
<?php }?>
<?php }} ?>
<?php }?>

 <?php if ($_smarty_tpl->getVariable('productNew')->value){?>
<div class="panel panel-primary customemobile" id="cusshopproduct">
    <div class="panel-heading cushead">
        <h1 class="panel-title custitle" id="panel-title">Sản Phẩm Mới Nhất</h1>
    </div>
    <div class="panel-body">
        <div class="row">
         <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('productNew')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
         <?php $_smarty_tpl->assign('avatar',$_smarty_tpl->getVariable('item')->value->getProperty('avatar'),null,null);?>
          <div class="col-san-pham cussp">
                <div class="thumbnail custhumnail">
                    <a href="<?php echo $_smarty_tpl->getVariable('item')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><h4 style=" text-align:center"><img class="img-thumbnail cusimg" src="/upload/1/products/a_<?php echo $_smarty_tpl->getVariable('avatar')->value;?>
" alt="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
" /></h4></a>
                    <div class="caption cuscap">
                    	<p class=""><a id="bangmauan" href="<?php echo $_smarty_tpl->getVariable('item')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" class="btn btn-primary cusimg" role="button" title="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"></a></p>
                        <h2 class="cush2"><a href="<?php echo $_smarty_tpl->getVariable('item')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</a></h2>
                        <p style="margin-bottom:0px;font-size:80%; margin-top:10px; text-align:left">Mã SP: <span style=" font-weight:bold"><?php echo $_smarty_tpl->getVariable('item')->value->getSku();?>
</span></p>
                        
                        <?php if ($_smarty_tpl->getVariable('item')->value->getMarketPrice()>0){?><p style="color:#00507c;font-size:80%; text-align:left; margin-bottom:0px"><b>Giá cũ: <span style=" font-weight:bold; color:#666; font-size:14px; text-decoration:line-through"><?php echo number_format($_smarty_tpl->getVariable('item')->value->getMarketPrice());?>
  vnđ</span></b></p><?php }?>
                        
                        <p style="color:#00507c;font-size:80%; text-align:left"><b>Giá: <span style=" font-weight:bold; color:red; font-size:14px"><?php if ($_smarty_tpl->getVariable('item')->value->getPrice()>0){?><?php echo number_format($_smarty_tpl->getVariable('item')->value->getPrice());?>
  vnđ<?php }else{ ?>Liên hệ<?php }?></span></b></p>
                       
                    </div>
                     <p class="p-product1"><a id="bangmauan" href="<?php echo $_smarty_tpl->getVariable('item')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" class="btn btn-primary cusimg" role="button" title="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
">Xem chi tiết</a></p>
                </div>
            </div>
          <?php }} ?>
        
        </div>
    </div>
</div>
<?php }?>

<?php if ($_smarty_tpl->getVariable('listAd_3')->value){?>
<?php  $_smarty_tpl->tpl_vars['ads'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('listAd_3')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['ads']->key => $_smarty_tpl->tpl_vars['ads']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['ads']->key;
?>
<?php if ($_smarty_tpl->tpl_vars['i']->value==1){?>
  <?php $_smarty_tpl->assign('banner',$_smarty_tpl->getVariable('ads')->value->getProperty('logo'),null,null);?>
  <?php $_smarty_tpl->assign('url',$_smarty_tpl->getVariable('ads')->value->getProperty('url'),null,null);?> 
<div class="panel panel-primary customemobile" id="cusshopproduct">
 <a href="<?php echo $_smarty_tpl->getVariable('url')->value;?>
" title="<?php echo $_smarty_tpl->getVariable('ads')->value->getContent($_smarty_tpl->getVariable('lang')->value);?>
"><img class="addimages" src="/upload/1/resources/l_<?php echo $_smarty_tpl->getVariable('banner')->value;?>
" alt="<?php echo $_smarty_tpl->getVariable('ads')->value->getContent($_smarty_tpl->getVariable('lang')->value);?>
"   /></a>  
</div>
<?php }?>
<?php }} ?>
<?php }?>



 <?php if ($_smarty_tpl->getVariable('productPromotion')->value){?>
<div class="panel panel-primary customemobile" id="cusshopproduct">
    <div class="panel-heading cushead">
        <h1 class="panel-title custitle" id="panel-title">Sản Phẩm Khuyến Mãi</h1>
    </div>
    <div class="panel-body">
        <div class="row">
         <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('productPromotion')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
         <?php $_smarty_tpl->assign('avatar',$_smarty_tpl->getVariable('item')->value->getProperty('avatar'),null,null);?>
          <div class="col-san-pham cussp">
                <div class="thumbnail custhumnail">
                    <a href="<?php echo $_smarty_tpl->getVariable('item')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><h4 style=" text-align:center"><img class="img-thumbnail cusimg" src="/upload/1/products/a_<?php echo $_smarty_tpl->getVariable('avatar')->value;?>
" alt="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
" /></h4></a>
                    <div class="caption cuscap">
                    	<p class=""><a id="bangmauan" href="<?php echo $_smarty_tpl->getVariable('item')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" class="btn btn-primary cusimg" role="button" title="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"></a></p>
                        <h2 class="cush2"><a href="<?php echo $_smarty_tpl->getVariable('item')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</a></h2>
                        <p style="margin-bottom:0px;font-size:80%; margin-top:10px; text-align:left">Mã SP: <span style=" font-weight:bold"><?php echo $_smarty_tpl->getVariable('item')->value->getSku();?>
</span></p>
                        
                      <?php if ($_smarty_tpl->getVariable('item')->value->getMarketPrice()>0){?><p style="color:#00507c;font-size:80%; text-align:left; margin-bottom:0px"><b>Giá cũ: <span style=" font-weight:bold; color:#666; font-size:14px; text-decoration:line-through"><?php echo number_format($_smarty_tpl->getVariable('item')->value->getMarketPrice());?>
  vnđ</span></b></p><?php }?>
                        
                        <p style="color:#00507c;font-size:80%; text-align:left"><b>Giá: <span style=" font-weight:bold; color:red; font-size:14px"><?php if ($_smarty_tpl->getVariable('item')->value->getPrice()>0){?><?php echo number_format($_smarty_tpl->getVariable('item')->value->getPrice());?>
  vnđ<?php }else{ ?>Liên hệ<?php }?></span></b></p>
                       
                    </div>
                     <p class="p-product1"><a id="bangmauan" href="<?php echo $_smarty_tpl->getVariable('item')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" class="btn btn-primary cusimg" role="button" title="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
">Xem chi tiết</a></p>
                </div>
            </div>
          <?php }} ?>
        
        </div>
    </div>
</div>
<?php }?>



 <?php if ($_smarty_tpl->getVariable('comboLeft2')->value){?>
<!-- custome san pham mobile-->
<div class="col-sm-6 col-xs-6">
<div class="row">
<div class="panel panel-primary customedesktop">
<div class="panel-heading">
<h3 class="panel-title" id="panel-title">Danh Mục Sản Phẩm</h3>
</div>
<div id="cusnsxmenu" class="panel-body">
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
       <li style="padding:6px 0px" class='has-sub'><a href="<?php echo $_smarty_tpl->getVariable('combo')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
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
                <li style="padding:6px 0px"><a href="<?php echo $_smarty_tpl->getVariable('combochild')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
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
</div>
</div>
<?php }?>



<div class="col-sm-6 col-xs-6">
<div class="row">

<?php if ($_smarty_tpl->getVariable('productSethome')->value){?>
<div class="col-sm-12 col-xs-12">
<div class="row">
<div class="panel panel-primary customedesktop">
<div class="panel-heading">
<h3 class="panel-title" id="panel-title">Sơn Nổi Bật</h3>
</div>
<div class="panel-body">
<div class="row">
<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('productSethome')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
  <?php $_smarty_tpl->assign('avatar',$_smarty_tpl->getVariable('item')->value->getProperty('avatar'),null,null);?>
    <div class="thumbnail custhumnail" style="height:250px">
        <a href="<?php echo $_smarty_tpl->getVariable('item')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><h4 style="text-align:center"><img class="img-thumbnail cusimg" src="/upload/1/products/a_<?php echo $_smarty_tpl->getVariable('avatar')->value;?>
" alt="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
" style="width:80% !important;height:60% !important;"/></h4></a>
        <div class="caption cuscap">
            <h2 class="cush2"><small class="cusmall"><a href="<?php echo $_smarty_tpl->getVariable('item')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><?php echo $_smarty_tpl->getVariable('item')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</a></small></h2>
            <p style="margin-bottom:0px;font-size:80%;">Mã SP: <?php echo $_smarty_tpl->getVariable('item')->value->getSku();?>
</p>
            <p style="color:#00507c;font-size:80%;"><b>Giá: <span style=" color:red" ><?php if ($_smarty_tpl->getVariable('item')->value->getPrice()>0){?><?php echo number_format($_smarty_tpl->getVariable('item')->value->getPrice());?>
  vnđ<?php }else{ ?>Liên hệ<?php }?></span></b></p>
        </div>
    </div>
 <?php }} ?>
</div>
</div>
</div>
</div>
</div>
<?php }?>


</div>
</div>
<!-- end custome san pham mobile -->
            </div>
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('usertemplate')->value)."/right.tpl.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>	
 <?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('usertemplate')->value)."/footer.tpl.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>	          
          
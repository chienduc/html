<?php /* Smarty version Smarty-3.0-RC2, created on 2016-11-04 09:28:43
         compiled from "C:\xampp\htdocs\projects\shop/templates/son//right.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:19377581bf25b950390-06223399%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ff7bc9b15dc4486b125b0ac36b723f9b4f5916b6' => 
    array (
      0 => 'C:\\xampp\\htdocs\\projects\\shop/templates/son//right.tpl.html',
      1 => 1466244018,
    ),
  ),
  'nocache_hash' => '19377581bf25b950390-06223399',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div id="rightcontent" class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
 <div class="row">
  <div class="panel panel-primary cuscate">
   <div class="panel-heading">
      <h3 class="panel-title custitle" id="panel-title">Liên hệ mua hàng</h3>
    </div>
<div class="panel-body cusbodysup active-mobile">
<div class="panel-body cussup" style=" background:#F1E5D9">
    <ul>
       <li class="cusli"><b>Giờ làm việc</b><br/>
       <b>Sáng: 8h00 đến 12h00</b><br/>
       <b>Chiều: 13h00 đến 17h00</b></li>
       <li style="text-align:center">-------------------</li>
       <?php if ($_smarty_tpl->getVariable('supportRight')->value){?>
       <?php  $_smarty_tpl->tpl_vars['support'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('supportRight')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['support']->key => $_smarty_tpl->tpl_vars['support']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['support']->key;
?>
       <li class="cussupport">
          <span><a href="ymsgr:sendim?<?php echo $_smarty_tpl->getVariable('support')->value->getNickYahoo();?>
" title="<?php echo $_smarty_tpl->getVariable('support')->value->getFullName();?>
"><img  src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/yahoo.png" class="cusimgsup" alt="<?php echo $_smarty_tpl->getVariable('support')->value->getFullName();?>
" title="<?php echo $_smarty_tpl->getVariable('support')->value->getFullName();?>
"></a></span>
          <span class="cusskype"><a href="Skype:<?php echo $_smarty_tpl->getVariable('support')->value->getNickSkype();?>
?chat" title="<?php echo $_smarty_tpl->getVariable('support')->value->getFullName();?>
"><img src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/skype.png" class="cusimgskype" title="<?php echo $_smarty_tpl->getVariable('support')->value->getFullName();?>
" alt="<?php echo $_smarty_tpl->getVariable('support')->value->getFullName();?>
"></a></span>	
          <span>
              <b class="namsup"><?php echo $_smarty_tpl->getVariable('support')->value->getFullName();?>
</b>
              <br><div class="cusdt"><?php echo $_smarty_tpl->getVariable('support')->value->getTelephone();?>
</div><br>
          </span>
       </li>
       <?php }} ?>
       <?php }?>
      
       <li style="text-align:center;margin-bottom:5px;font-size:100%"><b>Góp ý</b><br/>
       <b class="addmail"><a href="mailtp:<?php echo $_smarty_tpl->getVariable('estore')->value->getEmail();?>
"><?php echo $_smarty_tpl->getVariable('estore')->value->getEmail();?>
</a></b></li>
                     

                     
        </ul>
    </div>
</div>
</div>


<div class="panel panel-primary cuscate">
   
<div class="panel-body cusbodysup active-mobile">
<div class="panel-body cussup">
    <ul>
                 
<?php if ($_smarty_tpl->getVariable('listAd_2')->value){?>
<?php  $_smarty_tpl->tpl_vars['ads'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('listAd_2')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['ads']->key => $_smarty_tpl->tpl_vars['ads']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['ads']->key;
?>
<?php $_smarty_tpl->assign('banner',$_smarty_tpl->getVariable('ads')->value->getProperty('logo'),null,null);?>
<?php $_smarty_tpl->assign('url',$_smarty_tpl->getVariable('ads')->value->getProperty('url'),null,null);?> 
<li class="cusbanner">
<span><a href="<?php echo $_smarty_tpl->getVariable('url')->value;?>
" title="<?php echo $_smarty_tpl->getVariable('ads')->value->getContent($_smarty_tpl->getVariable('lang')->value);?>
"><img class="imagesads" src="/upload/1/resources/l_<?php echo $_smarty_tpl->getVariable('banner')->value;?>
" alt="<?php echo $_smarty_tpl->getVariable('ads')->value->getContent($_smarty_tpl->getVariable('lang')->value);?>
"  /></a></span>
</li>
<?php }} ?>
<?php }?>  
<li class="cusbanner linkfb">
<span>
<div class="fb-page" data-href="https://www.facebook.com/dailyson247/?fref=ts" data-width="200" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"></div>
</span>
</li>
                     
        </ul>
    </div>
</div>
</div>


<?php if ($_smarty_tpl->getVariable('listBanggia')->value){?>
<div class="panel panel-primary cuscate">
   <div class="panel-heading">
      <h3 class="panel-title custitle" id="panel-title">Bảng Giá Sơn</h3>
    </div>
<div class="panel-body cusbodysup active-mobile">
<div class="panel-body cussup">
    <ul>
       <?php if ($_smarty_tpl->getVariable('listBanggia')->value){?>
       <?php  $_smarty_tpl->tpl_vars['new'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('listBanggia')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['new']->key => $_smarty_tpl->tpl_vars['new']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['new']->key;
?>
       <li class="cussupport">
          <span><a style="color:black; font-weight:bold" href="<?php echo $_smarty_tpl->getVariable('new')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('new')->value->getTitle($_smarty_tpl->getVariable('lang')->value);?>
"><?php echo $_smarty_tpl->getVariable('new')->value->getTitle($_smarty_tpl->getVariable('lang')->value);?>
</a></span>  
       </li>
       <?php }} ?>
       <?php }?>            
        </ul>
    </div>
</div>
</div>
<?php }?>

<?php if ($_smarty_tpl->getVariable('leftNews')->value){?>
<div class="panel panel-primary cuscate">
   <div class="panel-heading">
      <h3 class="panel-title custitle" id="panel-title">Tin tức & Sự Kiện</h3>
    </div>
<div class="panel-body cusbodysup active-mobile">
<div class="panel-body cussup">
    <ul>
       <?php if ($_smarty_tpl->getVariable('leftNews')->value){?>
       <?php  $_smarty_tpl->tpl_vars['new'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('leftNews')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['new']->key => $_smarty_tpl->tpl_vars['new']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['new']->key;
?>
       <li class="cussupport">
          <span><a style="color:black; font-weight:bold" href="<?php echo $_smarty_tpl->getVariable('new')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('new')->value->getTitle($_smarty_tpl->getVariable('lang')->value);?>
"><?php echo $_smarty_tpl->getVariable('new')->value->getTitle($_smarty_tpl->getVariable('lang')->value);?>
</a></span>  
       </li>
       <?php }} ?>
       <?php }?>            
        </ul>
    </div>
</div>
</div>
<?php }?>



</div>
 </div>

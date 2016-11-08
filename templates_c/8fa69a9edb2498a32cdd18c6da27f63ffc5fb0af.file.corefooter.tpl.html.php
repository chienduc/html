<?php /* Smarty version Smarty-3.0-RC2, created on 2016-11-03 10:45:14
         compiled from ".\templates\admin/corefooter.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:9311581ab2ca0de018-98170386%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8fa69a9edb2498a32cdd18c6da27f63ffc5fb0af' => 
    array (
      0 => '.\\templates\\admin/corefooter.tpl.html',
      1 => 1462786904,
    ),
  ),
  'nocache_hash' => '9311581ab2ca0de018-98170386',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>

<div id="footer"> 
<ul>
<li><a href="/" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('home_page');?>
"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('home_page');?>
</a></li>
<li><a href="/admin.php?op=support" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('support');?>
"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('support');?>
</a></li>
<li><a href="/admin.php?op=helpdesk" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('helpdesk');?>
"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('helpdesk');?>
</a></li>
</ul>
<p><?php echo $_smarty_tpl->getVariable('locale')->value->msg('copy_right');?>
</p>
</div>
<?php if ($_smarty_tpl->getVariable('urlPopup')->value){?>
<!--Get URL Popup-->  
<div class="popup2">
<p class="btnClose2"><a href="javascript:;" class="close" title="Close">X Close</a></p>
<div class="popupInner2">
<form action="" method="post" name="formContact">
<fieldset>
<ul>
<li>Get URL</li>
</ul>
<p>
<label for="name">Relative link</label>
<input type="text" value="" name="relative" id="valueRelative" /> 
</p>
<p>
<label for="name">Permanent link</label>
<input type="text" value="http://<?php echo $_smarty_tpl->getVariable('estore')->value->getDomain();?>
" name="relative" id="valuePermanent" />  
</p>
</fieldset>
</form>
</div>
</div>
<!--end Get URL Popup-->
<?php }?>
<?php if ($_smarty_tpl->getVariable('act')->value=='woodshop'||($_smarty_tpl->getVariable('act')->value=='product'&&$_smarty_tpl->getVariable('mod')->value=='edit')||($_smarty_tpl->getVariable('act')->value=='product'&&$_smarty_tpl->getVariable('mod')->value=='add')||$_smarty_tpl->getVariable('act')->value=='campaign'){?>
<script type="text/javascript" src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/scripts/jquery.js"></script>
<script type="text/javascript" src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/scripts/start.js"></script>
<script type="text/javascript" src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/scripts/function.js"></script>
<?php }?>

<script type="text/javascript" src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/scripts/forms.js"></script>
<script type="text/javascript" src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/scripts/menu.js"></script>
<script type="text/javascript" src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/scripts/common.js"></script>
</body>
</html>
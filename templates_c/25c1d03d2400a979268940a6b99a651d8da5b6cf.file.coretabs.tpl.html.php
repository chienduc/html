<?php /* Smarty version Smarty-3.0-RC2, created on 2016-11-03 10:45:13
         compiled from ".\templates\admin/coretabs.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:14521581ab2c9f12b55-99701389%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '25c1d03d2400a979268940a6b99a651d8da5b6cf' => 
    array (
      0 => '.\\templates\\admin/coretabs.tpl.html',
      1 => 1462786904,
    ),
  ),
  'nocache_hash' => '14521581ab2c9f12b55-99701389',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_smarty_tpl->getVariable('listTabs')->value){?>
<ul class="tabs">
<?php  $_smarty_tpl->tpl_vars['url'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('listTabs')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['tab']['iteration']=0;
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['url']->key => $_smarty_tpl->tpl_vars['url']->value){
 $_smarty_tpl->tpl_vars['name']->value = $_smarty_tpl->tpl_vars['url']->key;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['tab']['iteration']++;
?>
<li<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['tab']['iteration']==$_smarty_tpl->getVariable('currentTab')->value){?> class="current"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</a></li>
<?php }} ?>
</ul>
<?php }?>
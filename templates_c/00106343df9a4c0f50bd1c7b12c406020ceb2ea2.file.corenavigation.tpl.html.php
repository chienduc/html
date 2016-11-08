<?php /* Smarty version Smarty-3.0-RC2, created on 2016-11-03 10:45:13
         compiled from ".\templates\admin/corenavigation.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:23168581ab2c9e95b39-39051034%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '00106343df9a4c0f50bd1c7b12c406020ceb2ea2' => 
    array (
      0 => '.\\templates\\admin/corenavigation.tpl.html',
      1 => 1462786904,
    ),
  ),
  'nocache_hash' => '23168581ab2c9e95b39-39051034',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_smarty_tpl->getVariable('topNav')->value){?>
<ul class="breadcrumb">
<?php  $_smarty_tpl->tpl_vars['url'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('topNav')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['url']->total=count($_from);
 $_smarty_tpl->tpl_vars['url']->iteration=0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['nav']['total'] = $_smarty_tpl->tpl_vars['url']->total;
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['url']->key => $_smarty_tpl->tpl_vars['url']->value){
 $_smarty_tpl->tpl_vars['name']->value = $_smarty_tpl->tpl_vars['url']->key;
 $_smarty_tpl->tpl_vars['url']->iteration++;
 $_smarty_tpl->tpl_vars['url']->last = $_smarty_tpl->tpl_vars['url']->iteration === $_smarty_tpl->tpl_vars['url']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['nav']['last'] = $_smarty_tpl->tpl_vars['url']->last;
?>
<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['nav']['last']){?><li class="last"><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</li>
<?php }else{ ?><li><a href="<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</a></li>
<?php }?>
<?php }} ?>
</ul>
<?php }?>
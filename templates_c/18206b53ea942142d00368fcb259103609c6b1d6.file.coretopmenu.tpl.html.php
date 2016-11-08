<?php /* Smarty version Smarty-3.0-RC2, created on 2016-11-03 10:45:12
         compiled from ".\templates\admin/coretopmenu.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:20402581ab2c8a09595-47192166%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '18206b53ea942142d00368fcb259103609c6b1d6' => 
    array (
      0 => '.\\templates\\admin/coretopmenu.tpl.html',
      1 => 1462786904,
    ),
  ),
  'nocache_hash' => '20402581ab2c8a09595-47192166',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_smarty_tpl->getVariable('authUser')->value){?>
<div id="nav">
<?php if ($_smarty_tpl->getVariable('authUser')->value){?><p class="datetime"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('hello');?>
 <a href="/<?php echo $_smarty_tpl->getVariable('aScript')->value;?>
?op=system&act=profile" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('system_profile');?>
"><strong><?php echo $_smarty_tpl->getVariable('authUser')->value->getFullname();?>
</strong></a> (<?php echo $_smarty_tpl->getVariable('authUser')->value->getUsername();?>
) [<?php echo $_smarty_tpl->getVariable('authUser')->value->getType();?>
]. [<a href="/<?php echo $_smarty_tpl->getVariable('aScript')->value;?>
?op=logout" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('log_out');?>
"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('log_out');?>
</a>]</p><?php }?>
<ul id="listmenu">
<li><a href="/<?php echo $_smarty_tpl->getVariable('aScript')->value;?>
?op=dashboard" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('dash_board');?>
"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('dash_board');?>
</a></li>
<li><a href="/<?php echo $_smarty_tpl->getVariable('aScript')->value;?>
?op=manage" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('manage_website');?>
"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('manage_website');?>
</a></li>
<li><a href="/<?php echo $_smarty_tpl->getVariable('aScript')->value;?>
?op=system" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('system');?>
"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('system');?>
</a></li>
</ul>
</div>
<?php }else{ ?>
<div id="nav">
<ul id="listmenu">
<li><a href="/" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('home_page');?>
"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('home_page');?>
</a></li>
</ul>
</div>
<?php }?>
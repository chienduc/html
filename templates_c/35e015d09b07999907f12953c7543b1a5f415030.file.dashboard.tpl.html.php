<?php /* Smarty version Smarty-3.0-RC2, created on 2016-11-04 09:42:05
         compiled from ".\templates\admin/dashboard.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:19597581bf57dca73d0-59573414%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '35e015d09b07999907f12953c7543b1a5f415030' => 
    array (
      0 => '.\\templates\\admin/dashboard.tpl.html',
      1 => 1462786904,
    ),
  ),
  'nocache_hash' => '19597581bf57dca73d0-59573414',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_truncate')) include 'C:\xampp\htdocs\projects\shop\classes\template\plugins\modifier.truncate.php';
?><?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('userTemplate')->value)."/coreheader.tpl.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('title','site'-'header'); echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
<div id="main">
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('userTemplate')->value)."/coreleft.tpl.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('title','site'-'top'-'menu'); echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
<div id="content">
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('userTemplate')->value)."/corenavigation.tpl.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('title','navigation'-'bar'); echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
<div class="innerContent">    	
<div class="contType2">


</div>
<div class="contType2">
<div class="infoType">
<h3><a href="?op=dashboard&doo=updateStatistic"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('require_update');?>
</a><?php echo $_smarty_tpl->getVariable('locale')->value->msg('statistics');?>
 <?php if ($_smarty_tpl->getVariable('statistic')->value){?><span style="font-weight:normal">[<?php echo $_smarty_tpl->getVariable('locale')->value->msg('update_at');?>
 <?php echo $_smarty_tpl->getVariable('statistic')->value['last_updated'];?>
]</span><?php }?></h3>
<table class="tblType" width="100%" border="0" cellspacing="0" cellpadding="0">
<?php if ($_smarty_tpl->getVariable('statistic')->value){?>
<tr><td><?php echo $_smarty_tpl->getVariable('locale')->value->msg('total_product');?>
:</td><td><?php echo number_format($_smarty_tpl->getVariable('statistic')->value['tp']);?>
</td></tr>
<tr class="bgType"><td><?php echo $_smarty_tpl->getVariable('locale')->value->msg('product_in_month');?>
:</td><td><?php echo number_format($_smarty_tpl->getVariable('statistic')->value['tpim']);?>
</td></tr>
<tr><td><?php echo $_smarty_tpl->getVariable('locale')->value->msg('order_in_year');?>
:</td><td><?php echo number_format($_smarty_tpl->getVariable('statistic')->value['oiy']);?>
</td></tr>
<tr class="bgType"><td><?php echo $_smarty_tpl->getVariable('locale')->value->msg('order_in_month');?>
:</td><td><?php echo number_format($_smarty_tpl->getVariable('statistic')->value['oim']);?>
</td></tr>
<tr><td><?php echo $_smarty_tpl->getVariable('locale')->value->msg('revenues_in_year');?>
:</td><td><?php echo number_format($_smarty_tpl->getVariable('statistic')->value['riy']);?>
 <?php echo $_smarty_tpl->getVariable('estore')->value->getProperty('currency');?>
</td></tr>
<tr class="bgType"><td><?php echo $_smarty_tpl->getVariable('locale')->value->msg('revenues_in_month');?>
:</td><td><?php echo number_format($_smarty_tpl->getVariable('statistic')->value['rim']);?>
 <?php echo $_smarty_tpl->getVariable('estore')->value->getProperty('currency');?>
</td></tr>
<tr><td><?php echo $_smarty_tpl->getVariable('locale')->value->msg('total_posts');?>
:</td><td><?php echo number_format($_smarty_tpl->getVariable('statistic')->value['te']);?>
</td></tr>
<tr class="bgType"><td><?php echo $_smarty_tpl->getVariable('locale')->value->msg('posts_in_month');?>
:</td><td><?php echo number_format($_smarty_tpl->getVariable('statistic')->value['eim']);?>
</td></tr>
<tr><td><?php echo $_smarty_tpl->getVariable('locale')->value->msg('total_access');?>
:</td><td><?php echo number_format($_smarty_tpl->getVariable('statistic')->value['hits']);?>
</td></tr>
<tr class="bgType"><td><?php echo $_smarty_tpl->getVariable('locale')->value->msg('access_in_month');?>
:</td><td><?php echo number_format($_smarty_tpl->getVariable('statistic')->value['him']);?>
</td></tr>
<tr><td><?php echo $_smarty_tpl->getVariable('locale')->value->msg('access_today');?>
:</td><td><?php echo number_format($_smarty_tpl->getVariable('statistic')->value['hid']);?>
</td></tr>
<?php }?>
<tr class="bgType"><td><a href="admin.php?op=dashboard&act=online"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('online_customers');?>
:</a></td><td><?php echo $_smarty_tpl->getVariable('onlineUsers')->value;?>
</td></tr>
</table>
</div>
<div class="infoType last">
<h3><?php echo $_smarty_tpl->getVariable('locale')->value->msg('product_viewed_most');?>
</h3>
<?php if ($_smarty_tpl->getVariable('topViewedProducts')->value){?>
<table class="tblType" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<th><?php echo $_smarty_tpl->getVariable('locale')->value->msg('numbers');?>
</th>
<th><?php echo $_smarty_tpl->getVariable('locale')->value->msg('code');?>
</th>
<th><?php echo $_smarty_tpl->getVariable('locale')->value->msg('sku');?>
</th>
<th><?php echo $_smarty_tpl->getVariable('locale')->value->msg('product_name');?>
</th>
<th class="right"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('price');?>
 (<?php echo $_smarty_tpl->getVariable('estore')->value->getProperty('currency');?>
)</th>
<th class="right"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('views');?>
</th>
</tr>
<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['no'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('topViewedProducts')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value){
 $_smarty_tpl->tpl_vars['no']->value = $_smarty_tpl->tpl_vars['product']->key;
?>
<tr<?php if ($_smarty_tpl->tpl_vars['no']->value%2==0){?> class="bgType"<?php }?>>
<td><?php echo $_smarty_tpl->tpl_vars['no']->value+1;?>
</td>
<td><a href="admin.php?op=manage&act=product&mod=edit&id=<?php echo $_smarty_tpl->tpl_vars['product']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['product']->value['id'];?>
</a></td>
<td><?php echo $_smarty_tpl->tpl_vars['product']->value['sku'];?>
</td>
<td><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['product']->value['name'],50);?>
</td>
<td class="right"><?php echo number_format($_smarty_tpl->tpl_vars['product']->value['price']);?>
</td>
<td class="right"><?php echo number_format($_smarty_tpl->tpl_vars['product']->value['viewed']);?>
</td>
</tr>
<?php }} ?>
</table>
<?php }else{ ?>
<?php echo $_smarty_tpl->getVariable('locale')->value->msg('non_product_in_database');?>
.
<?php }?>
</div>
</div>
</div>
</div>
</div>
</div>
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('userTemplate')->value)."/corefooter.tpl.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('title','site'-'footer'); echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
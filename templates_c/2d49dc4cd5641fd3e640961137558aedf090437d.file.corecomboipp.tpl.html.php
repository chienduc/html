<?php /* Smarty version Smarty-3.0-RC2, created on 2016-11-03 10:45:58
         compiled from ".\templates\admin/corecomboipp.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:24015581ab2f67120d4-27142075%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2d49dc4cd5641fd3e640961137558aedf090437d' => 
    array (
      0 => '.\\templates\\admin/corecomboipp.tpl.html',
      1 => 1462786904,
    ),
  ),
  'nocache_hash' => '24015581ab2f67120d4-27142075',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div class="item-pages">
<label for="pagenumber"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('items_per_page');?>
</label>
<select name="pagenumber" id="pagenumber" onchange="MM_jumpMenu('parent',this,0)">
<option<?php if ($_smarty_tpl->getVariable('ipp')->value==2){?> selected="selected"<?php }?> value="2" >2</option>
<option<?php if ($_smarty_tpl->getVariable('ipp')->value==10){?> selected="selected"<?php }?> value="10" >10</option>
<option<?php if ($_smarty_tpl->getVariable('ipp')->value==20){?> selected="selected"<?php }?> value="20" >20</option>
<option<?php if ($_smarty_tpl->getVariable('ipp')->value==30){?> selected="selected"<?php }?> value="30" >30</option>
<option<?php if ($_smarty_tpl->getVariable('ipp')->value==50){?> selected="selected"<?php }?> value="50" >50</option>
<option<?php if ($_smarty_tpl->getVariable('ipp')->value==100){?> selected="selected"<?php }?> value="100" >100</option>
<option<?php if ($_smarty_tpl->getVariable('ipp')->value==200){?> selected="selected"<?php }?> value="200" >200</option>
<option<?php if ($_smarty_tpl->getVariable('ipp')->value==500){?> selected="selected"<?php }?> value="500" >500</option>
<option<?php if ($_smarty_tpl->getVariable('ipp')->value==1000){?> selected="selected"<?php }?> value="1000" >1000</option>
</select>
</div>
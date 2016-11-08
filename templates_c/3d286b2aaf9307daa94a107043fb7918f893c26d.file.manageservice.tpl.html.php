<?php /* Smarty version Smarty-3.0-RC2, created on 2016-11-03 10:46:06
         compiled from ".\templates\admin/manageservice.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:5733581ab2fe92eee4-03969262%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3d286b2aaf9307daa94a107043fb7918f893c26d' => 
    array (
      0 => '.\\templates\\admin/manageservice.tpl.html',
      1 => 1462786907,
    ),
  ),
  'nocache_hash' => '5733581ab2fe92eee4-03969262',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('userTemplate')->value)."/coreheader.tpl.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('title','site'-'header'); echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
<div id="main" class="left-content">
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('userTemplate')->value)."/coreleft.tpl.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('title','site'-'top'-'menu'); echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
<div id="content">
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('userTemplate')->value)."/corenavigation.tpl.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('title','navigation'-'bar'); echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
<div class="innerContent">
<div id="tabContent" class="tabContent">
<?php if ($_smarty_tpl->getVariable('mod')->value=="list"){?>
<div class="search">
<form action="/<?php echo $_smarty_tpl->getVariable('aScript')->value;?>
" method="post" name="formSearch">
<fieldset>
<select name="pId">
<option value="">-----<?php echo $_smarty_tpl->getVariable('locale')->value->msg('filter_group');?>
-----</option>
<?php echo $_smarty_tpl->getVariable('categoryCombo')->value;?>

</select>
<input type="text" value="<?php echo $_smarty_tpl->getVariable('kw')->value;?>
" name="kw" />
<input type="submit" value="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('search');?>
" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('search');?>
" name="btnSearch" class="btnSearch" />
<input type="hidden" name="op" value="manage" />
<input type="hidden" name="act" value="service" />
<input type="hidden" name="mod" value="list" />
<input type="hidden" name="doo" value="search" />
<input type="hidden" name="lang" value="<?php echo $_smarty_tpl->getVariable('lang')->value;?>
" />
<input type="hidden" name="ipp" value="<?php echo $_smarty_tpl->getVariable('ipp')->value;?>
" />
<input type="hidden" name="sk" value="<?php echo $_smarty_tpl->getVariable('sk')->value;?>
" />
<input type="hidden" name="sd" value="<?php echo $_smarty_tpl->getVariable('sd')->value;?>
" />
<input type="hidden" name="pg" value="<?php echo $_smarty_tpl->getVariable('pg')->value;?>
" />
</fieldset>
</form>
</div>
<?php }elseif($_smarty_tpl->getVariable('mod')->value=="listcategory"){?>
<div class="search">
<form action="/<?php echo $_smarty_tpl->getVariable('aScript')->value;?>
" method="post" name="formSearch">
<fieldset>
<input type="text" value="<?php echo $_smarty_tpl->getVariable('kw')->value;?>
" name="kw" />
<input type="submit" value="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('search');?>
" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('search');?>
" name="btnSearch" class="btnSearch" />
<input type="hidden" name="op" value="manage" />
<input type="hidden" name="act" value="service" />
<input type="hidden" name="mod" value="listcategory" />
<input type="hidden" name="pId" value="<?php echo $_smarty_tpl->getVariable('pId')->value;?>
" />
<input type="hidden" name="doo" value="search" />
<input type="hidden" name="lang" value="<?php echo $_smarty_tpl->getVariable('lang')->value;?>
" />
<input type="hidden" name="ipp" value="<?php echo $_smarty_tpl->getVariable('ipp')->value;?>
" />
<input type="hidden" name="sk" value="<?php echo $_smarty_tpl->getVariable('sk')->value;?>
" />
<input type="hidden" name="sd" value="<?php echo $_smarty_tpl->getVariable('sd')->value;?>
" />
<input type="hidden" name="pg" value="<?php echo $_smarty_tpl->getVariable('pg')->value;?>
" />
</fieldset>
</form>
</div>
<?php }?>
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('userTemplate')->value)."/coretabs.tpl.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('title','show'-'tabs'); echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
<div class="tableContent">
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('userTemplate')->value)."/".($_smarty_tpl->getVariable('op')->value).($_smarty_tpl->getVariable('act')->value).($_smarty_tpl->getVariable('mod')->value).".tpl.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('title','show'-'tabs'); echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
</div>
</div>
</div>
</div>
</div>
</div>
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('userTemplate')->value)."/corefooter.tpl.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('title','site'-'footer'); echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
<?php /* Smarty version Smarty-3.0-RC2, created on 2016-11-03 10:46:06
         compiled from ".\templates\admin/manageservicelistcategory.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:32570581ab2feb69475-04190125%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'df0e8bcec1e5b9d78c8c3c76495062ab9f88f31e' => 
    array (
      0 => '.\\templates\\admin/manageservicelistcategory.tpl.html',
      1 => 1478143971,
    ),
  ),
  'nocache_hash' => '32570581ab2feb69475-04190125',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_smarty_tpl->getVariable('result_code')->value){?><div class="message"><?php echo $_smarty_tpl->getVariable('amessages')->value['result_code'][$_smarty_tpl->getVariable('result_code')->value];?>
</div><?php }?>
<?php if ($_smarty_tpl->getVariable('error_code')->value){?><div class="message2"><?php echo $_smarty_tpl->getVariable('amessages')->value['error_code'][$_smarty_tpl->getVariable('error_code')->value];?>
</div><?php }?>
<?php if ($_smarty_tpl->getVariable('pId')->value){?><p><a href="/<?php echo $_smarty_tpl->getVariable('aScript')->value;?>
?op=manage&act=service&mod=listcategory&pId=<?php echo $_smarty_tpl->getVariable('gfId')->value;?>
" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('back_group');?>
"><img src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/icon_parent.png" alt="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('back_group');?>
" width="32" height="32" />...<?php echo $_smarty_tpl->getVariable('locale')->value->msg('back');?>
</a></p><?php }?>
<?php if ($_smarty_tpl->getVariable('listItems')->value){?>
<form action="/<?php echo $_smarty_tpl->getVariable('aScript')->value;?>
" method="post" name="formType" id="formType">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<thead>
<tr>
<th><input type="checkbox" name="all" id="all" value="1" class="box3" onClick="toggleAllChecks('formType');" /></th>
<th><?php echo $_smarty_tpl->getVariable('locale')->value->msg('numbers');?>
</th>
<th><a href="javascript:void(0)" onClick="javascript:MM_sortField('parent','<?php echo $_smarty_tpl->getVariable('sk')->value;?>
','id','<?php if ($_smarty_tpl->getVariable('sd')->value=="DESC"){?>ASC<?php }else{ ?>DESC<?php }?>');" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('arrangement_by_code');?>
"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('code');?>
</a><?php if ($_smarty_tpl->getVariable('sk')->value=="id"){?><?php if ($_smarty_tpl->getVariable('sd')->value=="DESC"){?>&darr;<?php }else{ ?>&uarr;<?php }?><?php }?></th>
<th><a href="javascript:void(0)" onClick="javascript:MM_sortField('parent','<?php echo $_smarty_tpl->getVariable('sk')->value;?>
','slug','<?php if ($_smarty_tpl->getVariable('sd')->value=="DESC"){?>ASC<?php }else{ ?>DESC<?php }?>');" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('arrangement_by_slug');?>
"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('slug');?>
</a><?php if ($_smarty_tpl->getVariable('sk')->value=="slug"){?><?php if ($_smarty_tpl->getVariable('sd')->value=="DESC"){?>&darr;<?php }else{ ?>&uarr;<?php }?><?php }?></th>
<th><a href="javascript:void(0)" onClick="javascript:MM_sortField('parent','<?php echo $_smarty_tpl->getVariable('sk')->value;?>
','name','<?php if ($_smarty_tpl->getVariable('sd')->value=="DESC"){?>ASC<?php }else{ ?>DESC<?php }?>');" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('arrangement_by_name');?>
"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('name');?>
</a><?php if ($_smarty_tpl->getVariable('sk')->value=="name"){?><?php if ($_smarty_tpl->getVariable('sd')->value=="DESC"){?>&darr;<?php }else{ ?>&uarr;<?php }?><?php }?></th>
<th><a href="javascript:void(0)" onClick="javascript:MM_sortField('parent','<?php echo $_smarty_tpl->getVariable('sk')->value;?>
','position','<?php if ($_smarty_tpl->getVariable('sd')->value=="DESC"){?>ASC<?php }else{ ?>DESC<?php }?>');" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('arrangement_by_position');?>
"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('position');?>
</a><?php if ($_smarty_tpl->getVariable('sk')->value=="position"){?><?php if ($_smarty_tpl->getVariable('sd')->value=="DESC"){?>&darr;<?php }else{ ?>&uarr;<?php }?><?php }?></th>

<th><a href="javascript:void(0)" onClick="javascript:MM_sortField('parent','<?php echo $_smarty_tpl->getVariable('sk')->value;?>
','status','<?php if ($_smarty_tpl->getVariable('sd')->value=="DESC"){?>ASC<?php }else{ ?>DESC<?php }?>');" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('arrangement_by_status');?>
"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('system_status');?>
</a><?php if ($_smarty_tpl->getVariable('sk')->value=="status"){?><?php if ($_smarty_tpl->getVariable('sd')->value=="DESC"){?>&darr;<?php }else{ ?>&uarr;<?php }?><?php }?></th>
<th><?php echo $_smarty_tpl->getVariable('locale')->value->msg('actions');?>
</th>
</tr>
</thead>
<tbody>
<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['no'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('listItems')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
 $_smarty_tpl->tpl_vars['no']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
<tr<?php if ($_smarty_tpl->tpl_vars['no']->value%2==0){?> class="bgType"<?php }?>>
<td><input type="checkbox" name="ids[]" id="ids[]" value="<?php echo $_smarty_tpl->getVariable('item')->value->getId();?>
" class="box3" /></td>
<td><?php echo $_smarty_tpl->getVariable('startNum')->value+$_smarty_tpl->tpl_vars['no']->value;?>
</td>
<td><a class="underline" href="/<?php echo $_smarty_tpl->getVariable('aScript')->value;?>
?op=manage&act=service&mod=listcategory&pId=<?php echo $_smarty_tpl->getVariable('item')->value->getId();?>
" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('view_list_group_child');?>
"><?php echo $_smarty_tpl->getVariable('item')->value->getId();?>
</a></td>
<td><a href="/<?php echo $_smarty_tpl->getVariable('aScript')->value;?>
?op=manage&act=service&mod=listcategory&pId=<?php echo $_smarty_tpl->getVariable('item')->value->getId();?>
" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('view_list_group_child');?>
"><?php echo $_smarty_tpl->getVariable('item')->value->getSlug();?>
</a></td>
<td><a href="/<?php echo $_smarty_tpl->getVariable('aScript')->value;?>
?op=manage&act=service&mod=listcategory&pId=<?php echo $_smarty_tpl->getVariable('item')->value->getId();?>
" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('view_list_group_child');?>
"><?php echo $_smarty_tpl->getVariable('item')->value->getName();?>
</a></td>
<td><input type="text" name="positions[<?php echo $_smarty_tpl->getVariable('item')->value->getId();?>
]" value="<?php echo $_smarty_tpl->getVariable('item')->value->getPosition();?>
" /></td>

<td><?php echo $_smarty_tpl->getVariable('item')->value->getStatusTextBackend();?>
</td>
<td>

<a href="/<?php echo $_smarty_tpl->getVariable('aScript')->value;?>
?op=manage&act=service&mod=editcategory&id=<?php echo $_smarty_tpl->getVariable('item')->value->getId();?>
&lang=<?php echo $_smarty_tpl->getVariable('lang')->value;?>
" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('edit');?>
"><img src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/ico_edit.png" alt="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('edit');?>
" width="16" height="16" /></a>
<a href="javascript:formSubmit('formType','listcategory','enable',<?php echo $_smarty_tpl->getVariable('item')->value->getId();?>
);" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('enable');?>
"><img src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/ico_enable.png" alt="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('enable');?>
" width="16" height="16" /></a>
<a href="javascript:formSubmit('formType','listcategory','disable',<?php echo $_smarty_tpl->getVariable('item')->value->getId();?>
);" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('disable');?>
"><img src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/ico_disable.png" alt="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('disable');?>
" width="16" height="16" /></a>
<a href="javascript:formSubmit('formType','listcategory','delete',<?php echo $_smarty_tpl->getVariable('item')->value->getId();?>
);" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('delete');?>
"><img src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/ico_delete.png" alt="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('delete');?>
" width="16" height="16" /></a>


</td>
</tr>
<?php }} ?>
</tbody>
</table>
<div class="paging">
<p class="listBtn">
<a title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('button_update');?>
" href="javascript:void(0);" onClick="javascript:formSubmit('formType','listcategory','changeposition','0');;"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('button_update');?>
</a>
<a title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('button_enable');?>
" href="javascript:void(0);" onClick="javascript:formSubmit('formType','listcategory','enable','0');;"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('button_enable');?>
</a>
<a title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('button_disable');?>
" href="javascript:void(0);" onClick="javascript:formSubmit('formType','listcategory','disable','0');"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('button_disable');?>
</a>
<a title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('button_delete');?>
" href="javascript:void(0);" onClick="javascript:formSubmit('formType','listcategory','delete','0');"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('button_delete');?>
</a>
</p>
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('userTemplate')->value)."/corepager.tpl.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('title','pager'); echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
</div>
<div class="infoType2">
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('userTemplate')->value)."/corecomboipp.tpl.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('title','ipp'); echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
<div class="listCategory">
<select name="parent_id">
<option value="0" selected="selected">----- <?php echo $_smarty_tpl->getVariable('locale')->value->msg('select_group');?>
 -----</option>
<?php echo $_smarty_tpl->getVariable('categoryCombo')->value;?>

</select>
<input type="button" value="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('button_move');?>
" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('button_move');?>
" class="btnSubmit2" name="btnSubmit2" onClick="javascript:formSubmit('formType','listcategory','changegroup','0');" />
</div>
</div>
<input type="hidden" name="op" value="manage" />
<input type="hidden" name="act" value="service" />
<input type="hidden" name="mod" value="listcategory" />
<input type="hidden" name="pId" value="<?php echo $_smarty_tpl->getVariable('pId')->value;?>
" />
<input type="hidden" name="doo" value="" />
<input type="hidden" name="kw" value="<?php echo $_smarty_tpl->getVariable('kw')->value;?>
" />
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
<input type="hidden" name="id" value="" />
</form>
<?php }else{ ?>
<?php echo $_smarty_tpl->getVariable('locale')->value->msg('no_record');?>

<form action="/<?php echo $_smarty_tpl->getVariable('aScript')->value;?>
" method="post" name="formType" id="formType">
<input type="hidden" name="op" value="manage" />
<input type="hidden" name="act" value="service" />
<input type="hidden" name="mod" value="listcategory" />
<input type="hidden" name="doo" value="" />
</form>
<?php }?>
</div>
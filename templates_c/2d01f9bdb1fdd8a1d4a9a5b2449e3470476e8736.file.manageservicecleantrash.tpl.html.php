<?php /* Smarty version Smarty-3.0-RC2, created on 2016-11-03 10:45:14
         compiled from ".\templates\admin/manageservicecleantrash.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:13566581ab2ca0498f7-54383531%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2d01f9bdb1fdd8a1d4a9a5b2449e3470476e8736' => 
    array (
      0 => '.\\templates\\admin/manageservicecleantrash.tpl.html',
      1 => 1478144143,
    ),
  ),
  'nocache_hash' => '13566581ab2ca0498f7-54383531',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div class="contType">
<h2><?php echo $_smarty_tpl->getVariable('locale')->value->msg('notes');?>
:</h2><?php echo $_smarty_tpl->getVariable('locale')->value->msg('notes_clean_trash');?>

</div>
<form name="formClean" id="formClean" action="/<?php echo $_smarty_tpl->getVariable('aScript')->value;?>
" method="post">
<fieldset>
<p><input type="checkbox" name="categories" checked="checked" value="1" style="display: none"/></p>
<input type="checkbox" name="items" checked="checked" value="1" style="display: none" /> </fieldset>
</p>
<input type="hidden" name="op" value="manage" />
<input type="hidden" name="act" value="service" />
<input type="hidden" name="mod" value="list" />
<input type="hidden" name="doo" value="cleantrash" />
<input type="hidden" name="lang" value="<?php echo $_smarty_tpl->getVariable('lang')->value;?>
" />
<p class="btn"><input type="submit" value="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('button_ok');?>
" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('button_ok');?>
" name="btnSubmit2" />
<input type="button" onClick="javascript:formSubmit('formClean','list','cancel',0);" value="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('button_cancel');?>
" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('button_cancel');?>
" name="btnCancel" />
</p>
</fieldset>
</form>
</div>
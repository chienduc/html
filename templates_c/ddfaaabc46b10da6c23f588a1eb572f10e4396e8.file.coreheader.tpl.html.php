<?php /* Smarty version Smarty-3.0-RC2, created on 2016-11-03 10:45:12
         compiled from ".\templates\admin/coreheader.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:32189581ab2c885f8b2-14659252%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ddfaaabc46b10da6c23f588a1eb572f10e4396e8' => 
    array (
      0 => '.\\templates\\admin/coreheader.tpl.html',
      1 => 1462786904,
    ),
  ),
  'nocache_hash' => '32189581ab2c885f8b2-14659252',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="index, follow" />
<meta name="keywords" content="DeraCMS, e-commerce solution, administration page" />
<meta name="description" content="DeraCMS for E-store" />
<title><?php echo $_smarty_tpl->getVariable('locale')->value->msg('manage_website');?>
</title>
<link rel="stylesheet" type="text/css" href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/css/screen.css" media="all" />
<link rel="stylesheet" type="text/css" href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/css/add.css" media="all" />
<script type="text/javascript" src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/scripts/mootools.js"></script>

<?php if ($_smarty_tpl->getVariable('ckEditor')->value){?><script type="text/javascript" src="/ckeditor/ckeditor.js"></script><?php }?>
<script type="text/javascript" src="/js/swfobject.js"></script>
<?php if ($_smarty_tpl->getVariable('selectPhoto')->value){?>
<script type="text/javascript">
function init(){
	var els=document.getElementsByTagName('*');
	var reg=/(^| )selectPhoto($| )/;
	for(i in els){
		var el=els[i];
		if(reg.test(el.className))el.onclick=function(){
			window.SetUrl=(function(id){
				return function(value){
					value=value.replace(/[a-z]*:\/\/[^\/]*/,'');
					document.getElementById(id).value=value;
				}
			})(this.id);
			var photo_url='/plugins/filemanager/browse.php?type=images';
			window.open(photo_url,'selectPhoto','modal,width=600,height=400');
		}
	}
}
</script>
<?php }?>

<script type="text/javascript">
   function confirmAction() {
        return confirm("Do you want intall this addon?");
      }
   function confirmUninstall() {
        return confirm("Do you want unintall this addon?");
      }
</script>

</head>
<body<?php if ($_smarty_tpl->getVariable('selectPhoto')->value){?> onload="init()"<?php }?>>
<div id="wrapper">
<div id="header">
<?php if ($_smarty_tpl->getVariable('estore')->value->getProperty('admin_logo')){?>
<h1><a href="/" title="Home page" target="_blank"><img src="<?php echo $_smarty_tpl->getVariable('estore')->value->getProperty('admin_logo');?>
" width="181" height="51" alt="Logo" /></a></h1>
<?php }else{ ?>
<h1><a href="/<?php echo $_smarty_tpl->getVariable('aScript')->value;?>
?op=system&act=config&mod=general"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('update_admin_logo');?>
</a></h1>
<?php }?>
<div class="topnav">
<ul>
<?php if ($_smarty_tpl->getVariable('authUser')->value){?><li><a href="/<?php echo $_smarty_tpl->getVariable('aScript')->value;?>
?op=profile" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('system_profile');?>
"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('system_profile');?>
</a></li><?php }?>
<li><a href="http://forum.deracms.com" target="_blank" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('forum');?>
"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('forum');?>
</a></li>
<li><a href="http://wiki.deracms.com" target="_blank" title="<?php echo $_smarty_tpl->getVariable('locale')->value->msg('helpdesk');?>
"><?php echo $_smarty_tpl->getVariable('locale')->value->msg('helpdesk');?>
</a></li>
</ul>
</div>
</div>    
<?php $_template = new Smarty_Internal_Template(($_smarty_tpl->getVariable('userTemplate')->value)."/coretopmenu.tpl.html", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
$_template->assign('title','site'-'top'-'menu'); echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
<?php /* Smarty version Smarty-3.0-RC2, created on 2016-11-04 09:28:43
         compiled from "C:\xampp\htdocs\projects\shop/templates/son//footer.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:22678581bf25bc616c6-45383666%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2fd2b9338a4d4611448c391f1b302f5a42debf7d' => 
    array (
      0 => 'C:\\xampp\\htdocs\\projects\\shop/templates/son//footer.tpl.html',
      1 => 1464400797,
    ),
  ),
  'nocache_hash' => '22678581bf25bc616c6-45383666',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
 <div id="footer_top_s" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row">
                	<!-- top footer -- footer -->
<div id="footer_top" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row">
        
        
          <?php if ($_smarty_tpl->getVariable('listMenus_5')->value){?>
           <?php  $_smarty_tpl->tpl_vars['menuMain'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('listMenus_5')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['menuMain']->key => $_smarty_tpl->tpl_vars['menuMain']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['menuMain']->key;
?> 
            <?php $_smarty_tpl->assign('menuChildren',$_smarty_tpl->getVariable('menuMain')->value->getChildren(),null,null);?>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 <?php if ($_smarty_tpl->tpl_vars['i']->value%2!=0){?>cusdisnoe<?php }?>">
            <div class="row" style="margin:0px -10px">
                <div class="panel panel-default cus_bottom">
                  <!-- Default panel contents -->
                  <div class="panel-heading"><?php echo $_smarty_tpl->getVariable('menuMain')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</div>
                  <?php if ($_smarty_tpl->getVariable('menuChildren')->value){?>
                  <ul class="list-group active-mobile">
                   <?php  $_smarty_tpl->tpl_vars['menuchildren'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['j'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('menuChildren')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['menuchildren']->key => $_smarty_tpl->tpl_vars['menuchildren']->value){
 $_smarty_tpl->tpl_vars['j']->value = $_smarty_tpl->tpl_vars['menuchildren']->key;
?> 
                    <li class="list-group-item"><a href="<?php echo $_smarty_tpl->getVariable('menuchildren')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('menuchildren')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><?php echo $_smarty_tpl->getVariable('menuchildren')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</a></li>
                    <?php }} ?>
                  </ul>
                  <?php }?>
                </div>
            </div>
        </div>
        <?php }} ?>
        <?php }?>
        
        
        
    </div>
</div>
<!-- footer -->
<footer class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="row">
    	<div class="panel panel-default">
        <div class="panel-heading cus_footer_dis">Thông tin công ty</div>
		<div class="panel-body active-mobile" style="background:#E4EBF2">
    	<div class="col-lg-3 col-md-3"></div>
        <div class="col-lg-7 col-md-6 col-sm-12 col-xs-12">
            <div class="panel panel-default" style="border-color:white;box-shadow: 0 0px 0px rgba(0,0,0,.05) !important;margin-bottom:0px;background:#E4EBF2">
              <div class="panel-body" style="text-align:center">
                <span style="text-transform:capitalize;color: #0B2A63;
    font-size: 25px;
    font-weight: bold;"><b><?php echo $_smarty_tpl->getVariable('estore')->value->getCompany();?>
</b></span><br/>
                 <span style=" font-size:18px">ĐC: <?php echo $_smarty_tpl->getVariable('estore')->value->getAddress();?>
</span><br/>
               
                <span style=" font-size:18px">Email: <a href="mailto:<?php echo $_smarty_tpl->getVariable('estore')->value->getEmail();?>
" title="<?php echo $_smarty_tpl->getVariable('estore')->value->getEmail();?>
"><?php echo $_smarty_tpl->getVariable('estore')->value->getEmail();?>
</a></span><br/>
                <span style=" font-size:18px">MST : <?php echo $_smarty_tpl->getVariable('estore')->value->getCell();?>
</span><br/>
                 <span style=" font-size:18px">Hotline : <b style="color:red; font-weight:bold; font-size:20px"><?php echo $_smarty_tpl->getVariable('estore')->value->getTel();?>
</b></span>
              </div>
            </div>
        </div>
     
        
        
        
		</div>
        </div>
    </div>
</footer>
                </div>
            </div>
            <!-- footer -->
        </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/js/script.js"></script>
    
    <script>
		jQuery(document).ready(function($){
			$('#hidden-relav').hover(
				 function(){ $('#hiddenmenu').addClass('active-menu')},
				 function(){ $('#hiddenmenu').removeClass('active-menu')}
			)
			$('#hidden-relav').hover(
				 function(){ $('#cusnsxmenu').addClass('nsx-menu')},
				 function(){ $('#cusnsxmenu').removeClass('nsx-menu')})
		   	/*click menu top*/
			jQuery('#headermobile .cusmenu-click #menu_mobile_ne').append('<span class="toggles_menu"></span>');
			jQuery('#headermobile .cusmenu-click #menu_mobile_ne').on("click", function(){
				if (jQuery(this).find('> span').attr('class') == 'toggles_menu open-menu') { jQuery(this).find('> span').removeClass('open-menued').parents('.cusmenu-click').find('.ne_show_menu').slideToggle(); }
				else {
					jQuery(this).find('> span').addClass('open-menu').parents('.cusmenu-click').find('.ne_show_menu').slideToggle();
				}
			});
			/*click menu category 1*/
			jQuery('#leftcontent .menuhoverhidden .title-custome').append('<span class="toggles"></span>');
			jQuery('#leftcontent .menuhoverhidden .title-custome').on("click", function(){
				if (jQuery(this).find('> span').attr('class') == 'toggles open') { jQuery(this).find('> span').removeClass('opened').parents('.menuhoverhidden').find('.active-mobile').slideToggle(); }
				else {
					jQuery(this).find('> span').addClass('open').parents('.menuhoverhidden').find('.active-mobile').slideToggle();
				}
			});
			/*click menu category 2*/
			jQuery('#leftcontent .cuscate .panel-title').append('<span class="toggle"></span>');
			jQuery('#leftcontent .cuscate .panel-title').on("click", function(){
				if (jQuery(this).find('> span').attr('class') == 'toggle opened') { jQuery(this).find('> span').removeClass('opened').parents('.cuscate').find('.active-mobile').slideToggle(); }
				else {
					jQuery(this).find('> span').addClass('opened').parents('.cuscate').find('.active-mobile').slideToggle();
				}
			});
			/*/ click menu support /*/
			jQuery('#rightcontent .cuscate .panel-title').append('<span class="toggle"></span>');
			jQuery('#rightcontent .cuscate .panel-title').on("click", function(){
				if (jQuery(this).find('> span').attr('class') == 'toggle opened') { jQuery(this).find('> span').removeClass('opened').parents('.cuscate').find('.active-mobile').slideToggle(); }
				else {
					jQuery(this).find('> span').addClass('opened').parents('.cuscate').find('.active-mobile').slideToggle();
				}
			});
			/*/click menu footer/*/
			jQuery('#footer_top_s .panel-default .panel-heading').append('<span class="toggles"></span>');
			jQuery('#footer_top_s .panel-default .panel-heading').on("click", function(){
				if (jQuery(this).find('> span').attr('class') == 'toggles open') { jQuery(this).find('> span').removeClass('open').parents('.panel-default').find('.active-mobile').slideToggle(); }
				else {
					jQuery(this).find('> span').addClass('open').parents('.panel-default').find('.active-mobile').slideToggle();
				}
			});
			
		});
	</script>
    <script language="javascript">
    	var ww = document.body.clientWidth;
		$(document).ready(function() {
			$(".nav li a").each(function() {
				if ($(this).next().length > 0) {
					$(this).addClass("parent");
				};
			})
			
			$(".toggleMenu").click(function(e) {
				e.preventDefault();
				$(this).toggleClass("active");
				$(".nav").toggle();
			});
			adjustMenu();
		})
		
		$(window).bind('resize orientationchange', function() {
			ww = document.body.clientWidth;
			adjustMenu();
		});
		
		var adjustMenu = function() {
			if (ww < 768) {
				$(".toggleMenu").css("display", "inline-block");
				if (!$(".toggleMenu").hasClass("active")) {
					$(".nav").hide();
				} else {
					$(".nav").show();
				}
				$(".nav li").unbind('mouseenter mouseleave');
				$(".nav li a.parent").unbind('click').bind('click', function(e) {
					// must be attached to anchor element to prevent bubbling
					e.preventDefault();
					$(this).parent("li").toggleClass("hover");
				});
			} 
			else if (ww >= 768) {
				$(".toggleMenu").css("display", "none");
				$(".nav").show();
				$(".nav li").removeClass("hover");
				$(".nav li a").unbind('click');
				$(".nav li").unbind('mouseenter mouseleave').bind('mouseenter mouseleave', function() {
					// must be attached to li so that mouseleave is not triggered when hover over submenu
					$(this).toggleClass('hover');
				});
			}
		}
    </script>
     
    <script type="text/javascript" src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/js/jquery.nivo.slider.js"></script> 
    <script type="text/javascript" src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/js/jquery.fancybox.js"></script>
	<script type="text/javascript" src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/js/jquery.fancybox-buttons.js"></script>
     
    <script type="text/javascript">
        $(document).ready(function() {
            $('.fancybox-buttons').fancybox({
                openEffect  : 'none',
                closeEffect : 'none',
                prevEffect : 'none',
                nextEffect : 'none',
                closeBtn  : false,
                helpers : {
                    title : {
                        type : 'inside'
                    },
                    buttons	: {}
                },
                afterLoad : function() {
                    this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
                }
            });
        });
    </script>
    <script>
    // You can also use "$(window).load(function() {"
    $(function () {
      // Slideshow 1
      $("#slider1").responsiveSlides({
        //maxwidth: 100%,
        speed: 400
      });
    });
  </script>
  
  </body>
</html>
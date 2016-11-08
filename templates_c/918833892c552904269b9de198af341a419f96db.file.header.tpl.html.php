<?php /* Smarty version Smarty-3.0-RC2, created on 2016-11-08 16:33:49
         compiled from "C:\xampp\htdocs\projects\shop/templates/shop//header.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:2479858219bfd92d0a4-44072507%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '918833892c552904269b9de198af341a419f96db' => 
    array (
      0 => 'C:\\xampp\\htdocs\\projects\\shop/templates/shop//header.tpl.html',
      1 => 1478570634,
    ),
  ),
  'nocache_hash' => '2479858219bfd92d0a4-44072507',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_strip_tags')) include 'C:\xampp\htdocs\projects\shop\classes\template\plugins\modifier.strip_tags.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" itemscope itemtype="http://schema.org/Product">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php if ($_smarty_tpl->getVariable('pageTitle')->value){?><?php echo smarty_modifier_strip_tags($_smarty_tpl->getVariable('pageTitle')->value);?>
 - <?php }?><?php echo $_smarty_tpl->getVariable('estore')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</title>
    <meta name="keywords" content="<?php if ($_smarty_tpl->getVariable('pageKeywords')->value){?><?php echo smarty_modifier_strip_tags($_smarty_tpl->getVariable('pageKeywords')->value);?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('estore')->value->getKeywords($_smarty_tpl->getVariable('lang')->value);?>
<?php }?>" />
    <meta name="description" content="<?php if ($_smarty_tpl->getVariable('pageDescription')->value){?><?php echo smarty_modifier_strip_tags($_smarty_tpl->getVariable('pageDescription')->value);?>
<?php }else{ ?><?php echo $_smarty_tpl->getVariable('estore')->value->getDescription($_smarty_tpl->getVariable('lang')->value);?>
<?php }?>" />
    <meta name="robots" content="INDEX,FOLLOW" />
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1" />
    <meta name="theme-color" content="#ffffff">


    <!-- criteo integration start -->
    <script type="text/javascript" src="http://static.criteo.net/js/ld/ld.js" async="true"></script>
    <!-- criteo intgration end -->


    <link rel="stylesheet" type="text/css" href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/css/b0bdb4136c22f5dff8ef3fbccda0e198.css" media="all" />
    <link rel="stylesheet" type="text/css" href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/css/01ff5530feac6b07b69ca07fe73bd823.css" media="print" />
    <script type="text/javascript" src="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/js/8feb81926045a77ffe61cbe61f0b9c69.js"></script>
  
    
    <script type="text/javascript">
        //<![CDATA[
        Mage.Cookies.path     = '/';
        Mage.Cookies.domain   = '.www.castlery.com';
        var CSLR = CSLR || {};
        CSLR.skin_url = 'https://www.castlery.com/skin/frontend/castlery/default/';
        CSLR.base_url = 'https://www.castlery.com/';

        CSLR.is_loggedin = false;
        //]]>
    </script>



    <script type="text/javascript">
        //<![CDATA[
        optionalZipCountries = ["HK","IE","MO","PA"];
        //]]>
    </script>



    <script type="text/javascript">
        //<![CDATA[
        Searchanise = {};
        Searchanise.host        = 'http://www.searchanise.com';
        Searchanise.api_key     = '7C8r7e1I5k';
        Searchanise.SearchInput = '#search';

        Searchanise.AutoCmpParams = {};
        Searchanise.AutoCmpParams.union = {}; Searchanise.AutoCmpParams.union.price = {}; Searchanise.AutoCmpParams.union.price.min = 'se_price_0';
        Searchanise.AutoCmpParams.restrictBy = {};
        Searchanise.AutoCmpParams.restrictBy.status = '1';
        Searchanise.AutoCmpParams.restrictBy.visibility = '4';
        Searchanise.AutoCmpParams.restrictBy.is_in_stock = '1';Searchanise.AutoCmpParams.restrictBy.tag_ids = '!33';

        Searchanise.options = {};
        Searchanise.AdditionalSearchInputs = '#name,#description,#sku';

        Searchanise.options.PriceFormat = {
            decimals_separator:  '.',
            thousands_separator: ',',
            symbol:              '$',

            decimals: '2',
            rate:     '1',
            after:     false
        };

        (function() {
            var __se = document.createElement('script');
            __se.src = 'https://www.searchanise.com/widgets/v1.0/init.js';
            __se.setAttribute('async', 'true');
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(__se, s);
        })();
        //]]>
    </script>
    <script type="text/javascript">//<![CDATA[
    var Translator = new Translate([]);
    //]]></script>

    


</head>

<body class=" cms-index-index cms-castlery">
<div class="sidemenu__wrapper">
    <div class="sidemenu__mask"></div>
    <div class="sidemenu">
        <ul class="sidemenu__lvl1">
            <li><a href="https://www.castlery.com/new-arrivals.html">New Arrivals</a></li>
            <li>
                <a>
                    Living Room
                    <svg class="sidemenu__lvl1__expandIcon">
                        <use xlink:href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/svg-icons.svg#plus"></use>
                    </svg>
                </a>
                <ul class="sidemenu__lvl2">
                    <li>
                        <a href="https://www.castlery.com/all-living-room/sofas-loveseats.html">Sofas + Loveseat</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-living-room/sofa-beds.html">Sofa Beds</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-living-room/sectional-sofas.html">Sectional Sofas</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-living-room/armchairs.html">Armchairs</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-living-room/ottomans-poufs.html">Ottomans & Pouf</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-living-room/coffee-side-tables.html">Coffee & Side Tables</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-living-room/tv-consoles.html">TV Consoles</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-living-room/shelves-cabinets.html">Shelves & Cabinets</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-bundles/living-room-bundles.html">Living Room Bundles</a>
                    </li>
                    <li>
                        <a class="highlight" href="https://www.castlery.com/all-living-room.html">All Living Room</a>
                    </li>
                </ul>
            </li>
            <li>
                <a>
                    Dining Room
                    <svg class="sidemenu__lvl1__expandIcon">
                        <use xlink:href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/svg-icons.svg#plus"></use>
                    </svg>
                </a>
                <ul class="sidemenu__lvl2">
                    <li>
                        <a href="https://www.castlery.com/all-dining-room/dining-tables.html">Dining Table</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-dining-room/dining-chairs.html">Dining Chairs</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-dining-room/dining-benches.html">Dining Benches</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-dining-room/bar-stools.html">Stools & Barstools</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-dining-room/storage-shelves.html">Storage & Shelves</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-bundles/dining-room-bundles.html">Dining Room Bundles</a>
                    </li>
                    <li>
                        <a class="highlight" href="https://www.castlery.com/all-dining-room.html">All Dining Room</a>
                    </li>
                </ul>
            </li>
            <li>
                <a>
                    Bedroom
                    <svg class="sidemenu__lvl1__expandIcon">
                        <use xlink:href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/svg-icons.svg#plus"></use>
                    </svg>
                </a>
                <ul class="sidemenu__lvl2">
                    <li>
                        <a href="https://www.castlery.com/all-bedroom/queen-size-bedframes.html">Queen Size Bedframes</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-bedroom/king-size-bedframes.html">King Size Bedframes</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-bedroom/bedroom-benches.html">Bedroom Benches</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-bedroom/bedside-storage.html">Bedside Storage</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-bedroom/mattresses.html">Mattresses</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-bedroom/mirrors.html">Mirrors</a>
                    </li>
                    <!--<li>
                        <a href="https://www.castlery.com/all-bedroom/dressing-tables.html">Dressing Tables</a>
                    </li> -->
                    <li>
                        <a href="https://www.castlery.com/all-bundles/bedroom-bundles.html">Bedroom Bundles</a>
                    </li>
                    <li>
                        <a class="highlight" href="https://www.castlery.com/all-bedroom.html">All Bedroom</a>
                    </li>
                </ul>
            </li>
            <!--
            <li>
                <a>
                    Entryway
                    <svg class="sidemenu__lvl1__expandIcon">
                        <use xlink:href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/svg-icons.svg#plus"></use>
                    </svg>
                </a>
                <ul class="sidemenu__lvl2">
                    <li>
                        <a href="https://www.castlery.com/all-entryway/seating.html">Seating</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-entryway/storage.html">Storage</a>
                    </li>
                    <li>
                        <a class="highlight" href="https://www.castlery.com/all-entryway.html">All Entryway</a>
                    </li>
                </ul>
            </li>
            -->
            <li>
                <a>
                    Home Office
                    <svg class="sidemenu__lvl1__expandIcon">
                        <use xlink:href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/svg-icons.svg#plus"></use>
                    </svg>
                </a>
                <ul class="sidemenu__lvl2">
                    <li>
                        <a href="https://www.castlery.com/all-home-office/study-tables.html">Study Tables</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-home-office/office-chairs.html">Office Chairs</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-home-office/office-storage.html">Office Storage</a>
                    </li>
                    <!--<li>
                        <a href="https://www.castlery.com/all-bundles/home-office-bundles.html">Home Office Bundles</a>
                    </li>-->
                    <li>
                        <a href="https://www.castlery.com/all-home-office.html" class="highlight">All Home Office</a>
                    </li>
                </ul>
            </li>
            <li>
                <a>
                    Bundle &amp; Save
                    <svg class="sidemenu__lvl1__expandIcon">
                        <use xlink:href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/svg-icons.svg#plus"></use>
                    </svg>
                </a>
                <ul class="sidemenu__lvl2">
                    <li>
                        <a href="https://www.castlery.com/all-bundles/living-room-bundles.html">Living Room Bundles</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-bundles/dining-room-bundles.html">Dining Room Bundle</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/all-bundles/bedroom-bundles.html">Bedroom Bundle</a>
                    </li>
                    <li>
                        <a class="highlight" href="https://www.castlery.com/all-bundles.html">All Bundle</a>
                    </li>
                </ul>
            </li>
            <li>
                <a>
                    Lighting
                    <svg class="sidemenu__lvl1__expandIcon">
                        <use xlink:href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/svg-icons.svg#plus"></use>
                    </svg>
                </a>
                <ul class="sidemenu__lvl2">
                    <li>
                        <a href="https://www.castlery.com/lighting/pendant-light.html">Pendant Lights</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/lighting/floor-lamp.html">Floor Lamps</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/lighting/table-lamp.html">Table Lamps</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/lighting/wall-lamp.html">Wall Lamps</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/lighting/lightbulbs.html">Lightbulbs</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/lighting.html">All Lighting</a>
                    </li>
                </ul>
            </li>
            <li>
                <a>
                    Accessories
                    <svg class="sidemenu__lvl1__expandIcon">
                        <use xlink:href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/svg-icons.svg#plus"></use>
                    </svg>
                </a>
                <ul class="sidemenu__lvl2">
                    <li>
                        <a href="https://www.castlery.com/accessories/rugs.html">Rugs</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/accessories/poufs.html">Poufs</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/accessories/fabric-covers.html">Fabric Covers</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/accessories/throw-pillows.html">Throw Pillows</a>
                    </li>
                    <li>
                        <a href="https://www.castlery.com/accessories.html">All Accessories</a>
                    </li>
                </ul>
            </li>
            <li><a href="https://www.castlery.com/sale.html">Sale</a></li>
            <li><a href="https://www.castlery.com/customization.html">Customization</a></li>
            <li><a href="https://www.castlery.com/reviews">Customer Review</a></li>
        </ul>
        <div class="sidemenu__divider"></div>
        <ul class="sidemenu__sub">
            <li style="padding-right: 10px"  class="first last" ><a href="https://www.castlery.com/customer/account/login/referer/aHR0cHM6Ly93d3cuY2FzdGxlcnkuY29tLw,,/" title="Log In" >Signup / Login</a></li>
            <li><a href="https://www.castlery.com/contact-us">Help</a></li>
            <li><a href="tel:+6567445333">+65 67445333</a></li>
            <li class="sidemenu__sub__divider"><a href="https://www.castlery.com/our-story">Our Story</a></li>
            <li><a href="https://www.castlery.com/book-appointment">Visit the Studio</a></li>
            <li><a href="http://castlery.workable.com">Careers</a></li>
            <li><a href="https://www.castlery.com/delivery">Delivery Info</a></li>
            <li><a href="https://www.castlery.com/returns-exchanges">Return Policy</a></li>
        </ul>
    </div>
    <div class="sidemenu__close">
        <svg class="sidemenu__close__icon">
            <use xlink:href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/svg-icons.svg#close"></use>
        </svg>
    </div>
</div>                    <!-- Facebook Pixel Code -->







<script>dataLayer = [{"visitorLoginState":"Logged out","visitorType":"NOT LOGGED IN","visitorLifetimeValue":0,"visitorExistingCustomer":"No"}];</script>




<div class="wrapper">


    <div class="page page-content">
        <div class="header-container">
            <div class="header">
                <div class="header-content">
                    <div class="header-phone-menu">
                        <svg class="header-menu-bar sidemenu-btn">
                            <use xlink:href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/svg-icons.svg#menubar"></use>
                        </svg>
                    </div>
                    
                    <div class="cslr-logo">
                        <h1 class="logo"><strong>Castlery</strong><a
                                href="https://www.castlery.com/" title="Castlery"
                                class="logo"><img src="https://www.castlery.com/skin/frontend/castlery/default/images/logo.png"
                                                  alt="Castlery"/></a></h1>
                    </div>
                    <div class="header-custommenu clearfix">
                     <?php if ($_smarty_tpl->getVariable('listMenus_2')->value){?>
                     <?php  $_smarty_tpl->tpl_vars['menuMain'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('listMenus_2')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['menuMain']->key => $_smarty_tpl->tpl_vars['menuMain']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['menuMain']->key;
?> 
                     <?php $_smarty_tpl->assign('menuChildren',$_smarty_tpl->getVariable('menuMain')->value->getChildren(),null,null);?>
                        
                        <div class="header-menu header-menu--dropdown">
                            <a class="topnav" href="<?php echo $_smarty_tpl->getVariable('menuMain')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('menuMain')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><?php echo $_smarty_tpl->getVariable('menuMain')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</a>
                            <div class="header-sub furniture">
                            <?php if ($_smarty_tpl->getVariable('menuChildren')->value){?>
                             <?php  $_smarty_tpl->tpl_vars['menuMainChild'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['j'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('menuChildren')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['menuMainChild']->key => $_smarty_tpl->tpl_vars['menuMainChild']->value){
 $_smarty_tpl->tpl_vars['j']->value = $_smarty_tpl->tpl_vars['menuMainChild']->key;
?> 
                             <?php $_smarty_tpl->assign('menusubChildren',$_smarty_tpl->getVariable('menuMainChild')->value->getChildren(),null,null);?>
                             
                                <div class="sub-col">
                                    <div class="sub-col-header">
                                        <a href="<?php echo $_smarty_tpl->getVariable('menuMainChild')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('menuMainChild')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><?php echo $_smarty_tpl->getVariable('menuMainChild')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</a>
                                    </div>
                                    <?php if ($_smarty_tpl->getVariable('menusubChildren')->value){?>
                                    
                                    <ul>
                                      <?php  $_smarty_tpl->tpl_vars['menusubchildren'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('menusubChildren')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['menusubchildren']->key => $_smarty_tpl->tpl_vars['menusubchildren']->value){
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['menusubchildren']->key;
?> 
                                        <li>
                                            <a href="<?php echo $_smarty_tpl->getVariable('menuMainChild')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('menuMainChild')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><?php echo $_smarty_tpl->getVariable('menuMainChild')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</a>
                                        </li>
                                       <?php }} ?>
                                    </ul>
                                    <?php }?>
                                </div>
                                <?php }} ?>
                                <?php }?>
                                
                            </div>
                        </div>
                        <?php }} ?>
                        <?php }?>
                    </div>

                    <div class="header-content-right">
                        <div class="header-search">
                            <svg>
                                <use xlink:href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/svg-icons.svg#search"></use>
                            </svg>
                        </div>
                        <div class="top-cart-wrapper">
                            <div class="top-cart-contain">
                                
                                <script type="text/javascript">
                                    $jq(document).ready(function () {
                                        var enable_module = $jq('#enable_module').val();
                                        if (enable_module == 0) return false;
                                    })

                                </script>
                                
                                <div id="mini_cart_block">
                                    <div class="mini_cart_ajax">
                                        <div class="block-cart">
                                            <div class="header-content-shoppingCart">
                                                <a href="https://www.castlery.com/checkout/cart/" class="shopping-cart-icon" id="header-cart-dropdown" aria-expanded="false">
                                                    <span>0</span>
                                                    <svg class="header-shopping-cart__icon">
                                                        <use xlink:href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/svg-icons.svg#shoppingcart"></use>
                                                    </svg>
                                                </a>

                                                <div class="header-content-shoppingCart-outside shoppingCart-container">
                                                    <div class="header-content-shoppingCart-box">
                                                        <i class="i-icoTop"></i>
                                                        <a href="https://www.castlery.com/checkout/cart/" class="header-content-shoppingCart-title">Your Cart
                                                            (<span>0</span>)</a>
                                                        <div class="header-content-shoppingCart-list">
                                                            <p class="header-content-shoppingCart-no">You have no items in your shopping cart.</p>
                                                        </div>


                                                        <div class="header-content-shoppingCart-bottom clearfix">

                                                            <strong class="clearfix"><span class="price-title">Subtotal                                    :</span> <span class="price">$0</span>                            </strong>
                                                            <strong class="clearfix"><span class="price-title">Total (Incl. GST)                                    :</span> <span class="price">$0</span>                            </strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <ul class="header-content-contact clearfix">
                            <li>
                                <a href="#" id="trigger-overlay">
                                    <svg class="header-desktop-search__icon">
                                        <use xlink:href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/svg-icons.svg#search"></use>
                                    </svg>
                                    Search
                                </a>
                            </li>
                            <li><span class="header-content-contact-line"></span></li>
                            <li style="padding-right: 10px"  class="first last" ><a href="https://www.castlery.com/customer/account/login/referer/aHR0cHM6Ly93d3cuY2FzdGxlcnkuY29tLw,,/" title="Log In" >Signup / Login</a></li>

                        </ul>
                    </div>
                </div>
                <div class="header-search-container">
                    <form action="https://www.castlery.com//catalogsearch/result/" class="header-search-container__form">
                        <svg class="header-search-container__icon">
                            <use xlink:href="/<?php echo $_smarty_tpl->getVariable('templatePath')->value;?>
/<?php echo $_smarty_tpl->getVariable('userTemplate')->value;?>
/images/svg-icons.svg#search"></use>
                        </svg>
                        <input type="text" id="search" class="header-search-container__input" name="q" placeholder="Search">
                    </form>
                    <div class="header-search-container__cancel">
                        <!--                <svg>-->
                        <!--                    <use xlink:href="--><!--#menu-close"></use>-->
                        <!--                </svg>-->
                        <span class="search-close-left"></span>
                        <span class="search-close-right"></span>
                    </div>
                </div>
                
                <div class="overlay overlay-scale" id="search-overlay">
                    <button type="button" class="overlay-close">Close</button>

                    <form id="search_mini_form" action="https://www.castlery.com/catalogsearch/result/" method="get">
                        <div class="overlay-keywords">
                            <input id="search" placeholder="Type to Search..." type="text" name="q" value="" maxlength="128" autocomplete="off" />
                           
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <a href="#" id="chat-button" class="chat-button-mobile i-icoMobileChat" onclick="LC_API.open_chat_window();return false;"></a>
        <a href="javascript:;" id="back-top" class="back-top i-icoScrollTop" style="display: block;"></a>

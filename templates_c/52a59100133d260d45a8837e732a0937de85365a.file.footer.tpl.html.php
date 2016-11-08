<?php /* Smarty version Smarty-3.0-RC2, created on 2016-11-08 16:33:50
         compiled from "C:\xampp\htdocs\projects\shop/templates/shop//footer.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:479958219bfe1f5806-60126563%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '52a59100133d260d45a8837e732a0937de85365a' => 
    array (
      0 => 'C:\\xampp\\htdocs\\projects\\shop/templates/shop//footer.tpl.html',
      1 => 1478570638,
    ),
  ),
  'nocache_hash' => '479958219bfe1f5806-60126563',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
  
        <div class="footer-press" style="">
            <div class="press-inner">
                <a href="https://www.castlery.com/press">
                    <img src="https://www.castlery.com/skin/frontend/castlery/default/images/home/press.jpg" />
                </a>
            </div>
        </div>
        <div class="ma-footer-static">
            <div class="container">
                <div class="footer-static">
                    <div class="row" id="footer-row-one">
                        <div class="f-col f-col-content f-col-6 col-sm-6 col-md-4 col-lg-4" id="footer-about">
                            <div class="footer-static-title">
                                <h3>About</h3>
                            </div>
                            <p class="footer-static-description">Castlery was founded with one ambition - to reinvent how
                                furniture retail works. We design and produce furniture with great aesthetics at a competitive
                                price point, without compromising on quality. By cutting out the middleman and keeping our
                                inventory lean we can keep our costs down and extend the savings to you.</p>
                        </div>
                        <div class="f-col f-col-3 col-sm-6 col-md-4 col-lg-4 footer-large-desktop" id="footer-quick-links">
                            <div class="footer-static-title">
                                <h3>Quick Links</h3>
                            </div>
                            <div class="footer-static-content">
                                <ul>
                                    <li><a href="https://www.castlery.com/our-story">Our Story</a></li>
                                    <li><a href="https://www.castlery.com/press">Press</a></li>
                                    <li><a href="https://www.castlery.com/book-appointment">Visit the Studio</a></li>
                                    <li><a href="https://www.castlery.com/fabric-swatches">Request Fabric Swatches</a>
                                    </li>
                                    <li><a href="https://www.castlery.com/contact-us">Contact Us</a></li>
                                    <li><a href="http://castlery.workable.com">Careers</a></li>
                                    <li><a href="https://www.castlery.com/delivery">Delivery Info</a></li>
                                    <li><a href="https://www.castlery.com/returns-exchanges">Return Policy</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="f-col f-col-3 col-sm-6 col-md-4 col-lg-4">
                            <div class="footer-static-title" id="footer-sign-up-header">
                                <h3>$25 off for your First Order</h3>
                            </div>
                            <div class="footer-static-content">
                                <p class="footer-static-description">Want exclusive offers and first access to products? Sign up
                                    for email alerts.</p>
                                <div class="footer-input">
                                    <input type="text" class="form-control footer-sign-up-input" placeholder="Your email">
                            <span class="signup-login-button footer-sign-up-button">
                              <button class="btn-subscribe">Join</button>
                            </span>
                                </div><!-- /input-group -->
                                <div id="newsletter-subscribe-message"></div>
                                <div class="share-links">
                                    <a href="http://facebook.com/castlery" target="_blank"
                                       class="i-icoShareFacebook share-icon"></a>
                                    <a href="https://twitter.com/Castlery" class="i-icoShareTwitter share-icon"></a>
                                    <a href="https://plus.google.com/+Castlerysg" class="i-icoShareG share-icon"></a>
                                    <a href="http://pinterest.com/castlery/" class="i-icoSharePinterest share-icon"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer-middle-section">
                        <div class="row footer-med-desktop">
                            <div class="f-col f-col-3 col-sm-12 col-md-12 col-lg-12" id="footer-quick-links">
                                <div class="footer-static-title">
                                    <h3>Quick Links</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row footer-med-desktop footer-static-content">
                            <div class="col-md-3">
                                <ul>
                                    <li><a href="https://www.castlery.com/our-story">Our Story</a></li>
                                    <li><a href="https://www.castlery.com/press">Press</a></li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <ul>
                                    <li><a href="https://www.castlery.com/book-appointment">Visit the Studio</a></li>
                                    <li><a href="https://www.castlery.com/contact-us">Contact Us</a></li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <ul>
                                    <li><a href="https://www.castlery.com/fabric-swatches">Fabric Swatch</a></li>
                                    <li><a href="http://castlery.workable.com">Careers</a></li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <ul>
                                    <li><a href="https://www.castlery.com/delivery">Delivery Info</a></li>
                                    <li><a href="https://www.castlery.com/returns-exchanges">Return Policy</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="footer-row-two">
                     <?php if ($_smarty_tpl->getVariable('listMenus_5')->value){?>
                       <?php  $_smarty_tpl->tpl_vars['menuMain'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('listMenus_5')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['menuMain']->key => $_smarty_tpl->tpl_vars['menuMain']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['menuMain']->key;
?> 
                        <?php $_smarty_tpl->assign('menuChildren',$_smarty_tpl->getVariable('menuMain')->value->getChildren(),null,null);?>
                        <div class="f-col f-col-3 col-sm-6 col-md-3 col-lg-3">
                            <div class="footer-static-title">
                                <h3><?php echo $_smarty_tpl->getVariable('menuMain')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</h3>
                            </div>
                             <?php if ($_smarty_tpl->getVariable('menuChildren')->value){?>
                            <div class="footer-static-content">
                                 <ul>
                                  <?php  $_smarty_tpl->tpl_vars['menuchildren'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['j'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('menuChildren')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['menuchildren']->key => $_smarty_tpl->tpl_vars['menuchildren']->value){
 $_smarty_tpl->tpl_vars['j']->value = $_smarty_tpl->tpl_vars['menuchildren']->key;
?> 
                                    <li><a href="<?php echo $_smarty_tpl->getVariable('menuchildren')->value->getUrl($_smarty_tpl->getVariable('lang')->value);?>
" title="<?php echo $_smarty_tpl->getVariable('menuchildren')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
"><?php echo $_smarty_tpl->getVariable('menuchildren')->value->getName($_smarty_tpl->getVariable('lang')->value);?>
</a></li>
                                   <?php }} ?>
                                </ul>
                            </div>
                             <?php }?>
                        </div>
                        <?php }} ?>
                        <?php }?>

                    </div>
                </div>
                <p class="footer-static-copyright">Copyright © Castlery Pte Ltd. All Rights Reserved.</p>
            </div>



            <div id ="ajaxconfig_info" style ="display:none">
                <a href ="https://www.castlery.com/"></a>
                <div ><img src ="https://www.castlery.com/media/theme/default/loader_2.gif" alt ="Loading Image" /></div>
                <button name ="#000000" value ="0.3" ></button>
                <input type="hidden" value =""/>
                <input type="hidden" id="enable_module" value ="1"/>
                <input class="effect_to_cart" type="hidden" value ="0"/>
                <input class="title_shopping_cart" type="hidden" value ="Go to shopping cart"/>
                <input class="title_shopping_continue" type="hidden" value ="Shop more design"/>
                <input class="title_shopping_wishlist" type="hidden" value ="Go to wishlist"/>
                <input class="title_shopping_compare" type="hidden" value ="Go to list Compare"/>
                <input class="confirm_delete_product" type="hidden" value ="Are you sure you would like to remove this item ?"/>
                <input class="title_add_to_cart" type="hidden" value ="Item was successfully added to your cart"/>
                <input class="title_add_to_wishlist" type="hidden" value ="Item was successfully added to your wishlist"/>
                <input class="title_add_to_compare" type="hidden" value ="Item was successfully added to your compare"/>
                <input class="using_ssl" type="hidden" value ="0"/>


            </div>


            <div id="ajaxcart-checkout-content" style="display:none;"></div>
            <div id ="productHaveOption"  style="display:block;" ></div>

            
            <script  type="text/javascript">
            $jq(document).ready(function() {
                if(!CSLR.is_loggedin && Cookies.get("shownewsletter") != 1){
                    $jq('#newsletter-modal').modal();
                    Cookies.set('shownewsletter','1',{ expires: 7});
                }
                $jq( "#newsletter-modal form" ).submit(function( event ) {
                    var email = $jq('#newsletter_subscribe').val();
                    var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
                    if (testEmail.test(email)){
                        return true;
                    }
                    else{
                        $jq('#newsletter_subscribe').addClass('validation-failed');
                        $jq('#advice-required-entry-newsletter').show();
                        return false;
                    }
                });
            });
        </script>
            
            <!-- Element to pop up -->
            <div class="modal fade newsletter-subscribe" id="newsletter-modal" tabindex="-1" role="dialog" aria-labelledby="newsletter-modal-label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-home modal-dialog-center">
                    <div class="modal-content">
                        <a href="javascript:;" type="button" class="i-icoCloseBlack" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></a>
                        <div class="modal-dialog-home-body clearfix">
                            <div class="modal-dialog-home-body-sign">
                                <img src="https://www.castlery.com/skin/frontend/castlery/default/images/sample/header.jpg" />
                                <h2>$25 Off </h2>
                                <h3>-WELCOME GIFT-</h3>
                                <P>Sign up and be an insider. Receive all our latest deals, events, design tips and get a free $25 voucher.</P>
                                <form action="https://www.castlery.com/newsletter/subscriber/new/" method="post" id="newsletter-validate">
                                    <ul class="newsletter-signup-form">
                                        <li><input type="text" name="email" id="newsletter_subscribe" placeholder="Enter your email" class="input-text required-entry validate-email" /></li>
                                        <div style="" id="advice-required-entry-newsletter" class="validation-advice">This is a required field.</div>
                                        <li><button type="submit" title="Subscribe" class="button_subscribe">Sign up</button></li>
                                    </ul>
                                    <a href="#" data-dismiss="modal" aria-label="Close"><strong>No, I don't like discounts.</strong></a>
                                </form>
                                
                                <script type="text/javascript">
                                    //<![CDATA[
                                    var newsletterSubscriberFormDetail = new VarienForm('newsletter-validate');
                                    //]]>
                                </script>
                                
                            </div>
                            <div class="modal-dialog-home-body-img"></div>
                        </div>
                    </div>
                </div>
            </div>


            <script>var FEED_BASE_URL="https://www.castlery.com/";</script><script src="https://www.castlery.com/js/mirasvit/code/feedexport/performance.js" type="text/javascript"></script>
            
            <script type="text/javascript">
                //<![CDATA[
                Mage.Cookies.set(
                        'PAGECACHE_ENV',
                        '',
                        new Date(1970, 1, 1, 0, 0, 0)
                );
                //]]>
            </script>
            <script type="text/javascript">
                if (document.cookie.length && (document.cookie.indexOf('PAGECACHE_FORMKEY=') == -1)) {
                    Mage.Cookies.set(
                            'PAGECACHE_FORMKEY',
                            '9ALwZfwyNbpJQtwv',
                            new Date(new Date().getTime() + 86400000)
                    );
                }
            </script>
            
        </div>
    </div>
</body>
</html>

<?php
 
/* =============================================================================
   SHOPPERPRESS HOOKS // 
   ========================================================================== */
   
function premiumpress_shippingmethods($content){ return apply_filters('premiumpress_shippingmethods',$content);  } //Executed after the closing #menubar DIV tag
 

/* =============================================================================
   ACTIONS FOR SHOPPERPRESS THEME
   ========================================================================== */
   
function ShopperPressActions(){

global $post, $userdata, $wpdb;

	// widthdrawl form
	if(isset($_GET['action']) && $_GET['action'] == "withdraw"){ 
	
	   $message 	 = "<p> Username : " . strip_tags($userdata->user_login) . "
					<p> Email : " . strip_tags($userdata->user_email) . "
					<p> Withdrawal Amount : " . strip_tags($_POST['amount']) . "
					<p> Method : " . strip_tags($_POST['method']) . "";
					
		$emailID = array();			
		$emailID['subject'] 		= "WIDTHDRAWL FORM";
		$emailID['description'] 	= $message;
		SendMemberEmail("admin", $emailID);

		$GLOBALS['error'] 		= 1;
		$GLOBALS['error_type'] 	= "success"; //ok,warn,error,info
		$GLOBALS['error_msg'] 	= "Thank you, one of our team will be in contact shortly.";	
		 	
	
	} // end add feedback

} // end function

add_action('premiumpress_action','ShopperPressActions'); // add in new hook


/* =============================================================================
   CUSTOM ACCOUNT OPTIONS // V7 // MARCH 29TH
   ========================================================================== */

function shopperpress_accountoptions(){

global $wpdb, $PPT, $userdata;

  if(get_option("display_credit_options") == "yes"){ ?>    
    
    <div class="full clearfix border_t box"> <br />
    <p class="f_half left"> 
        <a href="javascript:void(0);" onclick="jQuery('#My').hide(); jQuery('#PaymentDeposit').show()" title="">
        <img src="<?php echo IMAGE_PATH; ?>a8.png" style="float:left; padding-right:20px; margin-top:10px;" />
        <b><?php echo $PPT->_e(array('sp','_tpl_myaccount41')); ?></b><br />
        <?php echo $PPT->_e(array('sp','_tpl_myaccount42')); ?>
        </a>
    </p> 
    
    <?php if(get_user_meta($userdata->ID, 'aim', true) > 0){ ?>
    <p class="f_half left"> 
        <a href="javascript:void(0);" onclick="jQuery('#My').hide(); jQuery('#PaymentWithdraw').show()" title="">
        <img src="<?php echo IMAGE_PATH; ?>a7.png" style="float:left; padding-right:20px; margin-top:10px;" />
        <b><?php echo $PPT->_e(array('sp','_tpl_myaccount43')); ?></b><br />
        <?php echo $PPT->_e(array('sp','_tpl_myaccount44')); ?>
        </a> 
    </p> 
    <?php } ?>
    
    </div>
    
<?php }   

}
add_action('premiumpress_account_options','shopperpress_accountoptions');

function sp_mybalance(){ global $userdata, $wpdb; if(get_option('display_credit_options') == "no"){ return; } ?>
<div class='green_box'><div class='green_box_content'><div align="center">

<img src="<?php echo $GLOBALS['template_url'].'/template_shopperpress/images/'; ?>money.png" align="absmiddle" /> Your account balance is <?php echo premiumpress_price(get_user_meta($userdata->ID, 'aim', true),$GLOBALS['premiumpress']['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1); ?> 

</div><div class="clearfix"></div></div></div>
<?php } 

add_action('premiumpress_account_top','sp_mybalance');

function shopperpress_accountoptions_inner(){

global $wpdb, $PPT, $userdata; $csymbol = get_option('currency_symbol');

?>

<div id="PaymentDeposit" style="display:<?php if(isset($_POST['step1'])){ echo "visible"; }else{ echo "none"; } ?>">
 

<form action="" method="post" style="display:<?php if(isset($_POST['step1'])){ echo "none"; }else{ echo "visible"; } ?>"> 
<input type="hidden" name="step1" value="1" />


<div class="itembox">
    
	<h2><?php echo $PPT->_e(array('sp','_tpl_myaccount45')); ?></h2> 
    
	<div class="itemboxinner greybg">  
    
    <div class="full clearfix box">
    
    <h5><?php echo $PPT->_e(array('sp','_tpl_myaccount46')); ?></h5>   
  
            <ul class="amounts"> 
                <li> 
                  <label><input checked="checked" class="checkbox" value="20" name="amount" style="border: none" type="radio" /> 
                  <span><?php echo $csymbol; ?></span>20</label> 
                </li> 
                <li> 
                  <label><input class="checkbox" value="30" name="amount" style="border: none" type="radio" /> 
                  <span><?php echo $csymbol; ?></span>30</label> 
                </li> 
                <li> 
                  <label><input class="checkbox" value="40" name="amount" style="border: none" type="radio" /> 
                  <span><?php echo $csymbol; ?></span>40</label> 
                </li> 
                <li> 
                  <label><input class="checkbox" value="50" name="amount" style="border: none" type="radio"  /> 
                  <span><?php echo $csymbol; ?></span>50</label> 
                </li> 
                <li> 
                  <label><input class="checkbox" value="60" name="amount" style="border: none" type="radio" /> 
                  <span><?php echo $csymbol; ?></span>60</label> 
                </li> 
                <li> 
                  <label><input class="checkbox" value="70" name="amount" style="border: none" type="radio"/> 
                  <span><?php echo $csymbol; ?></span>70</label> 
                </li> 
                <li> 
                  <label><input class="checkbox" value="80" name="amount" style="border: none" type="radio" /> 
                  <span><?php echo $csymbol; ?></span>80</label> 
                </li> 
                <li> 
                  <label><input class="checkbox" value="90" name="amount" style="border: none" type="radio"/> 
                  <span><?php echo $csymbol; ?></span>90</label> 
                </li> 
                <li> 
                  <label><input class="checkbox" value="100" name="amount" style="border: none" type="radio"/> 
                  <span><?php echo $csymbol; ?></span>100</label> 
                </li> 
              </ul> 
             
           </div><!-- end box -->
            
         
       </div><!-- end inner box --> 
       
	<!-- start buttons -->
    <div class="enditembox inner"> 
                
      	<input type="button" onclick="jQuery('#My').show(); jQuery('#PaymentDeposit').hide()" class="button gray right" tabindex="15" value="<?php echo $PPT->_e(array('button','8')); ?>" />
        
        <input type="submit" name="submit" id="submit" class='button green' tabindex="15" value="<?php echo $PPT->_e(array('button','21')); ?>" />  
           
    </div>
    <!-- end buttons -->	
        
</div><!-- end item box -->
       
 
</form> 
 

</div>
    
<?php if(isset($_POST['step1'])){ ?>
<script type="text/javascript">

jQuery(document).ready(function(){

	// HIDE MY ACCOUNT BOX
	jQuery('#My').hide();	
});

</script> 

  <div class="itembox">
    
	<h2><?php echo $PPT->_e(array('sp','_tpl_myaccount48')); ?></h2> 
    
	<div class="itemboxinner greybg"> 
    
    <div class="full clearfix box"> <br />
    
    <p><?php echo $PPT->_e(array('sp','_tpl_myaccount49')); ?></p> 
    
    <?php include(str_replace("functions/","",THEME_PATH)."/PPT/func/func_paymentgateways.php");  ?>
    
                <table cellpadding="0" cellspacing="0" class="select notoppadding" style="padding:0px; margin:0px; margin-top:20px; ">
                
                <?php
                $_POST['price']['total'] 	= $_POST['amount'];
                $_POST['orderid'] 			= $userdata->ID."-".$_POST['amount']."-".RandomID(5)."-CREDITS";
                $_POST['description'] 		= "Cart Order ID: ".$_POST['orderid'];
                  
                $i=1;
                if(is_array($gatway)){
                foreach($gatway as $Value){
                if(get_option($Value['function']) =="yes"){
				
				if( $Value['function'] == "gateway_bank"){ ?>
                <tr style="height:60px; border-bottom:1px solid #cccccc;">
                
                <td colspan="3" style="padding:10px;"> <b><?php echo get_option($Value['function']."_name"); ?></b> <br /><br /> <?php echo nl2br(get_option("bank_info")); ?> </td>
                
                </tr><tr><td colspan=3><hr /></td></tr>
                
                <?php  }elseif( $Value['function'] != "gateway_paypalpro" && $Value['function'] != "gateway_ewayAPI" && $Value['function'] !="gateway_blank_form"){  ?>     
                
                <tr style="height:60px; background:#eee; border-bottom:1px solid #cccccc;">
                <td width="80"><?php if(strlen(get_option($Value['function']."_icon")) > 5){ echo "<img src='".get_option($Value['function']."_icon")."' />"; } ?></td>
                <td width="434">&nbsp;&nbsp;<b><?php echo get_option($Value['function']."_name"); ?></b></td>
                <td width="123"><?php echo $Value['function']($_POST); ?></td>
                </tr>
                <tr><td colspan="3"><hr /></td></tr>
                
                <?php 
    
                }else{
                echo "<tr><td colspan=3>";
                echo $Value['function']($_POST);
                echo "</td></tr><td colspan=3><hr /></td></tr>";		
                }
    
    
                } } }  ?>
                </table> 
    
    
    </div>
    
    </div>
    
    </div>
    
    <?php if($userdata->wp_user_level == "10"){  ?>
 
     <p style="padding:6px; color:white;background:red;"><b>Admin View Only</b> - <a href="#" onclick="document.AdminTest.submit();" style="color:white;">Click here to skip payment and test callback link.</a> </p>


    <form name="AdminTest" id="AdminTest" action="<?php echo $GLOBALS['bloginfo_url']; ?>/callback/" method="post">
    <input type="hidden" name="custom" value="<?php echo $_POST['orderid']; ?>">
    <input type="hidden" name="payment_status" value="Completed">
    <input type="hidden" name="mc_gross" value="<?php echo $newPrice; ?>" />
    </form> 


	<?php }   ?>
    
     
       
    
     
<?php } ?>


 

<div id="PaymentWithdraw" style="display:none;">
  
     <form action="" method="post"> 
     <input type="hidden" name="action" value="withdraw" />
         
    <div class="itembox">
    
	<h2>Withdraw Money</h2> 
    
	<div class="itemboxinner greybg">  	
    
    <p>Your withdrawal request will be sent to one of our team who will contact you personally to discuss your options.</p>
    
 
        <div class="full clearfix border_t box"> 
        <p class="f_half left"> 
            <label for="name">Amount to deposit </label><br /> 
            <input type="text" value="0" class="short" name="amount" /><br /> 
            
        </p> 
        <p class="f_half left"> 
            <label for="email">Deposit Type <span class="required">*</span></label><br /> 
            <select name='method' class="short" tabindex="14">
            <option>Check</option>
            <option>Paypal</option>
            </select>
            <br /> 
        </p> 
        </div> 
   
    
    </div>
    
	<!-- start buttons -->
    <div class="enditembox inner"> 
                
      	<input type="button" onclick="jQuery('#My').show(); jQuery('#PaymentWithdraw').hide()" class="button gray right" tabindex="15" value="<?php echo $PPT->_e(array('button','8')); ?>" />
        
        <input type="submit" name="submit" id="submit" class='button green' tabindex="15" value="<?php echo $PPT->_e(array('button','10')); ?>" />  
           
    </div>
    <!-- end buttons -->
    
    </div>
    
    <!-- end item box -->

</form> 
</div>

<?php 

}

add_action('premiumpress_account_bottom','shopperpress_accountoptions_inner');




/* =============================================================================
   CUSTOM HEADER/FOOTER CODE // 26TH MARCH
   ========================================================================== */

function shopperpress_footer($content){ 
global $ThemeDesign;
 
// backup incase user disabled submenu car
if(isset($GLOBALS['ppt_layout_styles']['submenubar']) && $GLOBALS['ppt_layout_styles']['submenubar']['hide'] == 1){   echo '<div style="text-indent:-9999px;">'.$ThemeDesign->BASKET()."</div>";  }

 ?>
 
  <script type="text/javascript"> 
		 
		jQuery(document).ready(function() {
		 
		 <?php  if(isset($_GET['commentstab'])){ ?>
			 
			jQuery('#comments').addClass("active").show();
			 
			<?php } ?>
			
			 
		});

</script>


	<?php /*------------------- GLOBAL IDS FOR CART -----------------------*/ ?>
    
    <span id="CustomField_1" class="rfield"></span>
    <span id="CustomField_2" class="rfield"></span>
    <span id="CustomField_3" class="rfield"></span>
    <span id="CustomField_4" class="rfield"></span>
    <span id="CustomField_5" class="rfield"></span>
    <span id="CustomField_6" class="rfield"></span>
    <span id="CustomField_7" class="rfield"></span>
    <span id="CustomField_1_required" class="rfield"><?php if(isset($GLOBALS['customlist1_has_value']) && $GLOBALS['customlist1_has_value'] !=""){ echo get_option('custom_field1_required'); } ?></span>
    <span id="CustomField_2_required" class="rfield"><?php if(isset($GLOBALS['customlist2_has_value']) &&  $GLOBALS['customlist2_has_value'] !=""){ echo get_option('custom_field2_required'); } ?></span>
    <span id="CustomField_3_required" class="rfield"><?php if(isset($GLOBALS['customlist3_has_value']) &&  $GLOBALS['customlist3_has_value'] !=""){ echo get_option('custom_field3_required'); } ?></span>
    <span id="CustomField_4_required" class="rfield"><?php if(isset($GLOBALS['customlist4_has_value']) &&  $GLOBALS['customlist4_has_value'] !=""){ echo get_option('custom_field4_required'); } ?></span>
    <span id="CustomField_5_required" class="rfield"><?php if(isset($GLOBALS['customlist5_has_value']) &&  $GLOBALS['customlist5_has_value'] !=""){ echo get_option('custom_field5_required'); } ?></span>
    <span id="CustomField_6_required" class="rfield"><?php if(isset($GLOBALS['customlist6_has_value']) &&  $GLOBALS['customlist6_has_value'] !=""){ echo get_option('custom_field6_required'); } ?></span>
    <span id="CustomField_7_required" class="rfield"><?php if(isset($GLOBALS['customlist7_has_value']) &&  $GLOBALS['customlist7_has_value'] !=""){ echo get_option('custom_field7_required'); } ?></span>
    <span id="CustomQty" class="rfield"></span>
    <span id="CustomShipping" class="rfield"><?php if(isset($GLOBALS['default_shipping'])){ echo $GLOBALS['default_shipping']; } ?></span>
    <span id="CustomSize" class="rfield"></span>
    <span id="CustomColor" class="rfield"></span>
    <span id="CustomExtra" class="rfield"></span>
    
    <script type="text/javascript">
    
           jQuery(document).ready(function() {
                jQuery(".dropdown img.flag").addClass("flagvisibility");
    
                jQuery(".dropdown dt a").click(function() {
                    jQuery(".dropdown dd ul").toggle();
                });
                            
                jQuery(".dropdown dd ul li a").click(function() {
                    var text = jQuery(this).html();
                    jQuery(".dropdown dt a span").html(text);
                    jQuery(".dropdown dd ul").hide();
                   // jQuery("#result").html("Selected value is: " + getSelectedValue("sample"));
                });
    
    
                jQuery(".dropdown1 img.flag").addClass("flagvisibility");
    
                jQuery(".dropdown1 dt a").click(function() {
                    jQuery(".dropdown1 dd ul").toggle();
                });
                            
                jQuery(".dropdown1 dd ul li a").click(function() {
                    var text = jQuery(this).html();
                    jQuery(".dropdown1 dt a span").html(text);
                    jQuery(".dropdown1 dd ul").hide();
                   // jQuery("#result").html("Selected value is: " + getSelectedValue("sample"));
                });
				
                jQuery(".dropdown2 img.flag").addClass("flagvisibility");
    
                jQuery(".dropdown2 dt a").click(function() {
                    jQuery(".dropdown2 dd ul").toggle();
                });
                            
                jQuery(".dropdown2 dd ul li a").click(function() {
                    var text = jQuery(this).html();
                    jQuery(".dropdown2 dt a span").html(text);
                    jQuery(".dropdown2 dd ul").hide();
                   // jQuery("#result").html("Selected value is: " + getSelectedValue("sample"));
                });                            
                function getSelectedValue(id) {
                    return jQuery("#" + id).find("dt a span.value").html();
                }
    
                jQuery(document).bind('click', function(e) {
                    var $clicked = jQuery(e.target);
                    if (! $clicked.parents().hasClass("dropdown"))
                        jQuery(".dropdown dd ul").hide();
                    if (! $clicked.parents().hasClass("dropdown1"))
                        jQuery(".dropdown1 dd ul").hide();
					if (! $clicked.parents().hasClass("dropdown2"))
                        jQuery(".dropdown2 dd ul").hide();	
                });
    
    
                jQuery("#flagSwitcher").click(function() {
                    jQuery(".dropdown img.flag").toggleClass("flagvisibility");
                });
            });
    </script> <?php 
}
add_action('wp_footer',  'shopperpress_footer' , 10);




function shopperpress_bit_submenu_inside(){ /*SUB MENU BAR */
			
			global $wpdb,$PPT,$ThemeDesign, $userdata; get_currentuserinfo(); $string='';  
        
        	$string .= '<div id="submenubar"><div class="w_960">';
            
            if(isset($GLOBALS['ppt_layout_styles']['submenubar']) && isset($GLOBALS['ppt_layout_styles']['submenubar']['search']) && $GLOBALS['ppt_layout_styles']['submenubar']['search'] == 1){
            
            	$string .= '<div id="hpages"><ul>'.premiumpress_pagelist().'</ul></div>';
            
            }else{
            
				$string .= '<form method="get" action="'.$GLOBALS['bloginfo_url'].'/" name="searchBox" id="searchBox">
				<div class="searchBtn" onclick="document.searchBox.submit();"> &nbsp;</div>
				<input type="text" value="'.$PPT->_e(array('head','2')).'" name="s" id="s" onfocus="this.value=\'\';"  />				 
				';
				
				if(get_option("display_advanced_search") ==1){
					$string .= '<a href="javascript:jQuery(\'#AdvancedSearchBox\').show(); javascript:void(0);"><small>'.$PPT->_e(array('head','3')).'</small></a>';
				}             
				$string .= '</form>';
				
				if(get_option("display_subnav_currency") == "yes"){  $string .= $ThemeDesign->SUBMENUDROPDOWN("currency"); } 
             
             	if(get_option("display_subnav_flags") == "yes"){ $string .= $ThemeDesign->SUBMENUDROPDOWN("language"); } 
            
             }
            
			 
            $string .= '<ul class="submenu_account">';
			
			if(isset($GLOBALS['ppt_layout_styles']['submenubar']) && isset($GLOBALS['ppt_layout_styles']['submenubar']['loginlogout']) && $GLOBALS['ppt_layout_styles']['submenubar']['loginlogout'] == 1){ }else{
			
                       
				if ( isset($userdata) && $userdata->ID ){ 
				
					$string .= '<li id="submenu_li_logout"><a href="'.wp_logout_url().'">'.$PPT->_e(array('head','4')).'</a></li>
					<li id="submenu_li_account"><a href="'.$GLOBALS['premiumpress']['dashboard_url'].'">'.$PPT->_e(array('head','5')).'</a></li>
					<li id="submenu_li_reports"><a href="http://localschooldeals.com/current_user_report">'.$PPT->_e(array('head','9')).'</a></li>
					<li id="submenu_li_username"><b>'.$userdata->display_name.'</b></li>';
				
				}else{
				
					$string .= '<li><a href="'.$GLOBALS['bloginfo_url'].'/wp-login.php" rel="nofollow" id="submenu_li_login">'. $PPT->_e(array('head','6')).'</a> 
					<a href="'.$GLOBALS['bloginfo_url'].'/wp-login.php?action=register" rel="nofollow" id="submenu_li_register">'.$PPT->_e(array('head','7')).'</a></li>';
				
				}
			}
			
			if($GLOBALS['shopperpress']['price_tag'] != "no"){
			
			$string .= '<li><a id="hbasket" href="'.$GLOBALS['premiumpress']['checkout_url'].'">'.$ThemeDesign->BASKET().'</a></li>';
			
			}
             
            $string .= '</ul> ';      
        
        	$string .= '</div> <!-- end w_960 --> </div><!-- end submenubar -->';
			
			return $string;
            
}
add_action('premiumpress_submenu_inside','shopperpress_bit_submenu_inside');
 













/* =============================================================================
  SHOPPERPRESS ACTIONS
   ========================================================================== */

function shopperpress_actions(){
 
global $wpdb, $PPT, $ThemeDesign;

if(isset($_GET['loggedout'])){@session_destroy();}
/* ============================= PREMIUM PRESS GLOBALS ========================= */
$GLOBALS['shopperpress']['price_tag'] 			= get_option("display_pricetag");
 
$GLOBALS['shopperpress']['product_price_extra'] = get_option("product_price_extra");
$GLOBALS['shopperpress']['StockControl']		= get_option("display_ignoreQTY");
$GLOBALS['premiumpress']['checkout_url'] 		= get_option("checkout_url");
$GLOBALS['galleryblockstop'] = 3; 
$GLOBALS['display_sidebar_basket'] 		= get_option("display_sidebar_basket");
/* ============================= PREMIUM PRESS SESSIONS ========================= */
if(isset($_GET['emptyCart'])){ foreach($_SESSION as $key => $value){ unset($_SESSION[$key]); }}	 
if(!isset($_SESSION['ddc']['cartqty'])) $_SESSION['ddc']['cartqty'] = 0;
if(!isset($_SESSION['ddc']['price'])) $_SESSION['ddc']['price'] = 0.00;
// CHECK DEFAULT PRICING
$_SESSION['ddc']['price'] = updatePrice();
/********************** SHOPPERPRESS CURRENCY **********************/
/************************************************************************/

		
		if(get_option("display_subnav_currency") !="asdyes"){

		if ( !isset($_SESSION['c'] ) && !isset($_REQUEST['c']) ){
		
			$_SESSION['currency_symbol'] = get_option("currency_symbol"); // DEFAULT CURRENCY
 
			$data = $ThemeDesign->CURRENCYEXCHANGE($_SESSION['currency_symbol']); // get the default values
 			if(isset($data['caption'])){
			$_SESSION['currency_caption'] 	= $data['caption'];
			$_SESSION['currency_code'] 		= $data['code'];			 
			$_SESSION['currency_value'] 	= $data['value'];
			$_SESSION['currency_symbol'] 	= $data['symbol'];
			$_SESSION['c'] = 1;
 			}
			
		}elseif(isset($_REQUEST['c'])){	
 
			$data = $ThemeDesign->CURRENCYEXCHANGE($_REQUEST['c']);
	
			if($data['code'] !=""){
	
			unset($_SESSION['currency']);
			unset($_SESSION['currency_symbol']);
			unset($_SESSION['currency_value']);

			$_SESSION['currency_symbol'] 	= $data['symbol'];
			$_SESSION['currency_caption'] 	= $data['caption'];
			$_SESSION['currency_code'] 		= $data['code'];			 
			$_SESSION['currency_value'] 	= $data['value'];	

			$_SESSION['c'] = 1;
			
			}

		}

	}else{
	
		$_SESSION['currency_symbol'] 	= get_option("currency_symbol"); // DEFAULT CURRENCY
		$_SESSION['currency_code'] 		= get_option("currency_code");
	}
	 
	
	if($_SESSION['currency_symbol'] == ""){  $_SESSION['currency_symbol'] 	= get_option("currency_symbol"); }
 	if(!isset($_SESSION['currency_code']) || $_SESSION['currency_code'] == ""){  $_SESSION['currency_code'] 		= get_option("currency_code"); }
}

add_action('premiumpress_action','shopperpress_actions'); // add in new hook









function CheckDownloadLinks(){

global $wpdb, $ThemeDesign, $PPT; 

// CHECK FOR DOWNLOADABLE FILES
if(isset($GLOBALS['PPTorderID'])){
	$dwl = $ThemeDesign->CheckDownloadLinks($GLOBALS['PPTorderID']); $dwl_content = "";
	if(is_array($dwl)){		
		foreach($dwl as $download){ 
		
		@setcookie("ItemDownload".$download['id'], $download['id']."-".$userdata->ID, time()+3600*100);
		
		}			
	}
}

 if(is_array($dwl)){
            
        foreach($dwl as $download){
                
                                 
                    $dwl_content .= "<div class='downloaditem'>";
                    
                    if(strlen($download['image']) > 2){
                    
                        $dwl_content .= " <img src='".premiumpress_image_check($download['image'],"m")."'>";
                    
                    }                    
                    
                    $dwl_content .= "<strong>".$download['name']."</strong><p>";
                    
                    $dwl_content .= '<form action="'.$GLOBALS['bloginfo_url'].'/" method="POST" name="downloadform'.$download['id'].'">';
                    $dwl_content .= wp_nonce_field('FileDownload');
                    $dwl_content .= "<input type='hidden' name='hash' value='123'>
                     <input type='hidden' name='force' value='1'>
                    <input type='hidden' name='fileID' value='".($download['id']*800)."'>";
                 
                    $dwl_content .= '<a  href="javascript:void(0);" onclick="document.downloadform'.$download['id'].'.submit();" >';
                    $dwl_content .= $PPT->_e(array('sp','7'));
                    $dwl_content .= "</a> </form> </p></div>";
                
        }
        
        echo $dwl_content;			
    }  

}

add_action('premiumpress_callback_thankyou','CheckDownloadLinks');









/* =============================================================================
   Get Cart Sub Total
   ========================================================================== */

function getCartSubtotal($products,$tax=0) {

	if(!isset($GLOBALS['shopperpress']['product_price_extra'])){ $GLOBALS['shopperpress']['product_price_extra'] = 0; }
	$total = 0; 
 
	if(is_array($products)){		 
 
			foreach($products as $key => $quantity) {
			
			 //print_r($_SESSION['ddc'][$key])."<--<br>";	
			 
				

				if(isset($_SESSION['ddc'][$key]['main_ID'])){	$productID = $_SESSION['ddc'][$key]['main_ID'];	}else{  $productID =$key; }		
					 
				if(isset($_SESSION['ddc'][$key]['custom3']) && is_numeric($_SESSION['ddc'][$key]['custom3'])){ $total += GetPrice($_SESSION['ddc'][$key]['custom3'])*$quantity-$GLOBALS['shopperpress']['product_price_extra']; }
				
 				if(isset($_SESSION['ddc'][$key]['custom6']) && is_numeric($_SESSION['ddc'][$key]['custom6'])){ $total += GetPrice($_SESSION['ddc'][$key]['custom6'])*$quantity-$GLOBALS['shopperpress']['product_price_extra']; }
				
				 //echo "QTY: ".$quantity." <br>";
				 //echo "Price Extra: ".$GLOBALS['shopperpress']['product_price_extra']." <br>";	
				// echo "Custom 3: ".$_SESSION['ddc'][$key]['custom3']." <br>";
 				// echo "Custom 6: ".$_SESSION['ddc'][$key]['custom6']." <br>";
				 
				$total += (getProductPrice($productID))*$quantity;

			}
	}
	
	return $total;
}

/* ============================= KEY CART FUNCTION ========================= */


function updatePrice() {
 
global $wpdb; $total = 0; $extraPrice =0;

if(isset($_SESSION['ddc']['productsincart']) && is_array($_SESSION['ddc']['productsincart']) ) {

	foreach($_SESSION['ddc']['productsincart'] as $product => $quantity) { 

			if(isset($_SESSION['ddc'][$product]['main_ID'])){	$productID = $_SESSION['ddc'][$product]['main_ID'];	}else{ $productID  =  $product; }						
			if(isset($_SESSION['ddc'][$product]['custom3']) && is_numeric($_SESSION['ddc'][$product]['custom3'])){ $total += GetPrice($_SESSION['ddc'][$product]['custom3'])*$quantity; }
			if(isset($_SESSION['ddc'][$product]['custom6']) && is_numeric($_SESSION['ddc'][$product]['custom6'])){ $total += GetPrice($_SESSION['ddc'][$product]['custom6'])*$quantity; }  
	 
			$total += getProductPrice($productID)*$quantity;
		
	}
	 
}
		 
return $total;
		
}

function updateQty() {
 
	$qty = 0;
	
		if(is_array($_SESSION['ddc']['productsincart'])){
			foreach($_SESSION['ddc']['productsincart'] as $qtyval) {
				$qty = $qty + $qtyval;
			}
		}
		$_SESSION['ddc']['cartqty'] = $qty;
		return $qty;

}

 


/* =============================================================================== */

 



function getProductPrice($id, $copyID="") {

		global $wpdb;
 
		if(is_numeric($copyID) && $copyID != 0){ $id = $copyID; }

		if(function_exists('get_post_meta')){			
 
			$PriceIncludingExtra = GetPrice(get_post_meta($id, "price", true));	
						
			return $PriceIncludingExtra;	
		} 
	  }



 



	function GetPrice($price=0){
	
	global $userdata, $ThemeDesign;
 
	 if(isset($GLOBALS['shopperpress']['product_price_extra'])){
	 $TotalPrice = $price+$GLOBALS['shopperpress']['product_price_extra'];
	 }else{
	 $TotalPrice = $price;
	 }
		 

	// CURRENCY OPTIONS BUILT INTO 2.10
	
	if(!isset($_SESSION['currency_value']) || $_SESSION['currency_value']=="" || ( isset($_REQUEST['c']) && $_REQUEST['c'] =="USD" ) ){$_SESSION['currency_value']=1;} // || ( isset($_REQUEST['c']) && $_REQUEST['c'] =="GBP" )
 
	$TotalPrice = $TotalPrice*$_SESSION['currency_value'];
 
	if(isset($GLOBALS['shopperpress']['price_tag']) && $GLOBALS['shopperpress']['price_tag'] == "yesvat"){
	
		// CUSTOM PERCENTAGE ADDED IN 7.0.9.5
		$perc = get_option("display_pricetag_percent");
		if(!is_numeric($perc)){ $perc = 20; }
		$TotalPrice += $TotalPrice/100*$perc;
	 
	}// end vat
	
 
		if(isset($GLOBALS['shopperpress']['price_tag']) && $GLOBALS['shopperpress']['price_tag'] == "no"){
			return "";
		}elseif(isset($GLOBALS['shopperpress']['price_tag']) && $GLOBALS['shopperpress']['price_tag'] == "member"){				 
			if($userdata->ID > 0){
				return $TotalPrice;
			}else{
				return;
			}			
		}else{
			return $TotalPrice; 
		}
		
	
	}












	
	
	
	
function ShopperPressCustomFieldTxt($key, $value,$num){

	global $wpdb;
	
	$ListBoxValues 	= get_post_meta($key, "customlist".$num, true);
	
	$SIZEARRAY = explode(",",$ListBoxValues);
	
	foreach($SIZEARRAY as $value1){
	
		$c3v = explode("=",$value1);
	 
		if($c3v[0] == $value){
		
			return $c3v[1];
		
		}
	
	}
 

}


	function getProductName($id, $copyID="") {

		global $wpdb;

		if(is_numeric($copyID) && $copyID != 0){ $id = $copyID; }

		$post_id_7 = get_post($id); 
		if(isset($post_id_7->post_title)){		
			$title = $post_id_7->post_title;
			return $title;
		}
	}
 

	function getProductImg($id, $copyID="") {

		global $wpdb;

		if(is_numeric($copyID) && $copyID != 0){ $id = $copyID; }

		return get_post_meta($id, "image", true);
	}	
	
	
	
	function displayProducts($products) {
		
	$list = "";
		
		
		if($products != "") {

			//$products = array_reverse($products);
			foreach($products as $key => $value) {
			
			if(isset($_SESSION['ddc'][$key]['main_ID'])){
			$PriceKey = $_SESSION['ddc'][$key]['main_ID'];					 
			}else{
			$PriceKey = $key;
			}

			$list .= '<div id="ddcproduct'.$key.'"  productname="'.getProductName($PriceKey).'" qty="'.$value.'" price="'.getProductPrice($PriceKey).'" style="display:none;">
			 
			<div id="cell1">'.getProductName($key).'</div>
			<div id="cell2">'.$value.'</div>
			<div id="cell3">$'.getProductPrice($key).'</div>
			
			</div>';
				
			}

		} 
		
		return $list;
	}



 

		
	
	
	
	
	
	
	
	
	
	
	function CartAction($type="add",$data,$featured=0,$useCustomArray=0){

		global $post, $PPT; $link="";
		
		if($type == "remove"){		
		
			return "CheckoutAlert1('".str_replace("'","",$PPT->_e(array('sp','10')))."', '".get_option("checkout_url") ."','".$data."','".$featured."')";	
		
		}elseif($type == "removeall"){		
		
			return "CheckoutAlert3('".str_replace("'","",$PPT->_e(array('sp','10')))."', '".get_option("checkout_url") ."','".$data."','".$featured."')";	
		
		}elseif($type =="increase"){
		
			return " CheckoutAlert2('".str_replace("'","",$PPT->_e(array('sp','10')))."', '".get_option("checkout_url") ."','".$data."','".$featured."','".$useCustomArray."')";
		
		
		}elseif($type == "add"){

			 $fqty=1; $fcolor="na"; $fsize="na";  
	
			if($useCustomArray ==0){
			
				if($featured ==1){
					$fID 		= $thepost->ID;
					$ftitle 	= $thepost->post_title;
					$fprice 	= GetPrice(get_post_meta($thepost->ID, "price", true));
				}else{
					$fID 		= $post->ID;
					$ftitle 	= $post->post_title;
					$fprice 	= GetPrice(get_post_meta($data->ID, "price", true));	
				}
	
			}else{
					$fID 		= $data['id'];
					$ftitle 	= $data['title'];
					$fprice 	= GetPrice($data['price']);
			}
			 
			//if(isset($GLOBALS['shopperpress']['StockControl']) && $GLOBALS['shopperpress']['StockControl'] == "yes"){
			
			$ThisQty 	= get_post_meta($data->ID, "qty", true);
			if(!is_numeric($ThisQty)){ $ThisQty =0; }
			
			$link .="CheckRemaindingQty(".$ThisQty.");";
			 
			//}
	
			if($featured > 1){ $featured=1; }
			 
			$ftitle = preg_replace('/[^\w\d_ -]/si', '', $ftitle);
			
			 $link .= "addProduct('".session_id()."','".get_template_directory_uri()."/', '', 1, '".$fID."|ITEM|".str_replace(",","",$fprice)."','".$featured."','".$PPT->_e(array('validate','0'))."','".$PPT->_e(array('sp','9'))."','".str_replace("http://","",$GLOBALS['premiumpress']['checkout_url'])."'); ";   
 		 
		}

		return $link;	
	
	}
	
	
	
	
	
	
	
	 
	
	
	
	
	
	

class Theme_Design {

 
function GALLERYBLOCK(){

	global $PPTDesign;
	
	return $PPTDesign->GALLERYBLOCK();
}



/* =============================================================================
   CUSTOM ORDER BY BOX ON GALLERY PAGE // V7 // MARCH 16TH
   ========================================================================== */		

function OrderBy(){
	 
global $PPT, $wpdb; $STRING = ""; 
	 
if(!isset($GLOBALS['premiumpress']['catID'])){ $GLOBALS['premiumpress']['catID']=""; }
	 
if($GLOBALS['premiumpress']['catID'] == ""){ return; }
	 
	 	if(get_option("listbox_custom") =="1"){
		
	 
 	 $STRING .= '<dl id="sporderby" class="dropdown2">
			 
			 <dt><a href="#" ><span> '.get_option("listbox_custom_title").'<img src="'.$GLOBALS['template_url'].'/template_shopperpress/images/icons/select_right.gif" border="0" align="middle" class="right" alt="arrow" /></span></a></dt>
			 
			 <dd><ul>';				
			
			$i=1; $a=0; $lv = explode("**",get_option("listbox_custom_string"));
			while($i < 10){
				
				$title = $lv[$a]; $a++;
				$key = $lv[$a]; $a++;
				$value = $lv[$a]; $a++;
				$extra = $lv[$a]; $a++;
				if(strlen($title) > 1){
				
				if(isset($_GET['s'])){ $extra .= "&s=".strip_tags($_GET['s']).""; }
				 
				$STRING .= '<li><a href="'.get_option('siteurl').'/index.php?cat='.$GLOBALS['premiumpress']['catID'].'&amp;orderby=meta_value&amp;key='.$key.'&amp;order='.$value.$extra.'" rel="nofollow">'.$title.'</a></li>';
				
				 } 
				  
			$i++; }
			
				$STRING .= '</ul></dd></dl>';
		
		}
	 
	return $STRING;
}

















 



// CURRENCY OPTIONS FOR MULTIPLE CURRENCY VALUES

	function CURRENCYEXCHANGE($CurrencyCode){

	$CC = array();

	$CurrencyCode = strip_tags($CurrencyCode);
	
	// CHECK THE DEFAULT ONE FIRST
	
	if($CurrencyCode == get_option("currency_code")){
	
			$CC['caption'] = get_option("currency_caption");
			$CC['code'] = get_option("currency_code");
			$CC['symbol'] = get_option("currency_symbol");
			$CC['value'] = get_option("currency_value");
			
			return $CC;
			
	}
 
	for($i=1; $i < 6; $i++){
	 	
		if( $CurrencyCode == get_option("currency_code_".$i)){		
		
			$CC['caption'] = get_option("currency_caption_".$i);
			$CC['code'] = get_option("currency_code_".$i);
			$CC['symbol'] = get_option("currency_symbol_".$i);
			$CC['value'] = get_option("currency_value_".$i);

		}
	
	}	
	
	return $CC;

	}





function CheckHasAmazonProducts(){

	if(is_array($_SESSION['ddc']['productsincart'])){   
	
		$flag=0;
	
		foreach($_SESSION['ddc']['productsincart'] as $key => $QUANTITY) { 
			
		
			if(isset($_SESSION['ddc'][$key]['main_ID'])){ $ProductID = $_SESSION['ddc'][$key]['main_ID']; } else{  $ProductID =$key; }
			
			if(get_post_meta($ProductID, "amazon_guid", true) != ""){
			
			$flag=1;
			}
		}
	
	}
	
	if($flag == 0){ return false; }else{ return true; }
	

}


 

/*****************************************************************
	THIS FUNCTION IS USED TO DISPLAY THE CART BASKET WHICH SHOWS
	THE TOTAL PRICE AND NUMBER OF ITEMS WITHIN YOUR CART
*******************************************************************/

function BASKET($type=1){

	global $wp,$PPT,$userdata; get_currentuserinfo(); 
	
	if($GLOBALS['premiumpress']['currency_position'] == "r"){
	
		$string = '<span id="cartqty">'.$_SESSION['ddc']['cartqty']."</span> / <span id='carttotal'>".premiumpress_price($_SESSION['ddc']['price'],"",$GLOBALS['premiumpress']['currency_position'],1,2,true)."</span>".$_SESSION['currency_symbol'].""; 
	
	}else{
		$string = '<span id="cartqty">'.$_SESSION['ddc']['cartqty']."</span> / ".$_SESSION['currency_symbol']."<span id='carttotal'>".premiumpress_price($_SESSION['ddc']['price'],"",$GLOBALS['premiumpress']['currency_position'],1,2,true)."</span>"; 

	}
		
	return $string;

} 

/*****************************************************************
	THIS FUNCTION IS USED TO DISPLAY THE CART ITEMS AND ARRAY OF 
	ITEMS ON THE CHECKOUT PAGE ONLY
*******************************************************************/

function CARTITEMS(){

global $PPT; $store1=0; $STRING = ""; $ENABLE_SHIPPING_COST 	= get_option('shipping_enable');
  
if(is_array($_SESSION['ddc']['productsincart']) && !empty($_SESSION['ddc']['productsincart']) ){  

	$STRING .= '<table id="hor-zebra"><thead><tr>
	<th scope="col">'.$PPT->_e(array('title','1')).'</th>
	<th scope="col">'.$PPT->_e(array('title','2')).'</th>
	<th scope="col">'.$PPT->_e(array('sp','1')).'</th>
	<th scope="col">'.$PPT->_e(array('title','21')).'</th>
	<th scope="col">'.$PPT->_e(array('title','3')).'</th>
	</tr></thead><tbody>'; 
	 
	foreach($_SESSION['ddc']['productsincart'] as $key => $QUANTITY) { 
	

	if(isset($_SESSION['ddc'][$key]['main_ID'])){ $ProductID = $_SESSION['ddc'][$key]['main_ID']; } else{  $ProductID =$key; }
	
	
	$PROPRICE = getProductPrice($ProductID);	
	
	if($PROPRICE != ""){	


		$QTYVALUE 	= get_post_meta($key, "qty", true); if($QTYVALUE == ""){ $QTYVALUE=100;  } // changed to auto show increase field
		
		$Item_ShippingCost = ($_SESSION['ddc'][$ProductID]['shipping']*$_SESSION['currency_value'])*$QUANTITY;
		
		
		$STRING .= '<tr id="ddcproductCheckoutt'.$key.'" >';// if($i%2){ class="odd" } 
 
		
		$TITLE = getProductName($ProductID);
		
		
		$STRING .= '<td><a href="'.get_permalink($ProductID).'" title="'.$TITLE.'" class="frame"><img src="'.premiumpress_image_check(getProductImg($ProductID)).'" alt="'.$TITLE.'" style="max-width:45px; max-height:45px;" /></a></td>
	
					<td valign="top"><a href="'.get_permalink($ProductID).'" title="'.$TITLE.'"><b>'.$TITLE.' (#'.$ProductID.')</a></b>';		
		
	 
					 
				$CF=1;  while($CF < 7){ 
						 
				if(isset($_SESSION['ddc'][$key]['custom'.$CF]) && $_SESSION['ddc'][$key]['custom'.$CF] !="na" && $_SESSION['ddc'][$key]['custom'.$CF] !="" && $_SESSION['ddc'][$key]['custom'.$CF] !="0" ){ 						 
								 
				$STRING .= '<br /><em>'.get_option('custom_field'.$CF).': ';
			
					if($CF == 3 || $CF ==6){ 					
								
						$store1 += GetPrice($_SESSION['ddc'][$key]['custom'.$CF])*$QUANTITY; 				 
								
						$STRING .= ShopperPressCustomFieldTxt($ProductID, $_SESSION['ddc'][$key]['custom'.$CF],$CF); 					
								
					}else{  
								 
					$STRING .= $_SESSION['ddc'][$key]['custom'.$CF]; 
					
					
					}
				
				$STRING .= '</em>';
				
				} 
							 
						 
			  $CF++; 
			  
			  } // end while
					
	
		
		
		if(isset($_SESSION['ddc'][ $key]['custom7']) && $_SESSION['ddc'][$key]['custom7'] !="na" && $_SESSION['ddc'][$key]['custom7'] !="" && $_SESSION['ddc'][$key]['custom7'] !="0"){ 
		
		
			$STRING .= '<p><em>'.$PPT->_e(array('sp','50')).': <a href="'.stripslashes(get_option("imagestorage_link"));
		
			if($CF == 3 || $CF ==6){  $STRING .= $_SESSION['currency_symbol']; } 
		
			$STRING .= $_SESSION['ddc'][$key]['custom'.$CF] .'" target="_blank"><img src="'.stripslashes(get_option("imagestorage_link")); 
			 
			if($CF == 3 || $CF ==6){  
			
				$store1 += $_SESSION['ddc'][$key]['custom'.$CF]*$QUANTITY;
							
				$STRING .= ShopperPressCustomFieldTxt($ProductID, $_SESSION['ddc'][$key]['custom'.$CF],$CF); 
				
			}else{ 
			
				$STRING .= $_SESSION['ddc'][$key]['custom'.$CF]; 
				
			} 
			
			$STRING .= '" style="max-width:50px; max-height:50px;" align="middle" /></a></em></p>';
		
		
		} 
		
		
		if($ENABLE_SHIPPING_COST == "yes"){ if(isset($_SESSION['ddc'][$key]['shipping']) && $_SESSION['ddc'][$key]['shipping'] !="na" && $_SESSION['ddc'][$key]['shipping'] !="0" && $ShippingCost > 0){ 
		
		
		$STRING .= '<p><em>'.$PPT->_e(array('title','25')).': '.$_SESSION['currency_symbol'].$_SESSION['ddc'][$key]['shipping'].'</em></p>';
		
		 } } 	
		
		
		$STRING .= '</td><td>';
		
		 
			
				$STRING .= '<a href="#" onclick="'.CartAction("remove",$key, $Item_ShippingCost).';"><img src="'.$GLOBALS['template_url'].'/template_shopperpress/images/delete.png" alt="remove" align="middle" class="checkoutadd" /></a>';
					
				 
				
				if($QUANTITY < $QTYVALUE){ 
			
				$STRING .= '<a href="#" onclick="'.CartAction("increase",$key, $Item_ShippingCost,$QTYVALUE).';"><img src="'.$GLOBALS['template_url'].'/template_shopperpress/images/add.png" alt="add" align="middle" class="checkoutadd" /></a>';
					
				} 
				
				$STRING .= '<div class="checkoutqty"><span id="CheckoutQty'.$key.'">'.$QUANTITY.'</span> </div>';
				
				
	
				
				
				$STRING .= ' </td>';
				
				
				$STRING .= '<td> <span class="Price">'.premiumpress_price($PROPRICE*$QUANTITY+$store1,$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1,2).'</span></td>';
	 
				
				
				$STRING .= '<td><a href="#" onclick="'.CartAction("removeall",$key, $Item_ShippingCost).';jQuery(\'#ddcproductCheckoutt'.$key.'\').hide();';
				$STRING .= 'jQuery(\'#CheckOut-Totals\').html(\'<img src='.$GLOBALS['template_url'].'/PPT/img/loader.gif\>\');"><img src="'.$GLOBALS['template_url'].'/template_shopperpress/images/cross.png" alt="remove" /></a></td> ';  
				 
	
		} // end if no price
	
 
     
	  if(isset($_SESSION['ddc']['productsincart'])){ echo displayProducts($_SESSION['ddc']['productsincart']); } // DO NOT REMOVED 



	$store1=0;


    $STRING .= '</tr>';
        
         }
         
         $STRING .= '</tbody></table><strong id="cartqty" style="color:white;">'. $_SESSION['ddc']['cartqty'].'</strong>';
		 
		  
		 
	}else{
	$STRING .= '<div class="yellow_box"><div class="yellow_box_content"><div align="center">'.$PPT->_e(array('sp','43')).'</div></div></div>'; 
	
	}

return  $STRING;
}







/*****************************************************************
	THIS FUNCTION IS USED TO DISPLAY THE SHIPPING METHODS
	ON THE CHECKOUT PAGE
*******************************************************************/

function SHIPPINGMETHODS(){

global $PPT; $store1=0; $STRING = "";

$i=1; while($i < 12){ 

	if(get_option('pak_enable_'.$i) == 1){
	
	// give it the first name
	if(!isset($spn)){ $spn = get_option("pak_name_".$i); }
 
		$p_price = get_option('pak_price_'.$i);
		
		if($i == 1){ $ext ="checked=checked"; }else{ $ext=""; }
		
		$STRING .= '<input name="form[shippingmethod]" onclick="document.getElementById(\'shippingmethod_name\').value=\''.get_option("pak_name_".$i).'\'" type="radio" value="'.$p_price.'"  '.$ext.'/> '.premiumpress_price(GetPrice($p_price),$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1)." - ".get_option('pak_name_'.$i)."<br />";
		
			
	}	
	
$i++;
}
 

$STRING .= $this->FEDEXMETHODS();
if(strlen($STRING) > 5){ // only add this if the string to not empty
	$STRING .= '<input id="shippingmethod_name" name="form[shippingmethod_name]" type="hidden" value="'.$spn.'">';
}

return premiumpress_shippingmethods($STRING);

}




function FEDEXMETHODS(){

return; // removed in v7, not setup 

}
/*****************************************************************
	THIS FUNCTION IS USED TO DISPLAY THE SHIPPING METHODS
	ON THE CHECKOUT PAGE
*******************************************************************/

function UPSMETHODS($test=false){

global $wpdb,$PPT; $STRING ="";
 
if(get_option("shipping_enable_UPS") =="yes"){

	$weightMetric = get_option("shipping_weight_metric");
	if($weightMetric == ""){ $weightMetric = "LBS"; }
 
 
		// You need a UPS accesskey, userid, password contact www.ups.com for this information.
		define('UPS_ACCESSKEY', get_option("shipping_UPS_accesskey"));
		define('UPS_USERID', get_option("shipping_UPS_userID"));
		define('UPS_PASSWD', get_option("shipping_UPS_password"));
		
		/** IMPORT VARIABLES **/
		if(!isset($GLOBALS['SHOPPERPRESS_WEIGHT']) || $GLOBALS['SHOPPERPRESS_WEIGHT'] == ""){ $GLOBALS['SHOPPERPRESS_WEIGHT']=1; }
		 
		$ups_rates = new ups_rates();

		$ups_rates->pickup_type = '01';		
	
		// You can have two weight types: LBS or KGS, depending on what country you ship FROM
		// depends on what weight type you choose for example England is KGS.
		$ups_rates->weight_type = $weightMetric;
		
		 
		$ups_rates->weight = $this->ShippingWeight();				// The weight of the shipment.
	
		$ups_rates->from_state = get_option("shipping_UPS_userSTATE");			// The state location you are shipping from.
		$ups_rates->from_zip = get_option("shipping_UPS_userZIP");//'90310';			// The zip location you are shipping from.
		$ups_rates->from_country = get_option("shipping_UPS_userCOUNTRY");//'US';		// The country location you are shipping from.
	
	
		if($test){
		$ups_rates->ship_zip = "01821";
		$ups_rates->ship_country = 'US';
		  $ups_rates->weight = 5;
		}else{
		
		if(isset($_POST['shipping']['postcode']) && strlen($_POST['shipping']['postcode']) > 1){
		$customerZip = $_POST['shipping']['postcode'];
		}elseif(isset($_POST['form']['postcode']) && strlen($_POST['form']['postcode']) > 1){
		$customerZip = $_POST['form']['postcode'];
		}else{
		$customerZip =  "01821";
		}
	 
		$ups_rates->ship_zip = $customerZip;
		
		
		if(isset($_POST['form']['country']) && $_POST['form']['country'] == "United States"){
		
			switch($_POST['form']['country']){
			
				case "United States": { 	$shipto = "US"; } break;
				case "United Kingdom": { 	$shipto = "UK"; } break;
				case "New Zealand": { 		$shipto = "NZ"; } break;
				case "China": { 		$shipto = "CN"; } break;
				case "France": { 		$shipto = "FR"; } break;
				case "Germany": { 		$shipto = "DE"; } break;
				
				default: {  $shipto = "US";} 
			}
				
		}else{
		$shipto = "US";
		}
		
		$ups_rates->ship_country = $shipto;	
		
		} 
		
		// This just tell's UPS if the address is a residential address or a business. It doesn't 
		// seem to make any difference, so I just leave it on. However, you can ask the customer if you want.
		$ups_rates->residential = true;
	
		// This tell's the script what should be the default shipping method. We get this from the customer 
		// when he changes the shipping option, then next time he see's the page, he see's the same ship method.
		//$ups_rates->default_rate = $_SESSION['ship_method'];
		 
		$select_list = $ups_rates->fetch_rates();
		 			 
		if (strpos($select_list, "error") === false) { return $select_list;} else {
		if($test){ return $select_list; }else{ return "";}
		
		}
		 


}

return $STRING;

}


/*****************************************************************
	THIS FUNCTION IS USED TO DISPLAY THE PAYMENT METHOD OPTIONS
	ON THE CHECKOUT PAGE
*******************************************************************/

function PAYMENTMETHODS(){

global $PPT; $i=0; $STRING = "";

	include(str_replace("functions/","",THEME_PATH)."/PPT/func/func_paymentgateways.php");
	
	// HOOK INTO THE PAYMENT GATEWAY ARRAY // V7
	$gatway = premiumpress_admin_payments_gateways($gatway);

	if(is_array($gatway)){
        foreach($gatway as $Value){
            if(get_option($Value['function']) =="yes"){ 
			
			if($i == 0){ $cK = 'checked=checked'; }else{ $cK = ''; }
		
			$STRING .= '<p><input name="form[payment_method]" type="radio" value="'.$Value['function'].'" '.$cK.' /> '.get_option($Value['function']."_name")."</p>";
			$i++;
			}
		}	
	}

return $STRING;

}
 
/*****************************************************************
	THIS FUNCTION IS USED TO DISPLAY SUB CATEGORIES ON THE GALLERY PAGE
	FOR ANY INNER CATEGORIES 
*******************************************************************/
function SUBCATEGORIES(){
	
		//$SHOWCATCOUNT = get_option("display_categories_count");
		 
		 
			if(is_home()){ 
	
				$Maincategories = get_categories('orderby='.get_option("display_homecats_orderby").'&pad_counts=1&use_desc_for_title=1&hide_empty=0&hierarchical=0&child_of=0&exclude='.str_replace("-","",get_option('article_cats'))); 
					
			}elseif( isset($GLOBALS['premiumpress']['catID']) ){
			
			
				$arg= array();
				$arg['child_of'] = $GLOBALS['premiumpress']['catID'];
				$arg['hide_empty'] = false;
				$arg['pad_counts'] = 1;
				$arg['exclude'] = str_replace("-","",get_option('article_cats'));
				$Maincategories = get_categories( $arg );
	 
				//$Maincategories = get_categories('orderby='.get_option("display_homecats_orderby").'pad_counts=1&use_desc_for_title=1&hierarchical=0&hide_empty=1&child_of='.$GLOBALS['premiumpress']['catID'].'&exclude='.); 
	  
			} 
	 
	
			if(isset($Maincategories)){
	
			$catlist=""; 
			$Maincatcount = count($Maincategories);	
	 
	
			if($Maincatcount > 0){ $catlist .= '<div class="StoreCategories"><ul>';}
	 
				foreach ($Maincategories as $Maincat) { if(strlen($Maincat->name) > 1){ 
		 
		 
					if(!is_home() && isset($GLOBALS['premiumpress']['catID']) && $Maincat->category_parent == $GLOBALS['premiumpress']['catID']){
			
						$catlist .= '<li><a href="'.get_category_link( $Maincat->term_id ).'" title="'.$Maincat->category_nicename.'">';
						$catlist .= $Maincat->name;
						 $catlist .= " (".$Maincat->count.')</a>'; }else{ $catlist .= '</a>'; //if($SHOWCATCOUNT =="yes"){ }
						//$catlist .= '</span></a>'; 
						
						
						$catlist .= '</li>';
			
					}
				}
			}
		
	
			if($Maincatcount > 0){ $catlist .= '</ul><div class="clearfix"></div></div>'; }
	
			echo $catlist;
	
			}	
		 
	
	}


/*****************************************************************
	THIS FUNCTION IS USED TO DISPLAY HOME PAGE CATEGORIES
*******************************************************************/
function HomeCategories(){

global $PPT;
	
		$SHOWCATCOUNT = get_option("display_categories_count");		 
		$SHOW_SUBCATS = get_option("display_50_subcategories");
	
		if(is_home()){ 
	
				$Maincategories = get_categories('orderby='.get_option("display_homecats_orderby").'&pad_counts=1&use_desc_for_title=1&hide_empty=0&hierarchical=0&child_of=0&exclude='.str_replace("-","",get_option('article_cats'))); 
					
		}elseif( isset($GLOBALS['premiumpress']['catID']) ){
			
			
				$arg= array();
				$arg['child_of'] = $GLOBALS['premiumpress']['catID'];
				$arg['hide_empty'] = false;
				$arg['pad_counts'] = 1;
				$arg['exclude'] = str_replace("-","",get_option('article_cats'));
				$Maincategories = get_categories( $arg );
	 
				//$Maincategories = get_categories('orderby='.get_option("display_homecats_orderby").'pad_counts=1&use_desc_for_title=1&hierarchical=0&hide_empty=1&child_of='.$GLOBALS['premiumpress']['catID'].'&exclude='.); 
	  
		} 
	 
	
			if(isset($Maincategories)){
	
			$catlist="";  $i=1;
			$Maincatcount = count($Maincategories);	
	 
	
			if($Maincatcount > 0){ $catlist .= '<div class="homeCategoryBox"><ul>';}
	 
				foreach ($Maincategories as $Maincat) { if(strlen($Maincat->name) > 1){ 
		 
		 
					if(is_home() && $Maincat->parent ==0){	
					
					if($SHOW_SUBCATS == "yes"){
						$categories= get_categories('child_of='.$Maincat->cat_ID.'&amp;depth=1&hide_empty=0&exclude='.str_replace("-","",get_option('article_cats'))); 
						$catcount = count($categories);
						
						if($catcount >10){ $imgPadding="padding-bottom:300px;"; }elseif($catcount > 5){ $imgPadding="padding-bottom:200px;"; }elseif($catcount > 3){ $imgPadding="padding-bottom:100px;"; }elseif($catcount > 1){ $imgPadding="padding-bottom:10px;"; }
					}			
					
					//$img = get_option("cat_extra_image_".$Maincat->term_id);
					//if($img == ""){ $img ="na.gif"; }
							
						$catlist .= '<li>';
						//style=""
						//if($img != ""){ $catlist .= '<img src="'.premiumpress_image_check($img).'&amp;w=50&amp;h=50" style="'.$imgPadding.'">'; }
						
						$catlist .= '<span><a href="'.get_category_link( $Maincat->term_id ).'" title="'.$Maincat->category_nicename.'" id="t'. $i.'" class="tp">'; $i++;
						$catlist .= $Maincat->name;
						if($SHOWCATCOUNT =="yes"){ $catlist .= " (".$Maincat->count.')</a></span>'; }else{ $catlist .= '</a></span>'; }
						//$catlist .= '</span></a>';		
	
							if($SHOW_SUBCATS == "yes"){
							
								
								
							 	$stopShow=0;
								$currentcat=get_query_var('cat');	
								if(count($categories) > 0){
								$catlist .= '<div style="margin-left:10px; margin-bottom:30px;">';
									foreach ($categories as $cat) { if($stopShow < 10){
										$catlist .= '<a href="'.get_category_link( $cat->term_id ).'" title="'.$cat->cat_name.'" class="sm">';
										$catlist .= $cat->cat_name;
										//if(get_option("display_categories_count") =="yes"){ $catlist .= " (".$cat->count.")"; }
										$catlist .= '</a> ';
										} $stopShow++;
									}
									$catlist .= '</div>';
								}	 
								
							}
						
						 $catlist .= '</li>';
						
					} elseif(!is_home() && isset($GLOBALS['premiumpress']['catID']) && $Maincat->category_parent == $GLOBALS['premiumpress']['catID']){
			
						$catlist .= '<li><span><a href="'.get_category_link( $Maincat->term_id ).'" title="'.$Maincat->category_nicename.'" class="suba"><b>';
						$catlist .= $Maincat->name;
						if($SHOWCATCOUNT =="yes"){ $catlist .= " (".$Maincat->count.')</b></a></span>'; }else{ $catlist .= '</b></a></span>'; }
						//$catlist .= '</span></a>';
						
							if($SHOW_SUBCATS == "yes"){
							
								$categories= get_categories('child_of='.$Maincat->cat_ID.'&amp;depth=1&hide_empty=0&exclude='.str_replace("-","",get_option('article_cats'))); 
								$catcount = count($categories);	
								
							 
								$currentcat=get_query_var('cat');	
								if(count($categories) > 0){
								$catlist .= '<div style="margin-left:10px; margin-bottom:30px;">';
									foreach ($categories as $cat) {
										$catlist .= '<a href="'.get_category_link( $cat->term_id ).'" class="sm">';
										$catlist .= $cat->cat_name;
										//if(get_option("display_categories_count") =="yes"){ $catlist .= " (".$cat->count.")"; }
										$catlist .= '</a> ';
									}
									$catlist .= '</div>';
								}	 
								
							}					
						
						
						
						
						$catlist .= '</li>';
			
					}
				} 
			}
		
	
			if($Maincatcount > 0){ $catlist .= '</ul><div class="clearfix"></div></div>'; }
	
			echo $catlist;
	
			}	
		 
}





/*****************************************************************
	THIS FUNCTION IS USED TO DISPLAY HOME PAGE FEATURED PRODUCTS
*******************************************************************/

function HOMEPRODUCTS($count=10){

	global $wpdb, $PPT; $i=1; $cat = get_option('display_home_products_cat'); $ids = get_option('display_home_products_IDs'); 
 
	if($cat != "" && $cat != "featured"){	$ex = "&cat=".$cat; }elseif($cat == "featured"){	$ex ="&meta_key=featured&meta_value=yes"; }else{	$ex =""; }
	
	if($cat == "choose" && $ids != "" && strlen($ids) > 2 ){  
	$string = array( 'post__in' => explode(",",$ids) );
	}else{
	$string = '&posts_per_page='.$count.'&orderby=rand'.$ex;
	}
 
		 
	return $string;

}


 



	function SIDEBARCATEGORIES($type=0){
	
			$catlist="";
			$TabsCat = get_option("display_tabbed_cats");
	 		$catCount = get_option("display_categories_count");
			$catCount1 = get_option("display_categories_count_inner");
			$Maincategories= get_categories('pad_counts=1&use_desc_for_title=1&hide_empty=0&hierarchical=0&exclude='.str_replace("-","",get_option('article_cats').",".get_option('article_cats')));
			
			$Maincatcount = count($Maincategories);	 
	
			$i=1;
			foreach ($Maincategories as $Maincat) { if(strlen($Maincat->name) > 1){ 
	 
			if($Maincat->parent ==0){
			
	
					$categories= get_categories('child_of='.$Maincat->cat_ID.'&amp;depth=1&hide_empty=0&exclude='.str_replace("-","",get_option('article_cats')).",".str_replace("-","",get_option('article_cats'))); 
					$catcount = count($categories);	
					
					if($TabsCat =="yes"){
						if($catcount ==0){ 
							$ThisLink = get_category_link( $Maincat->term_id );   $class="";
						}else{
							$ThisLink = "javascript:toggleLayer('DropList".$i."')";   $class="AA";
						}
					}else{
						$ThisLink = get_category_link( $Maincat->term_id );   $class="";
					}
	
					$ThisClass = ($i == count($Maincategories) - 1) ? '' : ''; //last
					$catlist .= '<li class="'.$ThisClass.$class.'">  <a href="'.$ThisLink.'" title="'.$Maincat->category_nicename.'">';
					$catlist .= $Maincat->name;
					if($catCount =="yes"){ $catlist .= " (".$Maincat->count.")"; }
					$catlist .= '</a>';
								 
			
					// do sub cats
					$currentcat=get_query_var('cat');				

					if(count($categories) > 0){
					$catlist .="<ul id='DropList".$i."' style='display:none;'>";
					if($class == "AA"){ 
						$catlist .= "<li class='sub'><a href='".get_category_link( $Maincat->term_id )."'>".$Maincat->name;
						if($catCount1 =="yes"){ $catlist .= " (".$Maincat->count.")"; }
						$catlist .=  "</a></li>"; 
					}
						foreach ($categories as $cat) {		
					
							$catlist .= '<li class="sub '.$ThisClass.'"> <a href="'.get_category_link( $cat->term_id ).'">';
							$catlist .= $cat->cat_name;
							if($catCount1 =="yes"){ $catlist .= " (".$cat->count.")"; }
							$catlist .= '</a></li>';
							 
							$i++;		
						}
					 $catlist .="</ul>";
					}
		
				$catlist .= '</li>';
				$i++;
			} 
	 } }
	return $catlist;
	
	}


  
	 
function SUBMENUDROPDOWN($type="language"){

global $post; $pLink = "";

$STRING = ''; $WEBSITEURL = $GLOBALS['bloginfo_url']; $TMPURL = $GLOBALS['template_url']."/template_shopperpress/images/langs/";

if(is_home()){
$pLink = get_home_url();

}elseif(is_single()){

get_permalink($post->ID);

}elseif(is_category()){

$pLink = get_category_link( $GLOBALS['premiumpress']['catID'] );

}elseif(isset($post->ID)){

$pLink = get_page_link($post->ID);
}


if($type == "language"){

	$CUSTOMLANG = get_option("display_subnav_flags_type");
	if($CUSTOMLANG == "yes"){ $tip = "link"; }else{ $tip = "link1";} 
	$flagsarray = array(
	
		array('name' => "English", "img" => "us.png", "link"=>"?l=english", "link1"=>"?l=english" ),
		array('name' => "French", "img" => "fr.png", "link1"=>"?l=french", 		"link"=>"http://translate.google.com/translate?js=n&amp;prev=_t&amp;hl=en&amp;ie=UTF-8&amp;layout=2&amp;eotf=1&amp;sl=auto&amp;tl=fr&amp;u=".$pLink ),
		array('name' => "German", "img" => "de.png", "link1"=>"?l=german",		"link"=> 'http://translate.google.com/translate?js=n&amp;prev=_t&amp;hl=en&amp;ie=UTF-8&amp;layout=2&amp;eotf=1&amp;sl=auto&amp;tl=de&amp;u='.$pLink ),
		array('name' => "Spanish", "img" => "es.png", "link1"=>"?l=spanish",	"link"=> 'http://translate.google.com/translate?js=n&amp;prev=_t&amp;hl=en&amp;ie=UTF-8&amp;layout=2&amp;eotf=1&amp;sl=auto&amp;tl=es&amp;u='.$pLink ),
		array('name' => "Russian", "img" => "ru.png", "link1"=>"?l=russian",	"link"=> 'http://translate.google.com/translate?js=n&amp;prev=_t&amp;hl=en&amp;ie=UTF-8&amp;layout=2&amp;eotf=1&amp;sl=auto&amp;tl=ru&amp;u='.$pLink ),
	);
	

	$STRING .= '<dl id="ShopperFlags" class="dropdown1">';
	
	
	if(isset($_SESSION['lang']) && $_SESSION['lang'] != "" && $_SESSION['lang'] != "language_english"){ $shot=false; 
		foreach($flagsarray as $lang){ 
			if($_SESSION['lang'] == strtolower(str_replace("language_","",$lang['name']))){
			$shot=true;
			$STRING .= '<dt><a href="#" title="'.$lang['name'].'"><span><img class="flag" src="'.$TMPURL.''.$lang['img'].'" alt="'.$lang['name'].'" />'.$lang['name']
			.'<img src="'.$TMPURL.'arrow.png" border="0" align="middle" class="pull" alt="arrow"/></span></a></dt>';
			}
		}
		if(!$shot){ $STRING .= '<dt><a href="#" title="'.$lang['name'].'"><span><img class="flag" src="'.$TMPURL.''.$lang['img'].'" alt="'.$lang['name'].'" />'.$lang['name']
			.'<img src="'.$TMPURL.'arrow.png" border="0" align="middle" class="pull" alt="arrow"/></span></a></dt>'; }
	}else{
	$STRING .= '<dt><a href="#" title="'.$flagsarray[0]['name'].'"><span><img class="flag" src="'.$TMPURL.''.$flagsarray[0]['img'].'" alt="'.$flagsarray[0]['name'].'" /> '.$flagsarray[0]['name'].'<img src="'.$TMPURL.'arrow.png" border="0" align="middle" class="pull" alt="arrow" /></span></a></dt>';
	}
	
 
 
	$STRING .= '<dd><ul>';
	foreach($flagsarray as $lang){
	$STRING .= '<li><a href="'.$lang[$tip].'" title="'.$lang['name'].'"><img class="flag" src="'.$TMPURL.''.$lang['img'].'" alt="'.$lang['name'].'" />'.$lang['name'].'</a></li>';
	}
	$STRING .= '</ul></dd></dl>';
 
			
			
	}elseif($type=="currency"){


		$STRING .='<dl id="ShopperPressCurrency" class="dropdown">';

		 if(isset($_SESSION['currency_caption']) && $_SESSION['currency_caption'] !=""){ 
		 
        $STRING .='<dt><a href="#"><span><img src="'.$GLOBALS['template_url'].'/template_shopperpress/images/'.$this->CurrencyIcon($_SESSION['currency_code']).'.png" border="0" alt="a" class="flag"  />'.$_SESSION['currency_caption'].'<img src="'.$TMPURL.'arrow.png" border="0" align="middle" class="pull" alt="arrow" /></span></a></dt>';

		 }else{ 
        
        $STRING .='<dt><a href="#"><span><img src="'.$GLOBALS['template_url'].'/template_shopperpress/images/'.$this->CurrencyIcon($_SESSION['currency_code']).'.png" border="0" alt="a" class="flag"  />'.get_option("currency_caption").'<img src="'.$TMPURL.'arrow.png" border="0" align="middle" class="pull" alt="arrow" /></span></a></dt>';

		}
		
        $STRING .= '<dd><ul>';
		
		
		$CC = array(); $string="";
	
		$CurrencyCode = strip_tags($_SESSION['currency_code']);

		// BASE CURRENCY

		$CC['caption'] = get_option("currency_caption");
		$CC['code'] = get_option("currency_code"); 
		
		switch($CC['code']){
				case "GBP": { $cimg = "money_pound"; } break;
				case "EUR": { $cimg = "money_euro"; } break;
				case "YEN": { $cimg = "money_yen"; } break;
				case "USD": { $cimg = "money_dollar"; } break;
				
				default: { $cimg = "money"; }
		}

		$STRING .= '<li><a href="?c='.$CC['code'].'"><img src="'.$GLOBALS['template_url'].'/template_shopperpress/images/'.$this->CurrencyIcon($CC['code']).'.png" border="0" class="flag" alt="a" align="middle" />&nbsp;&nbsp;&nbsp;&nbsp;  '.$CC['caption'].'</a></li>';
	 
		for($i=1; $i < 6; $i++){
		
			if( get_option("currency_code_".$i) !=""){		
			
				$CC['caption'] = get_option("currency_caption_".$i);
				$CC['code'] = get_option("currency_code_".$i);
				

				$STRING .= '<li><a href="?c='.$CC['code'].'"><img src="'.$GLOBALS['template_url'].'/template_shopperpress/images/'.$this->CurrencyIcon($CC['code']).'.png" border="0" class="flag" alt="a" align="middle" />&nbsp;&nbsp;&nbsp;&nbsp; '.$CC['caption'].'</a></li>';
	
			}
		
		}	
     
       $STRING .= '</ul></dd></dl>';


}		
			

return $STRING;			

}	 

function CurrencyIcon($code){

switch($code){
	case "GBP": { $cimg = "money_pound"; } break;
	case "EUR": { $cimg = "money_euro"; } break;
	case "YEN": { $cimg = "money_yen"; } break;
	case "USD": { $cimg = "money_dollar"; } break;	
	default: { $cimg = "money"; }
}
return $cimg;
}
	 
	 
	 
	 
	 
	 


 	
	
	
	
	
	
	
function CHECKOUTTOTALS(){ 

global $PPT; $STRING = ""; 

	$STRING .= '<li>'.$PPT->_e(array('title','23')).' <span id="CheckoutSubTotal_text">'.premiumpress_price($GLOBALS['SHOPPERPRESS_SUBTOTAL'],$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1,2).'</span>
	<input type="hidden" id="CheckoutSubTotal" value="'.$GLOBALS['SHOPPERPRESS_SUBTOTAL'].'">
	</li>';
		
	if($GLOBALS['SHOPPERPRESS_WEIGHT'] !=""){ 
	
		$STRING .= '<li>'.$PPT->_e(array('title','24')).' <span>'.$GLOBALS['SHOPPERPRESS_WEIGHT'].get_option("shipping_weight_metric");
		if($GLOBALS['SHOPPERPRESS_WEIGHT_PRICE'] != ""){
		$STRING .= ' ('.premiumpress_price($GLOBALS['SHOPPERPRESS_WEIGHT_PRICE'],$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1,2).') <input type="hidden" name="form[weightprice]" value="'.$GLOBALS['SHOPPERPRESS_WEIGHT_PRICE'].'">	';
		}
		$STRING .= '</span><input type="hidden" name="form[weight]" value="'.$GLOBALS['SHOPPERPRESS_WEIGHT'].'"> </li>';
	} 
	
	if(isset($GLOBALS['SHOPPERPRESS_WEIGHT_PRICE']) && $GLOBALS['SHOPPERPRESS_WEIGHT_PRICE'] > 0){ $GLOBALS['SHOPPERPRESS_SHIPPING'] += $GLOBALS['SHOPPERPRESS_WEIGHT_PRICE']; }
	
	if(get_option('shipping_enable') == "yes" && $GLOBALS['SHOPPERPRESS_SHIPPING'] > 0  ){	
	
	$STRING .= '<li>'.$PPT->_e(array('title','25')).' <span>'.premiumpress_price($GLOBALS['SHOPPERPRESS_SHIPPING'],$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1,2).'</span>
	<input type="hidden" name="form[shipping]" value="'.$GLOBALS['SHOPPERPRESS_SHIPPING'].'"></li>';
	
	}
	
	if($GLOBALS['SHOPPERPRESS_TAX'] != ""){ //BG160072254
	
	 
	
	$STRING .= '<li>'.$PPT->_e(array('title','26')).' <span>'.premiumpress_price($GLOBALS['SHOPPERPRESS_TAX'],$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1,2).'</span>
	<input type="hidden" name="form[tax]" value="'.round($GLOBALS['SHOPPERPRESS_TAX'],2).'"> 
	</li>';
	
	}
	
	if(get_option("enable_VAT") =="yes" && isset($_POST['form']['VATnum']) && $_POST['form']['VATnum'] !=""){
	
	$STRING .= '<li>'.$PPT->_e(array('sp','12')).' <span>'.premiumpress_price($GLOBALS['SHOPPERPRESS_TAX'],$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1,2).'</span> 
	<input type="hidden" name="form[tax]" value="'.($GLOBALS['SHOPPERPRESS_TAX']+$GLOBALS['SHOPPERPRESS_TAX']).'"></li> 
	';
	
	
	}	
	
	if($GLOBALS['SHOPPERPRESS_PROMOTIONS'] > 0 && ( $GLOBALS['SHOPPERPRESS_PROMOTIONS'] != $GLOBALS['SHOPPERPRESS_COUPONDISCOUNT'] ) ){
	
	$STRING .= '<li>'.$PPT->_e(array('sp','13')).' <span> -'.premiumpress_price($GLOBALS['SHOPPERPRESS_PROMOTIONS']-$GLOBALS['SHOPPERPRESS_COUPONDISCOUNT'],$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1,2).'</span>
	<input type="hidden" name="form[discount]" value="'.$GLOBALS['SHOPPERPRESS_PROMOTIONS'].'">
	</li>'; 
	}	
	
	if($GLOBALS['SHOPPERPRESS_COUPONDISCOUNT'] > 0){  
	$STRING .= '<li class="coupon">'.$PPT->_e(array('title','27')).' <span> -'.premiumpress_price($GLOBALS['SHOPPERPRESS_COUPONDISCOUNT'],$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1,2).'</span>
	<input type="hidden" name="form[discount]" value="'.$GLOBALS['SHOPPERPRESS_PROMOTIONS'].'"></li>'; 
 
	}
	
	if($GLOBALS['SHOPPERPRESS_CARTTOTAL'] < 0){ $GLOBALS['SHOPPERPRESS_CARTTOTAL']="0"; }
	
	$STRING .= '<li class="total">'.$PPT->_e(array('title','22')).' <span id="carttotal_text">'.premiumpress_price($GLOBALS['SHOPPERPRESS_CARTTOTAL'],$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1,2,true).'</span>
	<input type="hidden" id="carttotal" value="'.$GLOBALS['SHOPPERPRESS_CARTTOTAL'].'">	
	</li>
	';

return $STRING;
}	
	
	
	
	


	

	
	
 



function CheckDownloadLinks($orderID){

global $wpdb;

	$SQL = "SELECT order_items FROM ".$wpdb->prefix."orderdata WHERE order_id= ('".strip_tags(PPTCLEAN($orderID))."') GROUP BY order_id ORDER BY autoid DESC"; 
	$result = mysql_query($SQL, $wpdb->dbh) ;
	$array = mysql_fetch_assoc($result);	
	
	if(strlen($array['order_items']) > 0){

	// MUST PASS IN THE order_items FIELD FROM THE ORDERS DATA
	// FORMAT IS: 58x1,
	
	$o1 = explode(",",$array['order_items']); $i=0; $data = array();

	foreach($o1 as $items){
	
		$o2 = explode("x",$items);
		
		//$o2[0] <-- ITEM ID
		//$o2[1] <-- QUANTITY
		$dfile = get_post_meta($o2[0], "file", true);
		if(strlen($dfile) > 2){
		
			
		 $post_data = get_post( $o2[0] );
		 
		 $data[$i]['id']  	= $o2[0];	
		 $data[$i]['name'] 	= $post_data->post_title;
		 $data[$i]['qty']  	= $o2[1];
		 $data[$i]['file'] 	= $dfile;
		 $data[$i]['image'] = get_post_meta($o2[0], "image", true);
		
		}
	 
	 	$i++;
	 
	}
	
	}
	
	return $data;
}


 




















/* =============================================================================
   SHOPPERPRESS CORE CHECKOUT FUNCTION // V7 // UPDATED 29TH MARCH
   ========================================================================== */

function CHECKOUT(){

	global $PPT, $userdata; get_currentuserinfo();
	
	/* ====================== SHOPPERPPRESS SETUP DEFAULT VALUES FOR THE CHECKOUT PAGE =========================== */

	$ShippingCost 			= 0;
	$CountDiscount			= 0; 
	$ShippingCountryCost 	= 0;
	$TaxAmount				= 0;	
	$ExtraTax 				= 0;
	$TotalWeight			= 0;
	$TotalWeightPrice		= 0;
	$ThisTax 				= 0;
	$SHIPTHISCOUNTRYCHECK 	= 0;
	$ENABLE_SHIPPING_COST 	= get_option('shipping_enable');	
 
	/* ====================== SHOPPERPPRESS CALCULATE PROMOTIONS/COUPONS AND DISCOUNTS =========================== */
 
 	// CHECK FOR ANY COUPON CODES
	if(isset($_POST['ccode']) && strlen($_POST['ccode']) > 1){

		$FoundCoupon = $PPT->Coupon(strip_tags($_POST['ccode']));
			
		if(!is_array($FoundCoupon) && !isset($_POST['action']) ){ 
			 
			echo "<div class='red_box'><div class='red_box_content'><img src='".get_template_directory_uri()."/PPT/img/cross.png' align='absmiddle' /> ".$PPT->_e(array('sp','20'))."</div></div>";
			
		}
	}	
		
	if(isset($FoundCoupon) && is_array($FoundCoupon)){		
		
		if($FoundCoupon['price'] !="" ){  			
				$CountDiscount = $FoundCoupon['price']; 			
		}else{
			
				if(is_array($_SESSION['ddc']['productsincart'])){ 
					$EXCOUPON=0;
					foreach($_SESSION['ddc']['productsincart'] as $key => $value) { 
						if(isset($_SESSION['ddc'][$key]['main_ID'])){
						 $second_key = $_SESSION['ddc'][$key]['main_ID'];
						 $EXCOUPON += ($_SESSION['ddc'][$_SESSION['ddc'][$key]['main_ID']]['shipping']*$_SESSION['currency_value'])*$value;
						}else{
						 $second_key =0;
						 $EXCOUPON += ($_SESSION['ddc'][$key]['shipping']*$_SESSION['currency_value'])*$value;
						}
					}
				}
				 
				$CountDiscount = ($FoundCoupon['percentage']/100)*getCartSubtotal($_SESSION['ddc']['productsincart'])+$EXCOUPON;  			
			} 	
			
			 
			
			if(!isset($_POST['action'])){
			 
			echo "<div class='green_box'><div class='green_box_content'><img src='".get_template_directory_uri()."/PPT/img/admin/ok.png' align='absmiddle' /> ".$PPT->_e(array('sp','19'))."</div></div>";
			
			}
			
	}  // END CHECK COUPONS
  
    /* ====================== SHOPPERPPRESS CALCULATE THE SHOPPING COSTS =========================== */
 
 
 	if( $ENABLE_SHIPPING_COST == "yes"){
		
		// SETUP DEFAULTS FOR COUNTRY CHECKS
		if( isset($_POST['shipping']['country']) && strlen($_POST['shipping']['country']) > 1){ 
				$SHIPTHISCOUNTRYCHECK =  $_POST['shipping']['country']; 
		}elseif(isset($_POST['form']['country'])){
				$SHIPTHISCOUNTRYCHECK =  $_POST['form']['country'];  
		}
		

		// CHECK PRODUCT VALUES
		if(get_option("enable_weightshipping") =="1"){
	
			$TotalWeight 		= $this->ShippingWeight(); 
			$TotalWeightPrice 	= $this->SelectionPrice($TotalWeight, $SHIPTHISCOUNTRYCHECK,'sp_weightshipping'); 
	  
		}
 
	
		// LOOP THROUGH PRODUCTS IN THE BASKET, CHECK FOR A FIXED SHIPPING COST OR EXTRA COUNTRY COST
			
		if(isset($_SESSION['ddc']['productsincart']) && is_array($_SESSION['ddc']['productsincart'])){ 
	 
				foreach($_SESSION['ddc']['productsincart'] as $key => $value) { 
					if(isset($_SESSION['ddc'][$key]['main_ID'])){
					 $second_key = $_SESSION['ddc'][$key]['main_ID'];
					 $ShippingCost += ($_SESSION['ddc'][$_SESSION['ddc'][$key]['main_ID']]['shipping']*$_SESSION['currency_value'])*$value;
					 $perItemShipping = get_post_meta($second_key, 'shipping', true)*$value; // <-- EXTRA SHIPPING
					 
					 if(strlen($SHIPTHISCOUNTRYCHECK) > 2){ $perItemShipping .= get_post_meta($second_key, 'extraship_'.$SHIPTHISCOUNTRYCHECK, true)*$_SESSION['currency_value']; }  // <-- EXTRA COUNTRY SHIPPING 
					 
					 if(is_numeric($perItemShipping)){
					 	$ShippingCost += $perItemShipping;
					 }
					 
					}else{
					 $second_key =0;
					 $ShippingCost += ($_SESSION['ddc'][$key]['shipping']*$_SESSION['currency_value'])*$value;
					 
					 $perItemShipping = get_post_meta($key, 'shipping', true)*$value; // <-- EXTRA SHIPPING
					
					 if(strlen($SHIPTHISCOUNTRYCHECK) > 2){  $perItemShipping += get_post_meta($key, 'extraship_'.$SHIPTHISCOUNTRYCHECK, true)*$_SESSION['currency_value'];  }  // <-- EXTRA COUNTRY SHIPPING 
					 
					 if(is_numeric($perItemShipping)){
					 	$ShippingCost += $perItemShipping;
					 }
					}
				}
		}else{
		
			$_SESSION['ddc']['productsincart'] = array(); // product list is empty! something wrong..why are they at the checkout page?
			
		}
 
		// NOW CALCULATE EXTRA SYSTEM SHIPPING OPTIONS
		$ShippingCost = $this->SystemShipping(getCartSubtotal($_SESSION['ddc']['productsincart'],2)-$CountDiscount, $SHIPTHISCOUNTRYCHECK, $ShippingCost);
  		
		// SYSTEM COUNTRY BASED SHIPPING
		if(isset($_POST['form']['country']) && is_numeric($ShippingCost) && $ShippingCost != "-99" ){
			  
				if(strlen(get_option("shipping_country_fixed_".$SHIPTHISCOUNTRYCHECK)) > 0){
				 
				$ShippingCountryCost  = get_option("shipping_country_fixed_".$SHIPTHISCOUNTRYCHECK)*$_SESSION['currency_value'];

				}elseif(strlen(get_option("shipping_country_perc_".$SHIPTHISCOUNTRYCHECK)) > 0){ 
 
				$ShippingCountryCost  = getCartSubtotal($_SESSION['ddc']['productsincart'])/100*get_option("shipping_country_perc_".$SHIPTHISCOUNTRYCHECK)*$_SESSION['currency_value'];

				}
 				 
				$ShippingCost += $ShippingCountryCost;
			}
 
		} 
		 
		// MAKE GLOBAL FOR PLUGINS ETC
		$GLOBALS['SHIPTHISCOUNTRYCHECK'] = $SHIPTHISCOUNTRYCHECK;


		/* ====================== SHOPPERPPRESS CALCULATE THE TAX AMOUNT =========================== */		
 		
		if(get_option("checkout_tax_enable") ==	"yes"){    
     
			$ThisTax 	= get_option("checkout_tax_amount");			
			$subtotal 	= premiumpress_price(getCartSubtotal($_SESSION['ddc']['productsincart']));	 //-$CountDiscount
			$subtotal 	=  $subtotal - $CountDiscount+$ShippingCost;
			$TaxAmount  =  $subtotal/100*$ThisTax; 
 
		}		
 
		// include shipping
		if(isset($TotalWeightPrice)){
		 
			$TaxAmount = $TaxAmount + ($TotalWeightPrice/100*$ThisTax);
			 
		}
		 
		// SHIPPING METHODS PASSED IN BY UPS FOR PLUGIN
		if(isset($_POST['form']['shippingmethod']) && $ShippingCost != "-99" ){	
			 
			$ShippingCost += GetPrice($_POST['form']['shippingmethod']);
			 //if(isset($_POST['form']['shippingmethod-basic'])){
			 	//$ShippingCost += $_POST['form']['shippingmethod-basic'];
			 //} 
		}			
 
		// SEE IF TAX IS ENABLED ON SHIPPING
		if(get_option('checkout_ctax_enable') == "yes"){
 
			if(isset($_POST['form']['country']) ){  		
					$ExtraTax = $this->CountryTax($ShippingCost+$TotalWeightPrice);	// <-- pass in total shipping cost
			}
		}
		
		$TaxAmount += $ExtraTax;
		 
		if($TaxAmount < 0){ $TaxAmount=0; }//<-- JUST IN CASE :)
		
		/* ====================== PER PRODUCT VAT CALCULATIONS =========================== */

		if(get_option("enable_VAT") =="yes"){
		
		$newAmt = 0; $extra_coupon_discount = 0;
		 
			if(is_array($_SESSION['ddc']['productsincart'])){ 
				foreach($_SESSION['ddc']['productsincart'] as $key => $value) { 
				
					if(isset($_SESSION['ddc'][$key]['main_ID'])){					  
					 
					 $perItemVAT = get_post_meta($_SESSION['ddc'][$key]['main_ID'], 'vat', true);
					 if(is_numeric($perItemVAT)){
					 	
						// GET THE ITEM PRICE
					 	$item_price = get_post_meta($_SESSION['ddc'][$key]['main_ID'], 'price', true);					 
					 	// CHECK IF COUPON IS APPLIED THEN RE-CALUCLATE THE TAX
						if($CountDiscount > 0){			
						
							if(isset($FoundCoupon['percentage']) && is_numeric($FoundCoupon['percentage']) ){
							$new_vat_coupon_discount = (($item_price/100)*$perItemVAT);
							$new_vat_coupon_discount = ($new_vat_coupon_discount/100)*$FoundCoupon['percentage'];
							}
							
							
							$extra_coupon_discount += $new_vat_coupon_discount;	
								 
						}else{
					 
					 		$newAmt +=  ( $item_price ) *$value/100*$perItemVAT;	
						
						}					
					 }
					 
					}else{			
					 
					 $perItemVAT = get_post_meta($key, 'vat', true);
					 if(is_numeric($perItemVAT)){
					 
					 	// GET THE ITEM PRICE
					 	$item_price = get_post_meta($key, 'price', true);
						// CHECK IF COUPON IS APPLIED THEN RE-CALUCLATE THE TAX
						if($CountDiscount > 0){							 			
							//$new_vat_coupon_discount = (($perItemVAT/100)/$item_price)*100; /$FoundCoupon['percentage']
							if(isset($FoundCoupon['percentage']) && is_numeric($FoundCoupon['percentage']) ){
							//$new_vat_coupon_discount = (($item_price/100)*$perItemVAT);
							//$new_vat_coupon_discount = ($new_vat_coupon_discount/100)*$FoundCoupon['percentage'];
							$price_with_discount_applied = $item_price-($item_price/100*$FoundCoupon['percentage']);
							
							}
						
							//$extra_coupon_discount += $new_vat_coupon_discount;
							
							
							$newAmt += ($price_with_discount_applied)*$value/100*$perItemVAT; 
							
							//die("Counpon Discount: %".$FoundCoupon['percentage']."<br> 
							//Price: $".$item_price." with discount( $".$price_with_discount_applied.")<br> 
							//QTY:".$value."<br>
							//Sub-Total:".($price_with_discount_applied*$value)." <br> 
							//VAT %".$perItemVAT."<br> 
							//Total:".(($price_with_discount_applied*$value)-$newAmt)." <br>");		 
						
						 
						}else{
				  		
							$newAmt += ($item_price)*$value/100*$perItemVAT; 
							
							//die("price:".$item_price." <br> QTY:".$value." <br>sub-Total:".($item_price*$value)." <br> VAT %".$perItemVAT."<br> Total:".(($item_price*$value)-$newAmt)." ");		 
						 	
						
						}
						
					 }
					 
					}
				}
				
				$CountDiscount = ($FoundCoupon['percentage']/100)*getCartSubtotal($_SESSION['ddc']['productsincart'])+$EXCOUPON; 
				
			}
			
			 //die($newAmt." -- ".$new_vat_coupon_discount);
			 
			//$CountDiscount += $extra_coupon_discount;
			$TaxAmount += $newAmt;//-$new_vat_coupon_discount;
			 
			
			//if(get_option("enable_shipping_tax") =="1" && $shippingCost != "-99"){
				//$TaxAmount += $ShippingCost/100*20;
				//die($TaxAmount."<--".$ShippingCost);
			//}
			//$COLLECTEDVAT = $TaxAmount;
		}
		 
		/* ====================== SHOPPERPPRESS PAYMENT TOTALS ARE WORKED OUT HERE =========================== */
 
	    if($ShippingCost == "-99"){ $ShippingCost = 0; }
		
		$GLOBALS['SHOPPERPRESS_SUBTOTAL'] 			= getCartSubtotal($_SESSION['ddc']['productsincart']);//premiumpress_price();
		 
		 
		$GLOBALS['SHOPPERPRESS_SHIPPING'] 			= premiumpress_price($ShippingCost);
		
		// FREE SHIPPING
		$GLOBALS['SHOPPERPRESS_FREESHIPPING'] = false;
		if(get_option("shipping_free") =="yes" && $GLOBALS['SHOPPERPRESS_SUBTOTAL'] > get_option('shipping_free_price')){
		$GLOBALS['SHOPPERPRESS_SHIPPING'] = 0;
		$TotalWeightPrice=0;
		$ShippingCountryCost=0;
		$GLOBALS['SHOPPERPRESS_FREESHIPPING'] = true;
		
		if(isset($_POST['form']['shippingmethod'])){ $GLOBALS['SHOPPERPRESS_SHIPPING'] = $_POST['form']['shippingmethod']; }
		
		}
		
		 
		$GLOBALS['SHOPPERPRESS_SHIPPINGCOUNTRY']	= $ShippingCountryCost;	
		$GLOBALS['SHOPPERPRESS_TAX']				= $TaxAmount;
		 
		if(get_option("enable_VAT") =="yes" && isset($_POST['form']['VATnum']) && $_POST['form']['VATnum'] !=""){
		 
		 $TaxAmount = $TaxAmount + $ShippingCost/100*20;
		 
		$GLOBALS['SHOPPERPRESS_TAX_SAVING']			= $TaxAmount;
		}else{
		$GLOBALS['SHOPPERPRESS_TAX_SAVING'] = 0;
		}
		$GLOBALS['SHOPPERPRESS_WEIGHT'] 			= $TotalWeight;
		$GLOBALS['SHOPPERPRESS_WEIGHT_PRICE'] 		= $TotalWeightPrice;		
		$GLOBALS['SHOPPERPRESS_COUPONDISCOUNT']		= $CountDiscount;		 
		$GLOBALS['SHOPPERPRESS_PROMOTIONS']			= ShopperPressPromotions($_SESSION['ddc']['productsincart'],$GLOBALS['SHOPPERPRESS_SUBTOTAL'])+$CountDiscount;	 
 
		$GLOBALS['SHOPPERPRESS_CARTTOTAL'] 			= $GLOBALS['SHOPPERPRESS_SUBTOTAL']-($GLOBALS['SHOPPERPRESS_PROMOTIONS'])+($GLOBALS['SHOPPERPRESS_TAX']+$GLOBALS['SHOPPERPRESS_WEIGHT_PRICE']+$GLOBALS['SHOPPERPRESS_SHIPPING']-$GLOBALS['SHOPPERPRESS_TAX_SAVING']);		
 
		//ORDER ID STRUCTURE IS: [post IDs]-[user ID]-[type]-[new package ID]
		$GLOBALS['CHECKOUT_ORDERID'] = $userdata->ID."-".date("d")."".time();
		
		/*$debug = "<div style='padding:10px; display:clear; background:#efefef;'>";
		$debug .= "Sub Total: ".$GLOBALS['SHOPPERPRESS_SUBTOTAL']."<br>";
		$debug .= "Shipping: ".$GLOBALS['SHOPPERPRESS_SHIPPING']."<br>";
		$debug .= "Shipping Country: ".$GLOBALS['SHOPPERPRESS_SHIPPINGCOUNTRY']."<br>";
		$debug .= "Tax: ".$GLOBALS['SHOPPERPRESS_TAX']."<br>";
		$debug .= "Weight: ".$GLOBALS['SHOPPERPRESS_WEIGHT']."<br>";
		$debug .= "Weight Price: ".$GLOBALS['SHOPPERPRESS_WEIGHT_PRICE']."<br>";
		$debug .= "Coupon Discount: ".$GLOBALS['SHOPPERPRESS_COUPONDISCOUNT']."<br>";
		$debug .= "Promotions: ".$GLOBALS['SHOPPERPRESS_PROMOTIONS']."<br></div>";
		
		 echo $debug;*/ 
		
}

function ShippingWeight(){
global $wpdb;
$TotalWeight=0;
if(is_array($_SESSION['ddc']['productsincart'])){ 

			foreach($_SESSION['ddc']['productsincart'] as $key => $value) { 
 
				if(isset($_SESSION['ddc'][$key]['main_ID'])){	$productID = $_SESSION['ddc'][$key]['main_ID'];	}else{  $productID =$key; }	
 
				$lbs = get_post_meta($productID, "lbs", true);	
				if($lbs == ""){
				$lbs = get_post_meta($productID, "weight", true);				
				}
				if($value < 1){ $value=1; }

				if(is_numeric($lbs) && $lbs > 0){
					$TotalWeight = $TotalWeight + ($lbs * $value);
				}
			}
		
		}

	return $TotalWeight;
}	
function SelectionPrice($TotalWeight=0, $Country="",$val=""){

global $wpdb; $total=0;$a=1;  $sp_weightshipping = get_option($val); 
 
if(is_array($sp_weightshipping) && !empty($sp_weightshipping)){
 
	foreach($sp_weightshipping as $value){
	
	 	$canContinue=true;
	  
		// CHECK COUNTRY RESTRICTIONS
		if(is_array($value['cl']) && !empty($value['cl'])){
		
			if($Country == ""){ 
			
				$canContinue = false; // no country selected by user 
			
			}elseif(!in_array($Country,$value['cl'])){
			
				$canContinue = false; // not in the country list for this weight
			}
		
		}else{
		
		$canContinue = true;
		
		}
		
		//echo $TotalWeight."> ".$value['a']." and less than ".$value['b']."<br>";
		
		if($TotalWeight >= $value['a'] && $TotalWeight <= $value['b'] && $canContinue  ){ 
			$total += GetPrice($value['c']);		
		}	
	
	}

}
 
return $total;

}
function SystemShipping($total, $SHIPTHISCOUNTRYCHECK, $currentShippingPrice){
 
		$CurrentPrice = $total;
		$total=0;
		
		
		// FLAT RATE SHIPPING
		$total += GetPrice(get_option("shipping_cost")); 

		// PRICE RATE SHIPPING // V7 // MARCH 30TH
		if(get_option("enable_priceshipping") =="1"){
		 
			$total = $this->SelectionPrice($CurrentPrice, $SHIPTHISCOUNTRYCHECK,'sp_priceshipping');
			if($total > 0){ 			
			return $total; // ADDED13H APRIL
			}
		}
		
		// FREE SHIPPING DISCOUNT
		if(get_option("shipping_free") =="yes"){
 
			if( $CurrentPrice >= get_option("shipping_free_price") ){
 
				return -99;
	
			}		
		} 

	return $total+$currentShippingPrice;
}
function CountryTax($shippingCost){

	global $wpdb, $PPT;
	$extraTax = 0; $extraTax1 =0; 
	if($shippingCost < 0){ $shippingCost=0; }
	
	// GET THE PURCHASE TOTAL
	$total			= premiumpress_price(getCartSubtotal($_SESSION['ddc']['productsincart']));
 
 
	if(get_option("enable_shipping_tax") =="1" && $shippingCost != "-99"){
		$total +=  $shippingCost;
	}
	
	// GET THE DELIVERY COUNTRY
	$country 	= $_POST['form']['country'];
	$state 		= $_POST['form']['state'];

	if($_POST['shipping']['country'] != "" && strlen($_POST['shipping']['country']) > 1 ){
	$country 	= $_POST['shipping']['country'];
	$state 		= $_POST['shipping']['state'];
	}elseif($_POST['billing']['country'] != "" && strlen($_POST['billing']['country']) > 1 ){
	$country 	= $_POST['billing']['country'];
	$state 		= $_POST['billing']['state'];
	}
	
 
	// ADD EXTRA TAX ON FOR THE COUNTRY 	 
	$e1 = GetPrice(get_option("ctax_".$country));
	  //die($e1."<--".$country);
		if(is_numeric($e1)){	
				
			$extraTax =  ($total/100*$e1);	
					
		}
	
	$subtotal = $total+	$extraTax;
	 //echo $extraTax."<-- count tax ($subtotal)";
	// CHECK FOR STATE TAX+$extraTax
	if(isset($state) && strlen($state) > 1 ){
	
			$e2 = GetPrice(get_option("citytax_".$state));
			 
			if(is_numeric($e2)){	
					
				$extraTax1  = round(($subtotal/100*$e2),2);
			
			}

	}
	//echo $extraTax1."<-- state tax";
	 
	return $extraTax+$extraTax1;
 
}



/* =============================================================================
   EXTRA BUY LINKS FOR PRODUCT PAGE // V7 // UPDATED 29TH MARCH
   ========================================================================== */

function ExtraBuyLinks(){

	global $PPT, $post; $STRING = ""; $i=0;
	
	while($i < 5){
		if($i == 0){ $i=""; }
		
		$link = get_post_meta($GLOBALS['postID'], "buy_link".$i, true);
		
		if(strlen($link) > 0){

		// LINK CLOAKING
		if(get_option("display_linkcloak") =="yes" && !isset($_GET['link'])){		
			$link = get_template_directory_uri()."/_link.php?link=".$post->ID; 
		}
		
			$STRING .= '<div class="topper"><a href="'.$link.'" target="_blank" rel="nofollow" class="button gray">'.$PPT->_e(array('sp','eb'.$i)).'</a></div> ';
			
		}
		if($i == ""){ $i=0; } 
	$i++;
	
	} 
	
	$link = get_post_meta($GLOBALS['postID'], "link", true);	
	if(strlen($link) > 0){

		// LINK CLOAKING
		if(get_option("display_linkcloak") =="yes" && !isset($_GET['link'])){		
			$link = get_template_directory_uri()."/_link.php?link=".$post->ID; 
		}
		
		$STRING .= '<div class="topper"><a href="'.$link.'" target="_blank" rel="nofollow" class="button gray">'.$PPT->_e(array('sp','eb'.$i)).'</a></div> ';			
	}
	 
	 
	return $STRING;

}





 
}	 // END THEME CLASS



/* ============================= PREMIUM PRESS REGISTER WIDGETS ========================= */
 
if ( function_exists('register_sidebar') ){
register_sidebar(array('name'=>'Home Page Widget Box',
	'before_widget' => '<div class="itembox" id="%1$s">',
	'after_widget' 	=> '</div></div>',
	'before_title' 	=> '<h2 id="widget-box-id" class="title">',
	'after_title' 	=> '</h2><div class="itemboxinner greybg widget">',
	'description' => 'This is an empty widget box, its used only with the theme options found under "Display Settings" -> "Home Page" ',
	'id'            => 'sidebar-0',
));
register_sidebar(array('name'=>'Right Sidebar',
	'before_widget' => '<div class="itembox" id="%1$s">',
	'after_widget' 	=> '</div></div>',
	'before_title' 	=> '<h2 id="widget-box-id" class="title">',
	'after_title' 	=> '</h2><div class="itemboxinner greybg widget">',
	'description' => 'This is the right column sidebar for your website. Widgets here will display on all right side columns apart from those provided by the other widget blocks below. ',
	'id'            => 'sidebar-1',
));
register_sidebar(array('name'=>'Left Sidebar (3 Column Layouts Only)',
	'before_widget' => '<div class="itembox" id="%1$s">',
	'after_widget' 	=> '</div></div>',
	'before_title' 	=> '<h2 id="widget-box-id" class="title">',
	'after_title' 	=> '</h2><div class="itemboxinner greybg widget">',
	'description' 	=> 'This is the left column sidebar for your website. Widgets here will display on ALL left sidebars throughout your ENTIRE website.',
	'id'            => 'sidebar-2',
));
register_sidebar(array('name'=>'Listing Page',
	'before_widget' => '<div class="itembox" id="%1$s">',
	'after_widget' 	=> '</div></div>',
	'before_title' 	=> '<h2 id="widget-box-id" class="title">',
	'after_title' 	=> '</h2><div class="itemboxinner greybg widget">',
	'description' 	=> 'This is the right column sidebar for your LISTING PAGE only. Widgets here will ONLY display on your listing page. ',
	'id'            => 'sidebar-3',
));
register_sidebar(array('name'=>'Pages Sidebar',
	'before_widget' => '<div class="itembox" id="%1$s">',
	'after_widget' 	=> '</div></div>',
	'before_title' 	=> '<h2 id="widget-box-id" class="title">',
	'after_title' 	=> '</h2><div class="itemboxinner greybg widget">',
	'description' 	=> 'This is the right column sidebar for your website PAGES. All widgets here will display on the right side of your PAGES.',
	'id'            => 'sidebar-4',
));
register_sidebar(array('name'=>'Article/FAQ Page Sidebar',
	'before_widget' => '<div class="itembox" id="%1$s">',
	'after_widget' 	=> '</div></div>',
	'before_title' 	=> '<h2 id="widget-box-id" class="title">',
	'after_title' 	=> '</h2><div class="itemboxinner greybg widget">',
	'description' 	=> 'This is the right column sidebar for your website ARTICLES/FAQ PAGES.',
	'id'            => 'sidebar-5',
));
register_sidebar(array('name'=>'Footer Left Block (1/3)',
	'before_widget' => '',
	'after_widget' 	=> '',
	'before_title' 	=> '<h3>',
	'after_title' 	=> '</h3>',
	'description' 	=> 'This is left footer block, the footer sections is split into 3 blocks each of roughtly 300px width. ',
	'id'            => 'sidebar-6',
));
register_sidebar(array('name'=>'Footer Middle Block (2/3)',
	'before_widget' => '',
	'after_widget' 	=> '',
	'before_title' 	=> '<h3>',
	'after_title' 	=> '</h3>',
	'description' 	=> 'This is middle footer block, the footer sections is split into 3 blocks each of roughtly 300px width. ',
	'id'            => 'sidebar-7',
));
register_sidebar(array('name'=>'Footer Right Block (3/3)',
	'before_widget' => '',
	'after_widget' 	=> '',
	'before_title' 	=> '<h3>',
	'after_title' 	=> '</h3>',
	'description' => 'This is right footer block, the footer sections is split into 3 blocks each of roughtly 300px width. ',
	'id'            => 'sidebar-8',
));
  
} 
 
 
 
 
 
 /* ============================= OLD DIRECTORYPRESS FUNCTIONS ========================= */

 
 
 
 
 
 
 
 
 
 
 
 
  
 
 
 





 /* ============================= CHECKOUT PAGE FUNCTION ========================= */



	
	
	function ShopperPressPromotions($Items, $totalPrice){

	if(!is_array($Items)) { return 0; }
	
	$quantity = 0; $extraDiscount =0;
	foreach($Items as $key=>$qty){
		$quantity += $qty;
	}
 

		// QUANTITY BASED PROMOTIONS
		if(get_option("enable_promotionqty") =="1"){		
				 
				if( $quantity >= get_option("promotion_qty1a")  && $quantity <= get_option("promotion_qty1b") ){
		 
					if(strlen(get_option("promotion_qty1c")) > 0){
						$extraDiscount += $totalPrice/100*get_option("promotion_qty1c");
					}else{
						$extraDiscount += get_option("promotion_qty1d");
					}
				}	
		
				if( $quantity >= get_option("promotion_qty2a")  && $quantity <= get_option("promotion_qty2b") ){
		 
					if(strlen(get_option("promotion_qty2c")) > 0){
						$extraDiscount += $totalPrice/100*get_option("promotion_qty2c");
					}else{
						$extraDiscount += get_option("promotion_qty2d");
					}
				}		

				if( $quantity >= get_option("promotion_qty3a")  && $quantity <= get_option("promotion_qty3b") ){
		 
					if(strlen(get_option("promotion_qty3c")) > 0){
						$extraDiscount += $totalPrice/100*get_option("promotion_qty3c");
					}else{
						$extraDiscount += get_option("promotion_qty3d");
					}
				}	
			
		}
 
		return $extraDiscount;
	
	}
	

		





function BuyProductWidget(){ 


global $wpdb, $PPT, $ThemeDesign, $post; global $userdata; get_currentuserinfo(); $CSTRING = ""; ?>



<?php 

if($GLOBALS['shopperpress']['price_tag'] =="member" && $userdata->ID == 0){ return; }



	// BUILT THE CUSTOM FIELDS BOX
	$CF=1; $CSTRING = ""; while($CF < 7){ 
	
	$GLOBALS['customlist'.$CF.'_has_value'] = get_post_meta($post->ID, "customlist".$CF, true);
	
	if( $GLOBALS['customlist'.$CF.'_has_value'] !=""){ 
	
		$SIZEARRAY = explode(",",$GLOBALS['customlist'.$CF.'_has_value']); 
		
		$CSTRING .= '<div style="line-height:30px; float:left;"><b>'.get_option('custom_field'.$CF).':</b><br /><select size="1" onchange="ChangeCustomFieldValue(this.value,\'CustomField_'.$CF.'\'); return false;" class="pb7" ><option value="">&nbsp;</option>';
		
			foreach($SIZEARRAY as $value){
			
				if($CF ==3 || $CF ==6){ $c3v = explode("=",$value); if(!isset($c3v[1])){ continue; } 
					   $CSTRING .= '<option value="'.$c3v[0].'">'.$c3v[1].'</option>';
				}else{
					if(!isset($value) || $value == ""){ continue; }
						 $CSTRING .= '<option value="'.$value.'">'.$value.'</option>';
				}               
			}
				  
		$CSTRING .= '</select></div>';
			  
    } // end if
	
    $CF++; 
			
	} // end loop
	
	if($GLOBALS['shopperpress']['price_tag'] == "no"){ $CSTRING=""; }
	
	// STOP THE CART OPTIONS SHOWING IF ITS A AFFILIATE LINK 
	if(get_post_meta($GLOBALS['postID'], "buy_link", true) != ""){ $CSTRING="&nbsp;"; }
			

?>


<div class="pb1"> 


	<?php if($GLOBALS['qty'] >0 &&  get_post_meta($GLOBALS['postID'], 'allowupload', true) == "yes"){ ?>       
        
         
         <form action="" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" style="border-bottom:1px solid #ddd;min-height:45px; margin-top:10px;"> 
         <input type="hidden" name="doupload" value="1">
         
            <p id="f1_upload_process"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/loading.gif" /></p>
            <p id="f1_upload_form"><b style="float:left;"><?php echo $PPT->_e(array('sp','_side3')) ?>:</b>
            
            <input type="submit" name="submitBtn" class="button gray" value="<?php echo $PPT->_e(array('sp','_side4')) ?>" style="float:right; margin-left:20px;"  />
             
             
             <input name="attachment" type="file" class="supload"/>
                <div class="clearfix"></div>  
             </p>
                             <div class="clearfix"></div>
              <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
           </form>
        
        <div class="clearfix"></div>
        
        <script language="javascript" type="text/javascript">
        <!--
        function startUpload(){
              document.getElementById('f1_upload_process').style.visibility = 'visible';
              document.getElementById('f1_upload_form').style.visibility = 'hidden';
              return true;
        }
        function stopUpload(success, imageName){
              var result = '';
              if (success == 1){
                 result = '<span class="msg"><?php echo $PPT->_e(array('sp','_side10')); ?><\/span><br/><br/>';		 
                document.getElementById('CustomField_7').innerHTML  = imageName;
                document.getElementById('f1_upload_process').style.visibility = 'hidden';
                document.getElementById('f1_upload_form').innerHTML = result;
                document.getElementById('f1_upload_form').style.visibility = 'visible'; 
                return true;
              }
              else {
                 result = '<span class="emsg"><?php echo $PPT->_e(array('sp','_side11')); ?><\/span><br/><br/>';
             } 
              document.getElementById('f1_upload_process').style.visibility = 'hidden';
              document.getElementById('f1_upload_form').innerHTML = result + '<p id="f1_upload_form"><b><?php echo $PPT->_e(array('sp','_side12')); ?>:</b><br /><label>  <input name="d[]" type="file" style="100px;" /><\/label><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>';
              document.getElementById('f1_upload_form').style.visibility = 'visible';    
                
              return true;   
        }
        //-->
        </script>          
                
                
        <?php } ?> 







	<div class="pb2">
    
     	<?php if($GLOBALS['file_type'] == "free"){  echo $PPT->_e(array('button','20'));   }else{  if(strlen($GLOBALS['old_price'])> 1){ ?>
        <strike><?php echo premiumpress_price(GetPrice(get_post_meta($post->ID, "old_price", true)),$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1);  ?></strike> 
        <?php } ?>
        
		<?php echo premiumpress_price(GetPrice(get_post_meta($post->ID, "price", true)),$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1);  ?>
    	<?php } ?>
        
         <?php if(strlen($GLOBALS['amazon_link']) > 1 && $GLOBALS['shopperpress']['price_tag'] != "no"  ){ ?>
        <div id="AmazonDate">(as of <?php echo date('d/m/Y h:i',strtotime($post->post_date)); ?> - <a href="<?php echo premiumpress_link($post->ID,true) ?>" target="_blank" rel="nofollow">info</a>)</div><div class="clearfix"></div>
        <?php } ?> 
         </div><!-- end price tag -->
         
        <!-- start add to cart button -->
        <div class="pb3">
        
         <?php /*----------------------- ADD TO CART BUTTON ---------------------------*/ ?> 
        <?php if(strlen($CSTRING) < 2  &&  $GLOBALS['shopperpress']['price_tag'] != "no" ){ ?>
        
			<?php if( !isset($GLOBALS['hidecheckoutbtn']) && $GLOBALS['filename'] == "" && $GLOBALS['buy_network_icon'] == "none" && ( $GLOBALS['qty'] > 0 || $GLOBALS['shopperpress']['StockControl'] =="no" ) ){  ?>        
            <div  id="BUYBUTTON" style="display:none;"><a href="javascript:void(0);" class="button gray" onclick="<?php echo CartAction("add",$post,5); ?>;"> <?php echo $PPT->_e(array('sp','_side5')) ?></a></div>         
            
            <?php }else{ $GLOBALS['displayCheckoutBox'] = true;?>
            <span id="BUYBUTTON"></span>
            <?php } ?>
        
        <?php } ?>
        
        
        
        <?php /*----------------------- AMAZON BUY BUTTON ---------------------------*/ ?> 
        
        <?php if(strlen($GLOBALS['amazon_link']) > 1 && get_option("display_single_amazonbutton") != "no"){ ?>
        <a href="<?php echo premiumpress_link($post->ID,true) ?>" target="_blank" rel="nofollow" class="button gray" style="margin-top:3px;"><?php echo $PPT->_e(array('sp','_side6')); ?> <img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/amazon.png" alt="amazon" /></a>
        <?php }  ?> 
        
        
        
        
        <?php /*----------------------- FILE DOWNLOAD OPTIONS  ---------------------------*/ ?> 
        
        <?php if(strlen($GLOBALS['filename']) > 2){ ?>
        <script language="javascript" type="text/javascript">

		function CheckDownloadData()
		{		
		
		<?php if(isset($_COOKIE['ItemDownload'.$post->ID]) || $GLOBALS['file_type'] == "free"){ ?>
		
		document.downloadform.submit();
		
		<?php }elseif($userdata->aim == "" || $userdata->aim < $GLOBALS['price'] ){ ?>
		
		alert("<?php echo $PPT->_e(array('sp','_side14')); ?>");
		return false;
		
		<?php }else{ ?> 
		
			var answer = confirm("<?php echo str_replace("%a",premiumpress_price($GLOBALS['price'],$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1),$PPT->_e(array('sp','_side13')));  ?> ")
			if (answer){
				
				document.downloadform.submit();
				
			}
			else{
				return false;
			}
		<?php } ?>
			
		} 		 
		</script> 
        
     
            <a class="button gray" <?php global $user_ID; if ( $user_ID ) : ?>href="javascript:void(0);" onclick="CheckDownloadData();" <?php else :  ?>href="<?php bloginfo('url'); ?>/wp-login.php?action=login"<?php endif; ?> rel="nofollow">
           
            <?php echo $PPT->_e(array('sp','7')); ?>
            </a>
             
            <form action="<?php echo $GLOBALS['bloginfo_url']; ?>/" method="POST" name="downloadform">
            <?php wp_nonce_field('FileDownload') ?>
            <input type="hidden" name="hash" value="123">
            <input type="hidden" name="fileID" value="<?php echo $post->ID*800; ?>">			 
            </form> 
            
            <?php if(isset($_COOKIE['ItemDownload'.$post->ID]) && $GLOBALS['file_type'] != "free"){ ?>
            <div class="downloadinfo"><?php echo $PPT->_e(array('sp','_side8')); ?></div>
            <?php } ?> 
            
        <?php } ?> 
        
                
        
        <?php /*----------------------- CUSTOM BUY LINKS ---------------------------*/ ?> 
        
        <?php echo $ThemeDesign->ExtraBuyLinks(); ?> 
        
        
        </div><!-- end add to cart buttons -->  
        
          <?php  if(isset($GLOBALS['hidecheckoutbtn']) || strlen($GLOBALS['filename']) > 0 || strlen($GLOBALS['amazon_link']) > 1 ){ 
		
		}elseif(  $GLOBALS['shopperpress']['StockControl'] =="yes" && $GLOBALS['qty'] < 2){ ?>      
        
        <?php }elseif( $GLOBALS['shopperpress']['price_tag'] != "no"){ ?>
        
        <div class="pb4">
		
		<span class="pb4a"><?php echo $PPT->_e(array('sp','1')) ?>: </span>
        
			<?php if($GLOBALS['qty'] < 1  || $GLOBALS['shopperpress']['StockControl'] =="no" ){  ?>			
				 
 			<input type="text" value="1" style="width:30px; margin-left:15px;" onchange="ChangeCustomQty(this.value);"> 

			<?php  }elseif($GLOBALS['qty'] > 1 && strlen($GLOBALS['filename']) < 1){ ?>
		 
			<select onchange="ChangeCustomQty(this.value);" id="UpdateQtyBox">
			<?php $i=1; $t=$GLOBALS['qty']; if($t > 1000){ $t=1000; } while($t >= 1){ ?>
			<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
			<?php $t--; $i++; } ?>
			</select> 
			
			<?php }else{ ?>
            
            <input type="text" value="1" style="width:30px; margin-left:15px;" onchange="ChangeCustomQty(this.value);">

			<?php } ?>
 
        
        </div><!-- end QTY field -->
        
        <?php } ?>
        
        <div class="clearfix"></div>  
        
        </div><!-- end lines -->
    
 
    <?php  if(isset($GLOBALS['hidecheckoutbtn']) || strlen($GLOBALS['filename']) > 0 || strlen($GLOBALS['amazon_link']) > 1 ){ 		}elseif(  $GLOBALS['shopperpress']['StockControl'] =="yes" && $GLOBALS['qty'] < 1){ ?> 
    
    <p class="stockshow"><?php echo $PPT->_e(array('sp','_side2')); ?></p>
    
    <?php }else{ ?>
    
		<?php if(  $GLOBALS['shopperpress']['StockControl'] =="yes"){ ?>
        
        <p class="stockshow"><?php if( $GLOBALS['qty'] < 1 ){ echo $PPT->_e(array('sp','11'));  }else{  echo str_replace("%",number_format($GLOBALS['qty']),$PPT->_e(array('sp','8'))); } ?></p> 
        
        <?php } ?>
    
    <?php } ?>
           

	<?php if(  $GLOBALS['shopperpress']['price_tag'] != "no"  ){ 
     } // END DONT SHOW PRICE ?>  
      
   <div class="pb5" id="ppt-widget-advancedsearch-box" style="padding:0px;"> 
    
		
            
	   <?php 
	   
	   // STOP DISPLAY IF OUT OF STOCK
	   if($GLOBALS['shopperpress']['StockControl'] =="yes" && $GLOBALS['qty'] < 1){
	   $CSTRING = "";
	   }
	 
	   if(strlen($CSTRING) > 10 ){ ?>
              
       <div class="pb6">
       
       <?php echo $CSTRING; ?>
       
           <div class="clearfix"></div>	
        <div id="BUYBUTTON" class="marginTop"><a href="javascript:void(0);" class="button gray" onclick="<?php echo CartAction("add",$post,5); ?>;"><?php echo $PPT->_e(array('sp','2')) ?></a>        
             </div>
       <div class="clearfix"></div>	
       </div> 
       <hr />       
      <?php } ?>
      
      <div class="entry p10"><?php the_excerpt(); ?></div>
      
       
  
    
       </div><!-- end custom fields -->
     
 
     <!-- do not remove, used for QTY fields -->
    <input type="hidden" value="0" id="QtyTotal" />
     
 
 
 
 
 
 
 
 
 
<?php }


class ups_rates {
	
		var $version				= '1.0';
		var $debug 					= true;
	
		var $ups_access_key			= UPS_ACCESSKEY;
		var $ups_user_id			= UPS_USERID;
		var $ups_password			= UPS_PASSWORD;

		var $default_rate;
		
		var $pickup_type			= '01';
    	var $package_type 			= '02';
    	var $request_type;
    	var $weight_type			= 'LBS';
    	
    	var $from_state;
    	var $from_zip;
    	var $from_country			= 'US';

    	var $ship_state;        
    	var $ship_zip;
    	var $ship_country			= 'US';
    	
    	var $currency_type			= '$';
    	var $form_name 				= 'shipping_methods';
    	var $select_class			= '';
    	
    	var $pickup					= true;
    	var $pickup_cost			= '0.00';
    	
    	var $service 				= '';
    	var $residential 			= true;
    	
    	var $weight 				= 1;
    	var $subtotal 				= 1.00;
    	
    	var $rates					= array();
 
    	

		
		function check_settings() {
	
			// Checking UPS Account Information
			if ($this->ups_access_key == NULL && $this->ups_access_key == '') $error_msg = 'You did not specify a UPS Access Key<br>';
			if ($this->ups_user_id == NULL && $this->ups_user_id == '') $error_msg .= 'You did not specify a UPS User ID<br>';
			if ($this->ups_password == NULL && $this->ups_password == '') $error_msg .= 'You did not specify a UPS Access Key<br>';
			
			// Checking Ship From & Ship To Information
			if ($this->from_state == NULL && $this->from_state == '') $error_msg .= 'You did not enter a ship from state location<br>';
			if ($this->from_zip == NULL && $this->from_zip == '') $error_msg .= 'You did not enter a ship from zip location<br>';
			if ($this->from_country == NULL && $this->from_country == '') $error_msg .= 'You did not enter a ship from country location<br>';
			
			if ($this->ship_zip == NULL && $this->ship_zip == '') $error_msg .= 'You did not enter a ship to zip location<br>';
			if ($this->ship_country == NULL && $this->ship_country == '') $error_msg .= 'You did not enter a ship to country location<br>';
			
			// If you have the above variable $debug = true, then the script will halt on any errors and display what error occured, you can switch off this option if you want by setting $debug to false.
			if ($this->debug == true && $error_msg != NULL) exit('<strong>UPS Rates - Version '.$this->version.'</strong><br><br>'.$error_msg.'');
		
		}
		
		function service_codes($code) {
		
			// These are all the codes provided by UPS. Check the value in $code and apply for the array.
			
			$service_codes = array( '14' => 'Next Day Air Early AM',
									'01' => 'Next Day Air',
									'13' => 'Next Day Air Saver',
									'59' => '2nd Day Air AM', 
									'02' => '2nd Day Air',
									'12' => '3 Day Select', 
									'03' => 'Ground',

									'11' => 'Standard',
									'07' => 'Worldwide Express',
									'54' => 'Worldwide Express Plus', 
									'08' => 'Worldwide Expedited', 
									'65' => 'Saver',

									'82' => 'UPS Today Standard',
									'83' => 'UPS Today Dedicated Courier',
									'84' => 'UPS Today Intercity', 
									'85' => 'UPS Today Express', 
									'86' => 'UPS Today Express Saver');
									
									
			return $service_codes[$code];
		
		}
		
		function fetch_rates() {
		
		if(isset($GLOBALS['SHOPPERPRESS_FREESHIPPING']) && $GLOBALS['SHOPPERPRESS_FREESHIPPING']){ return; }

			$this->check_settings();
			
			if ($this->residential == true) $residential_address = '1';
			
			// Attach UPS XML
			$xml_data = '
			<?xml version="1.0"?>
			<AccessRequest xml:lang="en-US">
				<AccessLicenseNumber>'.$this->ups_access_key.'</AccessLicenseNumber>
				<UserId>'.$this->ups_user_id.'</UserId>
				<Password>'.$this->ups_password.'</Password>
			</AccessRequest>
			<RatingServiceSelectionRequest xml:lang="en-US">
				<Request>
					<TransactionReference>
						<CustomerContext>Rating and Service</CustomerContext>
						<XpciVersion>1.0</XpciVersion>
					</TransactionReference>
					<RequestAction>Rate</RequestAction>
					<RequestOption>Shop</RequestOption>
				</Request>
				<PickupType>
					<Code>'.$this->pickup_type.'</Code>
				</PickupType>
				<Shipment>
					<Shipper>
						<Address>
							<StateProvinceCode>'.$this->from_state.'</StateProvinceCode>
							<PostalCode>'.$this->from_zip.'</PostalCode>
							<CountryCode>'.$this->from_country.'</CountryCode>
						</Address>
					</Shipper>
					<ShipFrom>
						<Address>
							<StateProvinceCode>'.$this->from_state.'</StateProvinceCode>
							<PostalCode>'.$this->from_zip.'</PostalCode>
							<CountryCode>'.$this->from_country.'</CountryCode>
						</Address>
					</ShipFrom>
					<ShipTo>
						<Address>
							<StateProvinceCode>'.$this->ship_state.'</StateProvinceCode>
							<PostalCode>'.$this->ship_zip.'</PostalCode>
							<CountryCode>'.$this->ship_country.'</CountryCode>
							<ResidentialAddressIndicator>'.$residential_address.'</ResidentialAddressIndicator>
						</Address>
					</ShipTo>
					<Service>
						<Code>'.$this->service.'</Code>
					</Service>
					<Package>
						<PackagingType>
							<Code>'.$this->package_type.'</Code>
						</PackagingType>
						<PackageWeight>
							<UnitOfMeasurement>
								<Code>'.$this->weight_type.'</Code>
							</UnitOfMeasurement>
							<Weight>'.$this->weight.'</Weight>
						</PackageWeight>
					</Package>
				</Shipment>
			</RatingServiceSelectionRequest>';

			// Connect to UPS and assign data to $data.
			$url = 'https://onlinetools.ups.com/ups.app/xml/Rate';
			
			$ch = curl_init(); 
        	curl_setopt ($ch, CURLOPT_URL, $url);
	        curl_setopt ($ch, CURLOPT_HEADER, 0);
    	    curl_setopt ($ch, CURLOPT_POST, 1);
        	curl_setopt ($ch, CURLOPT_POSTFIELDS, $xml_data);
	        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    	    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        	curl_setopt ($ch, CURLOPT_TIMEOUT, 10); 
	        $data = curl_exec ($ch);
    	    curl_close ($ch);
        	
        	$xml = simplexml_load_string($data);
        	
        	$status = $xml->Response->ResponseStatusCode;

        	
        	if ($status == '1') {
        	
        		// UPS was succesful
        		$num_of_rates = count($xml->RatedShipment);
				
				// 7.1.1 DOUBLE PRICE OPTIONS
				if(get_option('shipping_UPS_doubleprice') == 1 && is_array($_SESSION['ddc']['productsincart']) ){ $double=0; 
				
					 foreach($_SESSION['ddc']['productsincart'] as $product => $quantity) { $double+=$quantity;  }  
				
				}else{  $double=1; }
        		
				$aa=1;
        		// Extract Information
        		for($i=0; $i<$num_of_rates; $i++) {
        			$service_code = strval($xml->RatedShipment[$i]->Service->Code);
        			$service_cost = strval($xml->RatedShipment[$i]->TotalCharges->MonetaryValue);
					
					$service_cost = ($service_cost*$double);
					$service_desc = 'UPS '.$this->service_codes($service_code);
        			$service_rates[$i]['code'] = $service_code;
        			$service_rates[$i]['cost'] = $service_cost;
        			$service_rates[$i]['desc'] = $service_desc;
        			
        			if($aa ==1){ $selected = 'checked'; }else{ $selected = ''; }
        			$option_list .= '<input '.$selected.' name="form[shippingmethod]" type="radio" value="'.$service_cost.'" onclick="document.getElementById(\'shippingmethod_name\').value=\''.$service_desc.' - '.$this->currency_type.$service_cost.'\'" >'.$service_desc.' - '.$this->currency_type.$service_cost.'<br />';
        			 
        			$aa++;
        			
        		}
        		
        		if ($this->pickup == true) {
        			$i++;
        			$service_rates[$i]['code'] = '00';
        			$service_rates[$i]['cost'] = $this->pickup_cost;
        			$service_rates[$i]['desc'] = 'Pickup';
        			
        			if ($this->default_rate == '00') $selected = ' selected';
        			
        			if ($this->pickup_cost == '') $this->pickup_cost = '0.00';
        			
        			//$option_list .= '<option value="00"'.$selected.'>Pickup - '.$this->currency_type.$this->pickup_cost.'</option>';
        		}
        		
        		$this->rates = $service_rates;
        	
        	} else {
        	
        		// UPS failed return only Pickup
        		if ($this->debug == true) return  'UPS returned this error: "<strong>'.$xml->Response->Error->ErrorDescription.'</strong>"';
        		
        		$option_list .= '<option value="00">Pickup - '.$this->curreny_type.$this->pickup_cost.'</option>';
        		
        	}
        	
        	if ($this->select_class != '') $class = ' class="'.$this->select_class.'"';
			
			$option_list .= '<input id="shippingmethod_name" name="form[shippingmethod_name]" type="hidden" value="'.$spn.'">';
        	return $option_list;
		
		}
		
		
	}

















/* =============================================================================
   SHOPPERPRESS ADMIN AREA OPTIONS // V7 // 29TH MARCH
   ========================================================================== */

if(isset($_POST['submittedType']) && isset($_POST['submitted']) && $_POST['submitted'] == "yes" ){

	switch($_POST['submittedType']){
	
		case "sp_shipping_price": 
		case "sp_shipping_weight": {	
	 
			$newa = array(); $i=0;
			// LOOP ONE VALUE AND BUILT NEW ARRAY
			foreach($_POST['shipping_weight']['a'] as $val){
			
				// CHECK ALL FIELDS ARE FILLED IN
				if(isset($_POST['shipping_weight']['b'][$i]) && $_POST['shipping_weight']['b'][$i] !="" && isset($_POST['shipping_weight']['c'][$i]) && $_POST['shipping_weight']['b'][$i] !=""){
				
					// MAKE NICE ARRAY			 
					$newa[] = array('a'=> $_POST['shipping_weight']['a'][$i], 'b'=> $_POST['shipping_weight']['b'][$i], 'c'=> $_POST['shipping_weight']['c'][$i], 'cl'=> $_POST['shipping_weight']['cl'][$i] );
				}
				$i++;
			}
			
			// SAVE VALUE
			if(isset($_POST['price'])){
			update_option("sp_priceshipping", $newa);
			}else{
			update_option("sp_weightshipping", $newa);
			}
			
		
		} break; // end sp_shipping_weight
		
		
		default: { }
		
		
	
	} // end switch

} // end submitted





/* =============================================================================
   SHOPPERPRESS ADMIN AREA OPTIONS // V7 // 29TH MARCH
   ========================================================================== */

 add_action('premiumpress_admin_post_custom_title','admin_post_custom_title');

function admin_post_custom_title(){ ?>

<a class="enabled">Attributes</a>
<a class="enabled">Shipping Options</a>
<a class="enabled">Affiliate Settings</a>
<a class="enabled">Tax</a>     

<?php }   
add_action('premiumpress_admin_post_custom_content','admin_post_custom_content');

function admin_post_custom_content(){

global $post, $wpdb; ?>
<!-- ***********************************************************
LIST BOX SETTINGS
*************************************************************** -->


<div class="ppt-tabs_content" style="left: -750px; ">

 <?php

		$fff=0;	
		$dd=1;
		$curencyCode = get_option("currency_code");
		while($dd < 7){
		
		
		 echo '<div class="misc-pub-section">';
 	
		echo '<div id="cf'.$dd.'" >'; 
		
  		echo '<div>';
		
		$nna = get_option("custom_field".$dd);
		if($nna == ""){$nna = "---"; }
		
		// INPUT BUTTON
		if($dd == 3 || $dd == 6){ $btnaa = 'pptinputbox1'; }else{ $btnaa = 'pptinputbox'; }
		
		
	
		 echo '<a style="float:right;" href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;<b>How do listbox attributes work?</b><br>This section let\'s you setup listbox values for website visitors to personalize product selections. Example, product colors and sizes. <br><br><b>Title Value</b><br> The title value is simply a display caption for your listbox. This title will be used for ALL listbox '.$dd.' fields on your website.  <br><br><b>Add Listbox Value</b><br /> Here you add a new value for the user to select. For example, if you enter the value \'red\' this will allow the user to select the value red on the product page. <br><br><b>Listbox 3 and 6</b><br/> These two listbox values are different because they offer you the extra option of attaching a price value to the listbox. For example, if the user selected a value \'red\' you can also apply an extra pricing value to this selection. (Red = extra $100) All extra prices values MUST be unique.  &quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="margin-left:10px;" /></a>'; 
	
		 	echo ' <a href="javascript:void(0);" onclick="'.$btnaa.'(\'sel'.$dd.'\');"  class="button tagadd" style="float:right;">Add Value</a>
		<a href="javascript:void(0);" onclick="toggleLayer(\'customfieldbox'.$dd.'\');toggleLayer(\'h2'.$dd.'\');"  class="button tagadd" style="float:right; margin-right:5px;">Edit Title</a>'; 
		
		
		echo ' 
		
		<div id="h2'.$dd.'"><img src="'.PPT_FW_IMG_URI.'admin/csku.png" style="float:left; margin-right:5px;margin-top:5px;" /> '.$dd.': '.$nna.'</div> ';
		
		echo '<div id="customfieldbox'.$dd.'" style="display:none;">
		<div class="clearfix"></div>
		 <hr>
		Display Caption: <input name="adminArray[custom_field'.$dd.']" type="text" style="font-size:11px;" value="'.get_option("custom_field".$dd).'" /> 
		
 
		<input type="checkbox" name="custom_field'.$dd.'_required" value="1"'; if(get_option("custom_field".$dd."_required") =="1"){ print "checked";} echo ' style="margin-left:100px;" /> Required 
		<hr>
		<a href="javascript:void(0);" onclick="toggleLayer(\'customfieldbox'.$dd.'\');toggleLayer(\'h2'.$dd.'\');"  class="button tagadd">Save</a> 
		<a href="javascript:void(0);" onclick="toggleLayer(\'customfieldbox'.$dd.'\');toggleLayer(\'h2'.$dd.'\');"  class="button tagadd">Cancel</a>
		 
		<hr>
		 </div>
		
		</div>
		'; 
		
		if(strlen(get_option("custom_field".$dd)) > 0){
		
		// SEPERATE THE STRING AND CREATE LIST	  	
		$data1 = get_post_meta($post->ID, 'customlist'.$dd, true); $data1bits = explode(",",$data1); $c=1;
		
		echo '<select name="marks_cust_field'.$dd.'[]" style="width:100%; font-size:10px; " multiple="" tabindex="3" id="sel'.$dd.'" data-placeholder="Choose a Country..."  class="chzn-select"> ';	
		
		foreach($data1bits as $value){ 
		
			if(strlen($value) > 0){
			
				if($dd == 3 || $dd == 6){ 
				$gg = explode("=",$value);   $value = $gg[1]; $pricebit = $gg[0];
				echo '<option selected=selected value="'.$gg[0]."=".$value.'">'.$gg[1].' ('.$curencyCode.$gg[0].')</option>';
				}else{
				echo '<option selected=selected value="'.$value.'">'.$value.'</option>';
				}	
					
			} // end if
		
		} // end foreach
		
		echo '</select>';
		
		} 
		
		echo '<div class="clearfix"></div> </div>';
		
		
			
			
		echo ' </div>';
		
		$dd++;$fff=0;
		} // end for loop

?>

<script>
function pptinputbox(div){

	jQuery.msgbox("Enter a new listbox value below: ", {
	  type: "prompt"
	}, function(result) {
	  if (result) {
	  jQuery('#'+div).append('<option value="'+result+'" selected="selected">'+result+'</option>');
		jQuery('#'+div).trigger("liszt:updated");
	  }

	});	

} 
function pptinputbox1(div){

	jQuery.msgbox("Enter a new listbox value and price below:", {
	  type: "prompt",
	   inputs  : [
      	{type: "text", label: "Display Caption:", value: "example", required: true},
      	{type: "text", label: "Price: (must be unique and not blank) (<?php echo $curencyCode; ?>)", value: "100", required: true}
    	],
	}, function(r1, r2) {
	  if (r1) {
	  
	   jQuery('#'+div).append('<option value="'+r2+'='+r1+'" selected="selected">'+r1+'(<?php echo $curencyCode; ?>'+r2+')</option>');	   
		jQuery('#'+div).trigger("liszt:updated");
	  }
	});	

} 
</script>

<div class="clearfix"></div>
</div>


<!-- ***********************************************************
SHIPPING OPTIONS jQuery('.smallBox').clone().insertAfter('#newshippingcountrylist');
*************************************************************** -->
<div class="ppt-tabs_content" style="left: -750px;">
 

<div class="green_box"><div class="green_box_content">


 <p class="titlep">Product Shipping Attributes</p>
 
 <p>Here you enter the product attributes used within the shipping calculations.</p>
 
 <hr />

<?php
 
 	 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Weight (".get_option('shipping_weight_metric').")", 'sp' ) . '</span>';
		echo '<input type="text" name="field[weight]" value="'.get_post_meta($post->ID, 'weight', true).'" class="ppt-forminput" style="width:50px;" />
				<div class="clearfix"></div></div>';

		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Dimensions (cm)", 'sp' ) . '</span>';
		echo 'L:<input type="text" name="field[length]" value="'.get_post_meta($post->ID, 'length', true).'" class="ppt-forminput" style="width:50px; margin-left:10px;" /> 
		W:<input type="text" name="field[width]" value="'.get_post_meta($post->ID, 'width', true).'" class="ppt-forminput" style="width:50px;margin-left:10px;" /> 
		H:<input type="text" name="field[height]" value="'.get_post_meta($post->ID, 'height', true).'" class="ppt-forminput" style="width:50px; margin-left:10px; " />
	 
		<div class="clearfix"></div></div>';			
 
?>
</div></div>








 <div class="blue_box"><div class="blue_box_content">

 <p class="titlep">Item Based Shipping</p>
 
 <p>Here you can enter an additional shipping cost for this item. It will be added ontop of all other shipping calculations at checkout and is applied to each item.</p>
 
 <hr />


 <?php

echo '<div class="ppt-form-line nob"><span class="ppt-labeltext">' . __("Fixed Price (".get_option('currency_symbol').") ", 'sp' ) . '</span>';
echo '<input type="text" name="field[shipping]" value="'.get_post_meta($post->ID, 'shipping', true).'" class="ppt-forminput" style="width:150px;" />
		<div class="clearfix"></div></div>';	

?>
 </div></div>
 
<div class="blue_box"><div class="blue_box_content">

 <p class="titlep">Country Shipping Price</p>
 
 <p>Here you can add an additional shipping price based on the shipping country selected at checkout. Select a country from the list to setup extra shipping values.</p>
 
 <hr />


 <table width="100%" border="0">
  <tr>
    <td>
    
 <select style="width:200px;height:100px;" multiple="multiple" onchange="jQuery('#selv').html(this.value);GetCountryShip(this.value, '<?php echo $post->ID; ?>', 'AJAXCOUNTRYSHIP','<?php echo str_replace("http://","",PPT_THEME_URI); ?>/PPT/ajax/');">
<?php include(str_replace("functions/","",THEME_PATH)."_countrylist.php"); ?>
</select>
   
    </td>
    <td style="width:100%;" valign="top">
 
 

<div id="selv" style="font-size:16px; font-weight:bold;"></div> 
<div id="AJAXCOUNTRYSHIP"></div>
 
  </td></tr></table>

</div></div>

 
 
<div class="clearfix"></div>
</div>
 
<!-- ***********************************************************
AFFILIATE SETTINGS
<a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;123 &quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="float:right;" /></a>
*************************************************************** -->
<div class="ppt-tabs_content" style="left: -750px; ">

<?php

$fea = get_post_meta($post->ID, 'redirect', true);
if($fea == "yes"){ $a1 = 'selected'; $a2=""; }else{$a1 = ''; $a2="selected"; } 
	
$nou = get_post_meta($post->ID, 'amazon_noupdate', true);
if($nou == "yes"){ $nou1 = 'selected'; $nou2=""; }else{$nou1 = ''; $nou2="selected"; } 
		
echo '<div class="green_box"><div class="green_box_content">';


echo "<p class='titlep'>Amazon Product Data</p> <p>If you are importing Amazon products the imported data will be displayed below.You do not need to fill in these fields manually they are auto completed when you import products using the import tool.</p><hr>";

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';

		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Amazon ASIN ", 'sp' ) . '</span>';
		echo '<input type="text" name="field[amazon_guid]" value="'.get_post_meta($post->ID, 'amazon_guid', true).'" class="ppt-forminput"/>';		
		echo '<div class="clearfix"></div></div>';
echo "</div>";

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';

		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Amazon Button Link", 'sp' ) . '</span>';
		echo '<input type="text" name="field[amazon_link]" value="'.get_post_meta($post->ID, 'amazon_link', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';
		
echo "</div>";	

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Dont Update Price", 'sp' ) . '<a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;This option tells the Amazon price update tool NOT to update this products price. &quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="float:right;" /></a></span>';
		echo '<select name="field[amazon_noupdate]" class="ppt-forminput">
		<option '.$nou1.'>yes</option>
		<option '.$nou2.'>no</option></select><div class="clearfix"></div></div>'; 
echo "</div>";	

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';

		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Amazon Reviews Link", 'sp' ) . '</span>';
		echo '<input type="text" name="field[amazon_reviews_link]" value="'.get_post_meta($post->ID, 'amazon_reviews_link', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';
		
echo "</div>";	
	

echo '<div class="clearfix"></div></div></div>';



	 echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
	 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Website Link", 'sp' ) . '</span>';
		echo '<input type="text" name="field[url]" value="'.get_post_meta($post->ID, 'url', true).'" class="ppt-forminput"/>';	
		echo '<div class="clearfix"></div></div>';	
	
	echo "</div>";	
		
	echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Affiliate Link", 'sp' ) . '</span>';
		echo '<input type="text" name="field[link]" value="'.get_post_meta($post->ID, 'link', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';	
		
	echo "</div>";	
		
 
	
echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';


		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Redirect Visitor", 'sp' ) . '<a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;This option will redirect the user to the buy_link instead of showing the product page. &quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="float:right;" /></a></span>';
		echo '<select name="field[redirect]" class="ppt-forminput">
		<option '.$a1.'>yes</option>
		<option '.$a2.'>no</option></select><div class="clearfix"></div></div>';
		
echo "</div>";	

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';

		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Buy Link 1", 'sp' ) . '<a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;This is a custom buy link. Enter a http:// link here for the user to click on to buy this product. &quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="float:right;" /></a></span></span>';

		echo '<input type="text" name="field[buy_link]" value="'.get_post_meta($post->ID, 'buy_link', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';
		
echo "</div>";	
		
echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		
 		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Buy Link 2", 'sp' ) . '<a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;This is a custom buy link. Enter a http:// link here for the user to click on to buy this product. &quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="float:right;" /></a></span>';
		echo '<input type="text" name="field[buy_link1]" value="'.get_post_meta($post->ID, 'buy_link1', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';
 
 echo "</div>";	
 
 echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';

 
 		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Buy Link 3", 'sp' ) . '</span>';
		echo '<input type="text" name="field[buy_link2]" value="'.get_post_meta($post->ID, 'buy_link2', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';
 
 
echo "</div>";

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
	
 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Buy Link 4", 'sp' ) . '</span>';
		echo '<input type="text" name="field[buy_link3]" value="'.get_post_meta($post->ID, 'buy_link3', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';

echo "</div>"; 

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
 
 		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Buy Link 5", 'sp' ) . '</span>';
		echo '<input type="text" name="field[buy_link4]" value="'.get_post_meta($post->ID, 'buy_link4', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';		
			
echo "</div>";	

?>

</div>	



 
 <!-- ***********************************************************
TAX OPTIONS
*************************************************************** -->
<div class="ppt-tabs_content" style="left: -750px;">

<?php

 echo '<div class="misc-pub-section">' . __("VAT Cost % ", 'sp' ) . '';
		echo '<input type="text" name="field[vat]" value="'.get_post_meta($post->ID, 'vat', true).'"  style="width:150px; font-size:11px;" />
		<div class="clearfix"></div> </div>';	 
		

?>
</div>


<?php } ?>

<?php 

$GLOBALS['IS_CHECKOUTPAGE'] =1; // global value simply to define the checkout page
$GLOBALS['checkVAT'] = get_option("enable_VAT"); // used to check if VAT is being used
 
get_header();

$ThemeDesign->CHECKOUT(); // calls the system_customdesign.php
 
/*----------------------------------------------------- PAYMENT FORM SECTION ----------------------------------------------------*/  
 
if(isset($_POST['action']) && $_POST['action'] == "checkout" && isset($_POST['orderID'])){ 
 

 
	include(str_replace("functions/","",THEME_PATH)."/PPT/func/func_paymentgateways.php");
	
	// HOOK INTO THE PAYMENT GATEWAY ARRAY // V7
	$gatway = premiumpress_admin_payments_gateways($gatway);
	
	include(TEMPLATEPATH ."/PPT/class/class_payment.php");	
	$PPTPayment 		= new PremiumPressTheme_Payment;

	if(isset($_POST['form']['payment_method'])){ $OnlyThis = $_POST['form']['payment_method']; }else{  $OnlyThis =""; }
	
 
	if(isset($_POST['save'])){

		// DATA TO ADD TO THE PAYMENT CALL
		$GLOBALS['total'] 		= $GLOBALS['SHOPPERPRESS_CARTTOTAL'];
		$GLOBALS['subtotal'] 	= $GLOBALS['SHOPPERPRESS_SUBTOTAL'];
		if(isset($GLOBALS['SHOPPERPRESS_WEIGHT_PRICE']) && strlen($GLOBALS['SHOPPERPRESS_WEIGHT_PRICE']) > 0){ $GLOBALS['SHOPPERPRESS_SHIPPING'] += $GLOBALS['SHOPPERPRESS_WEIGHT_PRICE']; }
		$GLOBALS['shipping'] 	= $GLOBALS['SHOPPERPRESS_SHIPPING'];  
		$GLOBALS['tax'] 		= $GLOBALS['SHOPPERPRESS_TAX'];
		$GLOBALS['coupon'] 		= $GLOBALS['SHOPPERPRESS_COUPONDISCOUNT'];
		$GLOBALS['promo'] 		= $_POST['ccode'];  
		
		
		if(get_option('checkout_skip_registration') == "yes"){ // quick fix for express checkout
	 
	 		$GLOBALS['total'] 	= $_POST['form']['total'];	 
 			$GLOBALS['promo'] 	= $_POST['coupon'];
			$GLOBALS['coupon'] 	= $_POST['form']['discount'];
			$GLOBALS['tax'] 	= $_POST['form']['tax'];
	 	}	
			 
		$PPTPayment->InsertOrder($_POST['form']['payment_method'],$_POST['orderID']);

	}
	
	$orderData = $PPTPayment->GetOrderData($_POST['orderID']);
	 
	 if($orderData['order_total'] < 0){ $orderData['order_total'] = 0; }
	 
	$TOTAL = premiumpress_price($orderData['order_total'],"",$GLOBALS['premiumpress']['currency_position'],1,2); // REQUIRED INCASE THEY USE CURRENCY CHANGE
 

 	//backup just incase
	if($TOTAL == ""){ $TOTAL= $GLOBALS['total']; }

?>




 
<div class="itembox"> 

	<h1 id="icon-checkout-title" class="title"><?php echo $PPT->_e(array('sp','42')) ?> <?php echo $_POST['orderID']; ?></h1>    
    
    <div class="itemboxinner greybg">
    
    
<?php
 
if(get_option("checkout_setup") =="message"){ 

	echo nl2br(stripslashes(get_option("checkout_message_text")));  
	
	// SEND AFTER PAYMENT EMAIL
	$emailID = get_option("email_order_after");			 
	if(is_numeric($emailID) && $emailID != 0){	
	  SendMemberEmail($userdata->ID, $emailID);	 						
	}

}elseif($orderData['order_total'] == 0){ ?>

<h2 class="h2top"><?php echo $PPT->_e(array('sp','49')); ?></h2>
<h3><?php echo $PPT->_e(array('callback','2')); ?></h3>
<p><?php echo $PPT->_e(array('callback','3')); ?></p>

<?php }else{ ?>
    
    <h3><?php echo $PPT->_e(array('sp','39')) ?> <?php echo premiumpress_price($orderData['order_total'],$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1,2); ?> </h3>
              
                <?php 
				
				// BUILT THE CHECKOUT DESCRIPTION
				$CHECKOUTDES = ""; $store1 ="";
				if(is_array($_SESSION['ddc']['productsincart'])){
				foreach($_SESSION['ddc']['productsincart'] as $key => $QUANTITY) { 
					
					if(isset($_SESSION['ddc'][$key]['main_ID'])){ $ProductID = $_SESSION['ddc'][$key]['main_ID']; } else{  $ProductID =$key; }
					
					$PROPRICE = getProductPrice($ProductID);
					 
					$TITLE = getProductName($ProductID);
					$TITLE1 = $TITLE;
					$CF=1;  
					while($CF < 7){ 						 
						if(isset($_SESSION['ddc'][$key]['custom'.$CF]) && $_SESSION['ddc'][$key]['custom'.$CF] !="na" && $_SESSION['ddc'][$key]['custom'.$CF] !="" && $_SESSION['ddc'][$key]['custom'.$CF] !="0" ){
						$TITLE .= ' ('.get_option('custom_field'.$CF).': ';
							if($CF == 3 || $CF ==6){
								$store1 += $_SESSION['ddc'][$key]['custom'.$CF]*$QUANTITY;
								$TITLE .= ShopperPressCustomFieldTxt($ProductID, $_SESSION['ddc'][$key]['custom'.$CF],$CF);
								$PROPRICE += $_SESSION['ddc'][$key]['custom'.$CF];
							}else{ 
								$TITLE .= $_SESSION['ddc'][$key]['custom'.$CF];
							}
						$TITLE .= ')';
						}
						$CF++; 
					} 
				
					if(strlen($TITLE1) > 1 && strlen($PROPRICE) > 0){
					$CHECKOUTDES .= $QUANTITY."x ".trim(strip_tags(str_replace("[","",str_replace("]","",$TITLE))))."[".number_format($PROPRICE, 2, '.', '')."]";
					$CHECKOUTDES = str_replace(",","",$CHECKOUTDES);
					$CHECKOUTDES .= "**";
					}
					//die($CHECKOUTDES."<--");
				}
				}
				$CHECKOUTDES = str_replace("**",",",$CHECKOUTDES); // clean up
			 	$CHECKOUTDES = substr($CHECKOUTDES,0,-1); // clean up
				
                $_POST['price']['total'] 	= $TOTAL;
                $_POST['orderid'] 			= $_POST['orderID'];
                $_POST['description'] 		= $CHECKOUTDES; //"Cart Order ID: ".$_POST['orderid'];
				$GLOBALS['currency_code'] = $_SESSION['currency_code'];
                //die($_POST['description']);
				
                $i=1;
                if(is_array($gatway)){
                foreach($gatway as $Value){
                if(get_option($Value['function']) =="yes" ){ if( $OnlyThis == $Value['function'] || $OnlyThis == ""){
      
				if( $Value['function'] == "gateway_bank"){ @session_destroy(); ?>
                
                
                <div class="gray_box"><div class="gray_box_content"> 
                
                	<h3><?php echo get_option($Value['function']."_name"); ?></h3>
                
                	<p><?php echo nl2br(get_option("bank_info")); ?></p>
                    
                <div class="clearfix"></div>
                </div></div>
                
                
                
                <?php  }elseif( $Value['function'] != "gateway_paypalpro" && $Value['function'] != "gateway_ewayAPI" && $Value['function'] !="gateway_blank_form"){  
				
				if( $Value['function'] == "gateway_paypal"){ if(isset($GLOBALS['SHOPPERPRESS_WEIGHT_PRICE']) && isset($_POST['form']['shipping']) && $GLOBALS['SHOPPERPRESS_WEIGHT_PRICE'] != $_POST['form']['shipping'] ){ 
				//$_POST['form']['shipping'] += $GLOBALS['SHOPPERPRESS_WEIGHT_PRICE'];  
				
				} 
				
				if(get_option("shipping_enable_UPS") =="yes" && isset($_POST['form']['shippingmethod']) ){ $_POST['form']['shipping'] += $_POST['form']['shippingmethod']; }
				 
				}
				?>  
                
               <div class="gray_box"><div class="gray_box_content">
               
               <?php if(strlen(get_option($Value['function']."_icon")) > 5){ echo "<a href='javascript:void();' class='frame left' style='margin-right:10px;'><img src='".get_option($Value['function']."_icon")."' /></a>"; } ?> 
                
                	<h3 class="left" style="margin-top:2px;"><?php echo get_option($Value['function']."_name"); ?></h3>
                
                	<?php echo $Value['function']($_POST); ?>
                    
                <div class="clearfix"></div>
                
                </div></div> 
                
                <?php 
    
                }else{ ?>
                
                <div class="gray_box"><div class="gray_box_content">
                
                <?php echo $Value['function']($_POST); ?>
                
                <div class="clearfix"></div>
                
                </div></div> 
                		
                <?php } } } } }  ?>
            
                
				<?php if($userdata->wp_user_level == "10"){  ?>
             
                 <p style="padding:6px; color:white;background:red; margin-top:10px;"><b>Admin View Only</b> - <a href="#" onclick="document.AdminTest.submit();" style="color:white;">Click here to skip payment and test callback link.</a> </p>
            
            
                <form name="AdminTest" id="AdminTest" action="<?php echo get_option('paypal_return'); ?>" method="post">
                <input type="hidden" name="custom" value="<?php echo $_POST['orderid']; ?>">
                <input type="hidden" name="payment_status" value="Completed">
                <input type="hidden" name="mc_gross" value="<?php echo $_POST['price']['total']; ?>" />
                </form> 
            
            
                <?php } ?> 
      
      <?php } ?>
            
     </div>            
            
            
            
</div> 







<?php }elseif(isset($_POST['step100'])){  ?>


<form action="" name="cartConfirmation" id="cartConfirmation" onSubmit="return CheckFormData();" method="post"> 
<input type="hidden" name="action" value="confirmation" />
<input type="hidden" name="step1" value="1" />
<input type="hidden" name="ccode" value="<?php if(isset($_POST['ccode'])){ echo strip_tags($_POST['ccode']); } ?>" />
 
<?php foreach($_POST['form'] as $key=>$value){ print '<input name="form['.$key.']" value="'.$value.'" type="hidden" id="hidden-'.$key.'">'; } ?>                
<?php foreach($_POST['shipping'] as $key=>$value){ print '<input name="shipping['.$key.']" value="'.$value.'" type="hidden">'; } ?>  
    
    
<?php  /*-----------------------------------------------------  style="display:none;"SIPPING & PAYMENT METHODS ----------------------------------------------------*/ ?>


<div  id="step3box" >
    
<div class="itembox">  <?php echo CheckoutForm0(); ?> </div>
 
                
     <div class="full clearfix border_t box"> 
            <p class="f_half left">
            
            <a class="button gray ico-arrow_l" onclick="document.location.href='<?php echo get_option('checkout_url'); ?>'">
            
            <?php echo $PPT->_e(array('button','7')) ?> 
            </a>
                  
            </p> 
             <p class="f_half left"> 
                 
                <a href="javascript:void(0)" onclick="document.cartConfirmation.submit();" class="button gray" style="float:right;">
                
				<?php echo $PPT->_e(array('button','10')) ?>
                </a> 
            </p>        
     
     </div> 
                
                
 </div>  

</form>

 


<?php }elseif(!isset($_POST['step1'])){ 

 /*----------------------------------------------------- SHOPPING BASKET SECTION ----------------------------------------------------*/ ?>

<script type="text/javascript">
function CheckoutAlert1(text, CheckoutLink,d,f){

var answer = confirm (text)
	if (answer){
		removeProduct('<?php echo session_id(); ?>','<?php echo $GLOBALS['template_url']."/"; ?>',''+d+'',''+f+'',1);
		setTimeout('window.location.reload()',2250);
	}
}
function CheckoutAlert2(text, CheckoutLink,d,f,c){

var answer = confirm (text)
	if (answer){
		increaseQty('<?php echo session_id(); ?>','<?php echo $GLOBALS['template_url']."/"; ?>',''+d+'',''+f+'',''+c+'');
		setTimeout('window.location.reload()',2250);
	}
}
function CheckoutAlert3(text, CheckoutLink,d,f){

var answer = confirm (text)
	if (answer){
		removeProduct('<?php echo session_id(); ?>','<?php echo $GLOBALS['template_url']."/"; ?>',''+d+'',''+f+'',100);
		setTimeout('window.location.reload()',2250);
	}
}

</script>
 
<div class="itembox">

	<h1 id="icon-checkout-title" class="title"><?php echo $PPT->_e(array('sp','14')); ?></h1>    
    
    <div class="itemboxinner greybg">
            
    	<?php echo $ThemeDesign->CARTITEMS(); ?>
            
     </div>            
            
</div> 

<?php if($_SESSION['ddc']['price'] > 0){ ?>
     
<div class="checkoutbox">
     
     <?php if(get_option("coupon_enable") =="yes" ){  ?>  
     
        <form name="ccode" method="POST" class="checkoutcouponbox">         
        
        <?php wp_nonce_field('ShopperPressCoupon') ?> 
        <b><?php echo $PPT->_e(array('sp','15')); ?></b><br />
            <input name="ccode" type="text" class="checkoutcouponinput"> 
            <a href="#" onclick="document.ccode.submit();">
                <img src="<?php echo get_bloginfo("template_url")."/template_shopperpress/images/refresh.png"; ?>" align="middle" />
            </a>
        </form>	
     
    <?php } ?> 
      
      
    <ul id="CheckOut-Totals"><?php echo $ThemeDesign->CHECKOUTTOTALS(); ?></ul>

	<div class="clearfix"></div>      
 
</div>    








     
     
<form action="" name="cartStep1" id="cartStep1" method="post" class="topper" >



<?php if(get_option('checkout_skip_registration') == "1page"){ ?>

    <div class="f_half left">
    
        <div class="itembox" style="margin-right:5px;">
         
            <?php echo CheckoutForm1(); ?>
        
        </div>
    
    </div>
                 
    
    <div class="f_half left"> 
    
        <div class="itembox" style="margin-left:5px;">
        
            <?php echo CheckoutForm0(); ?>
    
        </div>
    
    </div>

<?php } ?>


<?php if(get_option('display_amazon_checkout') == "yes" && $ThemeDesign->CheckHasAmazonProducts()){ $hidencheckoutbuttons=true;

	require_once (TEMPLATEPATH ."/PPT/class/class_amazon.php");	
	$obj = new AmazonProductAPI();

		/* BUILD PRODUCT ASIN DATA */
		$ap =1;
		if(is_array($_SESSION['ddc']['productsincart'])){ foreach($_SESSION['ddc']['productsincart'] as $key => $value) { 
 
			$parameters["Item.".$ap.".ASIN"] 		=  get_post_meta($key, "amazon_guid", true);
			$parameters["Item.".$ap.".Quantity"] 	=  $value;
			 
 			$ap++;
		}}		
 	 
	$parameters["Operation"] 		=  "CartCreate";
	$parameters["MergeCart"] 		=  "False";
	$parameters["AssociateTag"] 	=  get_option("affiliates_20_ID");
 
 
 	// CHECK THE AMAZON STORE COUNTRY CODE
	$country = get_option('enabled_amazon_updater_country');
	
	if ($country == "") {
		$country = "com";
	}
	
 	$result = $obj->CreateCart($parameters,$country);
	
	if(isset($result->Cart->Request->Errors->Error->Message)){
	
	echo "<div class='red_box'><div class='red_box_content'><img src='".get_template_directory_uri()."/PPT/img/cross.png' align='absmiddle' />".$result->Cart->Request->Errors->Error->Message."</div></div>";
	
	}
 
	// echo $result->Cart->CartItems->SubTotal->FormattedPrice;
	if(strlen($result->Cart->CartItems->SubTotal->FormattedPrice) > 0){
	 ?>
     <style> .checkoutbox { display:none; }	 </style>
	</form>
    
	<form method="post" action="<?php echo $result->Cart->PurchaseURL; ?>" class="amazoncheckoutbox1" id="amazoncheckoutbox1">
	
	<?php if(is_array($result->Cart->CartItems->CartItem)){ foreach($result->Cart->CartItems->CartItem as $item){  ?>
	<div style="padding:10px; background:#efefef;"><?php echo $item->Title; ?> - <?php echo $PPT->_e(array('sp','16')) ?> <?php echo $item->Price->FormattedPrice; ?></div>
	<?php } } ?>
	
	<?php if(strlen($result->Cart->CartItems->SubTotal->FormattedPrice) > 1){ ?>
	 <input class="button gray" type="submit"  value="<?php echo $PPT->_e(array('button','10')) ?>" /> 
   
     
	<?php } ?>

	</form><form> <?php
	
	}
 ?> 



<?php }elseif(get_option('checkout_skip_registration') == "yes"){ ?> 

    <input type="hidden" name="action" value="checkout" />
    <input type="hidden" name="save" value="1" />
    <input type="hidden" name="orderID" value="<?php echo $userdata->ID."-".date("d")."".time(); ?>" />
    <?php if($GLOBALS['SHOPPERPRESS_SHIPPING'] > 0){ ?><input type="hidden" name="form[shipping]" value="<?php echo $GLOBALS['SHOPPERPRESS_SHIPPING']; ?>"><?php } ?>
    <?php if($GLOBALS['SHOPPERPRESS_TAX'] > 0){ ?><input type="hidden" name="form[tax]" value="<?php echo $GLOBALS['SHOPPERPRESS_TAX']; ?>"> <?php } ?>
    <?php if($GLOBALS['SHOPPERPRESS_PROMOTIONS'] > 0){ ?><input type="hidden" name="form[discount]" value="<?php echo $GLOBALS['SHOPPERPRESS_PROMOTIONS']; ?>"><?php } ?>
    <input type="hidden" name="form[total]" value="<?php echo $GLOBALS['SHOPPERPRESS_CARTTOTAL']; ?>"> 
	<input type="hidden" name="coupon" value="<?php if(isset($_POST['ccode'])){ echo strip_tags($_POST['ccode']); } ?>" />  

<?php }elseif(get_option('checkout_skip_registration') == "1page"){ ?>

<input type="hidden" name="action" value="confirmation" />
<input type="hidden" name="step1" value="1" />
    <input type="hidden" name="orderID" value="<?php echo $userdata->ID."-".date("d")."".time(); ?>" />
    <?php if($GLOBALS['SHOPPERPRESS_SHIPPING'] > 0){ ?><input type="hidden" name="form[shipping]" value="<?php echo $GLOBALS['SHOPPERPRESS_SHIPPING']; ?>"><?php } ?>
    <?php if($GLOBALS['SHOPPERPRESS_TAX'] > 0){ ?><input type="hidden" name="form[tax]" value="<?php echo $GLOBALS['SHOPPERPRESS_TAX']; ?>"> <?php } ?>
    <?php if($GLOBALS['SHOPPERPRESS_PROMOTIONS'] > 0){ ?><input type="hidden" name="form[discount]" value="<?php echo $GLOBALS['SHOPPERPRESS_PROMOTIONS']; ?>"><?php } ?>
    <input type="hidden" name="form[total]" value="<?php echo $GLOBALS['SHOPPERPRESS_CARTTOTAL']; ?>"> 
	<input type="hidden" name="ccode" value="<?php if(isset($_POST['ccode'])){ echo strip_tags($_POST['ccode']); } ?>" />  

<?php }else{ ?>
<input type="hidden" name="step1" value="1" />
<input type="hidden" name="coupon" value="<?php if(isset($_POST['ccode'])){ echo strip_tags($_POST['ccode']); } ?>" />  

<?php } ?>

<?php if(!isset($hidencheckoutbuttons)){ ?>
<div class="full clearfix box"> 

 
<a class="button gray" href="<?php echo $GLOBALS['bloginfo_url']; ?>/index.php?emptyCart=1" onclick="return confirm('<?php echo $PPT->_e(array('validate','5')); ?>')">

<?php echo $PPT->_e(array('sp','17')) ?>
</a>    

 

<a class="button green right" href="javascript:void(0)" onclick="<?php if(get_option('checkout_skip_registration') == "1page"){ ?>return CheckFormData();<?php }else{ ?>document.cartStep1.submit();<?php } ?>" style="margin-left:10px; ">

<?php echo $PPT->_e(array('sp','9')) ?>
</a> 


<a href="<?php echo $GLOBALS['bloginfo_url']; ?>/" class="button gray right"><?php echo $PPT->_e(array('sp','18')) ?></a> 

        

</div>
<?php } ?>
</form>




<?php } ?>

<?php  /*----------------------------------------------------- END SHOPPING BASKET SECTION ----------------------------------------------------*/ 








}else{







if(isset($_POST['action']) && $_POST['action'] == "confirmation"){  ?>
 

<form action="" name="cartCheckout" id="cartCheckout" method="post"> 
<input type="hidden" name="action" value="checkout" />
<input type="hidden" name="save" value="1" />
<input type="hidden" name="ccode" value="<?php if(isset($_POST['ccode'])){ echo strip_tags($_POST['ccode']); } ?>" />
<input type="hidden" name="orderID" value="<?php echo $GLOBALS['CHECKOUT_ORDERID']; ?>" />
<?php foreach($_POST['form'] as $key=>$value){ 
if($key == "shippingmethod" && get_option("shipping_enable_UPS") =="yes"){
print '<input name="form['.$key.'-basic]" value="'.$value.'" type="hidden" id="hidden-'.$key.'-basic">'; 
}
print '<input name="form['.$key.']" value="'.$value.'" type="hidden" id="hidden-'.$key.'">'; } ?>                
<?php foreach($_POST['shipping'] as $key=>$value){ print '<input name="shipping['.$key.']" value="'.$value.'" type="hidden">'; } ?>  
<input type="hidden" name="total" value="<?php echo $GLOBALS['SHOPPERPRESS_CARTTOTAL']; ?>" />              
 
 
<div id="step4box">

	<div class="itembox">

	<h1 id="icon-checkout-title" class="title"><?php echo $PPT->_e(array('sp','31')) ?></h1>

	<div class="itemboxinner greybg">  
    
    <p><?php echo $PPT->_e(array('sp','32')) ?></p>  
            
		<div class="full clearfix">
			<div class="f_half left"><div class="gut">
            
               <h3><?php echo $PPT->_e(array('sp','33')) ?></h3><hr>
               
                               
               <?php echo "<b>".$_POST['form']['first_name']." ".$_POST['form']['last_name']."</b><br />".$_POST['form']['company']."<br />".$_POST['form']['address']." ".$_POST['form']['address2']." <br> ".
			   $_POST['form']['city'].", ".$_POST['form']['postcode']." <br> ".$_POST['form']['state']." <br> ".$_POST['form']['country']." <br> "; ?>

<!-- ============= Add Code for show the organization on the checkout page ====================== -->
             <!--  <p><?php echo $PPT->_e(array('sp','34')) ?> <?php echo $_POST['form']['phone']; ?> <br /> <?php echo $PPT->_e(array('sp','35')) ?> <?php echo $_POST['form']['email']; ?><br /></p>-->

<!-- ============= End Code for show the organization on the checkout page ====================== -->             
             
             <p><?php echo $PPT->_e(array('sp','34')) ?> <?php echo $_POST['form']['phone']; ?> <br /> <?php echo $PPT->_e(array('sp','35')) ?> <?php echo $_POST['form']['email']; ?><br /><b><?php echo $PPT->_e(array('sp','52')) ?></b>&nbsp;&nbsp;&nbsp;<?php echo $_POST['form']['organization']; ?></p>
               
                
                 
              </div>
			</div>
		
			<div class="f_half left">

        	<h3><?php echo $PPT->_e(array('sp','36')) ?></h3><hr>
        
			  <?php 
              
              if(!isset($_POST['form']['sameDelivery'])){ print "<div class='yellow_box'><div class='yellow_box_content'><div align='center'>".$PPT->_e(array('sp','38'))."</div></div></div>"; }else{ 
              
             echo "<b>".$_POST['shipping']['first_name']." ".$_POST['shipping']['last_name']."</b><br />".$_POST['shipping']['company']."<br />".$_POST['shipping']['address']." ".$_POST['shipping']['address2']." <br> ".
			 $_POST['shipping']['city'].", ".$_POST['shipping']['postcode']." <br> ".$_POST['shipping']['state']." <br> ".$_POST['shipping']['country']." <br> "; 
              
              } ?>
        
			</div>
            
            <div class="clearfix"></div>
            
             <div class="f_half left">
            	<div class="gut">
            
               <h3><?php echo $PPT->_e(array('sp','37')) ?></h3> 
               
               <?php echo nl2br(stripslashes(strip_tags($_POST['form']['comments']))); ?>
     			 
               </div>
            </div>           
            
            <div class="f_half left">
            	<div class="gut">
            
               <h3><?php echo $PPT->_e(array('sp','39')) ?></h3> 
               
     			<?php if($GLOBALS['SHOPPERPRESS_SHIPPINGCOUNTRY'] > 0){ ?>
			
				<p style="padding:5px; background:#e9ffdd; margin-top:10px;"><?php echo str_replace("%a",premiumpress_price($GLOBALS['SHOPPERPRESS_SHIPPINGCOUNTRY'],$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1,2),str_replace("%b",$GLOBALS['SHIPTHISCOUNTRYCHECK'],$PPT->_e(array('sp','40')))); ?> </p>
		
				<?php } ?> 
                
                <br /><ul id="CheckOut-Totals"><?php echo $ThemeDesign->CHECKOUTTOTALS(); ?></ul>
               
            </div>
         </div> 
			
		</div> 
            
	</div>            
 

	<?php 
	// UPS DISPLAY
	if(get_option("shipping_enable_UPS") =="yes"){ $ups = $ThemeDesign->UPSMETHODS(); if(strlen($ups) > 5){ ?>
    <div class='green_box'><div class='green_box_content'>
    <h3><?php echo $PPT->_e(array('sp','27')) ?></h3> <p><?php echo $PPT->_e(array('sp','41')) ?></p>
    <?php echo $ups;  ?>
    </div></div>
    <?php } } ?>
    
    
     

 </div>             
<div class="full clearfix border_t box"> 
<p class="f_half left"> 
    <a <?php if(get_option('checkout_skip_registration') == "1page"){ ?>href="javascript:history.back(1);"<?php }else{ ?>href="javascript:void(0)" onclick="jQuery('#step4box').hide();jQuery('#step2box').show();" <?php } ?> class="button gray">
    
        <?php echo $PPT->_e(array('button','7')) ?> 
    </a> 
</p> 
<p class="f_half left"> 
    <a href="javascript:void(0)" onclick="document.cartCheckout.submit();" class="button gray" style="float:right;">
    
        <?php echo $PPT->_e(array('button','10')) ?>            
    </a> 
</p>        
</div>            
</div> 
    
</form>     


<?php }else{  ?>
<script>    jQuery(document).ready(function() {     <?php if ( $user_ID ){ ?>jQuery('#step1box').hide();jQuery('#step2box').show();<?php } ?>    });    </script>

<?php } ?>   
     
     
 


 



















<div id="step1box" >

<?php echo CheckoutForm2(); ?>  

</div> 






<?php if(get_option('checkout_skip_registration') != "1page"){ // no need to display the forms since were using a single checkout ?>

<form action="" name="cartUserData" id="cartUserData" onSubmit="return CheckFormData();" method="post"> 
<input type="hidden" name="action" value="confirmation" />
<input type="hidden" name="step100" value="1" />
<input type="hidden" name="ccode" value="<?php if(isset($_POST['coupon'])){ echo strip_tags($_POST['coupon']); } ?>" />
    

<?php  /*----------------------------------------------------- CONTACT INFORMATION SECTION ----------------------------------------------------*/ ?>
    
     
<div  id="step2box" style="display:none;">

<div class="itembox">  <?php echo CheckoutForm1(); ?> </div>    
    

<div class="full clearfix border_t box"> 
        
		<p class="f_half left"> 
               <a href="<?php echo get_option('checkout_url'); ?>" rel="nofollow" class="button gray">
               
               <?php echo $PPT->_e(array('button','7')); ?>                 
                
                </a> 
            </p>
            
             <p class="f_half left"> 
                 
                <a href="javascript:void(0)" onclick="return CheckFormData();" class="button gray right">
				
				<?php echo $PPT->_e(array('button','10')) ?>
                
                </a> 
                
	</p>        
            
</div>
     
</div>

</form>



<?php } ?>








<?php } ?>

  
  
<?php  get_footer(); 


































function CheckoutForm0(){ global $ThemeDesign, $PPT; ?>

			<h1 id="icon-checkout-title" class="title"><?php echo $PPT->_e(array('sp','26')) ?></h1>    
    
    		<div class="itemboxinner greybg">             
            
            
            <?php $ShippingData = $ThemeDesign->SHIPPINGMETHODS(); if(strlen(trim($ShippingData)) > 5){ ?>
            
            <div style="background:#efefef; border:1px solid #ddd; padding:15px;">
            
            <p><b><?php echo $PPT->_e(array('sp','27')) ?></b></p>
            <p><?php echo $PPT->_e(array('sp','28')) ?></p>
            <?php echo $ShippingData; ?>
            </div>
            
            <?php } ?> 
             
             
            
			
			<?php $data = $ThemeDesign->PAYMENTMETHODS(); if(strlen($data) > 5){ ?>
             
            <div style="background:#efefef; border:1px solid #ddd; padding:15px; margin-top:15px;">
             
            <p><b><?php echo $PPT->_e(array('sp','26')) ?></b></p>
            <p><?php echo $PPT->_e(array('sp','29')) ?></p>
            
            <?php echo $data ; ?>
            
            </div>
            <?php } ?> 
            
             
                        
             <div style="background:#efefef; border:1px solid #ddd; padding:15px; margin-top:15px;" id="checkoutcommentsbox"> 
             
             <p><b><?php echo $PPT->_e(array('sp','30')) ?></b></p>            
             
                <textarea tabindex="5" class="long" rows="4" name="form[comments]"></textarea><br /> 
                
            </div> 
            
      </div>      
            

<?php }






function CheckoutForm1(){  global  $PPT, $userdata; get_currentuserinfo(); ?>

<?php $organization = get_user_meta($userdata->data->ID, 'organization', true); ?>
<? // echo"<pre>***"; print_r($_POST); echo"</pre>"; echo"<br/>";
//echo"<pre>PPT"; print_r($PPT); echo"</pre>";
//echo get_user_meta($userdata->data->ID, 'organization', true);
 ?>
 
<?php if($GLOBALS['checkVAT'] == "yes"){ ?>
<script type="text/javascript" src="<?php echo PPT_PATH; ?>js/jquery.VAT.js"></script>
<?php } ?>

<script language="javascript">

		function CheckFormData()
		{
 
 		
			var fname 	= document.getElementById("fname"); 
			var lname 	= document.getElementById("lname");
			var email = document.getElementById("email");
			var phone = document.getElementById("phone");
			var address = document.getElementById("address");
			var city = document.getElementById("city");
 			var zip = document.getElementById("zip");
			var country = document.getElementById("country");		
			var state = document.getElementById("state");
			
			var organization = document.getElementById("organization");
			 
			if(fname.value == '' || lname.value == ''  )
			{
				alert('<?php echo $PPT->_e(array('validate','1')) ?>');
				fname.style.backgroundColor="#ffcace";
				lname.style.backgroundColor="#ffcace";
				return false;
			}
			if(state.value == '0'  )
			{
				alert('<?php echo $PPT->_e(array('validate','0')) ?>');
				state.style.backgroundColor="#ffcace"; 
				return false;
			}
			if(email.value == ''  )
			{
				alert('<?php echo $PPT->_e(array('validate','3')) ?>');
				email.style.backgroundColor="#ffcace"; 
				return false;
			}			
			if(phone.value == ''  )
			{
				alert('<?php echo $PPT->_e(array('validate','0')) ?>');
				phone.style.backgroundColor="#ffcace"; 
				return false;
			}
// Validation of organization field			
			if(organization.value == ''  )
			{
				alert('<?php echo $PPT->_e(array('validate','0')) ?>');
				organization.style.backgroundColor="#ffcace"; 
				return false;
			}
// End code of validation of organization field
			if(address.value == ''  )
			{
				alert('<?php echo $PPT->_e(array('validate','0')) ?>');
				address.style.backgroundColor="#ffcace"; 
				return false;
			}		
			if(country.value == '0'  )
			{
				alert('<?php echo $PPT->_e(array('validate','0')) ?>');
				country.style.backgroundColor="#ffcace"; 
				return false;
			}
			if(city.value == ''  )
			{
				alert('<?php echo $PPT->_e(array('validate','0')) ?>');
				city.style.backgroundColor="#ffcace"; 
				return false;
			}
			if(zip.value == ''  )
			{
				alert('<?php echo $PPT->_e(array('validate','0')) ?>');
				zip.style.backgroundColor="#ffcace"; 
				return false;
			}
			
			<?php if($GLOBALS['checkVAT'] == "yes"){ ?>
			var vatnum = document.getElementById("VATnum").value; 
			if( vatnum != ''  )
			{
				if (!checkVATNumber (vatnum)) { 
				
				alert ("<?php echo $PPT->_e(array('validate','19')) ?>"); 
				return false;
				
				}		 
				 
			}
			<?php } ?>
			
			
			
			<?php   if(get_option('checkout_skip_registration') == "1page"){ ?>
				document.cartStep1.submit();
			<?php }else{ ?>
				 
				document.cartUserData.submit();
			
			<?php }   ?>
		}
		

		 
		  
</script>

<?php $ADD = explode("**",$userdata->jabber); ?>
<?php //echo"<pre>ADD"; print_r($ADD); echo"</pre>"; echo"<br/>";
//echo"<pre>userdata"; print_r($userdata); echo"</pre>";  ?>
<?php  

if(strlen($ADD[0]) > 1 && !isset($_POST['form']['country']) ){ $_POST['form']['country'] = $ADD[0]; } 
if(isset($ADD[1]) && strlen($ADD[1]) > 1 && !isset($_POST['form']['state']) ){ $_POST['form']['state'] = $ADD[1]; } 
if(isset($_POST['form']['country'])){ ?>
<script type="text/javascript"> jQuery(document).ready(function() { PremiumPressChangeStateMyAccount('<?php echo $_POST['form']['country']; ?>', '<?php echo $_POST['form']['state']; ?>'); }); </script>
<?php } ?>

 

<?php if(isset($_POST['shipping']['country'])){ ?>
<script type="text/javascript"> jQuery(document).ready(function() { PremiumPressChangeStateShipping('<?php echo $_POST['shipping']['country']; ?>'); }); </script>
<?php } ?>

<h1 id="icon-checkout-title" class="title"><?php echo $PPT->_e(array('sp','21')); ?></h1>

<div class="itemboxinner greybg">  
 

			<h3><?php echo $PPT->_e(array('sp','22')); ?></h3>
         
            <div class="full clearfix border_t box"> 
            
            <p class="f_half left"> 
                <label for="firstname"><?php echo $PPT->_e(array('myaccount','10')); ?> <span class="required">*</span></label>
                <input type="text" name="form[first_name]" id="fname" value="<?php if(isset($_POST['form']['first_name'])){ echo strip_tags($_POST['form']['first_name']); }else{ echo $userdata->first_name;} ?>" class="short" tabindex="1" />
                 
            </p> 
            <p class="f_half left"> 
                <label for="lastname"><?php echo $PPT->_e(array('myaccount','11')); ?> <span class="required">*</span></label>
                <input type="text" name="form[last_name]" id="lname" value="<?php if(isset($_POST['form']['last_name'])){ echo strip_tags($_POST['form']['last_name']); }else{ echo $userdata->last_name;} ?>" class="short" tabindex="2" />
                 
            </p> 
            
            </div>	
             
            
             <div class="full clearfix border_t box"> 
             
            <p class="f_half left"> 
                <label for="email"><?php echo $PPT->_e(array('myaccount','12')); ?> <span class="required">*</span></label>
                <input type="text" name="form[email]" id="email" value="<?php if(isset($_POST['form']['email'])){ echo strip_tags($_POST['form']['email']); }else{ echo $userdata->user_email;} ?>" class="short" tabindex="3" />
                 
            </p> 
            <p class="f_half left">  
                <label for="phone"><?php echo $PPT->_e(array('myaccount','19')); ?> <span class="required">*</span></label>
                <input type="text" name="form[phone]" id="phone" value="<?php if(isset($_POST['form']['phone'])){ echo strip_tags($_POST['form']['phone']); }else{ echo  $ADD[5];} ?>" class="short" tabindex="4" />
                 
            </p> 
            
            </div>
            
            
            
			<h3><?php echo $PPT->_e(array('sp','23')); ?></h3>
         
            <div class="full clearfix border_t box"> 
            
                <p class="f_half left"> 
                    <label for="name"><?php echo $PPT->_e(array('myaccount','33')); ?></label>
                    <input type="text" name="form[company]" value="<?php if(isset($_POST['form']['company'])){ echo strip_tags($_POST['form']['company']); } ?>" class="short" tabindex="5" />
                     
                </p>
                <p class="f_half left">
                
                <?php if($GLOBALS['checkVAT'] == "yes"){ ?>
                
                    <label for="name"><?php echo $PPT->_e(array('myaccount','34')); ?></label>
                    <input type="text" id="VATnum" name="form[VATnum]" value="<?php if(isset($_POST['form']['VATnum'])){ echo strip_tags($_POST['form']['VATnum']); } ?>" class="short" tabindex="5" /><br />
                                
                <?php } ?>                    
                </p> 
            
            </div>
            
<!-- ============== Code for add organization dropdown in the checkout page ============================= -->

			<h3><?php echo $PPT->_e(array('sp','51')); ?></h3>
			
            <?php
                    global $wpdb;
                    $organization = get_user_meta($userdata->ID, 'organization', true);
                    $sql = "SELECT org_name FROM all_organizations";
                    $results = $wpdb->get_results($sql);
            ?>
            <div class="full clearfix border_t box"> 

                <p class="f_half left"> 
                    <label for="organization" style ="width:200%; font-size:12px; font-weight:normal;"><?php echo $PPT->_e(array('sp','52')); ?><span class="required">*</span></label>
                    <select name ="form[organization]" id="organization">
                        <?php if (array_key_exists('6', $ADD)) { ?>
                            <option value= "">Select any one</option>
                        <?php foreach ($results as $result) {
                            if (isset($ADD[6]) && $ADD[6] ==$result->org_name) { ?>
                                <option selected ="selected" value = "<?php echo $result->org_name; ?>"><?php echo $result->org_name; ?></option>
                            <?php } else {?>
                                <option value = "<?php echo $result->org_name; ?>"><?php echo $result->org_name; ?></option>
                        <?php } } } else { ?>                    
                        <option value= "">Select any one</option>
                        <?php foreach ($results as $result) { 
                            if ($organization === $result->org_name) { ?>
                                <option selected ="selected" value = "<?php echo $result->org_name; ?>"><?php echo $result->org_name; ?></option>
                            <?php } else { ?>
                            <option value = "<?php echo $result->org_name; ?>"><?php echo $result->org_name; ?></option>
                        <?php } } } ?>
                    </select>

                </p>
                <p class="f_half left">

                </p>

            </div>


<!-- ============== End Code for add organization dropdown in the checkout page ========================= -->


            <div class="full clearfix border_t box">

            <p class="f_half left">
                <label for="address"><?php echo $PPT->_e(array('myaccount','17')); ?> 1 <span class="required">*</span></label>
                <input type="text" name="form[address]" id="address" value="<?php if(isset($_POST['form']['address'])){ echo strip_tags($_POST['form']['address']); }else{ echo  $ADD[2];} ?>" class="short" tabindex="6" />

            </p>
            <p class="f_half left"> 
                <label for="address2"><?php echo $PPT->_e(array('myaccount','17')); ?> 2</label>
                <input type="text" name="form[address2]" value="" class="short" tabindex="7" />

            </p>

            </div>

            <div class="full clearfix border_t box">

            <p class="f_half left"> 
                <label for="city"><?php echo $PPT->_e(array('myaccount','16')); ?> <span class="required">*</span></label>
                <input type="text" name="form[city]" id="city" value="<?php if(isset($_POST['form']['city'])){ echo strip_tags($_POST['form']['city']); }else{ echo  $ADD[3];} ?>" class="short" tabindex="8" />
                 
            </p> 
            <p class="f_half left"> 
                <label for="postcode"><?php echo $PPT->_e(array('myaccount','18')); ?> <span class="required">*</span></label>
                <input type="text" name="form[postcode]" id="zip" value="<?php if(isset($_POST['form']['postcode'])){ echo strip_tags($_POST['form']['postcode']); }else{ echo  $ADD[4];} ?>" class="short" tabindex="9" />

            </p>

            </div>


             <div class="full clearfix border_t box"> 
             
            <p class="f_half left"> 
                <label for="country"><?php echo $PPT->_e(array('myaccount','15')); ?> <span class="required">*</span></label>
                <select name="form[country]" id="country" onchange="PremiumPressChangeState(this.value)" class="short" tabindex="10"> 
                <?php if(isset($_POST['form']['country'])){ ?><option value="<?php echo $_POST['form']['country']; ?>"><?php echo $_POST['form']['country']; ?></option><?php } ?>               
                <?php if(file_exists(PPT_THEME_DIR. '/_countrylist.php')){ include(PPT_THEME_DIR. '/_countrylist.php' ); } ?>
        		</select><br />
                 
            </p>
             
            <div id="PremiumPressState"> <input type="hidden" value="0" name="form[state]" id="state"  /></div> 
            
            </div>	
            
            
            
            <p><input <?php if(isset($_POST['form']['sameDelivery']) && $_POST['form']['sameDelivery'] ==1){ echo "checked=checked"; } ?> name="form[sameDelivery]" type="checkbox" value="1" onclick="toggleLayer('ShippingForm')"  /> <?php echo $PPT->_e(array('sp','24')); ?> </p>
            
            
            
            <div id="ShippingForm" <?php if(isset($_POST['form']['sameDelivery'] ) && $_POST['form']['sameDelivery'] ==1){ echo 'style="display:visible"';  }else{ echo 'style="display:none"'; } ?>>
            
                <h3><?php echo $PPT->_e(array('sp','25')); ?></h3>
             
                <div class="full clearfix border_t box"> 
                <p class="f_half left"> 
                    <label for="name"><?php echo $PPT->_e(array('myaccount','33')); ?></label>
                    <input type="text" name="shipping[company]" value="<?php if(isset($_POST['shipping'])){ echo strip_tags($_POST['shipping']['company']); } ?>" class="short" tabindex="13" />
                     
                </p> 
                <p class="f_half left"> 
                    
                </p> 
            
            </div>	
            
            
            
            
             
              <div class="full clearfix border_t box"> 
              
                <p class="f_half left"> 
                    <label for="name"><?php echo $PPT->_e(array('myaccount','10')); ?> <span class="required">*</span></label>
                    <input type="text" name="shipping[first_name]" value="<?php if(isset($_POST['shipping'])){ echo strip_tags($_POST['shipping']['first_name']); } ?>" class="short" tabindex="14" />
                     
                </p> 
                <p class="f_half left"> 
                    <label for="email"><?php echo $PPT->_e(array('myaccount','11')); ?> <span class="required">*</span></label>
                    <input type="text" name="shipping[last_name]" value="<?php if(isset($_POST['shipping'])){ echo strip_tags($_POST['shipping']['last_name']); } ?>" class="short" tabindex="15" />
                     
                </p> 
            
            </div>	
                       
             <div class="full clearfix border_t box"> 
             
                <p class="f_half left"> 
                    <label for="name"><?php echo $PPT->_e(array('myaccount','17')); ?> 1 <span class="required">*</span></label>
                    <input type="text" name="shipping[address]" value="<?php if(isset($_POST['shipping'])){ echo strip_tags($_POST['shipping']['address']); } ?>" class="short" tabindex="16" />
                     
                </p> 
                <p class="f_half left"> 
                    <label for="email"><?php echo $PPT->_e(array('myaccount','17')); ?> 2 <span class="required">*</span></label>
                    <input type="text" name="shipping[address2]" value="<?php if(isset($_POST['shipping'])){ echo strip_tags($_POST['shipping']['address2']); } ?>" class="short" tabindex="17" />
                     
                </p> 
            
            </div>		

             <div class="full clearfix border_t box"> 
             
                <p class="f_half left"> 
                    <label for="name"><?php echo $PPT->_e(array('myaccount','16')); ?> <span class="required">*</span></label>
                    <input type="text" name="shipping[city]" value="<?php if(isset($_POST['shipping'])){ echo strip_tags($_POST['shipping']['city']); } ?>" class="short" tabindex="18" />
                     
                </p> 
                <p class="f_half left"> 
                    <label for="email"><?php echo $PPT->_e(array('myaccount','18')); ?> <span class="required">*</span></label>
                    <input type="text" name="shipping[postcode]" value="<?php if(isset($_POST['shipping'])){ echo strip_tags($_POST['shipping']['postcode']); } ?>" class="short" tabindex="19" />
                     
                </p> 
            
            </div>	
            
            
             <div class="full clearfix border_t box"> 
             
                <p class="f_half left"> 
                    <label for="name"><?php echo $PPT->_e(array('myaccount','15')); ?> <span class="required">*</span></label>
                    <select name="shipping[country]" id="country" onchange="PremiumPressChangeStateShipping(this.value)" class="short" tabindex="20"> 
                    <?php if(isset($_POST['shipping']['country'])){ ?><option value="<?php echo $_POST['shipping']['country']; ?>"><?php echo $_POST['shipping']['country']; ?></option><?php } ?>               
                    <?php if(file_exists(PPT_THEME_DIR. '/_countrylist.php')){ include(PPT_THEME_DIR. '/_countrylist.php' ); } ?>
                    </select><br />
                </p> 
                
                <div id="PremiumPressStateShipping"> </div>
             
            </div>
            
            
</div></div>
            
           

<?php } 







function CheckoutForm2(){ 

global $PPT,$ThemeDesign, $PPTDesign, $user_ID, $userdata; get_currentuserinfo(); ?>

<?php if ( !$user_ID && $_POST['step1'] != "step1" && !isset($_POST['form']['first_name']) ){ 

if(get_option('users_can_register') != 1){ ?>

<script type="text/javascript">jQuery(document).ready(function() { jQuery('#step1box').hide();jQuery('#step2box').show(); }); </script>

<?php }else{ ?>



	 
	<div class="itemboxinner greybg">             
            
		<div class="full clearfix">
			<div class="f_half left"><div class="gut">
            
                <h3><?php echo $PPT->_e(array('sp','45')); ?></h3><hr style="margin:5px;">
                
                <div id="loginformData"></div>
                
                <form class="loginform"  method="post" id="loginform" onSubmit="return false;">              
                <?php if(!isset($si_captcha_version)){ ?>
				<input type="hidden" name="captcha_code" id="captcha_code" value="">
                <input type="hidden" name="si_code_log" id="si_code_log" value="">
				<?php } ?> 
               <p>
                    <label for="user_login"><?php echo $PPT->_e(array('login','10')); ?>:</label>
                    <input name="user_login" class="mid" id="user_login" type="text">
                </p>
                <p>
                    <label for="user_pass"><?php echo $PPT->_e(array('myaccount','26')); ?>:</label>
                    <input name="user_pass" class="mid" id="user_pass" type="password">
                </p> 
                    <input name="rememberme"  value="forever" type="hidden">
                    
                    <?php do_action('login_form'); ?>
                     
                <p class="submit">
        
          
                <input type="submit" value="<?php echo $PPT->_e(array('button','16')); ?>" class="button gray" onclick="UserActionForm(user_login.value,user_pass.value,'login','loginformData',captcha_code.value,si_code_log.value);jQuery('#loginform').hide();">
                    <input type="hidden" name="testcookie" value="1">
                </p>
                 
            </form>
              </div>  
			</div>
			
            
			<div class="f_half left">


        <h3><?php echo $PPT->_e(array('sp','46')); ?></h3><hr style="margin:5px;">
         
        <div class='green_box'><div class='green_box_content'><?php echo $PPT->_e(array('sp','47')); ?></div></div>
      
       
        
    <div class="full clearfixbox"> 
    
   			
    
            <p class="f_half left"> 
               <a href="<?php echo home_url(); ?>/wp-login.php?action=register&r=1" rel="nofollow" class="button gray">
                <?php echo $PPT->_e(array('head','7')); ?>
                </a> 
            </p>
            
             
            
            <?php if(get_option("checkout_display_guest") =="yes"){  ?>
             <p class="f_half left"> 
                 
                <a href="javascript:void(0)" onclick="jQuery('#step1box').hide();jQuery('#step2box').show();" class="button gray" style="float:right;">
                <?php echo $PPT->_e(array('sp','48')); ?>
                </a> 
            </p>    
            <?php } ?>     
       </div>  
 
          
        
        <div class="clear"></div>


			</div>
			
		</div> 
            
	</div> 
 
 <?php } ?>              
<?php } ?>          

<?php }


?>

<?php
/*

Plugin Name: Paypal & Stripe - Donation Thank you Emails

Plugin URI: 

Description: Wordpress Paypal and Stripe IPN and send thank you emails automatically to donors.

Version: 1.0

Author: Hudson Atwell

Contributors: Hudson Atwell, w3care

License: GPL2

*/
 

// Default define constant

define("W3_SUBJECT","Your Subject");

define("W3_DESC","Hello {{firstname}} {{lastname}} <br /><br /> Your Message Body  <br /><br />Thanks");

define("W3_ST_DESC","Hello {{firstname}} <br /><br /> Your Message Body  <br /><br />Thanks");

define("W3_TEMPLATE","{{firstname}} {{lastname}},{{donar-email}}");
define("W3_ST_TEMPLATE","{{firstname}} ,{{donar-email}}");

  

// Hook for adding admin menus

add_action('admin_menu', 'w3_pp_ipn_plugin_admin_menu');

function w3_pp_ipn_plugin_admin_menu(){

	add_menu_page('Paypal IPN Settings ', 'Paypal IPN', 'publish_posts', 'paypal_ipn', 'settings_pp_ipn');
	//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	add_submenu_page("paypal_ipn", "Stripe IPN", "Stripe IPN", 0, "stripe_ipn", "settings_stripe_ipn");

}

function settings_stripe_ipn(){

?>

<div class="wrapper pp_ipn_cstm">

	<div class="wrap" style="float:left; width:100%;">

		<style type="text/css">

        .spn_clr{ height:20px;border:1px solid #000;padding-left: 16px;}

        .frm_setting{ border:1px solid #4F8A10; padding-bottom:20px; margin-top:20px;}

        .lbl_setting{ margin-left:5px; padding:0 5px;color:#4F8A10; text-transform:uppercase;}

        .w3_green_border{ padding:15px;background: #DFF2BF;border: 1px solid #4F8A10;}

        .wc_theme .form-table { margin-left:15px;}

		.wc_theme .form-table th, .wc_theme .form-table label { font-size: 12px; font-weight: normal !important;}
		.pp_ipn_cstm .form-table th , .pp_ipn_cstm .form-table td{  padding:5px;}

        </style>

        <div id="icon-options-general" class="icon32"><br />

        </div>

		<h2>Wordpress Stripe IPN Email Settings</h2>

        <div class="main_div">

    	<div class="metabox-holder" style="width:98%; float:left;">

        <?php 

				$options = array (

					  

					   'w3_st_from_name',

					   'w3_st_from_email',

					   'w3_st_subject',			

					   'w3_st_description',

					   'w3_st_email_temp'					 

				   

		        );

		   

			   if($_REQUEST['w3_reset']=='reset'){

				   

					  foreach ( $options as $opt )

					  {

						  delete_option ( 'wc_'.$opt, $_POST[$opt] );

						  $_POST[$opt]='';

						  add_option ( 'wc_'.$opt, $_POST[$opt] );	

					  }

				}

			 

			   if ( count($_POST) > 0 && isset($_POST['wc_settings'])){

					   

					  foreach ( $options as $opt )

					  {

						  delete_option ( 'wc_'.$opt, $_POST[$opt] );

						  add_option ( 'wc_'.$opt, $_POST[$opt] );	

					  }

			   }

			   w3_st_wc_settings();

	    ?>

         </div>        

        <div> 

    </div><!--.wrap-->    

</div><!--.wrapper-->

<?php

}


function settings_pp_ipn(){

?>

<div class="wrapper pp_ipn_cstm">

	<div class="wrap" style="float:left; width:100%;">

		<style type="text/css">

        .spn_clr{ height:20px;border:1px solid #000;padding-left: 16px;}

        .frm_setting{ border:1px solid #4F8A10; padding-bottom:20px; margin-top:20px;}

        .lbl_setting{ margin-left:5px; padding:0 5px;color:#4F8A10; text-transform:uppercase;}

        .w3_green_border{ padding:15px;background: #DFF2BF;border: 1px solid #4F8A10;}

        .wc_theme .form-table { margin-left:15px;}

		.wc_theme .form-table th, .wc_theme .form-table label { font-size: 12px; font-weight: normal !important;}
		.pp_ipn_cstm .form-table th , .pp_ipn_cstm .form-table td{  padding:5px;}

        </style>

        <div id="icon-options-general" class="icon32"><br />

        </div>

		<h2>Wordpress Paypal IPN Email Settings</h2>

        <div class="main_div">

    	<div class="metabox-holder" style="width:98%; float:left;">

        <?php 

				$options = array (

					  

					   'w3_from_name',

					   'w3_from_email',

					   'w3_subject',			

					   'w3_description',

					   'w3_email_temp'					 

				   

		        );

		   

			   if($_REQUEST['w3_reset']=='reset'){

				   

					  foreach ( $options as $opt )

					  {

						  delete_option ( 'wc_'.$opt, $_POST[$opt] );

						  $_POST[$opt]='';

						  add_option ( 'wc_'.$opt, $_POST[$opt] );	

					  }

				}

			 

			   if ( count($_POST) > 0 && isset($_POST['wc_settings'])){

					   

					  foreach ( $options as $opt )

					  {

						  delete_option ( 'wc_'.$opt, $_POST[$opt] );

						  add_option ( 'wc_'.$opt, $_POST[$opt] );	

					  }

			   }

			   w3_wc_settings();

	    ?>

         </div>        

        <div> 

    </div><!--.wrap-->    

</div><!--.wrapper-->

<?php

}

  

function w3_set_label($for,$text){

	 return '<label for="'.$for.'">'.$text.'</label>';

}

 

function w3_set_textbox($name,$value){

	 if(get_option('wc_'.$name)){

		  $wc_value = get_option('wc_'.$name);

	 }else{

		  $wc_value = $value;

	 }

	 return '<input name="'.$name.'" type="text" id="'.$name.'" value="'.$wc_value.'" class="regular-text" />';

}



function w3_set_textarea($name,$value){

	

	 if(get_option('wc_'.$name)){

		  $wc_value = nl2br(get_option('wc_'.$name));

	 }else{

		  $wc_value = $value;

	 }

	 

	 $settings = array(

							'wpautop' => true,

							'media_buttons' => false,

							'tinymce' => array(

												'theme_advanced_buttons1' => 'bold,italic,underline,blockquote,|,undo,redo,|,fullscreen,HTML',

												'theme_advanced_buttons2' => '',

												'theme_advanced_buttons3' => '',

												'theme_advanced_buttons4' => ''

												),

							'quicktags' => true

						);

								 

	 return wp_editor($wc_value, $name, $settings );

	 //return '<textarea name="'.$name.'" id="'.$name.'" rows="5" cols="50" >'.$wc_value.'</textarea>';

}

function w3_st_wc_settings(){

?>

    <fieldset class="frm_setting">

    <legend class="lbl_setting"><strong >General Settings</strong></legend>

    <div class="wc_theme" style="padding-right:25px;">

      <form method="post" action="">

        <table class="form-table">

	   	  <tr valign="top">

            <th scope="row"><?php echo w3_set_label('w3_st_from_name','From Name');?></th>

            <td><?php echo w3_set_textbox('w3_st_from_name','');?> </td>

          </tr>

		  

		   <tr valign="top">

            <th scope="row"><?php echo w3_set_label('w3_st_from_email','From Email');?></th>

            <td><?php echo w3_set_textbox('w3_st_from_email','');?> </td>

          </tr>

		  

		  <tr valign="top">

            <th scope="row"><?php echo w3_set_label('w3_st_subject','Subject');?></th>

            <td><?php echo w3_set_textbox('w3_st_subject',W3_SUBJECT);?> </td>

          </tr>

		       

          <tr valign="top">

            <th scope="row"><?php echo w3_set_label('w3_st_description','Message');?></th>

            <td><?php echo w3_set_textarea('w3_st_description',W3_ST_DESC);?></td>

          </tr>

          <tr valign="top">

            <th scope="row"><?php echo w3_set_label('w3_st_email_temp','Email Template');?></th>

            <td><?php echo w3_set_textarea('w3_st_email_temp',W3_ST_TEMPLATE);?></td>

          </tr>

          

          <tr valign="top">

            <th scope="row"></th>

            <td>     

            <input type="submit" name="Submit" class="button-primary" value="Save Changes" />

            <input type="hidden" name="wc_settings" value="save" style="display:none;" />

           </td>

          </tr>

        </table>

      </form>

    </div>    

   </fieldset>   

<?php
}  

function w3_wc_settings(){

?>

    <fieldset class="frm_setting">

    <legend class="lbl_setting"><strong >General Settings</strong></legend>

    <div class="wc_theme" style="padding-right:25px;">

      <form method="post" action="">

        <table class="form-table">

	   	  <tr valign="top">

            <th scope="row"><?php echo w3_set_label('w3_from_name','From Name');?></th>

            <td><?php echo w3_set_textbox('w3_from_name','');?> </td>

          </tr>

		  

		   <tr valign="top">

            <th scope="row"><?php echo w3_set_label('w3_from_email','From Email');?></th>

            <td><?php echo w3_set_textbox('w3_from_email','');?> </td>

          </tr>

		  

		  <tr valign="top">

            <th scope="row"><?php echo w3_set_label('w3_subject','Subject');?></th>

            <td><?php echo w3_set_textbox('w3_subject',W3_SUBJECT);?> </td>

          </tr>

		       

          <tr valign="top">

            <th scope="row"><?php echo w3_set_label('w3_description','Message');?></th>

            <td><?php echo w3_set_textarea('w3_description',W3_DESC);?></td>

          </tr>

          <tr valign="top">

            <th scope="row"><?php echo w3_set_label('w3_email_temp','Email Template');?></th>

            <td><?php echo w3_set_textarea('w3_email_temp',W3_TEMPLATE);?></td>

          </tr>

          

          <tr valign="top">

            <th scope="row"></th>

            <td>     

            <input type="submit" name="Submit" class="button-primary" value="Save Changes" />

            <input type="hidden" name="wc_settings" value="save" style="display:none;" />

           </td>

          </tr>

        </table>

      </form>

    </div>    

   </fieldset>   

<?php
}


add_action('init','sendEmail_pp_ipn');
function sendEmail_pp_ipn(){
/* check successfully payment and send mail */
	

	if (isset($_REQUEST["txn_id"]) && isset($_REQUEST["txn_type"]) && isset($_REQUEST["payer_id"]) ){	

		$user_fname = '';

		if(isset($_REQUEST['first_name'])){

			$user_fname = $_REQUEST['first_name'];

		}		

		$user_lname = '';

		if(isset($_REQUEST['last_name'])){

			$user_lname = $_REQUEST['last_name'];

		}		

		$payer_email='';

		if(isset($_REQUEST['payer_email'])){

			$payer_email = $_REQUEST['payer_email'];

		}

	  	if($payer_email!=''){ 

		$usermailsubject = get_option('wc_w3_subject');

		$usermailbody = nl2br(get_option('wc_w3_description'));

		
		$from_name = get_option('wc_w3_from_name');

		$from_email = get_option('wc_w3_from_email');
		

		$emailtemplate = nl2br(get_option('wc_w3_email_temp'));

	

		$usermailbody = str_replace('{{firstname}}',$user_fname,$usermailbody);

		$usermailbody = str_replace('{{lastname}}',$user_lname,$usermailbody);

		$emailtemplate = $usermailbody;
	

		$headers[] ='From:"'.$from_name.'" <'.$from_email.'>';

		add_filter( 'wp_mail_content_type', 'w3_set_html_content_type' );
		

		if(wp_mail($payer_email, $usermailsubject, $emailtemplate, $headers))

		{

				//echo __("Mail sent successfully");

			

		}else{
		

				echo __('There was an error sent mail, please try again!','wp-paypal-ipn');

				die;

			

		}

		remove_filter( 'wp_mail_content_type', 'w3_set_html_content_type' );	

	 

	}



	

} //end if

	

}// end sendEmail_pp_ipn

add_action('init','sendEmail_stripe_ipn');
function sendEmail_stripe_ipn(){
		
		if(isset($_POST['stripeToken']) && isset($_POST['wp_stripe_email']) ){
		
						$user_fname ='';
						if(isset($_POST['wp_stripe_name'])){
							$user_fname =  $_POST['wp_stripe_name'];
						}
						
						$user_email =  $_POST['wp_stripe_email'];	
					
						if($user_email!=''){ 

								$usermailsubject = get_option('wc_w3_st_subject');						
								$usermailbody = nl2br(get_option('wc_w3_st_description'));							
								$from_name = get_option('wc_w3_st_from_name');						
								$from_email = get_option('wc_w3_st_from_email');						
						
								$emailtemplate = nl2br(get_option('wc_w3_st_email_temp'));		
						
								$usermailbody = str_replace('{{firstname}}',$user_fname,$usermailbody);						
								$emailtemplate = $usermailbody;
						
								$headers[] ='From:"'.$from_name.'" <'.$from_email.'>';						
								add_filter( 'wp_mail_content_type', 'w3_set_html_content_type' );
								
															
								//	header('Location: '.$_SERVER['REQUEST_URI']);die;			
								if(wp_mail($user_email, $usermailsubject, $emailtemplate, $headers))
								{									
								}else{
										echo __('There was an error sent mail, please try again!','wp-paypal-ipn');
										die;
								}
								remove_filter( 'wp_mail_content_type', 'w3_set_html_content_type' );
							}						
	
	
			
	     }//end if
	
}

function w3_set_html_content_type() {

			return 'text/html';
}
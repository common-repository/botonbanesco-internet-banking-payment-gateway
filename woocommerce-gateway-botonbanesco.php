<?php
/*
Plugin Name: BotonBanesco Internet Banking Payment Gateway.
Plugin URI: http://www.banesco.com
Description: Pay using Internet Banking
Version: 1.0.0
Author: Banesco Banco Universal
Author URI: https://www.banesco.com
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/*
  wp-content\plugins\woocommerce\includes\class-wc-payment-gateways.php
*/
add_action('plugins_loaded', 'woocommerce_botonbanesco_init', 0); 
add_action('wp_enqueue_scripts','botonbanesco_js_init');
add_action( 'admin_enqueue_scripts', 'botonbanesco_js_init' );
add_action('admin_menu', 'consulta_banesco_admin_menu');

function botonbanesco_js_init() {
    wp_enqueue_script( 'botonbanesco-js', plugins_url( 'banesco.js', __FILE__ ));
}



function woocommerce_botonbanesco_init(){ 	 
	if(!class_exists('WC_Payment_Gateway')) return;
	
    /**
	 * botonbanesco Gateway Class
     *
     * @access public
     * @param 
     * @return 
     */	
	class WC_botonbanesco extends WC_Payment_Gateway {

	public $proxy;

		/**
		 * Constructor for the gateway.
		 */
		public function __construct() {
			global $woocommerce;
			if ( ! is_admin() )
				$iso_country_code='VE';
			else {
				$iso_country_code='VE';
				
			   }
			
			
			$icono=plugin_dir_url( __FILE__ ) .'img/logobanesco.jpg';
			$lenguaje=substr(get_bloginfo ( 'language' ), 0, 2);
			
			if ( !@file_get_contents($icono))
				$icono=plugin_dir_url( __FILE__ ) .'img/logobanesco.jpg';
			
			
			$this->load_plugin_textdomain();
			$this->id                 = 'botonbanesco';
			$this->icon               = $icono;
			$this->has_fields         = true;
			$this->method_title       = __( 'Bot&oacute;n BanescoPagos', 'botonbanesco-internet-banking-payment-gateway' );
			$this->method_description = __( 'Permite el pago usando su cuenta en banesco.', 'botonbanesco-internet-banking-payment-gateway' );

			// Load the settings.
			$this->init_form_fields();
			$this->init_settings();
			$this->options_update();
			$this->language 		= get_bloginfo('language');
		
            if(version_compare( WOOCOMMERCE_VERSION, '2.1', '>=')){
				$this->log = new WC_Logger();
			 }else{
				$this->log = $woocommerce->logger();
			}
			// Define user set variables
			$this->title        = 'Bot&oacute;n BanescoPagos' ; //__($this->get_option( 'title' ), 'botonbanesco-internet-banking-payment-gateway' );
			$this->description  = __($this->get_option( 'description' ) , 'botonbanesco-internet-banking-payment-gateway' );
			$this->instructions = __($this->get_option( 'instructions', $this->description ), 'botonbanesco-internet-banking-payment-gateway' );

			// Actions
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_receipt_botonbanesco', array( $this, 'receipt_page' ) );
			
			
            
			// Customer Emails
			add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
			add_action( 'woocommerce_api_' . strtolower('WC_botonbanesco'), array( $this, 'check_botonbanesco_response' ) );
			
			
		}
		
		public function process_admin_options() {
		   
			if($_POST['woocommerce_botonbanesco_BOTONBANESCO_ENV']=='qa'){
	        $url='https://qa-botondepago.banescopagos.com';
			} else {
			$url='https://botondepago.banescopagos.com';
			}
			
		    
			return parent::process_admin_options();
			
		}
		public function validate_URLNOTIFY_field ($key){
			$text  = $this->get_option( $key );
		    $field = $this->get_field_key( $key );
			if ( isset( $_POST[ $field ] ) ) {
			$text = sanitize_text_field($_POST[ $field ]);
				
		   }
		   
		   return $text ;
			
		}

        public function validate_URLSUCCESS_field ($key){
			$text  = $this->get_option( $key );
		    $field = $this->get_field_key( $key );
			if ( isset( $_POST[ $field ] ) ) {
			$text = wp_kses_post($_POST[ $field ]);
				
		   }
		   
		   return $text ;
			
		}
		
		public function validate_URLERROR_field ($key){
			$text  = $this->get_option( $key );
		    $field = $this->get_field_key( $key );
			if ( isset( $_POST[ $field ] ) ) {
			$text = wp_kses_post($_POST[ $field ]);
				
		   }
		   
		   return $text ;
			
		} 

		
		public function validate_BOTONBANESCO_APIKEY_field ($key){
			$text  = $this->get_option( $key );
		    $field = $this->get_field_key( $key );
			
			if ( isset( $_POST[ $field ] ) ) {
			$text = wp_kses_post( trim( stripslashes( $_POST[ $field ] ) ) );
				if (!isset($text) || strlen($text) == 0 )
				{$text ='';
				  WC_Admin_Settings::add_error( __( 'ApiKey must be present', 'botonbanesco-internet-banking-payment-gateway' ) );	   
				}
		   }

		  return $text;
		}
		
		public function validate_BOTONBANESCO_SECRETO_field ($key){
			$text  = $this->get_option( $key );
		    $field = $this->get_field_key( $key );
			
			if ( isset( $_POST[ $field ] ) ) {
			$text = wp_kses_post( trim( stripslashes( $_POST[ $field ] ) ) );
				if (!isset($text) || strlen($text) == 0 )
				{$text ='';
				  WC_Admin_Settings::add_error( __( 'Secret must be present', 'botonbanesco-internet-banking-payment-gateway' ) );	   
				}
		   }

		  return $text;
		}
		
		public function validate_BOTONBANESCO_TASA_DE_CAMBIO_field ($key){
			$text  = $this->get_option( $key );
		    $field = $this->get_field_key( $key );
			
			if ( isset( $_POST[ $field ] ) ) {
			$text = wp_kses_post( trim( stripslashes( $_POST[ $field ] ) ) );
				if (!isset($text) || strlen($text) == 0  || (int)($text) <=0 )
				{$text ='';
				  WC_Admin_Settings::add_error( __( 'Exchange rate must be grater than zero', 'botonbanesco-internet-banking-payment-gateway' ) );	   
				}
		   }

		  return $text;
		}
		
		public function validate_BOTONBANESCO_SERVER2_field ($key){
			$text  = $this->get_option( $key );
		    $field = $this->get_field_key( $key );
			
			if ( isset( $_POST[ $field ] ) ) {
			$text = wp_kses_post( trim( stripslashes( $_POST[ $field ] ) ) );
				if (!isset($text) || strlen($text) == 0 )
				{$text ='';
				  WC_Admin_Settings::add_error( __( 'Server must be present', 'botonbanesco-internet-banking-payment-gateway' ) );	   
				}
		   }

		  return $text;
		}
		
		public function validate_BOTONBANESCO_SERVER_field ($key){
			$text  = $this->get_option( $key );
		    $field = $this->get_field_key( $key );
			
			
			if ( isset( $_POST[ $field ] ) ) {
			$text = wp_kses_post( trim( stripslashes( $_POST[ $field ] ) ) );
			
				if (!isset($text) || strlen($text) == 0 )
				{$text ='';
				  WC_Admin_Settings::add_error( __( 'Server must be present', 'botonbanesco-internet-banking-payment-gateway' ) );	   
				}
		   }

		  return $text;
		}
		
		
		
		
		 public function load_plugin_textdomain() {
			load_plugin_textdomain( 'botonbanesco-internet-banking-payment-gateway', false, dirname( plugin_basename( __FILE__ ) ) . "/languages" );
	    }
		
		public function admin_options(){
			echo '<h3>'.__('Boton de Pagos Banesco', 'botonbanesco-internet-banking-payment-gateway').'</h3>';
			echo '<p>'.__('EndPoint for notification' , 'botonbanesco-internet-banking-payment-gateway') . '&nbsp;<strong style="font-size: 17px;">' .home_url().'/?wc-api=wc_botonbanesco</strong></p>';
			echo '<p>'.__('URL for success redirect' , 'botonbanesco-internet-banking-payment-gateway') . '&nbsp;<b style="font-size: 17px;">' .home_url().'/?wc-api=wc_botonbanesco&a=success</b></p>';
			echo '<p>'.__('URL for fail redirect' , 'botonbanesco-internet-banking-payment-gateway') . '&nbsp;<b style="font-size: 17px;">' .home_url().'/?wc-api=wc_botonbanesco&a=fail</b></p>';
			
			//echo '<p>'.__('Base url for api calls' , 'botonbanesco-internet-banking-payment-gateway') .'&nbsp;<b>'. esc_url(home_url().'/?wc-api=wc_botonbanesco').'</b></p>' ;
			echo '<p>'.__('Log file' ,'botonbanesco-internet-banking-payment-gateway'). '&nbsp;'. wc_get_log_file_path( 'botonbanesco' ). '</p>' ;
			echo '<table class="form-table">';
			// Generate the HTML For the settings form.
			$this->generate_settings_html();
			echo '</table>';
			
			
			
		}
		
		public function check_botonbanesco_response(){
			
			
			
	    	try {
				if ( ! empty( $_REQUEST ) ) {
					
					
					$issueCode=1;
					 
					if(isset($_REQUEST['a']))
					   $state=sanitize_text_field($_REQUEST['a']);
				    else
					   $state='';
				    if(!isset($_REQUEST['dato']) && $state=='') {
						echo "Datos de notificacion invalidos";
						exit; 
					};
					if(isset($_REQUEST['dato']) && $state=='') {
						$datos=sanitize_textarea_field($_REQUEST['dato']);
						$datos= str_replace('\"','"',$datos);
						
						
						$json= json_decode($datos);
						
						
						$estatus = $json->estatus;
						$referencia = $json->referencia;
						$valor5 = $json->valor5;
						$valor4 = $json->valor4;
						$valor1 = $json->valor1;
						$valor2 = $json->valor2;
						$valor3 = $json->valor3;
						$moneda = $json->moneda;
						$banco  = $json->id_banco_origen;
						$firma  = $json->firma;
						$fecha_pago= $json->fecha_pago;
						$ApiKey = $this->get_option('BOTONBANESCO_APIKEY');
						$Secret = $this->get_option('BOTONBANESCO_SECRETO');
						
						$firmar=  $valor1.$valor2.$valor3.$moneda.$valor4.$referencia.$valor5.$banco;
						$firmachk= hash_hmac('sha256', $firmar,$Secret);
						
						$order_id=$valor4;
						
						
						$order= wc_get_order( $order_id );
						if(! (! is_object($order) || is_null($order->id) ))
						{   
							if($firmachk  != $firma)
							{
								$estatus=7;
								echo("Error de Seguridad firma invalida");
								$order->update_status( 'wc-security-issue', sprintf( __( 'BotonBanesco Payment InCompleted', 'botonbanesco-woocommerce') ) );
								$order->add_order_note( 'Problema de seguridad - Error en verificacion de firma' );
								$issueCode = 8; 
								exit; 
							}	
							$status=$order->get_status() ;
							/* validar los datos que se reciben con los calculados , por hacer*/
								
							if( ($status=='pending' || $status=='completed') && $issueCode != 99  && $estatus==1) { 
							/* se recibio la notificacion de pago desde botonbanesco */ 					 
								$order->update_status( 'completed', sprintf( __( 'BotonBanesco Payment Completed', 'botonbanesco-woocommerce') ) );
								$issueCode = 0;
							}
							
							if( ($status=='pending') && $issueCode != 99  && $estatus!=1) { 
							/* se recibio la notificacion de pago desde botonbanesco */ 					 
								$order->update_status( 'failed', sprintf( __( 'BotonBanesco Payment Completed', 'botonbanesco-woocommerce') ) );
								$issueCode = 8;
							}
							
							if($status=='cancelled' ) {  
								$issueCode = 7;
							}
							if($status=='failed' ) {  
								$issueCode = 8;
							}
							if($status=='refunded' || $status=='on-hold' || $status=='processing') {  
								$issueCode = 6;
							}
							else $issueCode = 99; /* Order no exists*/
								
						
						}
						else
							$issueCode = 99; /* Order no exists*/
                    }
					if ($issueCode !=0 )
					{	
					   switch ($state)
						{
							case 'fail': 
									get_header( 'home' );
									echo( __("<div class='woocommerce'><div class='woocommerce-notices-wrapper'><p class='cart-empty woocommerce-info'>Pago no exitoso</p></div></div>", 'botonbanesco-internet-banking-payment-gateway') ); 
									get_sidebar();
									get_footer();
									exit; 
							case 'success': 
									get_header( 'home' );
									echo( __("<div class='woocommerce'><div class='woocommerce-notices-wrapper'><p class='cart-empty woocommerce-info'>Pago exitoso</p></div></div>", 'botonbanesco-internet-banking-payment-gateway') ); 
									get_sidebar();
									get_footer();
									 
									exit;         
						}
						
					}
					header( 'HTTP/1.1 200 OK' );
					
					
					echo esc_html('Notificacion recibida '.$ApiKey);
					$home_page = home_url();
					exit;
					
				} else {
					wp_die( __("BotonBanesco Request Failure", 'botonbanesco-internet-banking-payment-gateway') );
				}
			} catch (Exception $e) {
				    $r= $e->getMessage();
					if($this->get_option('BOTONBANESCO_LOG')==='yes')
					   $this->log->add( 'botonbanesco', 'ERROR CRITICO:' . $r );
				    get_header( 'home' );
					echo( __("<span class='woocommerce-error'>BotonBanesco - Error Critico &nbsp&nbsp&nbsp</sapn>", 'botonbanesco-internet-banking-payment-gateway') ); 
					get_sidebar();
					get_footer();
					exit; 
			  
			}	
		
		}

		public function receipt_page( $order ) {
			echo '<p>' . __( 'Thank you for your order, please pay with BotonBanesco.', 'botonbanesco-internet-banking-payment-gateway' ) . '</p>';
			echo $this->generate_botonbanesco_form( $order );
		}
		
		
		
		/**
		 * Generate the BotonBanesco button link.
		 *
		 * @since 1.0.0
		 */
		public function generate_botonbanesco_form( $order_id ) {
		   
		                            
		   
		   $order         = wc_get_order( $order_id );
		   $return_url=$order ->get_checkout_order_received_url() ;
		   $amount = $order->get_total();
		   $tasa = (float)$this->get_option('BOTONBANESCO_TASA_DE_CAMBIO');
		   if($tasa==0)
             $tasa=1;
		 
		   $amount = $amount * $tasa;
		   $CurrencyCode = $order->get_currency();
		   $iso_code_c = WC()->customer->get_billing_country();
		   
		   $apikey=$this->get_option('BOTONBANESCO_APIKEY');
		   $secreto=$this->get_option('BOTONBANESCO_SECRETO');
		   
		   $valor1= ""; 
		   $valor2=$amount;
		   //$valor3='moneda='.$CurrencyCode.'|urlok='. base64_encode($return_url).'|urlnook='. base64_encode($return_url);
		   $valor3='';
		   $valor4=$order_id;
		   $valor5="Pago de factura";
		   
		   $url=esc_url(plugin_dir_url( __FILE__ ));
		   
		   if($this->get_option('BOTONBANESCO_ENV')=='qa'){
	        $server_url='https://qa-botondepago.banescopagos.com';
			} else {
			$server_url='https://botondepago.banescopagos.com';
			}
		   
		   
		   $firmar= $apikey . $valor1 . $valor2. $valor3 . $valor4;
		   $firma= hash_hmac('sha256', $firmar,$secreto);
		   
		   $icon_online = plugin_dir_url( __FILE__ ) . "/img/logobanesco.jpg";
		   
		         
           $ShopperInformation= '{"ShopperInformation_email": "'.$order->get_billing_email().'" }' ;
	  	   $this->data_to_send = array(
				   'order_id'      => $order_id ,
				 );
			$largo= $this->get_option('BOTONBANESCO_LARGO_BOTON'); 	 
			$ancho= $this->get_option('BOTONBANESCO_ANCHO_BOTON'); 	 	 
				$return = '<script>
				          
						  var boton = Banesco_boton("'.$server_url.'",
							"'.$valor1.'",
							"'.$valor2.'",
							"'.$valor3.'",
							"'.$valor4.'",
							"'.$valor5.'",
							"'.$apikey.'",
							"'.$firma.'",
							1,
							"'.$largo.'",
							"'.$ancho.'"
						   );
						   document.write(boton);
					  </script>
					  
					';
			
			return $return;	
			
		}
		
	  public function getURLContent($url){
		$doc = new DOMDocument;
		$doc->preserveWhiteSpace = FALSE;
		@$doc->loadHTMLFile($url);
		return $doc->saveHTML();
	}
	
	   public function validate($input) {
			// All checkboxes inputs        
			$valid = array();

			//APIKEY
			$valid['APIKEY'] = (isset($input['APIKEY']) && !empty($input['APIKEY'])) ? 1 : 0;
			$valid['SECRETO'] = (isset($input['SECRETO']) && !empty($input['SECRETO'])) ? 1 : 0;
			//$valid['TASA_DE_CAMBIO'] = (isset($input['TASA_DE_CAMBIO']) && !empty($input['TASA_DE_CAMBIO']) && !(int)$input['TASA_DE_CAMBIO']>=1 ) ? 1 : 0;
			//add_settings_error($this->plugin_name,$this->plugin_name.'_error','Incorrect value entered!','error');
			add_settings_error($this->id,$this->id.'_error','Incorrect value entered!','error');

			return false;
		 }
		
		public function options_update() {
			register_setting($this->id, $this->id, array($this, 'validate'));
		 }
		 
		
 
		/**
		 * Initialise Gateway Settings Form Fields.
		 */
		public function init_form_fields() {
             
			
			$this->form_fields = array(
				'enabled' => array(
					'title'   => __( 'Enable/Disable', 'botonbanesco-internet-banking-payment-gateway' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable BotonBanesco Payment', 'botonbanesco-internet-banking-payment-gateway' ),
					'default' => 'yes'
				),
				'BOTONBANESCO_APIKEY' => array(
					'title'       => __( 'api key', 'botonbanesco-internet-banking-payment-gateway' ),
					'type'        => 'text',
					'description' => __( 'Hash from BotonBanesco.', 'botonbanesco-internet-banking-payment-gateway' ),
					'default'     => '',
					'desc_tip'    => true,
				),
				'BOTONBANESCO_SECRETO' => array(
					'title'       => __( 'Secret', 'botonbanesco-internet-banking-payment-gateway' ),
					'type'        => 'text',
					'description' => __( 'Hash from BotonBanesco.', 'botonbanesco-internet-banking-payment-gateway' ),
					'default'     => '',
					'desc_tip'    => true,
				),
				'BOTONBANESCO_LOG' => array(
					'title'   => __( 'Debug log', 'botonbanesco-internet-banking-payment-gateway' ),
					'type'    => 'checkbox',
					'label'   => __( 'Debug Log', 'botonbanesco-internet-banking-payment-gateway' ),
					'default' => 'yes',
					'desc_tip'    => true
				),
				'BOTONBANESCO_ENV' => array(
					'title'   => __( 'Server', 'botonbanesco-internet-banking-payment-gateway' ),
					'type'    => 'select',
					'description'   => __( 'Server to connect', 'botonbanesco-internet-banking-payment-gateway' ),
					'default' => 'qa',
					'options'     => array('qa'=>'Pruebas','pro'=>'Produccion'),
					'desc_tip'    => true,
					'custom_attributes'=> array("onchange"=>"woocommerce_botonbanesco_BOTONBANESCO_ENV_change()")
				),
				'BOTONBANESCO_SERVER' => array(
					'type'    => 'text',
					'default' => 'https://qa-botondepago.banescopagos.com',
					'class'   => '',
					'custom_attributes'=> array("readonly"=>"readonly")
					
				),
				'BOTONBANESCO_LARGO_BOTON' => array(
					'title'       => __( 'Button height', 'botonbanesco-internet-banking-payment-gateway' ),
					'type'        => 'text',
					'description' => __( 'image length of button', 'botonbanesco-internet-banking-payment-gateway' ),
					'default'     => '400', 
					'desc_tip'    => true,
				),
				'BOTONBANESCO_ANCHO_BOTON' => array(
					'title'       => __( 'Button width', 'botonbanesco-internet-banking-payment-gateway' ),
					'type'        => 'text',
					'description' => __( 'image width of button', 'botonbanesco-internet-banking-payment-gateway' ),
					'default'     => '400',
					'desc_tip'    => true,
				),/* Descomentar para mostrar el campo
				    'BOTONBANESCO_TASA_DE_CAMBIO' => array(
					'title'       => __( 'Exchange rate', 'botonbanesco-internet-banking-payment-gateway' ),
					'type'        => 'text',
					'description' => __( 'exchange rate to apply to order total', 'botonbanesco-internet-banking-payment-gateway' ),
					'default'     => '1',
					'desc_tip'    => true,
				),*/
			);
		}

		/**
		 * Output for the order received page.
		 */
		public function thankyou_page() {
			if ( $this->instructions )
				echo wpautop( wptexturize( $this->instructions ) );
			
		}

		/**
		 * Add content to the WC emails.
		 *
		 * @access public
		 * @param WC_Order $order
		 * @param bool $sent_to_admin
		 * @param bool $plain_text
		 */
		public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
			if ( $this->instructions && ! $sent_to_admin && 'botonbanesco' === $order->payment_method && $order->has_status( 'on-hold' ) ) {
				echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
			}
		}

		public function process_payment( $order_id ) {
			$order = wc_get_order( $order_id );
			$order->payment_complete();
			WC()->cart->empty_cart(); 
			$order->update_status( 'pending', sprintf( __( 'Awaiting Payment from user.', 'botonbanesco-woocommerce') ) );
			return array(
				'result' 	 => 'success',
				'redirect'	 => $order->get_checkout_payment_url( true )
			);
		} 
		
		public function is_available() {
			global $woocommerce;
			
			if ( 
			     (empty($this->get_option('BOTONBANESCO_APIKEY'))  ||  empty($this->get_option('BOTONBANESCO_SECRETO'))  ) 
			   )
			  return false;
		    return true;
		}
		
		
		public function payment_fields() {
			echo wpautop( wp_kses_post( "Permite hacer el pago usando una cuenta Banesco" ) );
			echo "<small><i>El boton de pago se mostrara en la siguiente pagina</i></small>";
		}			
		
	}
	


	function woocommerce_add_botonbanesco_class( $methods ) {
		$methods[] = 'WC_botonbanesco';
		return $methods;
	}
	   
    add_filter( 'woocommerce_payment_gateways', 'woocommerce_add_botonbanesco_class' );   
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'spyr_botonbanesco_action_links' );
	
   function spyr_botonbanesco_action_links( $links ) {
	$plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_botonbanesco' ) . '">' . __( 'Settings', 'botonbanesco-internet-banking-payment-gateway' ) . '</a>',
	);
	
	// Merge our new link with the default ones
	return array_merge( $plugin_links, $links );	
  }
  
  
    function register_security_issue_order_status() {
			register_post_status( 'wc-security-issue', array(
				'label'                     => __( 'Security Issue', 'botonbanesco-internet-banking-payment-gateway' ),
				'public'                    => true,
				'show_in_admin_status_list' => true,
				'show_in_admin_all_list'    => true,
				'exclude_from_search'       => false,
				'label_count'               => _n_noop( 'Security Issue <span class="count">(%s)</span>', 'Security Issue <span class="count">(%s)</span>' )
			) );
		}
	add_action( 'init', 'register_security_issue_order_status' );
	function add_security_issue_to_order_statuses( $order_statuses ) {
			$new_order_statuses = array();
			foreach ( $order_statuses as $key => $status ) {
				$new_order_statuses[ $key ] = $status;
				if ( 'wc-processing' === $key ) {
					$new_order_statuses['wc-security-issue'] = __( 'Security Issue', 'botonbanesco-internet-banking-payment-gateway' );
				}
			}
			return $new_order_statuses;
		}
	add_filter( 'wc_order_statuses', 'add_security_issue_to_order_statuses' );	
   
}


/*  Dibuja una tabla con el formulario 
*   para iniciar la consulta al api de 
*   banesco
*/
function display_consulta_banesco_page() {
	$numero_pedido = sanitize_text_field(isset($_POST['numero_pedido']) ?  $_POST['numero_pedido'] :''  );
	echo '<div id="consulta-banesco-div" style="width: 50%; margin-left: 15px;" >';
	echo '<form method="POST" >';
	echo '<table class="wp-list-table widefat fixed striped table-view-list posts" border="0" style="width: auto;padding:8px;display:block;border-top: 1px solid black;border-bottom:1px solid black;border-left: 1px solid black;border-right: 1px solid black;">'; 
			echo '<caption class="manage-column">Consulta del pago en banesco </caption>'; 
			echo '<tr><th>Numero de pedido:</th>'; 
			echo '<td><input class="input-text regular-input" type="text" name="numero_pedido" id="numero_pedido"></td>';
			echo '<td><span id="estatus_pedido">&nbsp;</span></td></tr><tr>';
			echo '<td><input class="button" type="submit" id="revisa_pedido"  value="Consultar Pago"></td>';
			echo '</tr>';
			echo '</table>';
			echo '<input   type="hidden" name="action" id="action" value="consulta_banesco"/>';
	echo '</form>';
	if(isset($numero_pedido) &&  $numero_pedido !='') {
		$actualizado='';
		$json=post_consulta_banesco($numero_pedido);
		if(is_object($json)) {
		
		if($json->detalle->estatus == "1")
			$estatus="Pago exitoso";
		else
			$estatus="Pago no exitoso";
		
		$order= wc_get_order( $numero_pedido );
		
        if(is_object($order) && !is_null($order->id) )
		{
			$order_status=$order->get_status() ;
			if( ($order_status=='pending')  && $json->detalle->estatus==1) { 
			/* se recibio la notificacion de pago desde botonbanesco */ 					 
				$order->update_status( 'completed', sprintf( __( 'BotonBanesco Payment Completed', 'botonbanesco-woocommerce') ) );
				$actualizado='S';
			}
	    } 
		
		echo '<table class="wp-list-table widefat fixed striped table-view-list posts" border="0" style="width: auto;padding:8px;display:block;border-top: 1px solid black;border-bottom:1px solid black;border-left: 1px solid black;border-right: 1px solid black;" >';  	
		echo '<caption>Datos del Pago para el pedido <b>'. esc_html($numero_pedido).'</b></caption>'; 
		if ($actualizado <> '')
		{
		   echo '<tr><td colspan="2" class="notice notice-success" >Pedido actualizado </td></tr>'; 	
		}
		echo '<tr><th>Estatus</th>'; 
		echo '<td>'.esc_html($estatus).'</td>'; 
		echo '</tr>';
	    echo '<tr><th>Mensaje</th>'; 
		echo '<td>'.esc_html($json->mensaje).'</td>'; 
		echo '</tr>';
		echo '<tr><th>Fecha de Pago</th>'; 
		echo '<td>'.esc_html($json->detalle->fecha_pago).'</td>'; 
		echo '</tr>';
		echo '<tr><th>Referencia Banesco</th>'; 
		echo '<td>'.esc_html($json->detalle->nro_referencia_banco).'</td>'; 
		echo '</tr>';
		echo '<tr><th>Monto</th>'; 
		echo '<td>'.esc_html($json->detalle->valor2.' '.$json->detalle->moneda).'</td>'; 
		echo '</tr>';
		
		echo '</table>';
		}
	} else echo "";
	echo "</div>";
}

/*  Crea la opcion de Consulta Banesco  
*   en el menu del administrador de WP
*   
*/
function consulta_banesco_admin_menu() {
  add_menu_page(
        'Consulta Banesco',// page title
        'Consulta Banesco',// menu title
        'manage_options',// capability
        'consulta_banesco',// menu slug
        'display_consulta_banesco_page', // callback function
		'dashicons-search'
    );
}

/*  Recibe los valores del formulario
*   e invoca al api de banesco
*   
*/
function post_consulta_banesco($pedido){
   $json=null;	
   if(!class_exists('WC_botonbanesco')) return;
   
   try {
	   
	   $boton= new	WC_botonbanesco();
	   $apikey=$boton->get_option('BOTONBANESCO_APIKEY');
	   $secreto=$boton->get_option('BOTONBANESCO_SECRETO');
	   $env=$boton->get_option('BOTONBANESCO_ENV');
	   
	   
	   if ((empty($apikey)  ||  empty( $secreto)  )) {
		   return ;
	   }
	   if($env=='qa') {
		 $url ="https://qa-botondepago.banescopagos.com:8443/GtwBotonPago/BtnPago/conciliacion/readPay/leerPago";
	   } else {
		$url ="https://botondepago.banescopagos.com:8443/GtwBotonPago/BtnPago/conciliacion/readPay/leerPago";   
	   }
	   
	   $firmar= $apikey.$pedido;
	   $firma= hash_hmac ('sha256', $firmar, $secreto);
	   $datos= array("nroComprobante" => $pedido, "apiKey" => $apikey, "firma" => $firma);
	   $json= json_encode($datos);
	   $data= base64_encode($json);
	   
	   $args = array(
			'body'        => $data,
			'blocking'    => true,
			'headers'     => array('Content-Type'=>'text/plain'),
		);
	   $res = wp_remote_post($url,$args);
	   $body= wp_remote_retrieve_body($res);
	   $json= json_decode($body);
	   
	   
	
		
			
   }
   catch(Exception $e) {
	    $r= $e->getMessage();
		if($this->get_option('BOTONBANESCO_LOG')==='yes')
		   $this->log->add( 'botonbanesco', 'ERROR CRITICO:' . $r );
   }
   return $json;   
	   
}

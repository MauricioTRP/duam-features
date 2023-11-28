<?php
/**
 * This class handles custom login, and registers via modal form
 * 
 * @package duam-features
 */
namespace DUAM_FEATURES\Inc;
use DUAM_FEATURES\Inc\Traits\Singleton;
use \Exception;
use \WP_Error;


class Duam_Custom_Forms_Handler {
	use Singleton;

    protected function __construct() {
        $this->setup_hooks();
    }

    protected function setup_hooks() {
        add_action( 'wp_loaded', [ $this, 'duam_process_login' ], 20 );
		add_action( 'wp_loaded', [ $this, 'duam_process_registration' ], 20 );
    }


    /**
     * Process the login form.
	 *
     * @throws Exception On login error.
	 */
	public static function duam_process_login() {
        
        static $valid_nonce = null;
        
		if ( null === $valid_nonce ) {
			$nonce_value = wc_get_var( $_REQUEST['woocommerce-login-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.
            
			$valid_nonce = wp_verify_nonce( $nonce_value, 'woocommerce-login' );
		}
        
		if ( isset( $_POST['duam-login'], $_POST['username'], $_POST['password'] ) && $valid_nonce ) {
            
            try {
                $creds = array(
					'user_login'    => trim( wp_unslash( $_POST['username'] ) ), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
					'user_password' => $_POST['password'], // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
					'remember'      => isset( $_POST['rememberme'] ), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				);

				$validation_error = new WP_Error();
				$validation_error = apply_filters( 'woocommerce_process_login_errors', $validation_error, $creds['user_login'], $creds['user_password'] );
                
				if ( $validation_error->get_error_code() ) {
                    throw new Exception( '<strong>' . __( 'Error:', 'woocommerce' ) . '</strong> ' . $validation_error->get_error_message() );
				}
                
				if ( empty( $creds['user_login'] ) ) {
                    throw new Exception( '<strong>' . __( 'Error:', 'woocommerce' ) . '</strong> ' . __( 'Username is required.', 'woocommerce' ) );
				}
                
				// On multisite, ensure user exists on current site, if not add them before allowing login.
				if ( is_multisite() ) {
                    $user_data = get_user_by( is_email( $creds['user_login'] ) ? 'email' : 'login', $creds['user_login'] );
                    
					if ( $user_data && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
                        add_user_to_blog( get_current_blog_id(), $user_data->ID, 'customer' );
					}
				}
                
				// Peform the login.
				$user = wp_signon( apply_filters( 'woocommerce_login_credentials', $creds ), is_ssl() );

				if ( is_wp_error( $user ) ) {
                    throw new Exception( $user->get_error_message() );
				} else {
					// Get current urls, and redirects
					$referer = $_POST[ '_wp_http_referer' ];
					$myaccount_url = wc_get_page_permalink( 'myaccount' );
					$checkout_url = wc_get_checkout_url();

                    if (  strpos( $myaccount_url, $referer ) === false || strpos( $checkout_url, $referer) === false  ) {
                        $redirect = wc_get_checkout_url();
					}
                    
					$redirect = remove_query_arg( array( 'wc_error', 'password-reset' ), $redirect );
                    
					wc_add_notice( 'Bienvenido/a ' . $user->display_name . ', ahora puedes seguir con tu matrícula' , 'success' );
					wp_redirect( wp_validate_redirect( apply_filters( 'woocommerce_login_redirect', $redirect, $user ), wc_get_checkout_url() ) ); // phpcs:ignore
					exit;
				}
			} catch ( Exception $e ) {
                wc_add_notice( apply_filters( 'login_errors', $e->getMessage() ), 'error' );
				do_action( 'woocommerce_login_failed' );
			}
		}
	}
    
	/**
	 * Process the registration form.
	 *
     * @throws Exception On registration error.
	 */
    public static function duam_process_registration() {
		$nonce_value = isset( $_POST['_wpnonce'] ) ? wp_unslash( $_POST['_wpnonce'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$nonce_value = isset( $_POST['woocommerce-register-nonce'] ) ? wp_unslash( $_POST['woocommerce-register-nonce'] ) : $nonce_value; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( isset( $_POST['duam-register'], $_POST['email'] ) && wp_verify_nonce( $nonce_value, 'woocommerce-register' ) ) {
			
			$username =  isset( $_POST['username'] ) ? wp_unslash( $_POST['username'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$password =  isset( $_POST['password'] ) ? $_POST['password'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			$email    = wp_unslash( $_POST['email'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			try {
				$validation_error  = new WP_Error();
				$validation_error  = apply_filters( 'woocommerce_process_registration_errors', $validation_error, $username, $password, $email );
				$validation_errors = $validation_error->get_error_messages();
                
				if ( 1 === count( $validation_errors ) ) {
					throw new Exception( $validation_error->get_error_message() );
				} elseif ( $validation_errors ) {
					foreach ( $validation_errors as $message ) {
						wc_add_notice( '<strong>' . __( 'Error:', 'woocommerce' ) . '</strong> ' . $message, 'error' );
					}
					throw new Exception();
				}
                				
				$new_customer = Duam_User_Functions::duam_create_new_customer( sanitize_email( $email ), wc_clean( $username ), $password );
				
				if ( is_wp_error( $new_customer ) ) {
                    throw new Exception( $new_customer->get_error_message() );
				}

				if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) ) {
                    wc_add_notice( __( 'Your account was created successfully and a password has been sent to your email address.', 'woocommerce' ) );
				} else {
                    wc_add_notice( __( 'Your account was created successfully. Your login details have been sent to your email address.', 'woocommerce' ) );
				}
                
				// Only redirect after a forced login - otherwise output a success notice.
				if ( apply_filters( 'woocommerce_registration_auth_new_customer', true, $new_customer ) ) {
                    wc_set_customer_auth_cookie( $new_customer );
                    
					$redirect = wc_get_checkout_url();
                    
					wp_redirect( wp_validate_redirect( apply_filters( 'woocommerce_registration_redirect', $redirect ), wc_get_checkout_url() ) ); //phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
					wc_add_notice( 'Bienvenido/a ' . $new_customer->display_name . ', ahora puedes seguir con tu matrícula' , 'success' );
					wc_add_notice( __( 'Your account was created successfully. Your login details have been sent to your email address.', 'woocommerce' ) );
					exit;
				}
			} catch ( Exception $e ) {
                if ( $e->getMessage() ) {
                    wc_add_notice( '<strong>' . __( 'Error:', 'woocommerce' ) . '</strong> ' . $e->getMessage(), 'error' );
				}
			}
		}
	}
}
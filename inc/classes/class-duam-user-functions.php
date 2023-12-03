<?php
/**
 * This class handle user functions on DUAM Commerce
 */
namespace DUAM_FEATURES\Inc;
use DUAM_FEATURES\Inc\Traits\Singleton;
use \WP_Error;

class Duam_User_Functions {
    use Singleton;

    private function __construct() {
        $this->setup_class();
    }

    private function setup_class() {
    }

    public static function duam_create_new_customer( $email, $username = '', $password = '', $args = array() ) {
        if ( empty( $email ) || ! is_email( $email ) ) {
            return new WP_Error( 'registration-error-invalid-email', __( 'Please provide a valid email address.', 'woocommerce' ) );
        }

        if ( email_exists( $email ) ) {
            return new WP_Error( 'registration-error-email-exists', apply_filters( 'woocommerce_registration_error_email_exists', __( 'An account is already registered with your email address. <a href="#" class="showlogin">Please log in.</a>', 'woocommerce' ), $email ) );
        }

        if ( 'yes' === get_option( 'woocommerce_registration_generate_username', 'yes' ) && empty( $username ) ) {
            $username = self::duam_generate_username( $email, $args );
        }

        $username = sanitize_user( $username );

        if ( empty( $username ) || ! validate_username( $username ) ) {
            return new WP_Error( 'registration-error-invalid-username', __( 'Please enter a valid account username.', 'woocommerce' ) );
		}
        
		if ( username_exists( $username ) ) {
            return new WP_Error( 'registration-error-username-exists', __( 'An account is already registered with that username. Please choose another.', 'woocommerce' ) );
		}
        
		// Handle password creation.
		$password_generated = false;
		if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && empty( $password ) ) {
			$password           = wp_generate_password();
			$password_generated = true;
		}

		if ( empty( $password ) ) {
			return new WP_Error( 'registration-error-missing-password', __( 'Please enter an account password.', 'woocommerce' ) );
		}

		// Use WP_Error to handle registration errors.
		$errors = new WP_Error();
    
		do_action( 'woocommerce_register_post', $username, $email, $errors );

		$errors = apply_filters( 'woocommerce_registration_errors', $errors, $username, $email );

		if ( $errors->get_error_code() ) {
            return $errors;
		}

		$new_customer_data = apply_filters(
			'woocommerce_new_customer_data',
			array_merge(
				$args,
				array(
					'user_login' => $username,
					'user_pass'  => $password,
					'user_email' => $email,
					'role'       => 'customer',
				)
			)
		);

		$customer_id = wp_insert_user( $new_customer_data );

        if ( is_wp_error( $customer_id ) ) {
            return $customer_id;
        }

		do_action( 'woocommerce_created_customer', $customer_id, $new_customer_data, $password_generated );

		return $customer_id;
    }

    public static function duam_generate_username( $email, $new_users_args = array(), $suffix = '' ) {
        // Generate username from the email address.
        $username_parts = array();

        if ( isset( $new_users_args['first_name'] ) ) {
            $username_parts[] = sanitize_user( $new_users_args['first_name'], true );
        }

        if ( isset( $new_users_args['last_name'] ) ) {
            $username_parts[] = sanitize_user( $new_users_args['last_name'], true );
        }

        // remove empty parts
        $username_parts = array_filter( $username_parts );

        // fallback to email, if there are no parts provided
        if( empty( $username_parts ) ) {
            $email_parts    = explode( '@', $email );
            $email_username       = strtolower( $email_parts[0] );
            
            // exclude common prefixes.
            if ( in_array(
                $email_username,
                array( 'admin', 'webmaster', 'info', 'support', 'billing', 'accounts', 'info', 'hello' ),
                true
                ) ) {

                // Get domain part.
                $email_username = $email_parts[1];
            }
                
            $username_parts[] = sanitize_user( $email_username, true );
        }

        $username = strtolower( implode( '.' , $username_parts ) );

        if ( $suffix ) {
            $username .= $suffix;
        }

        /**
         * filter list of blocked usernames.
         * 
         * @since 3.7.0
         * @param array $usernames Array of blocked usernames
         */
        $illegal_logins = (array) apply_filters( 'illegal_user_logins', array() );

        // Stop ilegal logins and generate a new random username.
        if ( in_array( strtolower( $username ), array_map( 'strtolower', $illegal_logins ), true ) ) {
            $new_args = array();
    
            /**
             * Filter generated customer username.
             *
             * @since 3.7.0
             * @param string $username      Generated username.
             * @param string $email         New customer email address.
             * @param array  $new_user_args Array of new user args, maybe including first and last names.
             * @param string $suffix        Append string to username to make it unique.
             */
            $new_args['first_name'] = apply_filters(
                'woocommerce_generated_customer_username',
                'woo_user_' . zeroise( wp_rand( 0, 9999 ), 4 ),
                $email,
                $new_user_args,
                $suffix
            );
    
            return duam_generate_username( $email, $new_args, $suffix );
        }

        /**
         * Filter new customer username.
         *
         * @since 3.7.0
         * @param string $username      Customer username.
         * @param string $email         New customer email address.
         * @param array  $new_user_args Array of new user args, maybe including first and last names.
         * @param string $suffix        Append string to username to make it unique.
         */
        return apply_filters( 'duam_generate_username', $username, $email, $new_user_args, $suffix );
    }
}

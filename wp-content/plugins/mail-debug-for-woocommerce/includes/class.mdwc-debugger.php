<?php

if ( !class_exists( 'MDWC_Debugger' ) ) {
    class MDWC_Debugger {
        private static $_instance;

        /** @var WC_Email|null */
        private $_current_email;

        /** @var int */
        private $_current_debug_id;

        public static function get_instance() {
            return !is_null( self::$_instance ) ? self::$_instance : self::$_instance = new self();
        }

        private function __construct() {
            if ( mdwc_is_debug_enabled() ) {
                add_filter( 'wp_mail', array( $this, 'set_all_emails_to' ), 9, 1 );
                add_filter( 'wp_mail', array( $this, 'set_all_emails_to' ), 999, 1 );
                add_filter( 'wp_mail', array( $this, 'wp_mail' ), 10, 1 );
                add_action( 'woocommerce_email_header', array( $this, 'set_current_email' ), 10, 2 );
                add_action( 'phpmailer_init', array( $this, 'additional_info' ), 999 );
            }
        }

        /**
         * Set all emails to
         *
         * @param array $args
         * @return array
         * @since 1.0.2
         */
        public function set_all_emails_to( $args = array() ) {
            $all_emails_to = get_option( 'mdwc_all_emails_to', '' );
            if ( $all_emails_to ) {
                $args[ 'to' ] = $all_emails_to;
            }

            return $args;
        }

        public function set_current_email( $heading, $email = false ) {
            if ( $email ) {
                $this->_current_email = $email;
            }
        }

        public function debug_email( $args = array() ) {
            $defaults = array(
                'to'             => '',
                'subject'        => '',
                'message'        => '',
                'headers'        => '',
                'attachments'    => array(),
                'email_id'       => false,
                'email_title'    => '',
                'email_object'   => false,
                'customer_email' => ''
            );
            $args     = wp_parse_args( $args, $defaults );

            if ( $this->_current_email && $this->_current_email instanceof WC_Email ) {
                $args[ 'email_id' ]    = $this->_current_email->id;
                $args[ 'email_title' ] = $this->_current_email->get_title();
                if ( isset( $this->_current_email->object ) && $this->_current_email->object && is_object( $this->_current_email->object ) ) {
	                $object      = $this->_current_email->object;
	                $object_info = get_class( $object );
	                $slash_pos   = strrpos( $object_info, '\\' );
	                if ( $slash_pos !== false ) {
		                $object_info = substr( $object_info, $slash_pos + 1 );
	                }

                    if ( is_callable( array( $object, 'get_id' ) ) ) {
                        $object_info .= ' #' . $object->get_id();
                    }
                    $args[ 'email_object' ] = $object_info;
                }

                $args[ 'customer_email' ] = !!$this->_current_email->is_customer_email() ? 'yes' : 'no';
            }

            $this->_current_debug_id = wp_insert_post( array(
                                                           'post_type'   => 'mail-debug',
                                                           'post_status' => 'publish',
                                                           'meta_input'  => $args
                                                       ) );

            $this->_current_email = false;
        }

        public function wp_mail( $args = array() ) {
            $this->debug_email( $args );
            return $args;
        }

        /**
         * @param PHPMailer $mailer
         */
        public function additional_info( $mailer ) {
            if ( $this->_current_debug_id ) {

                if ( $mailer->Ical ) {
                    update_post_meta( $this->_current_debug_id, 'ical', $mailer->Ical );
                }

                if ( $mailer->AltBody ) {
                    update_post_meta( $this->_current_debug_id, 'alt_body', $mailer->AltBody );
                }

                $this->_current_debug_id = false;
            }
        }
    }
}
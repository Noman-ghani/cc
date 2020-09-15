<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WC_Product_Woosb' ) && class_exists( 'WC_Product' ) ) {
	class WC_Product_Woosb extends WC_Product {
		public function __construct( $product = 0 ) {
			$this->supports[] = 'ajax_add_to_cart';
			parent::__construct( $product );
		}

		public function get_type() {
			return 'woosb';
		}

		public function add_to_cart_url() {
			$product_id = $this->id;

			if ( $this->is_purchasable() && $this->is_in_stock() && ! $this->has_variables() && ! $this->is_optional() ) {
				$url = remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $product_id ) );
			} else {
				$url = get_permalink( $product_id );
			}

			return apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );
		}

		public function add_to_cart_text() {
			if ( $this->is_purchasable() && $this->is_in_stock() ) {
				if ( ! $this->has_variables() && ! $this->is_optional() ) {
					$text = get_option( '_woosb_archive_button_add' );

					if ( empty( $text ) ) {
						$text = esc_html__( 'Add to cart', 'woo-product-bundle' );
					}
				} else {
					$text = get_option( '_woosb_archive_button_select' );

					if ( empty( $text ) ) {
						$text = esc_html__( 'Select options', 'woo-product-bundle' );
					}
				}
			} else {
				$text = get_option( '_woosb_archive_button_read' );

				if ( empty( $text ) ) {
					$text = esc_html__( 'Read more', 'woo-product-bundle' );
				}
			}

			$text = apply_filters( 'woocommerce_product_add_to_cart_text', $text, $this );

			return apply_filters( 'woosb_product_add_to_cart_text', $text, $this );
		}

		public function single_add_to_cart_text() {
			$text = get_option( '_woosb_single_button_add' );

			if ( empty( $text ) ) {
				$text = esc_html__( 'Add to cart', 'woo-product-bundle' );
			}

			$text = apply_filters( 'woocommerce_product_single_add_to_cart_text', $text, $this );

			return apply_filters( 'woosb_product_single_add_to_cart_text', $text, $this );
		}

		public function is_on_sale( $context = 'view' ) {
			if ( ! $this->is_fixed_price() && ( $this->get_discount_amount() || $this->get_discount() ) ) {
				return true;
			}

			return parent::is_on_sale( $context );
		}

		public function get_sale_price( $context = 'view' ) {
			if ( ! $this->is_fixed_price() ) {
				$discount_amount     = $this->get_discount_amount();
				$discount_percentage = $this->get_discount();
				$has_discount        = $discount_amount || $discount_percentage;

				if ( $discount_amount || $discount_percentage ) {
					$sale_price = 0;

					if ( $woosb_items = $this->get_items() ) {
						foreach ( $woosb_items as $woosb_item ) {
							$woosb_product = wc_get_product( $woosb_item['id'] );

							if ( ! $woosb_product || $woosb_product->is_type( 'woosb' ) ) {
								continue;
							}

							$woosb_product_price = WPCleverWoosb_Helper::woosb_get_price( $woosb_product, 'min', $has_discount ) * $woosb_item['qty'];

							if ( $discount_percentage ) {
								// if haven't discount_amount, apply discount percentage
								$sale_price += round( $woosb_product_price * ( 100 - $discount_percentage ) / 100, wc_get_price_decimals() );
							} else {
								$sale_price += $woosb_product_price;
							}
						}
					}

					if ( $discount_amount ) {
						return $sale_price - $discount_amount;
					}

					return $sale_price;
				}
			}

			return parent::get_sale_price( $context );
		}

		public function get_manage_stock( $context = 'view' ) {
			$exclude_unpurchasable = get_option( '_woosb_exclude_unpurchasable', 'no' );
			$parent_manage         = parent::get_manage_stock( $context );

			if ( ( $woosb_items = $this->get_items() ) && ! $this->is_optional() && ( $exclude_unpurchasable !== 'yes' ) ) {
				foreach ( $woosb_items as $woosb_item ) {
					$woosb_product = wc_get_product( $woosb_item['id'] );

					if ( ! $woosb_product || $woosb_product->is_type( 'woosb' ) ) {
						continue;
					}

					if ( $woosb_product->get_manage_stock( $context ) === true ) {
						return true;
					}
				}

				if ( $this->is_manage_stock() ) {
					return $parent_manage;
				}

				return false;
			}

			return $parent_manage;
		}

		public function get_stock_status( $context = 'view' ) {
			$exclude_unpurchasable = get_option( '_woosb_exclude_unpurchasable', 'no' );
			$parent_status         = parent::get_stock_status( $context );

			if ( ( $woosb_items = $this->get_items() ) && ! $this->is_optional() && ( $exclude_unpurchasable !== 'yes' ) ) {
				$stock_status = 'instock';

				foreach ( $woosb_items as $woosb_item ) {
					$woosb_product_id = $woosb_item['id'];
					$woosb_product    = wc_get_product( $woosb_product_id );

					if ( ! $woosb_product || $woosb_product->is_type( 'woosb' ) ) {
						continue;
					}

					$woosb_product_qty     = $woosb_item['qty'];
					$woosb_product_qty_min = absint( get_post_meta( $woosb_product_id, 'woosb_limit_each_min', true ) ?: 0 );
					$woosb_product_qty_max = absint( get_post_meta( $woosb_product_id, 'woosb_limit_each_max', true ) ?: 1000 );

					if ( $woosb_product_qty < $woosb_product_qty_min ) {
						$woosb_product_qty = $woosb_product_qty_min;
					}

					if ( ( $woosb_product_qty_max > $woosb_product_qty_min ) && ( $woosb_product_qty > $woosb_product_qty_max ) ) {
						$woosb_product_qty = $woosb_product_qty_max;
					}

					if ( ( $woosb_product->get_stock_status( $context ) === 'outofstock' ) || ( ! $woosb_product->has_enough_stock( $woosb_product_qty ) ) ) {
						return 'outofstock';
					}

					if ( $woosb_product->get_stock_status( $context ) === 'onbackorder' ) {
						$stock_status = 'onbackorder';
					}
				}

				if ( $this->is_manage_stock() ) {
					if ( $parent_status === 'instock' ) {
						return $stock_status;
					} else {
						return $parent_status;
					}
				}

				return $stock_status;
			}

			return $parent_status;
		}

		public function get_stock_quantity( $context = 'view' ) {
			$exclude_unpurchasable = get_option( '_woosb_exclude_unpurchasable', 'no' );
			$parent_quantity       = parent::get_stock_quantity( $context );

			if ( ( $woosb_items = $this->get_items() ) && ! $this->is_optional() && ( $exclude_unpurchasable !== 'yes' ) ) {
				$available_qty = array();

				foreach ( $woosb_items as $woosb_item ) {
					$woosb_product = wc_get_product( $woosb_item['id'] );

					if ( ! $woosb_product || $woosb_product->is_type( 'woosb' ) || ( $woosb_product->get_stock_quantity() === null ) ) {
						continue;
					}

					if ( $woosb_item['qty'] > 0 ) {
						$available_qty[] = floor( $woosb_product->get_stock_quantity() / $woosb_item['qty'] );
					}
				}

				if ( count( $available_qty ) > 0 ) {
					sort( $available_qty );

					if ( $this->is_manage_stock() && ( $parent_quantity < $available_qty[0] ) ) {
						return $parent_quantity;
					}

					return $available_qty[0];
				}
			}

			return $parent_quantity;
		}

		public function get_backorders( $context = 'view' ) {
			$exclude_unpurchasable = get_option( '_woosb_exclude_unpurchasable', 'no' );
			$parent_backorders     = parent::get_backorders( $context );

			if ( ( $woosb_items = $this->get_items() ) && ! $this->is_optional() && ( $exclude_unpurchasable !== 'yes' ) ) {
				$backorders = 'yes';

				foreach ( $woosb_items as $woosb_item ) {
					$woosb_product = wc_get_product( $woosb_item['id'] );

					if ( ! $woosb_product || $woosb_product->is_type( 'woosb' ) || ! $woosb_product->get_manage_stock() ) {
						continue;
					}

					if ( $woosb_product->get_backorders( $context ) === 'no' ) {
						return 'no';
					}

					if ( $woosb_product->get_backorders( $context ) === 'notify' ) {
						$backorders = 'notify';
					}
				}

				if ( $this->is_manage_stock() ) {
					if ( $backorders === 'yes' ) {
						return $parent_backorders;
					} else {
						return 'notify';
					}
				}

				return $backorders;
			}

			return $parent_backorders;
		}

		public function get_sold_individually( $context = 'view' ) {
			$exclude_unpurchasable = get_option( '_woosb_exclude_unpurchasable', 'no' );

			if ( ( $woosb_items = $this->get_items() ) && ! $this->is_optional() && ( $exclude_unpurchasable !== 'yes' ) ) {
				foreach ( $woosb_items as $woosb_item ) {
					$woosb_product = wc_get_product( $woosb_item['id'] );

					if ( ! $woosb_product || $woosb_product->is_type( 'woosb' ) ) {
						continue;
					}

					if ( $woosb_product->is_sold_individually() ) {
						return true;
					}
				}
			}

			return parent::get_sold_individually( $context );
		}

		// extra functions

		public function has_variables() {
			if ( $woosb_items = $this->get_items() ) {
				foreach ( $woosb_items as $woosb_item ) {
					$woosb_product = wc_get_product( $woosb_item['id'] );

					if ( $woosb_product && $woosb_product->is_type( 'variable' ) ) {
						return true;
					}
				}
			}

			return false;
		}

		public function is_optional() {
			$product_id = $this->id;

			return get_post_meta( $product_id, 'woosb_optional_products', true ) === 'on';
		}

		public function is_manage_stock() {
			$product_id = $this->id;

			return get_post_meta( $product_id, 'woosb_manage_stock', true ) === 'on';
		}

		public function is_fixed_price() {
			$product_id = $this->id;

			return get_post_meta( $product_id, 'woosb_disable_auto_price', true ) === 'on';
		}

		public function get_discount_amount() {
			$product_id      = $this->id;
			$discount_amount = 0;

			// discount amount
			if ( ! $this->is_fixed_price() && ( $woosb_discount_amount = get_post_meta( $product_id, 'woosb_discount_amount', true ) ) ) {
				$discount_amount = (float) $woosb_discount_amount;
			}

			return $discount_amount;
		}

		public function get_discount() {
			$product_id          = $this->id;
			$discount_percentage = 0;

			// discount percentage
			if ( ! $this->is_fixed_price() && ! $this->get_discount_amount() && ( $woosb_discount_percentage = get_post_meta( $product_id, 'woosb_discount', true ) ) && is_numeric( $woosb_discount_percentage ) && ( (float) $woosb_discount_percentage < 100 ) && ( (float) $woosb_discount_percentage > 0 ) ) {
				$discount_percentage = (float) $woosb_discount_percentage;
			}

			return $discount_percentage;
		}

		public function get_items() {
			$product_id = $this->id;
			$woosb_arr  = array();

			if ( $woosb_ids = get_post_meta( $product_id, 'woosb_ids', true ) ) {
				$woosb_items = explode( ',', $woosb_ids );

				if ( is_array( $woosb_items ) && count( $woosb_items ) > 0 ) {
					foreach ( $woosb_items as $woosb_item ) {
						$woosb_item_arr = explode( '/', $woosb_item );
						$woosb_arr[]    = array(
							'id'  => absint( isset( $woosb_item_arr[0] ) ? $woosb_item_arr[0] : 0 ),
							'qty' => (float) ( isset( $woosb_item_arr[1] ) ? $woosb_item_arr[1] : 1 )
						);
					}
				}
			}

			if ( count( $woosb_arr ) > 0 ) {
				return $woosb_arr;
			}

			return false;
		}
	}
}
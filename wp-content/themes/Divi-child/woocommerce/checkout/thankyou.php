<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order">

	<?php
	if ( $order ) :
		$num_of_Adult = WC()->session->get( 'no_of_adults', null );
		$no_of_Childs = WC()->session->get( 'no_of_childs', null );
		$total = WC()->session->get( 'updated_subtotal', null );
		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
					<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
					<?php if ( is_user_logged_in() ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
					<?php endif; ?>
				</p>

		<?php else : ?>

			<div class="summary-right-col">
				<!-- <h2 class="summary-id-txt">Your estimate survey id is <span class="order-id green"><?php echo $order->get_order_number();?></span></h2> -->
				<div class="summary-area">
					<div class="underine-summary">
					<p class="summary">
							Your food cost for 
							<span><?php echo $num_of_Adult;?></span> 
							adults and 
							<span><?php echo $no_of_Childs;?></span> 
							children is 
							<span class="green">
							$<?php echo WC()->session->get('shop_subtotal', 0)?>
							</span></p>
						<p><?php 
							if($order->shipping_total):?>
							Delivery charges <span class="green"> $ <?php echo $order->shipping_total;?> </span>
							<?php endif;?></p>
						<p> Server charges <span class="green"> $ <?php echo WC()->session->get('final_cost_servers', 0);?></span> </p>
					</div>
					<div class="underine-summary">
						<p>
							<strong>Total Price = 
								<span class="green">
									<?php echo $order->get_formatted_order_total();?>
								</span>
							</strong>
						</p>
						<div class="total-sum-price-note">(This is an estimated cost only. You are not locked to this order until we contact and discuss about your party.)</div>
					</div>
					<div class="underine-summary">
						<div class="txt-summary">Someone from CCE will contact you within 24 hours regarding your order.</div>
						<form method="post">
							<input type="hidden" name="mark_as_received" value="true">
							<input type="hidden" name="order_id" value="<?php echo $order->get_order_number();?>">
							<?php wp_nonce_field( 'so_38792085_nonce_action', '_so_38792085_nonce_field' ); ?> 
							<input type="submit" class="button email-estimate-btn" value="Email me estimate">
						</form>
					</div>
				</div>
			</div>

		<?php endif; ?>

	<?php endif; ?>

</div>

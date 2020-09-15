<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php do_action( 'wpo_wcpdf_before_document', $this->type, $this->order ); ?>

<table class="head container">
	<tr>
		<td class="header">
		<?php
		if( $this->has_header_logo() ) {
			$this->header_logo();
		} else {
			echo $this->get_title();
		}
		?>
		</td>
		<td class="shop-info">
			<div class="shop-name"><h3>Shop Name = <?php $this->shop_name(); ?></h3></div>
			<div class="shop-address">Shop Address = <?php $this->shop_address(); ?></div>
		</td>
	</tr>
</table>

<h1 class="document-type-label">
<?php if( $this->has_header_logo() ) echo $this->get_title(); ?>
</h1>

<?php do_action( 'wpo_wcpdf_after_document_label', $this->type, $this->order ); ?>

<table class="order-data-addresses">
	<tr>
		<td class="address billing-address">
			<!-- <h3><?php _e( 'Billing Address:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3> -->
			<?php do_action( 'wpo_wcpdf_before_billing_address', $this->type, $this->order ); ?>
			<?php $this->billing_address(); ?>
			<?php do_action( 'wpo_wcpdf_after_billing_address', $this->type, $this->order ); ?>
			<?php if ( isset($this->settings['display_email']) ) { ?>
			<div class="billing-email"><?php $this->billing_email(); ?></div>
			<?php } ?>
			<?php if ( isset($this->settings['display_phone']) ) { ?>
			<div class="billing-phone"><?php $this->billing_phone(); ?></div>
			<?php } ?>
		</td>
		<td class="address shipping-address">
			<?php if ( isset($this->settings['display_shipping_address']) && $this->ships_to_different_address()) { ?>
			<h3><?php _e( 'Ship To:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
			<?php do_action( 'wpo_wcpdf_before_shipping_address', $this->type, $this->order ); ?>
			<?php $this->shipping_address(); ?>
			<?php do_action( 'wpo_wcpdf_after_shipping_address', $this->type, $this->order ); ?>
			<?php } ?>
		</td>
		<td class="order-data">
			<table>
				<?php do_action( 'wpo_wcpdf_before_order_data', $this->type, $this->order ); ?>
				<?php if ( isset($this->settings['display_number']) ) { ?>
				<tr class="invoice-number">
					<th><?php _e( 'Invoice Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->invoice_number(); ?></td>
				</tr>
				<?php } ?>
				<?php if ( isset($this->settings['display_date']) ) { ?>
				<tr class="invoice-date">
					<th><?php _e( 'Invoice Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->invoice_date(); ?></td>
				</tr>
				<?php } ?>
				<tr class="order-number">
					<th><?php _e( 'Order Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->order_number(); ?></td>
				</tr>
				<tr class="order-date">
					<th><?php _e( 'Order Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->order_date(); ?></td>
				</tr>
				<!-- Hide for website -->
				<?php do_action( 'wpo_wcpdf_after_order_data', $this->type, $this->order ); ?>
			</table>			
		</td>
	</tr>
</table>

<?php do_action( 'wpo_wcpdf_before_order_details', $this->type, $this->order ); ?>

<table class="order-details">
	<thead>
		<tr>
			<th class="product" colspan="3"><?php _e('Product', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php $items = $this->get_order_items(); if( sizeof( $items ) > 0 ) : foreach( $items as $item_id => $item ) : ?>
		<tr class="<?php echo apply_filters( 'wpo_wcpdf_item_row_class', $item_id, $this->type, $this->order, $item_id ); ?>">
			<td class="product" colspan="3">
				<?php $description_label = __( 'Description', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
				<span class="item-name"><?php echo $item['name']; ?></span>
			</td>
		</tr>
		<?php endforeach; endif; 

		$need_server = get_post_meta($this->order_id,"need_servers",true );
		?>
		<tr class="extra-info">
			<th class="key" style="text-align: right;width: 80%;">Event date</th>
			<td class="value" style="text-align: left;width:20%;"><?php echo get_post_meta($this->order_id,"event_date",true ); ?></td>
		</tr>
		<tr class="extra-info">
			<th class="key" style="text-align: right;width: 80%;">Event Time</th>
			<td class="value" style="text-align: left;width:20%;"><?php echo get_post_meta($this->order_id,"event_time",true ); ?></td>
		</tr>
		<tr class="extra-info">
			<th class="key" style="text-align: right;width: 80%;">Need Server?</th>
			<td class="value" style="text-align: left;width:20%;"><?php echo $need_server; ?></td>
		</tr>
		<?php if($need_server == "yes" || $need_server == "Yes") :?>
		<tr class="extra-info">
			<th class="key" style="text-align: right;width: 80%;">How many servers?</th>
			<td class="value" style="text-align: left;width:20%;"><?php echo get_post_meta($this->order_id,"how_many_servers",true ); ?></td>
		</tr>
		<tr class="extra-info">
			<th class="key" style="text-align: right;width: 80%;">From Time</th>
			<td class="value" style="text-align: left;width:20%;"><?php echo get_post_meta($this->order_id,"server_from",true ); ?></td>
		</tr>
		<tr class="extra-info">
			<th class="key" style="text-align: right;width: 80%;">To Time</th>
			<td class="value" style="text-align: left;width:20%;"><?php echo get_post_meta($this->order_id,"server_to",true ); ?></td>
		</tr>
		<tr class="extra-info">
			<th class="key" style="text-align: right;width: 80%;">Server Cost</th>
			<td class="value" style="text-align: left;width:20%;"><?php echo WC()->session->get('final_cost_servers', 0);?></td>
		</tr>
		<?php endif;?>
	</tbody>
	<tfoot>
		<tr class="no-borders">
			<td class="no-borders" colspan="3">
				<table class="totals"  style="margin-top:0">
					<tfoot>
						<?php foreach( $this->get_woocommerce_totals() as $key => $total ) : 
						if(wp_kses_post( $total['label'] ) == "Payment method:" || wp_kses_post( $total['label'] ) == "Subtotal:"){
							continue;
						}		
						?>
						<tr class="<?php echo $key; ?>">
							<th class="description" style="text-align: right;width: 80%;"><?php echo $total['label']; ?></th>
							<td class="price" style="text-align: left;width:20%;"><span class="totals-price"><?php echo $total['value']; ?></span></td>
						</tr>
						<?php endforeach; ?>
					</tfoot>
				</table>
			</td>
		</tr>
	</tfoot>
</table>

<?php do_action( 'wpo_wcpdf_after_order_details', $this->type, $this->order ); ?>

<?php if ( $this->get_footer() ): ?>
<div id="footer">
	<?php $this->footer(); ?>
</div><!-- #letter-footer -->
<?php endif; ?>
<?php do_action( 'wpo_wcpdf_after_document', $this->type, $this->order ); ?>

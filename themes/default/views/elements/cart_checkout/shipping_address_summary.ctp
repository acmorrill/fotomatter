<?php $shipping_address = $this->Ecommerce->get_cart_shipping_address(); ?>
<?php //$shipping_address = $this->Ecommerce->get_cart_shipping_address(); ?>

<?php //debug($billing_address); ?>

<div id="shipping_address_summary">
	<h1><?php __('Shipping Address'); ?> <a href="/ecommerces/checkout_get_address">Edit</a></h1>
	<?php echo $shipping_address['firstname']; ?> <?php echo $shipping_address['lastname']; ?><br />
	<?php echo $shipping_address['address1']; ?><br />
	<?php if (!empty($shipping_address['address2'])): ?>
		<?php echo $shipping_address['address2']; ?><br />
	<?php endif; ?>
	<?php echo $shipping_address['city']; ?><br />
	<?php echo $shipping_address['zip']; ?><br />
	<?php echo $shipping_address['country_name']; ?><br />
	<?php echo $shipping_address['state_name']; ?><br />
</div>
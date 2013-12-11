<?php $billing_address = $this->Ecommerce->get_cart_billing_address(); ?>

<?php //debug($billing_address); ?>

<div id="billing_address_summary">
	<h1><?php __('Billing Address'); ?>  <a href="/ecommerces/checkout_get_address">Edit</a></h1>
	<?php if (!empty($billing_address)): ?>
		<?php echo $billing_address['firstname']; ?> <?php echo $billing_address['lastname']; ?><br />
		<?php echo $billing_address['address1']; ?><br />
		<?php if (!empty($billing_address['address2'])): ?>
			<?php echo $billing_address['address2']; ?><br />
		<?php endif; ?>
		<?php echo $billing_address['city']; ?><br />
		<?php echo $billing_address['zip']; ?><br />
		<?php echo $billing_address['country_name']; ?><br />
		<?php echo $billing_address['state_name']; ?><br />
	<?php endif; ?>
</div>
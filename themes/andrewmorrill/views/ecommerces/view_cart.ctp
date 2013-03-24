<?php //debug($cart_datas); ?>

<?php echo $this->Element('cart_checkout/cart_table_simple', array( 'cart_items' => $cart_datas['items'])); ?>


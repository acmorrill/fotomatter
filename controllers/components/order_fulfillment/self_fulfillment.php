<?php
require(ROOT . DS . 'app' . DS . 'controllers' . DS . 'components' . DS . 'order_fulfillment' . DS . 'generic_fulfillment_type.php');

class SelfFulfillmentComponent extends generic_fulfillment_type {
	public function finalize_order() {
		return 'suckit';
	}
}

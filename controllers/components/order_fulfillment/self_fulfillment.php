<?php
require(ROOT . DS . 'app' . DS . 'controllers' . DS . 'components' . DS . 'order_fulfillment' . DS . 'generic_fulfillment_type.php');

class SelfFulfillmentComponent extends generic_fulfillment_type {
	
	
	public function approve_order_line_items($line_items, $approval_date) {
		$this->AuthnetLineItem = ClassRegistry::init('AuthnetLineItem');
		
		$all_good = true;
		foreach ($line_items as $line_item) {
			$order_line_item_data = array();
			$order_line_item_data['AuthnetLineItem']['id'] = $line_item['id'];
			$order_line_item_data['AuthnetLineItem']['order_status'] = 'approved';
			$order_line_item_data['AuthnetLineItem']['approval_date'] = $approval_date;
			
			if (!$this->AuthnetLineItem->save($order_line_item_data)) {
				$this->AuthnetLineItem->major_error('Failed to approve a self fulfillment order line item', compact('line_item'), 'high');
				$all_good = false;
				break;
			}
		}
		
		return $all_good;
	}
}

<?php

abstract class generic_fulfillment_type extends Object{
	abstract public function approve_order_line_items($line_items);
}

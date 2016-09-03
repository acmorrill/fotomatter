<?php

namespace Ups;

require "autoload.php";

class ShippingEstimator {

	public $accessKey = '9D0BD578FC2E59F6';
	public $userId = 'acmorrill';
	public $password = 'AVnYDDF2d7MjcYpbIYOu';
	private $package_padding = 2;
	private $max_package_prints = 15;

	public function check_address() {
		$address = new \Ups\Entity\Address();
		$address->setAttentionName('Andrew Morrill');
//        $address->setBuildingName('Test');
		$address->setAddressLine1('1547 Crandall Dr');
//        $address->setAddressLine2('Address Line 2');
//        $address->setAddressLine3('Address Line 3');
		$address->setStateProvinceCode('UT');
		$address->setCity('Springville');
		$address->setCountryCode('US');
		$address->setPostalCode('84663');

		$xav = new \Ups\AddressValidation($this->accessKey, $this->userId, $this->password);
		$xav->activateReturnObjectOnValidate(); //This is optional
		try {
			$response = $xav->validate($address, $requestOption = \Ups\AddressValidation::REQUEST_OPTION_ADDRESS_VALIDATION, $maxSuggestion = 15);
		} catch (Exception $e) {
//            var_dump($e);
		}

		return $response;
	}

	/**
	 * Logic:
	 * 
	 * 
	 * 
	 * @return type
	 */
	public function get_shipping_price() {
		$cart_data = [
			'items' => [
				'key1' => [
					'qty' => 10,
					'short_side_inches' => 8,
					'long_side_inches' => 10,
					'is_pano' => false,
					'print_type_ships_by_itself' => false,
					'print_type_can_be_rolled' => false
				],
				'key2' => [
					'qty' => 10,
					'short_side_inches' => 11,
					'long_side_inches' => 14,
					'is_pano' => false,
					'print_type_ships_by_itself' => false,
					'print_type_can_be_rolled' => false
				],
				'key3' => [
					'qty' => 25,
					'short_side_inches' => 24,
					'long_side_inches' => 30.75,
					'is_pano' => false,
					'print_type_ships_by_itself' => false,
					'print_type_can_be_rolled' => true
				],
				'key4' => [
					'qty' => 10,
					'short_side_inches' => 20,
					'long_side_inches' => 24,
					'is_pano' => false,
					'print_type_ships_by_itself' => false,
					'print_type_can_be_rolled' => true
				],
				'key5' => [
					'qty' => 10,
					'short_side_inches' => 10,
					'long_side_inches' => 40,
					'is_pano' => true,
					'print_type_ships_by_itself' => false,
					'print_type_can_be_rolled' => true
				],
				'key6' => [
					'qty' => 10,
					'short_side_inches' => 8,
					'long_side_inches' => 40,
					'is_pano' => true,
					'print_type_ships_by_itself' => false,
					'print_type_can_be_rolled' => true
				],
				'key7' => [
					'qty' => 10,
					'short_side_inches' => 12,
					'long_side_inches' => 40,
					'is_pano' => true,
					'print_type_ships_by_itself' => false,
					'print_type_can_be_rolled' => true
				],
				'key8' => [
					'qty' => 10,
					'short_side_inches' => 8,
					'long_side_inches' => 40,
					'is_pano' => true,
					'print_type_ships_by_itself' => false,
					'print_type_can_be_rolled' => false
				],
				'key9' => [
					'qty' => 10,
					'short_side_inches' => 12,
					'long_side_inches' => 40,
					'is_pano' => true,
					'print_type_ships_by_itself' => false,
					'print_type_can_be_rolled' => false
				],
				'key10' => [
					'qty' => 3,
					'short_side_inches' => 40,
					'long_side_inches' => 50,
					'is_pano' => false,
					'print_type_ships_by_itself' => true,
					'print_type_can_be_rolled' => false
				]
			]
		];
		$items = $this->prepare_cart_items_for_packaging($cart_data['items']);
		$packages = $this->breakup_packages($items);

		
		// DREW TODO - START HERE TOMORROW
		// - need to split packages by zip code (from shipper)
		// - need to calculate package weight based size of package
		// - use actual data from the cart

		$rate = new \Ups\Rate(
				$this->accessKey, $this->userId, $this->password
		);

		try {
			$shipment = new \Ups\Entity\Shipment();

			$shipperAddress = $shipment->getShipper()->getAddress();
			$shipperAddress->setPostalCode('99205');

			$address = new \Ups\Entity\Address();
			$address->setPostalCode('99205');
			$shipFrom = new \Ups\Entity\ShipFrom();
			$shipFrom->setAddress($address);

			$shipment->setShipFrom($shipFrom);

			$shipTo = $shipment->getShipTo();
			$shipTo->setCompanyName('Test Ship To');
			$shipToAddress = $shipTo->getAddress();
			$shipToAddress->setPostalCode('99205');

			foreach ($packages as $curr_package) {
				$package = new \Ups\Entity\Package();
				$package->getPackagingType()->setCode(\Ups\Entity\PackagingType::PT_PACKAGE);
				$package->getPackageWeight()->setWeight(10);

				$dimensions = new \Ups\Entity\Dimensions();
				$dimensions->setHeight($curr_package['package_width']);
				$dimensions->setLength($curr_package['package_length']);
				$dimensions->setWidth(6);

				$unit = new \Ups\Entity\UnitOfMeasurement;
				$unit->setCode(\Ups\Entity\UnitOfMeasurement::UOM_IN);

				$dimensions->setUnitOfMeasurement($unit);
				$package->setDimensions($dimensions);
			}

			$shipment->addPackage($package);

			$rate_data = $rate->getRate($shipment);
		} catch (Exception $e) {
//            var_dump($e);
		}

		return $rate_data;
	}
	
	private function prepare_cart_items_for_packaging($items) {
		foreach ($items as &$item) {
			if ($item['print_type_can_be_rolled'] === true && $item['long_side_inches'] <= 20) {
				$item['print_type_can_be_rolled'] = false;
			}
			if ($item['is_pano'] === true) {
				if ($item['short_side_inches'] >= 25 || $item['long_side_inches'] >= 60) {
					$item['print_type_ships_by_itself'] = true;
				}
			} else {
				if ($item['short_side_inches'] >= 35 || $item['long_side_inches'] >= 45) {
					$item['print_type_ships_by_itself'] = true;
				}
			}
		}
		
		
		// sort items
		usort($items, function($a, $b) {
			if ($a['print_type_ships_by_itself'] === $b['print_type_ships_by_itself']) {
				if ($a['is_pano'] === $b['is_pano']) {
					if ($a['print_type_can_be_rolled'] === $b['print_type_can_be_rolled']) {
						if ($a['short_side_inches'] === $b['short_side_inches']) {
							return 0;
						}
						return ($a['short_side_inches'] < $b['short_side_inches']) ? 1 : -1;
					}
					return $a['print_type_can_be_rolled'] ? -1 : 1;
				}
				return $a['is_pano'] ? 1 : -1;
			}
			return $a['print_type_ships_by_itself'] ? 1 : -1;
		});
		
		
		return $items;
	}

	private function breakup_packages($items) {
		$packages = [];
		
		$can_be_rolled = [];
		$can_be_rolled_current_count = 0;
		$can_be_rolled_package_count = 0;
		
		$cannot_be_rolled = [];
		$cannot_be_rolled_current_count = 0;
		$cannot_be_rolled_package_count = 0;
		
		$can_be_rolled_pano = [];
		$can_be_rolled_pano_current_count = 0;
		$can_be_rolled_pano_package_count = 0;
		
		$cannot_be_rolled_pano = [];
		$cannot_be_rolled_pano_current_count = 0;
		$cannot_be_rolled_pano_package_count = 0;
		
		$by_itself_packages = [];

		foreach ($items as $key => &$item) {
			$item['package_qty'] = 1;
			for ($i = 0; $i < $item['qty']; $i++) {
				if ($item['print_type_ships_by_itself'] !== true) {
					if ($item['print_type_can_be_rolled'] === true) {
						if ($item['is_pano'] !== true) {
							$this->figure_package_data($can_be_rolled, $can_be_rolled_package_count, $can_be_rolled_current_count, $item, $key);
						} else {
							$this->figure_package_data($can_be_rolled_pano, $can_be_rolled_pano_package_count, $can_be_rolled_pano_current_count, $item, $key);
						}
					} else {
						if ($item['is_pano'] !== true) {
							$this->figure_package_data($cannot_be_rolled, $cannot_be_rolled_package_count, $cannot_be_rolled_current_count, $item, $key);
						} else {
							$this->figure_package_data($cannot_be_rolled_pano, $cannot_be_rolled_pano_package_count, $cannot_be_rolled_pano_current_count, $item, $key);
						}
					}
				} else {
					$by_itself_packages[] = [
						'items' => [
							$key => $item
						],
						'package_width' => ceil($item['short_side_inches']) + $this->package_padding,
						'package_length' => ceil($item['long_side_inches']) + $this->package_padding,
						'package_qty' => 1,
						'print_type_can_be_rolled' => false,
						'is_pano' => $item['is_pano']
					];
					$item['package_qty'] = 1;
				}
			}
		}

		$this->add_package_by_type($packages, $can_be_rolled, true, false);
		$this->add_package_by_type($packages, $cannot_be_rolled, false, false);
		$this->add_package_by_type($packages, $can_be_rolled_pano, true, true);
		$this->add_package_by_type($packages, $cannot_be_rolled_pano, false, true);
		foreach ($by_itself_packages as $by_itself_package) {
			$packages[] = $by_itself_package;
		}

		return $packages;
	}
	
	private function add_package_by_type(&$packages, $array, $can_be_rolled, $is_pano) {
		if (!empty($array)) {
			foreach ($array as $curr) {
				$packages[] = [
					'items' => $curr['items'],
					'package_width' => $curr['package_width'] + $this->package_padding,
					'package_length' => $curr['package_length'] + $this->package_padding,
					'package_qty' => $curr['package_qty'],
					'print_type_can_be_rolled' => $can_be_rolled,
					'is_pano' => $is_pano,
				];
			}
		}
	}
	
	private function figure_package_data(&$array, &$package_count, &$current_count, &$item, &$key) {
		if (empty($array[$package_count]['package_length'])) {
			$array[$package_count]['package_length'] = ceil($item['long_side_inches']);
		} else if (ceil($item['long_side_inches']) > $array[$package_count]['package_length']) {
			$array[$package_count]['package_length'] = ceil($item['long_side_inches']);
		}
		if (empty($array[$package_count]['package_width'])) {
			$array[$package_count]['package_width'] = ceil($item['short_side_inches']);
		} else if (ceil($item['short_side_inches']) > $array[$package_count]['package_width']) {
			$array[$package_count]['package_width'] = ceil($item['short_side_inches']);
		}
		if (empty($array[$package_count]['package_qty'])) {
			$array[$package_count]['package_qty'] = 1;
		} else {
			$array[$package_count]['package_qty']++;
		}
		$array[$package_count]['items'][$key] = $item;
		$current_count++;
		$item['package_qty']++;
		if ($current_count >= $this->max_package_prints) {
			$item['package_qty'] = 1;
			$current_count = 0;
			$package_count++;
		}
	}

}

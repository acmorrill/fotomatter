<?php

namespace Ups;

require "autoload.php";

class ShippingEstimator {

	public $accessKey = '9D0BD578FC2E59F6';
	public $userId = 'acmorrill';
	public $password = 'AVnYDDF2d7MjcYpbIYOu';
	private $package_padding_percent = 1.05;
	private $max_package_prints = 50;
	private $by_itself_lbs_per_cuin = .00333;
	private $loose_prints_lbs_per_cuin = .00347;

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
	public function get_shipping_price($cart_data) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
		$items = $this->prepare_cart_items_for_packaging($cart_data['items']);
		$this->sort_cart_items($items);
		$items_by_shipper = $this->breakup_items_by_shipper($items);
		$packages = [];
		foreach ($items_by_shipper as $ship_from_address_key => $brokenup_items) {
			$packages[$ship_from_address_key] = $this->breakup_packages($brokenup_items, $ship_from_address_key);
		}
		
		// DREW TODO - START HERE TOMORROW!
		// - use the actual shipping estimate in the final page of the cart
		// - return good errors

		
		$return_arr = $this->get_shipments_and_rates_by_packages($packages, $cart_data['shipping_address'], $cart_data['ship_from_addresses']);

		return $return_arr;
	}
	
	private function get_shipments_and_rates_by_packages(&$packages, &$ship_to_address, &$ship_from_addresses) {
		$rate = new \Ups\Rate(
				$this->accessKey, $this->userId, $this->password
		);

		$return_arr = [
			'total_cost' => 0,
			'total_packages' => 0,
			'shipments' => [],
			'ship_to_address' => $ship_to_address
		];
		try {
			foreach ($packages as $ship_from_address_key => &$shipment_packages) {
				$shipment_ship_from_address = $ship_from_addresses[$ship_from_address_key];

				// setup ship from address
				$shipment = new \Ups\Entity\Shipment();
				$shipperAddress = $shipment->getShipper()->getAddress();
				$shipperAddress->setPostalCode($shipment_ship_from_address['zip']);
				$address = new \Ups\Entity\Address();
				$address->setPostalCode($shipment_ship_from_address['zip']);
				$shipFrom = new \Ups\Entity\ShipFrom();
				$shipFrom->setAddress($address);
				$shipment->setShipFrom($shipFrom);
				
				// setup service // without the below everything defaults to ups ground - which is cheapest
//				$service = $shipment->getService();
//				$service->setCode(\Ups\Entity\Service::S_AIR_2DAY);
				
				// setup ship to address
				$shipTo = $shipment->getShipTo();
				$shipTo->setCompanyName("{$ship_to_address['firstname']} {$ship_to_address['lastname']}");
				$shipToAddress = $shipTo->getAddress();
				$shipToAddress->setPostalCode($ship_to_address['zip']);
				
				// add packages to shipment
				foreach ($shipment_packages as &$curr_package) {
					$package = $this->create_ups_package($curr_package);
					$shipment->addPackage($package);
				}
				
				// get the rate for current shipment
				$rate_data = $rate->getRate($shipment);
				
				
				
				// add package rate data to the actual packages
				foreach ($rate_data->RatedShipment[0]->RatedPackage as $index => $rated_package) {
					$shipment_packages[$index]['package_rate_raw_data'] = $rated_package->TotalCharges;
				}
				$rate_data->RatedShipment[0]->RatedPackage = [];
				
				
				// check for errors - DREW TODO
				
				
				// compile return data
				$return_arr['shipments'][] = [
					'cost' => $rate_data->RatedShipment[0]->TotalCharges->MonetaryValue,
					'packages_count' => count($shipment_packages),
					'packages' => $shipment_packages,
					'cost_currency' => $rate_data->RatedShipment[0]->TotalCharges->CurrencyCode,
					'ship_from_address' => $shipment_ship_from_address,
					'raw_rate_data' => print_r($rate_data, true)
				];
				// DREW TODO - make sure always returns USD - otherwise need to error
				$return_arr['total_cost'] += $rate_data->RatedShipment[0]->TotalCharges->MonetaryValue;
				$return_arr['total_packages'] += count($shipment_packages);
			}
		} catch (Exception $e) {
			// DREW TODO - need to smartly error here
//            var_dump($e);
		}
		
		return $return_arr;
	}
	
	private function create_ups_package(&$curr_package) {
		// - figure out the use of small flat boxes for small prints (figure out at package level - if package is small enough and goes flat then use the smaller options)
		//	- UPS Express Box Large (18"x13") - flat and less than 13" x 18" on both dimensions
		//	- UPS Express Pak (16"x12.75") - flat and less than 10" x 12" on both dimensions
		//	- UPS Express Tube - rolled and length less than 38" on short side
		$package = new \Ups\Entity\Package();
		$package->getPackageWeight()->setWeight($curr_package['weight_estimate']);
		
		
		$should_ship_in_tube = $curr_package['print_type_can_be_rolled'] === true && $curr_package['width'] < 38;
		$should_ship_express_pak = $curr_package['print_type_can_be_rolled'] === false && $curr_package['width'] < 12.75 && $curr_package['length'] < 16;
		$dimensions = new \Ups\Entity\Dimensions();
		if ($should_ship_in_tube) {
			$curr_package['package_type'] = 'tube';
			$dimensions->setLength($curr_package['length']);
			$dimensions->setWidth(6);
			$dimensions->setHeight(6);
		} else if ($should_ship_express_pak) {
			$curr_package['package_type'] = 'express_pak';
			$dimensions->setLength($curr_package['length']);
			$dimensions->setWidth($curr_package['width']);
			$dimensions->setHeight(1);
		} else {
			$curr_package['package_type'] = 'package';
			$dimensions->setLength($curr_package['length']);
			$dimensions->setWidth($curr_package['width']);
			$dimensions->setHeight($curr_package['height']);
		}
		
		$package->getPackagingType()->setCode(\Ups\Entity\PackagingType::PT_PACKAGE);
		
		
		$unit = new \Ups\Entity\UnitOfMeasurement;
		$unit->setCode(\Ups\Entity\UnitOfMeasurement::UOM_IN);

		$dimensions->setUnitOfMeasurement($unit);
		$package->setDimensions($dimensions);
		
		return $package;
	}
	
	private function prepare_cart_items_for_packaging($items) {
	    // START HERE TOMORROW - need to add print_type_can_be_rolled, print_type_ships_by_itself, is_pano to items
        // print_type_can_be_rolled - just needs to be general about the print type (not size)
        // print_type_ships_by_itself - just needs to be general about the print type (not size)
		foreach ($items as &$item) {
			if ($item['print_type_can_be_rolled'] == true && $item['long_side_inches'] <= 20) {
				$item['print_type_can_be_rolled'] = false;
			}
			if ($item['is_pano'] == true) {
				if ($item['short_side_inches'] >= 25 || $item['long_side_inches'] >= 60) {
					$item['print_type_ships_by_itself'] = true;
				}
			} else {
				if ($item['short_side_inches'] >= 35 || $item['long_side_inches'] >= 45) {
					$item['print_type_ships_by_itself'] = true;
				}
			}
		}
		
		return $items;
	}
	
	private function sort_cart_items(&$items) {
		// sort items
		usort($items, function($a, $b) {
			if ($a['ship_from_address_key'] === $b['ship_from_address_key']) {
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
			}
			return ($a['ship_from_address_key'] < $b['ship_from_address_key']) ? 1 : -1;
		});
	}
	
	private function breakup_items_by_shipper($items) {
		$broken_up_items = [];
		foreach ($items as &$item) {
			$broken_up_items[$item['ship_from_address_key']][] = $item;
		}
		
		return $broken_up_items;
	}

	
	/**
	 * NOTES about l x w x h
	 * Length = always the longest side of the package
	 * Width = the side opposite the length (parallel to length)
	 * Height = how tall the package is
	 * good info: http://www.wikihow.com/Measure-the-Length-x-Width-x-Height-of-Shipping-Boxes
	 * 
	 * Weight = L x W x H / 166 (if dimensions in inches then estimates the lbs)
	 * 
	 * @param type $items
	 * @param type $ship_from_address_key
	 * @return int
	 */
	private function breakup_packages(&$items, $ship_from_address_key) {
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
					$package_data = $this->get_package_dimensions_weight($item['long_side_inches'], $item['short_side_inches'], 8, $this->by_itself_lbs_per_cuin);
					$package_data['items'] = [
						$key => $item
					];
					$package_data['package_qty'] = 1;
					$package_data['print_type_can_be_rolled'] = false;
					$package_data['is_pano'] = $item['is_pano'];
					$package_data['ship_from_address_key'] = $ship_from_address_key;
					$by_itself_packages[] = $package_data;
					$item['package_qty'] = 1;
				}
			}
		}

		$this->add_package_by_type($packages, $can_be_rolled, true, false, $ship_from_address_key);
		$this->add_package_by_type($packages, $cannot_be_rolled, false, false, $ship_from_address_key);
		$this->add_package_by_type($packages, $can_be_rolled_pano, true, true, $ship_from_address_key);
		$this->add_package_by_type($packages, $cannot_be_rolled_pano, false, true, $ship_from_address_key);
		foreach ($by_itself_packages as $by_itself_package) {
			$packages[] = $by_itself_package;
		}

		return $packages;
	}
	
	private function get_package_dimensions_weight($long_side_inches, $short_side_inches, $height_inches, $lbs_per_cu_inch) {
		$data['length'] = round($long_side_inches * $this->package_padding_percent, 1, PHP_ROUND_HALF_UP);
		$data['width'] = round($short_side_inches * $this->package_padding_percent, 1, PHP_ROUND_HALF_UP);
		$data['height'] = $height_inches;
		$data['girth'] = (2 * $data['width']) + (2 * $data['height']);
		$data['size'] = $data['length'] + $data['girth'];
		$data['cu_in'] = $data['length'] * $data['width'] * $data['height'];
		$data['dimensional_weight'] = $data['cu_in'] / 166;
		$data['weight_estimate'] = $data['cu_in'] * $lbs_per_cu_inch;
		return $data;
	}
	
	private function add_package_by_type(&$packages, $array, $can_be_rolled, $is_pano, $ship_from_address_key) {
		if (!empty($array)) {
			foreach ($array as $curr) {
				$package_data = $this->get_package_dimensions_weight($curr['length'], $curr['width'], 2, $this->loose_prints_lbs_per_cuin);
				$package_data['items'] = $curr['items'];
				$package_data['package_qty'] = $curr['package_qty'];
				$package_data['print_type_can_be_rolled'] = $can_be_rolled;
				$package_data['is_pano'] = $is_pano;
				$package_data['ship_from_address_key'] = $ship_from_address_key;
				$packages[] = $package_data;
			}
		}
	}
	
	private function figure_package_data(&$array, &$package_count, &$current_count, &$item, &$key) {
		if (empty($array[$package_count]['length'])) {
			$array[$package_count]['length'] = round($item['long_side_inches'], 1, PHP_ROUND_HALF_UP);
		} else if (ceil($item['long_side_inches']) > $array[$package_count]['length']) {
			$array[$package_count]['length'] = round($item['long_side_inches'], 1, PHP_ROUND_HALF_UP);
		}
		if (empty($array[$package_count]['width'])) {
			$array[$package_count]['width'] = round($item['short_side_inches'], 1, PHP_ROUND_HALF_UP);
		} else if (ceil($item['short_side_inches']) > $array[$package_count]['width']) {
			$array[$package_count]['width'] = round($item['short_side_inches'], 1, PHP_ROUND_HALF_UP);
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

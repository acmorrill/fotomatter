<?php

namespace Ups;

require "autoload.php";


class ShippingEstimator {

    public $accessKey = '9D0BD578FC2E59F6';
    public $userId = 'acmorrill';
    public $password = 'AVnYDDF2d7MjcYpbIYOu';

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

    public function get_shipping_price() {
        // START HERE TOMORROW - need to start figuring out logic for print shipment price etc
        
        $rate = new \Ups\Rate(
            $this->accessKey,
            $this->userId,
            $this->password
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

            $package = new \Ups\Entity\Package();
            $package->getPackagingType()->setCode(\Ups\Entity\PackagingType::PT_PACKAGE);
            $package->getPackageWeight()->setWeight(10);

            $dimensions = new \Ups\Entity\Dimensions();
            $dimensions->setHeight(10);
            $dimensions->setWidth(10);
            $dimensions->setLength(10);

            $unit = new \Ups\Entity\UnitOfMeasurement;
            $unit->setCode(\Ups\Entity\UnitOfMeasurement::UOM_IN);

            $dimensions->setUnitOfMeasurement($unit);
            $package->setDimensions($dimensions);

            $shipment->addPackage($package);

            $rate_data = $rate->getRate($shipment);
        } catch (Exception $e) {
//            var_dump($e);
        }
        
        return $rate_data;
    }

}

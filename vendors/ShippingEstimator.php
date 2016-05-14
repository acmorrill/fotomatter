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
//		$address->setBuildingName('Test');
		$address->setAddressLine1('1547 Crandall Dr');
//		$address->setAddressLine2('Address Line 2');
//		$address->setAddressLine3('Address Line 3');
		$address->setStateProvinceCode('UT');
		$address->setCity('Springville');
		$address->setCountryCode('US');
		$address->setPostalCode('84663');

		$xav = new \Ups\AddressValidation($this->accessKey, $this->userId, $this->password);
		$xav->activateReturnObjectOnValidate(); //This is optional
		try {
			$response = $xav->validate($address, $requestOption = \Ups\AddressValidation::REQUEST_OPTION_ADDRESS_VALIDATION, $maxSuggestion = 15);
		} catch (Exception $e) {
			var_dump($e);
		}
		
		print_r($response);
	}
}
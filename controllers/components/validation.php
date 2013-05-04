<?php
	
class ValidationComponent extends Object {
	
	
	
	///////////////////////////////////////////////////////////////////////////////////
	// VALIDATION FUNCTIONS
	public function validate($type, $data, $value, $flash_message) {
		switch($type) {
			case 'not_empty':
				if (empty($data[$value])) {
					throw new Exception($flash_message);
					return;
				}
				break;
			case 'valid_email':
				if (!filter_var($data[$value], FILTER_VALIDATE_EMAIL)) {
					throw new Exception($flash_message);
					return;
				}
				break;
			case 'valid_password':
				if (!empty($data[$value]) && count($data[$value]) >= 8) {
					throw new Exception($flash_message);
					return;
				}
				break;
			case 'password_match':
				if ($data !== $value) {
					throw new Exception($flash_message);
					return;
				}
				break;
			case 'in_array':
				if (!in_array($data, $value)) {
					throw new Exception($flash_message);
					return;
				}
				break;
			case 'valid_cc':
				if ($this->check_cc($data) === false || $this->detectType($data) !== $value) {
					throw new Exception($flash_message);
					return;
				}
				break;
			case 'valid_cc_code':
				if ( !is_numeric($data[$value]) || (strlen($data[$value]) !== 3 && strlen($data[$value]) !== 4) ) {
					throw new Exception($flash_message);
					return;
				}
				break;
			default:
				break;
		}
	}

	/*
	* check method
	*   return true or false
	*/
	function check_cc($cardnum) {
		$cardnum = $this->_strtonum($cardnum);

		$card_type = $this->detectType($cardnum);
		if($card_type === false) {
			return false;
		}

		if($this->mod10($cardnum)) {
			return false;
		}

		return true;
	}
	
	
	/*
	* mod10 method - Luhn check digit algorithm
	*   return 0 if true and !0 if false
	*/
	function mod10($cardnum) {
		for($sum=0, $i=strlen($cardnum)-1; $i >= 0; $i--) {
			$sum += $cardnum[$i];
			$doubdigit = "".(2 * $cardnum[--$i]);
			for($j = strlen($doubdigit)-1; $j >= 0; $j--) {
				$sum += $doubdigit[$j];
			}
		}
		
		return $sum % 10;
	}
	
	
	/*
	* detectType method
	*   returns card type in number format
	*/
	function detectType($cardnum = 0) {
		if(!$cardnum) {
			return false;
		}

		if(preg_match("/^5[1-5]\d{14}$/", $cardnum)) {
			return "mastercard";
		} else if(preg_match("/^4(\d{12}|\d{15})$/", $cardnum)) {
			return "visa";
		} else if(preg_match("/^3[47]\d{13}$/", $cardnum)) {
			return "amex";
		} else if(preg_match("/^[300-305]\d{11}$/", $cardnum) || preg_match("/^3[68]\d{12}$/", $cardnum)) {
			return "dinners";
		} else if(preg_match("/^6011\d{12}$/", $cardnum)) {
			return "discover";
		} else if(preg_match("/^2(014|149)\d{11}$/", $cardnum)) {
			return "enroute";
		} else if(preg_match("/^3\d{15}$/", $cardnum) || preg_match("/^(2131|1800)\d{11}$/", $cardnum)) {
			return "jcb";
		}


		return false;;
	}
	
	
	/*
	* _strtonum private method
	*   return formated string - only digits
	*/
	function _strtonum($string) {
		$nstr = '';
		for($i = 0; $i < strlen($string); $i++) {
			if(!is_numeric($string[$i])) {
				continue;
			}
			$nstr = $nstr.$string[$i];
		}
		
		return $nstr;
	}
}
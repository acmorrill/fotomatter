<?php
	//require_once("errorHandling.php");
	class Dimension {
		var $relWidth;
		var $relHeight;
		var $inWidth;
		var $inHeight;
		var $aveInDimen;
		var $tier;
		var $imgID;
		var $myCostMult;
		var $landscapeFormat;
		var $numInCart;
		var $feetWidth;
		var $feetHeight;
		function Dimension($iID, $w, $h, $tier, $baseHW, $landscapeFrmt) {
			$this->relWidth = (float)$w;
			$this->relHeight = (float)$h;
			if ($landscapeFrmt) {
				$this->inHeight = (float)$baseHW;
			} else {
				$this->inWidth = (float)$baseHW;
			}
			$this->tier = $tier;
			$this->imgID = $iID;
			$this->landscapeFormat = $landscapeFrmt;
			$this->numInCart = 1;
			$this->feetHeight = 0;
			$this->feetWidth = 0;
		}
		
		function calcBaseWidthHeight() {
			if ($this->landscapeFormat) {
				$this->inWidth = ($this->inHeight * $this->relWidth) / $this->relHeight;
			} else {
				$this->inHeight = ($this->inWidth * $this->relHeight) / $this->relWidth;
			}
		}
		
		function calcAveDimen() {
			$this->aveInDimen = ($this->inHeight + $this->inWidth) / 2;
		}
		
		function getPrice() {
			/*$standardFormat = array('5','8','11', '16', '20', '24', '30', '40', '48');
			$panoramic = array('10', '16', '22', '29');*/
		
			$myPrice = 0;
		
			if (($this->inHeight * 1.8) < $this->inWidth) { // panoramic format
				if ($this->inHeight >= 10 && $this->inHeight < 16) {
					$myPrice = 135;
				} else if ($this->inHeight >= 16 && $this->inHeight < 22) {
					$myPrice = 350;
				} else if ($this->inHeight >= 22 && $this->inHeight < 29) {
					$myPrice = 495;
				} else if ($this->inHeight >= 29) {
					$myPrice = 750;
				}
			} else if ($this->inHeight >= $this->inWidth) { // portrait format
				if ($this->inWidth >= 5 && $this->inWidth < 8) {
					$myPrice = 35;
				} else if ($this->inWidth >= 8 && $this->inWidth < 11) {
					$myPrice = 70;
				} else if ($this->inWidth >= 11 && $this->inWidth < 16) {
					$myPrice = 115;
				} else if ($this->inWidth >= 16 && $this->inWidth < 20) {
					$myPrice = 175;
				} else if ($this->inWidth >= 20 && $this->inWidth < 24) {
					$myPrice = 255;
				} else if ($this->inWidth >= 24 && $this->inWidth < 30) {
					$myPrice = 360;
				} else if ($this->inWidth >= 30 && $this->inWidth < 35) {
					$myPrice = 495;
				} else if ($this->inWidth >= 35 && $this->inWidth < 40) {
					$myPrice = 585;
				} else if ($this->inWidth >= 40 && $this->inWidth < 44) {
					$myPrice = 665;
				} else if ($this->inWidth >= 44 && $this->inWidth < 48) {
					$myPrice = 775;
				} else if ($this->inWidth >= 48) {
					$myPrice = 875;
				}
			} else { // landscape format
				if ($this->inHeight >= 5 && $this->inHeight < 8) {
					$myPrice = 35;
				} else if ($this->inHeight >= 8 && $this->inHeight < 11) {
					$myPrice = 70;
				} else if ($this->inHeight >= 11 && $this->inHeight < 16) {
					$myPrice = 115;
				} else if ($this->inHeight >= 16 && $this->inHeight < 20) {
					$myPrice = 175;
				} else if ($this->inHeight >= 20 && $this->inHeight < 24) {
					$myPrice = 255;
				} else if ($this->inHeight >= 24 && $this->inHeight < 30) {
					$myPrice = 360;
				} else if ($this->inHeight >= 30 && $this->inHeight < 35) {
					$myPrice = 495;
				} else if ($this->inHeight >= 35 && $this->inHeight < 40) {
					$myPrice = 585;
				} else if ($this->inHeight >= 40 && $this->inHeight < 44) {
					$myPrice = 665;
				} else if ($this->inHeight >= 44 && $this->inHeight < 48) {
					$myPrice = 775;
				} else if ($this->inHeight >= 48) {
					$myPrice = 875;
				}
			}
		
			/*
			$aveFeetDimen = $this->aveInDimen/12;
			// old pricing scheme
				$myPrice = (($aveFeetDimen * 100) * ($aveFeetDimen);
			// new pricing scheme
				//$myPrice = $aveFeetDimen * 22;
			$myPrice = $myPrice * $this->tier['mult'];
			$finalVal = $myPrice; //$this->adjustLowPrices($myPrice);
			if ($finalVal < 100) {
				return round($finalVal , 0);
			} else {
				return round($finalVal , -1);
			}*/
			
			return $myPrice;
		}
		
		function getLinearPrice($length) {
			return ((42.857 * $length) - 435.71); 
		}
		
		function getInDimenAsText() {
			$value = "";
			$this->extractFeetFromInches();
			if (false) { //($this->isPanoramic() && $this->feetHeight >= 2 && $this->feetWidth >= 3) || (!$this->isPanoramic() && $this->feetHeight >= 3 && $this->feetWidth >= 3)) {
				$heightInches = $this->inHeight - (12 * $this->feetHeight);
				$widthInches = $this->inWidth - (12 * $this->feetWidth);
				$value .= $this->feetHeight." ft ".(round($heightInches, 0)?round($heightInches, 0)."\"":"")." x ".$this->feetWidth." ft ".(round($widthInches, 0)?round($widthInches, 0)."\"":"");
			} else {
				$value .= round($this->inHeight, 0)."\" x ".round($this->inWidth, 0)."\"";
			}	
			
			return $value;
		}
		
		function decimalToFraction($decimalInch) {
			if ($decimalInch < .03125) {
				return "";
			}
		
			// separate out decimal from whole number
			$pWhole = explode('.',$decimalInch); 
			$pWhole = $pWhole[0];
			$pDecimal = $decimalInch-$pWhole; 
		
			// create list of numbers to round to
			$fractionOption = array();
			$fractionOption['0/16'] = 0;
			$fractionOption['1/16'] = 0.0625;
			$fractionOption['1/8'] = 0.125;
			$fractionOption['3/16'] = 0.1875;
			$fractionOption['1/4'] = 0.25;
			$fractionOption['5/16'] = 0.3125;
			$fractionOption['3/8'] = 0.375;
			$fractionOption['7/16'] = 0.4375;
			$fractionOption['1/2'] = 0.5;
			$fractionOption['9/16'] = 0.5625;
			$fractionOption['5/8'] = 0.625;
			$fractionOption['11/16'] = 0.6875;
			$fractionOption['3/4'] = 0.75;
			$fractionOption['13/16'] = 0.8125;
			$fractionOption['7/8'] = 0.875;
			$fractionOption['15/16'] = 0.9375;
			$fractionOption['16/16'] = 1;
			
			// find closest number to round to
			foreach ($fractionOption as $k => $v) {
				 $tmpV[$k] = abs($pDecimal - $v);
			}
			asort($tmpV,SORT_NUMERIC);
			list($inch, $decimal) = each($tmpV);
			
			// clean up for edge values
			$inch = ($inch == '0/16') ? '': $inch;
			// round off to nearest whole number if 16/16
			if ($inch == '16/16') {
				$inch = '';
				$pWhole++;
			}
			
			// strip inch and return fraction formatted in css
			$finalText = "";
			if ($inch != '') { 
				$tFrac = explode('/',$inch);
				$fraction = "<span style=\"font-size: 75%; vertical-align: .5ex;\">$tFrac[0]</span>&#8260;<span style=\"font-size: 75%;\">$tFrac[1]</span>";
				$finalText .= $pWhole.' '.$fraction."\" ";	
			}
			else {
				$fraction = ''; 
				$finalText .= $pWhole."\" ";	
			}
			
			return $finalText;
		}
		
		function getExactInDimenAsText() {
			$aveFeetDimen = $this->aveInDimen/12;
		
			$value = "";
			$this->extractFeetFromInches();
			if (false) { //($this->isPanoramic() && $this->feetHeight >= 2 && $this->feetWidth >= 3) || (!$this->isPanoramic() && $this->feetHeight >= 3 && $this->feetWidth >= 3)) {
				$heightInches = $this->inHeight - (12 * $this->feetHeight);
				$widthInches = $this->inWidth - (12 * $this->feetWidth);
				$value .= $this->feetHeight." ft ".$this->decimalToFraction($heightInches)." x ".$this->feetWidth." ft ".$this->decimalToFraction($widthInches);
			} else {
				$value .= $this->decimalToFraction($this->inHeight)." x ".$this->decimalToFraction($this->inWidth);
			}			
			
			return $value;
		}
		
		function isPanoramic() {
			if (($this->relHeight * 2) < $this->relWidth) {
				return true;
			} else {
				return false;
			}
		}
		
		function extractFeetFromInches() {
			$inchesWidth = $this->inWidth;
			$inchesHeight = $this->inHeight;
			while ($inchesWidth >= 12) {
				$inchesWidth -= 12;
				$this->feetWidth += 1;
			}
			while ($inchesHeight >= 12) {
				$inchesHeight -= 12;
				$this->feetHeight += 1;
			}
		}
		
		function adjustLowPrices ($price) {
			if ($price < 70) {
				$price = ($price * .93) + 9.8;
			} else if ($price < 140) {
				$price = ($price * .97) + 4.2;
			}
			return $price;
		}
		
		function toHash() {
			$val = "";
			if ($this->landscapeFormat) {
				$val .= ((string)"".$this->imgID."|".$this->relWidth."|".$this->relHeight."|".$this->tier['id']."|".$this->inHeight."|".$this->landscapeFormat);
			} else {
				$val .= ((string)"".$this->imgID."|".$this->relWidth."|".$this->relHeight."|".$this->tier['id']."|".$this->inWidth."|".$this->landscapeFormat);
			}
			return $val;
		}
		
		function setNumInCart($num) {
			if ($num < 0) {
				$num = 0;
			}
			$this->numInCart = $num;
		}
		
		function incrementCart() {
			$this->numInCart++;
		}
	}
?>
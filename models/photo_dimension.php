<?php
class PhotoDimension extends AppModel {
     var $name = 'PhotoDimension';
     var $belongsTo = 'OldPhoto';

     public function addDimension($imageId, $w, $h, $baseHW) {
          $data['PhotoDimension']['allimage_id'] = $imageId;
          $data['PhotoDimension']['relWidth'] = $w;
          $data['PhotoDimension']['relHeight'] = $h;
          if ($w >= $h) {
               $data['PhotoDimension']['inHeight'] = $baseHW;
               $data['PhotoDimension']['inWidth'] = ($data['PhotoDimension']['inHeight'] * $data['PhotoDimension']['relWidth']) / $data['PhotoDimension']['relHeight'];
          } else {
                $data['PhotoDimension']['inWidth'] = $baseHW;
                $data['PhotoDimension']['inHeight'] = ($data['PhotoDimension']['inWidth'] * $data['PhotoDimension']['relHeight']) / $data['PhotoDimension']['relWidth'];
          }

          $this->create();
          return $this->save($data);
     }

     public function getPricesForImage($id) {
          $photoDimensions = $this->find('all', array(
               'conditions' => array('PhotoDimension.allimage_id' => $id),
              'order' => array('PhotoDimension.id')
          ));

          $oldPhoto = $this->OldPhoto->findById($id);

          $prices = array();
          foreach ($photoDimensions as $dimension) {
               $goUpPer;
               $editionOf;
               if ($oldPhoto['OldPhoto']['format'] == 'landscape' || $oldPhoto['OldPhoto']['format'] == 'square') {
                    if ($dimension['PhotoDimension']['inHeight'] <= 8) {
                         continue;
                         $goUpPer = 10;
                         $editionOf = 250;
                    } else if ($dimension['PhotoDimension']['inHeight'] <= 16) {
                         $goUpPer = 10;
                         $editionOf = 250;
                    } else {
                         $goUpPer = 10;
                         $editionOf = 250;
                    }
               } else if ($oldPhoto['OldPhoto']['format'] == 'panoramic') {
                    if ($dimension['PhotoDimension']['inHeight'] <= 8) {
                         continue;
                         $goUpPer = 10;
                         $editionOf = 250;
                    } else if ($dimension['PhotoDimension']['inHeight'] <= 10) {
                         $goUpPer = 10;
                         $editionOf = 250;
                    } else {
                         $goUpPer = 10;
                         $editionOf = 250;
                    }
               } else if ($oldPhoto['OldPhoto']['format'] == 'portrait') {
                    if ($dimension['PhotoDimension']['inWidth'] <= 8) {
                         continue;
                         $goUpPer = 10;
                         $editionOf = 250;
                    } else if ($dimension['PhotoDimension']['inWidth'] <= 16) {
                         $goUpPer = 10;
                         $editionOf = 250;
                    } else {
                         $goUpPer = 10;
                         $editionOf = 250;
                    }
               }


               $prices[$dimension['PhotoDimension']['id']]['editionOf'] = $editionOf;
               $prices[$dimension['PhotoDimension']['id']]['goUpPer'] = $goUpPer;
               $prices[$dimension['PhotoDimension']['id']]['price'] = "$".$this->getPrice($dimension['PhotoDimension']['id']);
               $aluminumPrice = $this->getAlumPrice($dimension['PhotoDimension']['id']);
               $prices[$dimension['PhotoDimension']['id']]['alumPrice'] = "$".$aluminumPrice;
               $aluma_25 = $this->getXPrintCost($aluminumPrice, 25, $goUpPer);
               $totals_25_low = '$'.$aluma_25[2];
               $totals_25_high = '$'.$aluma_25[3];
               $aluma_50 = $this->getXPrintCost($aluminumPrice, 50, $goUpPer);
               $totals_50_low = '$'.$aluma_50[2];
               $totals_50_high = '$'.$aluma_50[3];
               $aluma_100 = $this->getXPrintCost($aluminumPrice, 100, $goUpPer);
               $totals_100_low = '$'.$aluma_100[2];
               $totals_100_high = '$'.$aluma_100[3];
               $aluma_125 = $this->getXPrintCost($aluminumPrice, 125, $goUpPer);
               $totals_125_low = '$'.$aluma_125[2];
               $totals_125_high = '$'.$aluma_125[3];
               $aluma_175 = $this->getXPrintCost($aluminumPrice, 175, $goUpPer);
               $totals_175_low = '$'.$aluma_175[2];
               $totals_175_high = '$'.$aluma_175[3];
               $aluma_250 = $this->getXPrintCost($aluminumPrice, 250, $goUpPer);
               $totals_250_low = '$'.$aluma_250[2];
               $totals_250_high = '$'.$aluma_250[3];
               $prices[$dimension['PhotoDimension']['id']]['totals_25_low'] = $totals_25_low;
               $prices[$dimension['PhotoDimension']['id']]['totals_25_high'] = $totals_25_high;
               $prices[$dimension['PhotoDimension']['id']]['totals_50_low'] = $totals_50_low;
               $prices[$dimension['PhotoDimension']['id']]['totals_50_high'] = $totals_50_high;
               $prices[$dimension['PhotoDimension']['id']]['totals_100_low'] = $totals_100_low;
               $prices[$dimension['PhotoDimension']['id']]['totals_100_high'] = $totals_100_high;
               $prices[$dimension['PhotoDimension']['id']]['totals_125_low'] = $totals_125_low;
               $prices[$dimension['PhotoDimension']['id']]['totals_125_high'] = $totals_125_high;
               $prices[$dimension['PhotoDimension']['id']]['totals_175_low'] = $totals_175_low;
               $prices[$dimension['PhotoDimension']['id']]['totals_175_high'] = $totals_175_high;
               $prices[$dimension['PhotoDimension']['id']]['totals_250_low'] = $totals_250_low;
               $prices[$dimension['PhotoDimension']['id']]['totals_250_high'] = $totals_250_high;
               $prices[$dimension['PhotoDimension']['id']]['alum_print_25_low'] = "$".$aluma_25[0];
               $prices[$dimension['PhotoDimension']['id']]['alum_print_50_low'] = "$".$aluma_50[0];
               $prices[$dimension['PhotoDimension']['id']]['alum_print_100_low'] = "$".$aluma_100[0];
               $prices[$dimension['PhotoDimension']['id']]['alum_print_125_low'] = "$".$aluma_125[0];
               $prices[$dimension['PhotoDimension']['id']]['alum_print_175_low'] = "$".$aluma_175[0];
               $prices[$dimension['PhotoDimension']['id']]['alum_print_250_low'] = "$".$aluma_250[0];
               $prices[$dimension['PhotoDimension']['id']]['alum_print_25_high'] = "$".$aluma_25[1];
               $prices[$dimension['PhotoDimension']['id']]['alum_print_50_high'] = "$".$aluma_50[1];
               $prices[$dimension['PhotoDimension']['id']]['alum_print_100_high'] = "$".$aluma_100[1];
               $prices[$dimension['PhotoDimension']['id']]['alum_print_125_high'] = "$".$aluma_125[1];
               $prices[$dimension['PhotoDimension']['id']]['alum_print_175_high'] = "$".$aluma_175[1];
               $prices[$dimension['PhotoDimension']['id']]['alum_print_250_high'] = "$".$aluma_250[1];
               $prices[$dimension['PhotoDimension']['id']]['textSize'] = $this->getInDimenAsText($dimension['PhotoDimension']['id']);
          }

          return $prices;
     }

     protected function getAlumPrice($id) {
          $currDimesion = $this->findById($id);

          $price = $this->getPrice($id);

          App::import('Model', 'ImageWizardQuote');
          $imgWzrd = new ImageWizardQuote();

          $imageWizardsCost = $imgWzrd->getQuote(round($currDimesion['PhotoDimension']['inWidth'], 0), round($currDimesion['PhotoDimension']['inHeight'], 0));

          // testing code
          /*$costArr = array();
          for ($i = 1; $i <= 250; $i++) {
               $cost = $this->getXPrintCost($imageWizardsCost, $i);
               $costArr[$i] = $cost;
          }*/ 

          $totalAlumCost = $this->prettify_price($price + $imageWizardsCost + (.1 * $imageWizardsCost) + 100);

          return $totalAlumCost;
     }

     protected function getXPrintCost($firstPrintCost, $x, $every = 10) {
          $totalLow = 0;
          $totalHigh = 0;

          $endPrintCostLow = $firstPrintCost;
          $endPrintCostHigh = $firstPrintCost;
          $lowPercent = 1.10;
          $highPercent = 1.15;

          for ($i = 1; $i < $x; $i++) {
               $totalLow += $endPrintCostLow;
               $totalHigh += $endPrintCostHigh;
               if (($i % $every) == 0) {
                    $endPrintCostLow = $endPrintCostLow * $lowPercent;
                    $endPrintCostHigh = $endPrintCostHigh * $highPercent;
               }
          }

          return array($this->prettify_price($endPrintCostLow), $this->prettify_price($endPrintCostHigh), $this->prettify_price($totalLow), $this->prettify_price($totalHigh));
     }

     protected function prettify_price($price) {
          //return $price;

          if ($price < 100) {

          } elseif ($price >= 100 && $price < 300) {
               $price = $this->round_to_nearest($price, 5);
          } elseif ($price >= 300 && $price < 1000) {
               $price = $this->round_to_nearest($price, 15);
          } elseif ($price >= 1000) {
               $price = $this->round_to_nearest($price, 25);
          }

          return $price;
     }

     protected function round_to_nearest($value, $toNearest) {
          return round($value / $toNearest)*$toNearest;
     }

     protected function getPrice($id) {
          $currDimesion = $this->findById($id);

          $myPrice = 0;

          if (($currDimesion['PhotoDimension']['inHeight'] * 2) < $currDimesion['PhotoDimension']['inWidth']) { // panoramic format
               if ($currDimesion['PhotoDimension']['inHeight'] >= 10 && $currDimesion['PhotoDimension']['inHeight'] < 16) {
                    $myPrice = 135;
               } else if ($currDimesion['PhotoDimension']['inHeight'] >= 16 && $currDimesion['PhotoDimension']['inHeight'] < 22) {
                    $myPrice = 350;
               } else if ($currDimesion['PhotoDimension']['inHeight'] >= 22 && $currDimesion['PhotoDimension']['inHeight'] < 29) {
                    $myPrice = 495;
               } else if ($currDimesion['PhotoDimension']['inHeight'] >= 29) {
                    $myPrice = 750;
               }
          } else if ($currDimesion['PhotoDimension']['inHeight'] >= $currDimesion['PhotoDimension']['inWidth']) { // portrait format
               if ($currDimesion['PhotoDimension']['inWidth'] >= 5 && $currDimesion['PhotoDimension']['inWidth'] < 8) {
                    $myPrice = 35;
               } else if ($currDimesion['PhotoDimension']['inWidth'] >= 8 && $currDimesion['PhotoDimension']['inWidth'] < 11) {
                    $myPrice = 70;
               } else if ($currDimesion['PhotoDimension']['inWidth'] >= 11 && $currDimesion['PhotoDimension']['inWidth'] < 16) {
                    $myPrice = 115;
               } else if ($currDimesion['PhotoDimension']['inWidth'] >= 16 && $currDimesion['PhotoDimension']['inWidth'] < 20) {
                    $myPrice = 175;
               } else if ($currDimesion['PhotoDimension']['inWidth'] >= 20 && $currDimesion['PhotoDimension']['inWidth'] < 24) {
                    $myPrice = 255;
               } else if ($currDimesion['PhotoDimension']['inWidth'] >= 24 && $currDimesion['PhotoDimension']['inWidth'] < 30) {
                    $myPrice = 360;
               } else if ($currDimesion['PhotoDimension']['inWidth'] >= 30 && $currDimesion['PhotoDimension']['inWidth'] < 40) {
                    $myPrice = 495;
               } else if ($currDimesion['PhotoDimension']['inWidth'] >= 40 && $currDimesion['PhotoDimension']['inWidth'] < 48) {
                    $myPrice = 665;
               } else if ($currDimesion['PhotoDimension']['inWidth'] >= 48) {
                    $myPrice = 875;
               }
          } else { // landscape format
               if ($currDimesion['PhotoDimension']['inHeight'] >= 5 && $currDimesion['PhotoDimension']['inHeight'] < 8) {
                    $myPrice = 35;
               } else if ($currDimesion['PhotoDimension']['inHeight'] >= 8 && $currDimesion['PhotoDimension']['inHeight'] < 11) {
                    $myPrice = 70;
               } else if ($currDimesion['PhotoDimension']['inHeight'] >= 11 && $currDimesion['PhotoDimension']['inHeight'] < 16) {
                    $myPrice = 115;
               } else if ($currDimesion['PhotoDimension']['inHeight'] >= 16 && $currDimesion['PhotoDimension']['inHeight'] < 20) {
                    $myPrice = 175;
               } else if ($currDimesion['PhotoDimension']['inHeight'] >= 20 && $currDimesion['PhotoDimension']['inHeight'] < 24) {
                    $myPrice = 255;
               } else if ($currDimesion['PhotoDimension']['inHeight'] >= 24 && $currDimesion['PhotoDimension']['inHeight'] < 30) {
                    $myPrice = 360;
               } else if ($currDimesion['PhotoDimension']['inHeight'] >= 30 && $currDimesion['PhotoDimension']['inHeight'] < 40) {
                    $myPrice = 495;
               } else if ($currDimesion['PhotoDimension']['inHeight'] >= 40 && $currDimesion['PhotoDimension']['inHeight'] < 48) {
                    $myPrice = 665;
               } else if ($currDimesion['PhotoDimension']['inHeight'] >= 48) {
                    $myPrice = 875;
               }
          }

          return $myPrice;
     }

     public function calcAveDimen($height, $width) {
         return ($height + $width) / 2;
     }

     protected function getLinearPrice($length) {
         return ((42.857 * $length) - 435.71);
     }

     function getInDimenAsText($id) {
          $data = $this->findById($id);

          $value = "";
          $feetDimens = $this->extractFeetFromInches($data['PhotoDimension']['inWidth'], $data['PhotoDimension']['inHeight']);
          /*if (($this->isPanoramic($data['PhotoDimension']['relWidth'], $data['PhotoDimension']['relHeight']) && $feetDimens['feetHeight'] >= 2 && $feetDimens['feetWidth'] >= 3) || (!$this->isPanoramic($data['PhotoDimension']['relWidth'], $data['PhotoDimension']['relHeight']) && $feetDimens['feetHeight'] >= 3 && $feetDimens['feetWidth'] >= 3)) {
               $heightInches = $data['PhotoDimension']['inHeight'] - (12 * $feetDimens['feetHeight']);
               $widthInches = $data['PhotoDimension']['inWidth'] - (12 * $feetDimens['feetWidth']);
               $value .= $feetDimens['feetHeight']." ft ".(round($heightInches, 0)?round($heightInches, 0)."\"":"")." x ".$feetDimens['feetWidth']." ft ".(round($widthInches, 0)?round($widthInches, 0)."\"":"");
          } else {*/
               $value .= round($data['PhotoDimension']['inHeight'], 0)."\" x ".round($data['PhotoDimension']['inWidth'], 0)."\"";
          //}

          return $value;
     }

     protected function isPanoramic($relWidth, $relHeight) {
          if (($relHeight * 2) < $relWidth) {
               return true;
          } else {
               return false;
          }
     }

     protected function extractFeetFromInches($inchesWidth, $inchesHeight) {
          $feetWidth = 0;
          $feetHeight = 0;
          while ($inchesWidth >= 12) {
               $inchesWidth -= 12;
               $feetWidth += 1;
          }
          while ($inchesHeight >= 12) {
               $inchesHeight -= 12;
               $feetHeight += 1;
          }

          $returnVal = array();
          $returnVal['feetWidth'] = $feetWidth;
          $returnVal['feetHeight'] = $feetHeight;

          return $returnVal;
     }
     
}
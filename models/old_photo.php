<?php
/*
class OldPhoto extends AppModel {
     var $name = 'OldPhoto';
     var $hasMany = 'PhotoDimension';
     var $useDbConfig = 'old_photos';
     var $useTable = 'allimages';
     public $standardFormat = array('11', '16', '20', '24', '30', '40', '48');
     public $panoramic = array('10', '16', '22', '29');

     public function recalcPricesForImage($id) {
          $this->PhotoDimension->deleteAll(array(''));

          $image = $this->findById($id);

          // get image dimensions
          $dimensions = getimagesize ("photos/large/".$image['OldPhoto']['title']);
          $dimensions[0] = $dimensions[0]-20;
          $dimensions[1] = $dimensions[1]-20;

          $tier = $image['OldPhoto']['tier'];

          // get image format
          $format = $image['OldPhoto']['format'];

          // get images sizes
          $sizes = split(",",$image['OldPhoto']['availSizes']);

          for ($i = 0; $i < sizeof($sizes); $i++) {
               if ($format == "portrait") {
                    $this->PhotoDimension->addDimension($image['OldPhoto']['id'], $dimensions[0], $dimensions[1], $sizes[$i]);
               } else {
                    $this->PhotoDimension->addDimension($image['OldPhoto']['id'], $dimensions[0], $dimensions[1], $sizes[$i]);
               }
          }

          return $this->PhotoDimension->getPricesForImage($id);
     }

     // $this->data['OldPhoto']
     public function replicatePriceCal($id) {
          $this->data = $this->findById($id);
          $img_src = $this->data['OldPhoto']['title'];
          
           // get image dimensions
          $largeDestination = str_replace(DS, '/', realpath('../../app/webroot/photos/large/') . "/");
          $dimensions = getimagesize ($largeDestination.$img_src);
          $dimensions[0] = $dimensions[0]-20;
          $dimensions[1] = $dimensions[1]-20;

          // get image format
          $format = $this->data['OldPhoto']['format'];

          // get image tier
          $tier = $this->data['OldPhoto']['tier'];
           
          // get images sizes
          $sizes = split(",",$this->data['OldPhoto']['availSizes']);

          App::import('Vendor', 'Dimension');
          $imageDimens = array();
          for ($i = 0; $i < sizeof($sizes); $i++) {
              if ($format == "portrait") {
                      $newDimension = new Dimension($this->data['OldPhoto']['id'], ($dimensions[0]), ($dimensions[1]), $tier, $sizes[$i], 0);
                      $newDimension->calcBaseWidthHeight();
                      $newDimension->calcAveDimen();
                      $imageDimens[$i] = $newDimension;
              } else {
                      $newDimension = new Dimension($this->data['OldPhoto']['id'], ($dimensions[0]), ($dimensions[1]), $tier, $sizes[$i], 1);
                      $newDimension->calcBaseWidthHeight();
                      $newDimension->calcAveDimen();
                      $imageDimens[$i] = $newDimension;
              }
          }

          // save dimensions array to database
          $savetodb = serialize($imageDimens);
          $this->data['OldPhoto']['sizePriceArray'] = $savetodb;
          $this->save($this->data);
     }
}

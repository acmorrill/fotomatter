<?php
class ImageWizardQuote extends AppModel {
     var $name = 'ImageWizardQuote';

     public function getQuote($width, $height) {
          $prevQuote = $this->find('first', array(
               'conditions' => array('ImageWizardQuote.width' => $width, 'ImageWizardQuote.height' => $height)
          ));


          if (!empty($prevQuote)) {
               return $prevQuote['ImageWizardQuote']['quote'];
          } else {
               $ch = curl_init();

               $url = 'http://imagewizards.net/assets/php/quickQuote.php?height='.$height.'&width='.$width.'&quantity=1&finish=white_hg&framing=fte&packaging=crate';

               curl_setopt($ch, CURLOPT_URL, $url);
               curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
               $quoteVal = curl_exec($ch);
               curl_close($ch);

               $quoteVal = substr($quoteVal, 28);
               $quoteVal = substr($quoteVal, 0, -2);

               $data['ImageWizardQuote']['width'] = $width;
               $data['ImageWizardQuote']['height'] = $height;
               $data['ImageWizardQuote']['quote'] = $quoteVal;
               $this->save($data);

               return $quoteVal;
          }
     }

}
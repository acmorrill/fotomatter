<?php
class ImageWizardsComponent extends Object {

     function getQuote() {
            $ch = curl_init();

            $url = 'http://imagewizards.net/assets/php/quickQuote.php?height=10&width=30&quantity=1&finish=white_hg&framing=fte&packaging=crate';

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $returnVal = curl_exec($ch);
            curl_close($ch);

            return $returnVal;
     }
}

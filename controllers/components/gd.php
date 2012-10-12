<?php
class GdComponent extends Object {

     public function addBorder($add, $border = 2, $rgb = array('205', '205', '205')) {
          //$add="images/a.jpg";
          //$add2="images/1.jpg"; // Remove comment if a new image is to be created
          //$border=2; // Change the value to adjust width
          $im=ImageCreateFromJpeg($add);
          $width=ImageSx($im);
          $height=ImageSy($im);


          $img_adj_width=$width+(2*$border);
          $img_adj_height=$height+(2*$border);
          $newimage=imagecreatetruecolor($img_adj_width,$img_adj_height);

          $border_color = imagecolorallocate($newimage, $rgb[0], $rgb[1], $rgb[2]);
          imagefilledrectangle($newimage,0,0,$img_adj_width,$img_adj_height,$border_color);

          imageCopyResized($newimage,$im,$border,$border,0,0,$width,$height,$width,$height);
          ImageJpeg($newimage,$add,100); // change here to $add2 if a new image is to be created
          chmod("$add",0666); // change here to $add2 if a new image is to be created
     }
	 
	 //public function 

}
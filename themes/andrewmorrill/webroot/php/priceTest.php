<?php 
	$start = 35;
	$startIncreaseRate = 35;
	$startIncreaseRateRate = 10;
	$startIncreaseRateRateRate = 5;
	
	$sizeArray = array(.2430, .5554, 1.068, 2.221, 3.332, 5, 8.333, 13.88, 24);
	
	print("Price\t\tMyPrice\t\tMarkup\t\tPercentMarkup<br/>");
	for($i = 0; $i < 9; $i++) {
		$myPrice = round($sizeArray[$i]*15, 2);
		$markup = $start-$myPrice;
		$margin = round($markup/$myPrice, 2);
		print ($start."\t\t".$myPrice."\t\t".$markup."\t\t".($margin*100)."%<br/>");
		$start += $startIncreaseRate;
		$startIncreaseRate += $startIncreaseRateRate;
		$startIncreaseRateRate += $startIncreaseRateRateRate;
	}
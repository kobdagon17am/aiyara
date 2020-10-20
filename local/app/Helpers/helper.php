<?php

if (! function_exists('pricePro')) {
    function pricePro($cat_id, $product_id, $sPrice){
		if (\Cache::has('Promotion'))
		{
			$tPrice	= $nPrice = $sPrice;
			$sRow 	= \Cache::get('Promotion');
			if($sRow){
				if(isset($sRow['w'])){
					if($sRow['w']['T']=='B'){
						$nPrice = $sPrice - $sRow['w']['D'];
					}else{
						$nPrice = $sPrice-($sPrice*($sRow['w']['D']/100));
					}
					if($nPrice < $tPrice) $tPrice = $nPrice;
				}
				if(isset($sRow['c'])){
					if (array_key_exists($cat_id, $sRow['c'])){
						if($sRow['c'][$cat_id]['T']=='B'){
							$nPrice = $sPrice - $sRow['c'][$cat_id]['D'];
						}else{
							$nPrice = $sPrice-($sPrice*($sRow['c'][$cat_id]['D']/100));
						}
					}
					if($nPrice < $tPrice) $tPrice = $nPrice;
				}
				if(isset($sRow['p'])){
					if (array_key_exists($product_id, $sRow['p'])){
						if($sRow['p'][$product_id]['T']=='B'){
							$nPrice = $sPrice - $sRow['p'][$product_id]['D'];
						}else{
							$nPrice = $sPrice-($sPrice*($sRow['p'][$product_id]['D']/100));
						}
					}
					if($nPrice < $tPrice) $tPrice = $nPrice;
				}
			}
			return ($tPrice==$sPrice)?null:$tPrice;
		}
		return null;
    }
}
@php
@include(app_path() . '\Models\MyFunction.php');
@endphp
<style>
    body{
     font-family: "THSarabunNew";
     font-size: 20px;
     font-weight: bold;
     line-height: 14px !important;
    }
    @charset "utf-8";
</style>

<?php 

 $data = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
 $i=1;
 foreach ($data as $key => $value) {
 	echo $i.') '.$value."<br>";
 	if ( $i % 6 == 0 ){
        ?><div style="page-break-before:always;"> </div><?php
      }
	$i++;
 }
 
?>

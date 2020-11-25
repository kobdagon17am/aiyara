<center>
		<?php 
		
            $qrcode = "http://localhost/kunisit/nisit-detail/".$_REQUEST['data'];
            // $qrcode = "https://kunisit.tee.posorange.com/nisit-detail/".$_REQUEST['data'];

	        $_REQUEST['size'] = 40 ;

		    $PNG_TEMP_DIR = 'temp'.DIRECTORY_SEPARATOR;
		    
		    //html PNG location prefix
		    $PNG_WEB_DIR = 'temp/';

		    include "qrlib.php";    
		    //ofcourse we need rights to create temp dir
		    if (!file_exists($PNG_TEMP_DIR))
		        mkdir($PNG_TEMP_DIR);
		    
		    $errorCorrectionLevel = 'L';
		    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
		        $errorCorrectionLevel = $_REQUEST['level'];    
		    $matrixPointSize = 4;
		    if (isset($_REQUEST['size']))
		        $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);
		    //it's very important!
		    if (trim($qrcode) == '')
		        die('data cannot be empty!');
		    // user data
		    $filename = $PNG_TEMP_DIR.'test'.md5($qrcode.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
		    QRcode::png($qrcode, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
		    //display generated file
		    echo '<img src="../asset/qrcode/'.$PNG_WEB_DIR.basename($filename).'" />';  
		    


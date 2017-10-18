<?php

error_reporting(E_ALL);
set_time_limit(0);

date_default_timezone_set('Europe/London');

?>
<html>
<head>
<meta http-equiv="refresh" content="60">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>大于60、小于100的级的账号</title>

</head>
<body>

<h1>大于60、小于100的级的账号</h1>
<?php

/** Include path **/
//set_include_path(get_include_path() . PATH_SEPARATOR . '../../../Classes/');

/** PHPExcel_IOFactory */
include 'Classes/PHPExcel/IOFactory.php';


$inputFileType = 'CSV';

$dir="D:\\XXT_Lan_2.5.5.0\\data\\running60\\";
$files=scandir($dir);
$arr1 = array();
for($i=0;$i<count($files);++$i){ 
	if ($files[$i] <> '.' and $files[$i] <> '..' and stripos($files[$i],".csv")>1){
		echo $files[$i].'<br />'; 	
		$acct_file = $dir.$files[$i];
		
		array_push($arr1,$acct_file);
	}
}
//echo count($arr1);
if (count($arr1) >0) {
	

$inputFileNames = $arr1;

$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$inputFileName = array_shift($inputFileNames);
//echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' into WorkSheet #1 using IOFactory with a defined reader type of ',$inputFileType,'<br />';
$objPHPExcel = $objReader->load($inputFileName);
$objPHPExcel->getActiveSheet()->setTitle(pathinfo($inputFileName,PATHINFO_BASENAME));
foreach($inputFileNames as $sheet => $inputFileName) {
	//echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' into WorkSheet #',($sheet+2),' using IOFactory with a defined reader type of ',$inputFileType,'<br />';
	$objReader->setSheetIndex($sheet+1);
	$objReader->loadIntoExisting($inputFileName,$objPHPExcel);
	$objPHPExcel->getActiveSheet()->setTitle(pathinfo($inputFileName,PATHINFO_BASENAME));
}

echo '<hr />';

echo $objPHPExcel->getSheetCount(),' 个文件',(($objPHPExcel->getSheetCount() == 1) ? '' : ''),' 装载<br /><br />';
$loadedSheetNames = $objPHPExcel->getSheetNames();
foreach($loadedSheetNames as $sheetIndex => $loadedSheetName) {
	//echo '<b>Worksheet #',$sheetIndex,' -> ',$loadedSheetName,'</b><br />';
	echo '<b>文件',$sheetIndex+1,' -> ',$loadedSheetName,'</b><br />';
	$objPHPExcel->setActiveSheetIndexByName($loadedSheetName);
	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	
	//var_dump($sheetData);
	if (count($sheetData[1]) >1){
	$xuhao =1;
	for($i=1;$i<=count($sheetData);$i++){
		echo $xuhao.'.	'.$sheetData[$i]['A'].','.$sheetData[$i]['B'];
		echo '<br />';
		$xuhao =$xuhao +1;
	}
	}	
	echo '<br /><br />';
}
}else{
	echo "没有待运行账号文件...<br />";
}

?>
<br/>
<br/>
<a href="javascript:history.go(-1);"  title="返回">返回</a></br></br>
<br/>
<input type="button" value="刷新当前页面" onclick="javascript:location.reload();" />
</body>
</html>
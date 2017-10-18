<?php

error_reporting(E_ALL);
set_time_limit(0);

date_default_timezone_set('Europe/London');

?>
<html>
<head>
<meta http-equiv="refresh" content="60">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>正在手机上跑的账号，或者正在被手机占用的账号</title>

</head>
<body>

<h1>正在手机上跑的账号，或者正在被手机占用的账号</h1>
<?php

/** Include path **/
//set_include_path(get_include_path() . PATH_SEPARATOR . '../../../Classes/');

/** PHPExcel_IOFactory */
include 'Classes/PHPExcel/IOFactory.php';

class MyDB extends SQLite3
    {

        function __construct()
        {
            $this->open('D:\XXT_Lan_2.5.5.0\data\foo2.db');
        }
    }
    $db = new MyDB();
    if (! $db) {
        echo $db->lastErrorMsg();
    } else {
        //echo "Opened database successfully\n";
		echo "<br/>";
    }


$inputFileType = 'CSV';

$dir="D:\\XXT_Lan_2.5.5.0\\data\\is_running\\";
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
		//echo $xuhao.'.	'.$sheetData[$i]['A'].','.$sheetData[$i]['B'].','.$sheetData[$i]['C'].','.$sheetData[$i]['D'].','.$sheetData[$i]['E'];
		$sql1 = "insert into running_acct(acct_name,log_device_name,log_device_ip,log_time) 
		select '{$sheetData[$i]['A']}','{$sheetData[$i]['C']}','{$sheetData[$i]['D']}','{$sheetData[$i]['E']}'  ";
        //echo '<br />';
		//echo $sql1;
		$result2 = $db->exec($sql1);
		//echo '<br />';
		$xuhao =$xuhao +1;
	}
	}
 
	echo '<br /><br />';
}
}else{
	echo "没有待运行账号文件...<br />";
}

$sql1 = "delete from  mobile_acct;";
        //echo '<br />';
$db->exec($sql1);


$sql1 = "insert into mobile_acct (log_device_ip,acct_name,log_time) 
select bb.log_device_ip as log_device_ip, bb.acct_name as acct_name,bb.log_time as log_time from (
select b.acct_name,b.log_time,a.log_device_ip from running_acct a inner join 
(
select acct_name,max(log_time) as log_time from running_acct 
group by acct_name
) b on a.acct_name= b.acct_name and a.log_time = b.log_time
) aa inner join 
(
select b.log_device_ip,b.log_time,a.acct_name from running_acct a inner join (
select log_device_ip,max(log_time) as log_time from running_acct 
group by log_device_ip
) b on a.log_device_ip= b.log_device_ip and a.log_time = b.log_time
) bb
 on aa.acct_name = bb.acct_name and aa.log_device_ip = bb.log_device_ip";
        //echo '<br />';
//echo $sql1;	

$db->exec($sql1);

$result = $db->query("select * from mobile_acct;");
/**
var_dump();
*/
echo "<TABLE border='1' bgcolor='yellow'>";
while($row= $result->fetchArray()){
	$acct_name= $row['acct_name'];
	$log_device_ip= $row['log_device_ip'];
	$log_time= $row['log_time'];	
	$v_csv_data=$acct_name;
	echo "<tr>";
	echo "<td>";	
	
	echo "<input type='checkbox' name='hobby[]' value='$v_csv_data'/>";
	echo "</td>";
	echo "<td>";		
	echo "$log_device_ip";
	echo "</td>";
	echo "<td>";
	echo "$acct_name";
	echo "</td>";
	echo "<td>";
	echo "$log_time";
	echo "</td>";	
	echo "</tr>";
}	
echo "</TABLE>";


?>
<br/>
<br/>
<a href="javascript:history.go(-1);"  title="返回">返回</a></br></br>
<br/>
<input type="button" value="刷新当前页面" onclick="javascript:location.reload();" />
</body>
</html>
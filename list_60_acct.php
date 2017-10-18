<?php

error_reporting(E_ALL);
set_time_limit(0);

date_default_timezone_set('Europe/London');

?>
<html>
<head>
<meta http-equiv="refresh" content="60">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>跑到60级的账号列表</title>
<script>
function check_all(obj,cName) 
{ 
    var checkboxs = document.getElementsByName(cName); 
    for(var i=0;i<checkboxs.length;i++){checkboxs[i].checked = obj.checked;} 
} 
</script>
</head>
<body>
<h1>跑到60级的账号列表</h1>
  <form id="myform"  enctype="multipart/form-data" action="download60-excel.php" method="post">
  <p><input type="checkbox" name="all" onclick="check_all(this,'hobby[]')" />全选/全不选</p> 

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

$dir="D:\\XXT_Lan_2.5.5.0\\data\\complete\\";
$files=scandir($dir);
$arr1 = array();
for($i=0;$i<count($files);++$i){ 
	if ($files[$i] <> '.' and $files[$i] <> '..' and stripos($files[$i],".csv")>1){
		//echo $files[$i].'<br />'; 	
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

//echo '<hr />';

//echo $objPHPExcel->getSheetCount(),' 个文件',(($objPHPExcel->getSheetCount() == 1) ? '' : ''),' 装载<br /><br />';
$loadedSheetNames = $objPHPExcel->getSheetNames();
foreach($loadedSheetNames as $sheetIndex => $loadedSheetName) {
	//echo '<b>Worksheet #',$sheetIndex,' -> ',$loadedSheetName,'</b><br />';
	//echo '<b>文件',$sheetIndex+1,' -> ',$loadedSheetName,'</b><br />';
	$objPHPExcel->setActiveSheetIndexByName($loadedSheetName);
	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	
	//var_dump($sheetData);
	if (count($sheetData[1]) >1){
	$xuhao =1;
	for($i=1;$i<=count($sheetData);$i++){
		//echo $xuhao.'.	'.$sheetData[$i]['A'].','.$sheetData[$i]['B'].','.$sheetData[$i]['C'].','.$sheetData[$i]['D'].','.$sheetData[$i]['E'];
		$sql1 = "insert into acct_info_60(acct_name,log_time) 
		select '{$sheetData[$i]['A']}','{$sheetData[$i]['D']}'  ";
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



$result = $db->query("select a.acct_name,a.log_time, b.user_pwd,b.game_server from acct_info_60 a inner join excel_acct_info b on a.acct_name = b.acct_user order by a.log_time desc ; ");
/**
var_dump();
*/
$xuhao =1;
echo "<TABLE border='1' bgcolor='yellow'>";
while($row= $result->fetchArray()){
	$acct_name= $row['acct_name'];	
	$user_pwd= $row['user_pwd'];	
	$log_time= $row['log_time'];	
	$v_csv_data=$acct_name.','.$user_pwd;
	echo "<tr>";
	echo "<td>";	
	
	echo "<input type='checkbox' name='hobby[]' value='$v_csv_data'/>";
	echo "</td>";	
	echo "<td>";
	echo "$xuhao";
	echo "</td>";
	echo "<td>";
	echo "$acct_name";
	echo "</td>";
	echo "<td>";
	echo "$log_time";
	echo "</td>";	
	echo "</tr>";
	$xuhao = $xuhao +1 ;
}	
echo "</TABLE>";


?>
<br/>
<br/>
<br/>
<input type="submit" value="勾选账号继续跑到100级" onclick="check_submit('hobby[]')"/>
 <input type="button" value="刷新当前页面" onclick="javascript:location.reload();" />
 </br></br>
 <a href="javascript:history.go(-1);"  title="返回">返回</a>
 </form>  
 </body>
</body>
</html>
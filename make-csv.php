<?php
$csv_data_path = 'D:\\XXT_Lan_2.5.5.0\\data\\running\\';
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
//date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


$ROW_NUM =1;
$strs = array();
foreach( $_POST['hobby'] as $i)
{
	$strs = explode(',',$i);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ROW_NUM, $strs[0]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ROW_NUM, $strs[1]);
	$ROW_NUM= $ROW_NUM +1;
	//echo '<br>';
 //$result .= $i;
}

$objPHPExcel->getActiveSheet()->setTitle('Simple22');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

//ob_end_clean();
// Redirect output to a client’s web browser (Excel5)
//header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
//header('Content-Disposition: attachment;filename="待运行账号.xlsx"');


//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
//$objWriter->save('php://output');
$str = date('Y-m-d_His');
    $file_name = $str . ".csv";
$objWriter->save($csv_data_path.$file_name);

//echo '<a href="javascript:history.go(-1);"  title="返回">返回</a>';

?>
<html>
<body>
</br></br>
 <a href="javascript:history.go(-1);"  title="返回">返回</a>
  </body>
</html>
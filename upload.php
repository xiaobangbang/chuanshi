<?php
header("Content-type: text/html; charset=utf-8");
require_once 'Classes\PHPExcel.php';
require_once 'Classes\PHPExcel\IOFactory.php';
require_once 'Classes\PHPExcel\Reader\Excel2007.php';

$csv_data_path = 'D:\\XXT_Lan_2.5.5.0\\data\\running\\';
$excel_upload_path = 'C:\\xampp\\htdocs\\PHPExcel\\upload\\';

if (! empty($_FILES['file_stu']['name'])) {
    $tmp_file = $_FILES['file_stu']['tmp_name'];
    $file_types = explode(".", $_FILES['file_stu']['name']);
    $file_type = $file_types[count($file_types) - 1];
    /* 判别是不是.xls文件，判别是不是excel文件 */
    if (strtolower($file_type) != "xls" and strtolower($file_type) != "xlsx") {
        // $this->error ( '不是Excel文件，重新上传' );
        echo "不是Excel文件，重新上传'";
        echo '<a href="javascript:history.go(-1);"  title="返回">返回</a>';
        exit(0);
    }
    /* 设置上传路径 */
    $savePath = $excel_upload_path; // 'C:\xampp\htdocs\PHPExcel-1.8' . '/upload/';
    /* 以时间来命名上传的文件 */
    date_default_timezone_set('Asia/Shanghai');
    
    echo date('Y-m-d_His');
    
    $str = date('Y-m-d_His');
    $file_name = $str . "." . $file_type;
    /* 是否上传成功 */
    if (! copy($tmp_file, $savePath . $file_name)) {
        $this->error('上传失败');
    }
    /*
     * 对上传的Excel数据进行处理生成编程数据,这个函数会在下面第三步的ExcelToArray类中
     * 注意：这里调用执行了第三步类里面的read函数，把Excel转化为数组并返回给$res,再进行数据库写入
     */
    
    /**
     * Include path *
     */
    set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
    
    /**
     * PHPExcel_IOFactory
     */
    // include 'PHPExcel/IOFactory.php';
    
    $file_reader = "Excel2007";
    if (strtolower($file_type) == "xls") {
        $file_reader = 'Excel5';
    }
    if (strtolower($file_type) == "xlsx") {
        $file_reader = 'Excel2007';
    }
    
    $filename = $savePath . $file_name;
    $objReader = PHPExcel_IOFactory::createReader($file_reader);
    $objPHPExcel = $objReader->load($filename);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
    $objWriter->save($csv_data_path . str_replace('.' . $file_type, '.csv', $file_name));
	//$objWriter->save($csv_data_path .'running_acct.csv');
    
    echo "上传成功!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!";

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
    echo '<br>';
    $uploadfile = $filename;
    
    // $objReader = PHPExcel_IOFactory::createReader($file_reader);/*Excel5 for 2003 excel2007 for 2007*/
    // $objPHPExcel = $objReader->load($uploadfile); //Excel 路径
    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow(); // 取得总行数
    $highestColumn = $sheet->getHighestColumn(); // 取得总列数
    /* 方法一 */
    
    $strs = array();
    for ($j = 1; $j <= $highestRow; $j ++) { // 从第一行开始读取数据
        /* 注销上一行读取数据 */
        unset($str);
        unset($strs);
        $str = '';
        for ($k = 'A'; $k <= $highestColumn; $k ++) { // 从A列读取数据
                                             // 实测在excel中，如果某单元格的值包含了||||||导入的数据会为空
            $str .= $objPHPExcel->getActiveSheet()
                ->getCell("$k$j")
                ->getValue() . '||||||'; // 读取单元格
        }
		
        // explode:函数把字符串分割为数组。
        $strs = explode("||||||", $str);
                
        $sql1 = "insert into excel_acct_info(acct_user,user_pwd,game_server) select '{$strs[0]}','{$strs[1]}','{$strs[2]}' where not exists(select acct_user from excel_acct_info where acct_user = '{$strs[0]}') ";
        //echo '<br>';
        //echo $sql1 . '<br>';
        $result2 = $db->exec($sql1);
    }
    
    /* 方法二【推荐】 */
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
    $highestColumn = $objWorksheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // 总列数
    
    for ($row = 1; $row <= $highestRow; $row ++) {
        $strs = array();
        // 注意highestColumnIndex的列数索引从0开始
        for ($col = 0; $col < $highestColumnIndex; $col ++) {
            $strs[$col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
        }
        // print_r($strs);
    }
    /**
    $results = $db->query('SELECT * FROM excel_acct_info');
    while ($row = $results->fetchArray()) {
        var_dump($row);
        echo '<br>';
    }
	*/
} else {
    echo "没有选择文件，请返回'";
}
// header(location:.getenv("HTTP_REFERER"));
// echo "<script>alert('随便写点什么');history.go(-1);</script>";
echo '<a href="javascript:history.go(-1);"  title="返回">返回</a>';
?>
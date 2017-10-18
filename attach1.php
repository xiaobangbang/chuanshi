<?php
require_once 'Classes\FileUtil.php';
 
	
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
        echo "";
    }
	
	$dir="D:\\XXT_Lan_2.5.5.0\\data\\upload_sqlite\\";
	$backup_dir ="D:\\XXT_Lan_2.5.5.0\\data\\backup\\";
	$files=scandir($dir);

for($i=0;$i<count($files);++$i){ 
if ($files[$i] <> '.' and $files[$i] <> '..'){
	//echo $files[$i].'<br />'; 	

	$attach_dbfile = $dir.$files[$i];
	$attach_dbfile_backup = $backup_dir.$files[$i];
	$sql="ATTACH DATABASE '{$attach_dbfile}' as 'attachdb';";
	//echo $sql;
	$db->exec($sql);
	
		
	$sql = "insert into game_acct SELECT * FROM attachdb.game_acct b where not exists(select * from game_acct c where c.acct_name = b.acct_name);";        
    //echo $sql . '<br/>';
	$db->exec($sql);
	
	$sql ="update game_acct set flag60 = 'Y' where exists(select * from attachdb.game_acct c where c.acct_name = game_acct.acct_name and c.flag60 ='Y');";
	$db->exec($sql);
	
		
	$sql="DETACH DATABASE 'attachdb';";
	//echo $sql;
	$db->exec($sql);
	
	FileUtil::moveFile($attach_dbfile,$attach_dbfile_backup);

	}
} 
/**
$results = $db->query('SELECT * FROM game_acct');
    while ($row = $results->fetchArray()) {
        var_dump($row);
        echo '<br/>';
    }
*/	
 echo "成功";	
?>	

</br></br>
 <a href="javascript:history.go(-1);"  title="返回">返回</a>
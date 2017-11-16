<?php

    $mysql_server_name="localhost"; //数据库服务器名称

    $mysql_username="root"; // 连接数据库用户名

    $mysql_password=""; // 连接数据库密码

    $mysql_database="test"; // 数据库的名字

 

    // 连接到数据库

    $conn=mysql_connect($mysql_server_name, $mysql_username,$mysql_password);

 

    // 从表中提取信息的sql语句

    $strsql="select * from personal_info";

    // 执行sql查询

    $result=mysql_db_query($mysql_database, $strsql, $conn);

    // 获取查询结果

    $row=mysql_fetch_row($result);

 

    echo '<font face="verdana">';

    echo '<table border="1" cellpadding="1" cellspacing="2">';

 

    // 显示字段名称

    echo "/n<tr>/n";

    for ($i=0; $i<mysql_num_fields($result); $i++)

    {

      echo '<td bgcolor="#000F00"><b>'.

      mysql_field_name($result, $i);

      echo "</b></td>/n";

    }

    echo "</tr>/n";

    // 定位到第一条记录

    mysql_data_seek($result, 0);

    // 循环取出记录 何问起

    while ($row=mysql_fetch_row($result))

    {

      echo "<tr>/n";

      for ($i=0; $i<mysql_num_fields($result); $i++ )

      {

        echo '<td bgcolor="#00FF00">';

        echo "$row[$i]";

        echo '</td>';

      }

      echo "</tr>/n";

    }

 

    echo "</table>/n";

    echo "</font>";

    // 释放资源 hovertree.com

    mysql_free_result($result);

    // 关闭连接

    mysql_close();  

?>
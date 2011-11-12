<?php

    session_start();
    $sessionId=session_id();
    $resultStr="";
    $link = mysql_connect('localhost', 'tolkab5_shayuna', 'abra!2206');
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }

    $result=mysql_select_db("tolkab5_1");
    if (!$result){
    	die(mysql_error());
    }

    $sqlStr="select count(*) as cnt from talk_subject where myId='".$sessionId."'";
    $result=execSql($sqlStr);
    $row = mysql_fetch_array($result);
    $resultStr=$row['cnt'];

    function execSql($sqlStr)
    {
    	$result = mysql_query($sqlStr);
    	if (!$result){
    		die(mysql_error());
    	}
    	return $result;
    }

    mysql_close($link);

    echo $resultStr;

?>
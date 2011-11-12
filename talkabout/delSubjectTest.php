<?php

    session_start();
    $subjectIndx=$_GET["subjectIndx"];
    $sessionId=session_id();
    $resultStr="1";

    $link = mysql_connect('localhost', 'tolkab5_shayuna', 'abra!2206');
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }

    $result=mysql_select_db("tolkab5_1");
    if (!$result){
    	die(mysql_error());
    }

    $sqlStr="select 1 from subjectStatus_".$subjectIndx;
    $result=execSql($sqlStr);
    $returnedRows=0;

    if ($result)$returnedRows=mysql_num_rows($result);

    if ($returnedRows==0) $resultStr="0";
    else
    {
        $sqlStr="delete from subjects where c=".$subjectIndx;
        $result=mysql_query($sqlStr);
        $sqlStr="delete from subjectsTimeChk where c=".$subjectIndx;
        $result=mysql_query($sqlStr);
        $sqlStr="delete from tweets where c=".$subjectIndx;
        $result=mysql_query($sqlStr);
        $sqlStr="drop table subjectStatus_".$subjectIndx;
        $result=mysql_query($sqlStr);
        $sqlStr="drop table ditching_".$subjectIndx;
        $result=mysql_query($sqlStr);
        $sqlStr="drop table subjectMsgs_".$subjectIndx;
        $result=mysql_query($sqlStr);

    }

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
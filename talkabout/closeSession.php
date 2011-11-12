<?php
    session_start();
    $sessionId=session_id();

    $link = mysql_connect('localhost', 'tolkab5_shayuna', 'abra!2206');
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }

    $result=mysql_select_db("tolkab5_1");
    if (!$result){
    	die(mysql_error());
    }

    $sqlStr="select c from subjects where ownerId='".$sessionId."'";
    $result=execSql($sqlStr);

    if (mysql_num_rows($result)>0)
    {
        while($row = mysql_fetch_array($result))
        {
            $subjectIndx=$row["c"];
            $sqlStr="delete from subjects where c=".$subjectIndx;
            $result1=mysql_query($sqlStr);
            $sqlStr="delete from subjectsTimeChk where c=".$subjectIndx;
            $result1=mysql_query($sqlStr);
            $sqlStr="delete from tweets where c=".$subjectIndx;
            $result1=mysql_query($sqlStr);
            $sqlStr="drop table subjectStatus_".$subjectIndx;
            $result1=mysql_query($sqlStr);
            $sqlStr="drop table ditching_".$subjectIndx;
            $result1=mysql_query($sqlStr);
            $sqlStr="drop table subjectMsgs_".$subjectIndx;
            $result1=mysql_query($sqlStr);
        }
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
    session_destroy();
?>
<?php

    session_start();
    $subjectIndx=$_GET["subjectIndx"];

    $link = mysql_connect('localhost', 'tolkab5_shayuna', 'abra!2206');
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }

    $result=mysql_select_db("tolkab5_1");
    if (!$result){
    	die(mysql_error());
    }

    $sqlStr="update ditching_".$subjectIndx." set ditchInitiator=null,ownerDitchStatus=null,partnerDitchStatus=null";
    $result=execSql($sqlStr);

    function execSql($sqlStr)
    {
    	$result = mysql_query($sqlStr);
    	if (!$result){
    		die(mysql_error());
    	}
    	return $result;
    }

    mysql_close($link);

?>
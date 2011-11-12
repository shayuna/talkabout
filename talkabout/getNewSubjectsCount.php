<?php

    session_start();
    $sessionId=session_id();
    $privateCode=$_GET["privateCode"];
    $resultStr="";
    $link = mysql_connect('localhost', 'tolkab5_shayuna', 'abra!2206');
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }

    $result=mysql_select_db("tolkab5_1");
    if (!$result){
    	die(mysql_error());
    }

    $sqlStr=" select s.c,time_to_sec(timediff(Now(),tc.ownerTimeChk)) as ownerLapse from subjects s ".
            " inner join subjectsTimeChk tc on s.c=tc.c ".
            " where (s.ownerId='".$sessionId."' or tc.partnerTimeChk is null) ".
            " and privateCode='".$privateCode."' ";

    $result=execSql($sqlStr);
    $rsltAr = Array();
    while($row = mysql_fetch_array($result))
    {
        if ($row["ownerLapse"]<=20)
            $rsltAr[]=  $row["c"];
    }
    $resultStr=json_encode($rsltAr);

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
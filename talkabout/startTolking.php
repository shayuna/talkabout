<?php

    session_start();
    $subjectIndx=$_GET["subjectIndx"];
    $sessionId=session_id();

    $link = mysql_connect('localhost', 'tolkab5_shayuna', 'abra!2206');
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }

    $result=mysql_select_db("tolkab5_1");
    if (!$result){
    	die(mysql_error());
    }
//    $sqlStr="update subjectStatus_".$subjectIndx." st1 inner join subjectStatus_".$subjectIndx.
//            " st2 on st2.c=".$subjectIndx." and st1.ownerId=st2.ownerId set st1.partnerId='".
  //          $sessionId."' where st2.partnerId is null" ;
    $sqlStr="update subjectStatus_".$subjectIndx." st ".
    "inner join subjectsTimeChk tc on st.c=tc.c set st.partnerId='".$sessionId."' where tc.partnerTimeChk is null";
    $result=execSql($sqlStr);

    $sqlStr="select count(*) as cnt,ownerId from subjectStatus_".$subjectIndx." where partnerId='".$sessionId."';";
    $result=execSql($sqlStr);
    $row = mysql_fetch_array($result);
    //while($row = mysql_fetch_array($result))
    //{
    //  echo $row['subject'] . " " . $row['msg'] . "<br/>";
    //}

    if ($row["cnt"]==0)$isTolkEstablished=0;
    else
    {
        $isTolkEstablished=1;
        $sqlStr="update subjectMsgs_".$subjectIndx." sm inner join subjectStatus_".$subjectIndx." st on sm.c=st.c ".
                " set sm.msg=NULL where st.ownerId='".$row['ownerId']."'";
        $result=execSql($sqlStr);
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
    
    echo $isTolkEstablished;

    //echo $sqlStr;
?>

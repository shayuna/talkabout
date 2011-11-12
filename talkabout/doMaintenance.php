<?php                                                 

    session_start();
    $sessionId=session_id();
    $sbjToKeepAlive=$_GET["sbjToKeepAlive"];
    $sbjToKeepAliveAr=explode("*",$sbjToKeepAlive);
    $newSubjectIsTyping=$_GET["newSubjectIsTyping"];
    $fromListSubjectIsTyping=$_GET["fromListSubjectIsTyping"];
    $newSubjectIndx=$_GET["newSubjectIndx"];
    $subjectFromListIndx=$_GET["subjectFromListIndx"];
    $resultToReturn="";

    $link = mysql_connect('localhost', 'tolkab5_shayuna', 'abra!2206');
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }

    $result=mysql_select_db("tolkab5_1");
    if (!$result){
    	die(mysql_error());
    }
    if ($sbjToKeepAliveAr[0]!="")
    {
        $sqlStr="update subjectsTimeChk set ownerTimeChk=Now() where c in (".$sbjToKeepAliveAr[0].")";
        $result=execSql($sqlStr);
    }
    if ($sbjToKeepAliveAr[1]!="")
    {
        $sqlStr="update subjectsTimeChk set partnerTimeChk=Now() where c in ( ".
        "select c from subjects where ownerId=(select ownerId from subjects where c=".
        $sbjToKeepAliveAr[1].") )";
        $result=execSql($sqlStr);
    }
    if ($newSubjectIndx!="" && $newSubjectIsTyping=="1")
    {
        $sqlStr="update subjectMsgs_".$newSubjectIndx." set isOwnerTyping=true";
        $result=execSql($sqlStr);
        if ($result)$resultToReturn.="typing in new registered";
    }
    if ($subjectFromListIndx!="" && $fromListSubjectIsTyping=="1")
    {
        $sqlStr="update subjectMsgs_".$subjectFromListIndx." set isPartnerTyping=true";
        $result=execSql($sqlStr);
        if ($result)$resultToReturn.="typing in from list registered";
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

    echo $resultToReturn;

?>
                
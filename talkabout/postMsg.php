<?php

    session_start();
    $msg=str_replace("'","\'",$_GET["msg"]);
    $subjectIndx=$_GET["subjectIndx"];
    $newSubjectIndx=$_GET["newSubjectIndx"];
    $subjectFromListIndx=$_GET["subjectFromListIndx"];
    $lng=$_GET["lng"];
    $sessionId=session_id();
    $ajaxRslt=-1;

    $link = mysql_connect('localhost', 'tolkab5_shayuna', 'abra!2206');
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }

    $result=mysql_select_db("tolkab5_1");
    if (!$result){
        die(mysql_error());
    }
    $isOwnerTyping="isOwnerTyping";
    $isPartnerTyping="isPartnerTyping";
    if ($subjectIndx==$newSubjectIndx) $isOwnerTyping="0";
    if ($subjectIndx==$subjectFromListIndx) $isPartnerTyping="0";
    $sqlStr="update subjectMsgs_".$subjectIndx." set msg='".$msg."',msgOwner='".$sessionId."',msgStatus=0,".
            "isOwnerTyping=".$isOwnerTyping.",isPartnerTyping=".$isPartnerTyping.",lng='".$lng."' where ".
            " msgStatus in (0,3) or msgStatus is null";
    $result=execSql($sqlStr);
    $sqlStr="select count(*) as cnt from subjectMsgs_".$subjectIndx." where msgOwner='".$sessionId."' and msgStatus=0";   
    $result=execSql($sqlStr);
    if ($row = mysql_fetch_array($result)) $ajaxRslt=$row['cnt'];
        
    function execSql($sqlStr)
    {
        $result = mysql_query($sqlStr);
        if (!$result){
            die(mysql_error());
        }
        return $result;
    }

    mysql_close($link);

    echo $ajaxRslt;
?>

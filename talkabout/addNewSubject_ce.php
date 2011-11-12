<?php

    session_start();
    $newSubject=str_replace("'","\'",$_GET["newSubjectInput"]);
    $isFromTwitter=$_GET["isFromTwitter"];
    $privateCode=$_GET["privateCode"];
    $sessionId=session_id();
    $link = mysql_connect('localhost', 'tolkab5_shayuna', 'abra!2206');
    $mySubjectsCount=-1;
    $totalSubjectsCount=-1;
    
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }

    $result=mysql_select_db("tolkab5_1");
    if (!$result){
    	die(mysql_error());
    }

    $sqlStr="select count(*) as cnt from subjects where ownerId='".$sessionId.
            "' union all select count(*) as cnt from subjects";
    $result=execSql($sqlStr);
    while ($row = mysql_fetch_array($result))
    {
        if ($mySubjectsCount==-1)$mySubjectsCount=$row["cnt"];
        else $totalSubjectsCount=$row["cnt"];
    }

    $newSubjectIndx="-1";
    if ($mySubjectsCount<5 && $mySubjectsCount>-1 && $totalSubjectsCount<500)
    {
        $sqlStr="insert into subjects (nm,ownerId,privateCode,birthDt) values('".$newSubject."','".
                $sessionId."','" .$privateCode."',Now())";
        $result=execSql($sqlStr);
        $newSubjectIndx=trim(mysql_insert_id());
        $sqlStr="create table subjectStatus_".$newSubjectIndx."(c int not null,ownerId varchar(50),".
                "partnerId varchar(50))ENGINE=InnoDB";
        $result=execSql($sqlStr);
        $sqlStr="insert into subjectStatus_".$newSubjectIndx." (c,ownerId) values (".$newSubjectIndx.",'".$sessionId."')";
        $result=execSql($sqlStr);
        $sqlStr="create table subjectMsgs_".$newSubjectIndx."(c int not null,msg text,msgStatus tinyint,".
                "msgOwner varchar(50),isOwnerTyping bool,isPartnerTyping bool,lng char(2))ENGINE=InnoDB";
        $result=execSql($sqlStr);
        $sqlStr="create table ditching_".$newSubjectIndx."(c int not null,ditchInitiator varchar(50),".
                "ownerDitchStatus tinyint,partnerDitchStatus tinyint)ENGINE=InnoDB";
        $result=execSql($sqlStr);
        $sqlStr="insert into ditching_".$newSubjectIndx."(c) values (".$newSubjectIndx.")";
        $result=execSql($sqlStr);
        $sqlStr="insert into subjectMsgs_".$newSubjectIndx."(c) values (".$newSubjectIndx.")";
        $result=execSql($sqlStr);
        $sqlStr="insert into subjectsTimeChk (c,ownerTimeChk) values (".$newSubjectIndx.",Now())";
        $result=execSql($sqlStr);
        if (!$isFromTwitter)
        {
            $sqlStr="insert into tweets (c,subject) values (".$newSubjectIndx.",'".$newSubject."')";
            $result=execSql($sqlStr);
        }
    }



    mysql_close($link);

    echo $mySubjectsCount."*&*".$totalSubjectsCount."*&*".$newSubjectIndx."*&*".$newSubject;
    function execSql($sqlStr)
    {
    	$result = mysql_query($sqlStr);
    	if (!$result){
    		die(mysql_error());
    	}
    	return $result;
    }
?>

<?php

    session_start();
    $newSubjectIndx=$_GET["newSubjectIndx"];
    $subjectFromListIndx=$_GET["subjectFromListIndx"];
    $myList=$_GET["myList"];
    $isNewSbjFnd=false;
    $isSbjListFnd=false;
    if ($newSubjectIndx=="")
    {
        $newSubjectIndx="0";
        $isNewSbjFnd=true;
    }
    if ($subjectFromListIndx=="")
    {
        $subjectFromListIndx="0";
        $isSbjListFnd=true;
    }
    $sessionId=session_id();
    $link = mysql_connect('localhost', 'tolkab5_shayuna', 'abra!2206');
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }

    $result=mysql_select_db("tolkab5_1");
    if (!$result){
    	die(mysql_error());
    }
    if ($newSubjectIndx=="0")
    {
//        $sqlStr="select tc.c from subjectsTimeChk tc inner join subjects s on tc.c=s.c ".
  //              " where s.ownerId='".$sessionId."' and partnerTimeChk is not null";
        if ($myList!="")
        {
            $myListAr=explode(",",$myList);
            $sqlStr="select s.c from subjects s ";
            $whereStr="";
            $orStr="";
            for ($jj=0;$jj<count($myListAr);$jj++)
            {
                if ($myListAr[$jj]!="")
                {
                    $sqlStr=$sqlStr." left join subjectStatus_".$myListAr[$jj]." st".$jj." on st".$jj.".c=s.c ";
                    if ($whereStr!="")$orStr=" or ";
                    $whereStr=$whereStr.$orStr." st".$jj.".partnerId is not null";
                }
            }
            $sqlStr=$sqlStr." where ".$whereStr;

            $result=execSql($sqlStr);
            if ($row=mysql_fetch_array($result))
                $newSubjectIndx=$row['c'];

        }
    }
    if ($newSubjectIndx!="0")
    {
        $sqlStr="desc subjectStatus_".$newSubjectIndx;
        mysql_query($sqlStr);
        if (mysql_errno()==1146) $newSubjectIndx=0;

    }
    if ($subjectFromListIndx!="0")
    {
        $sqlStr="desc subjectStatus_".$subjectFromListIndx;
        mysql_query($sqlStr);
        if (mysql_errno()==1146) $subjectFromListIndx=0;

    }
//    exit ($myList);
    if ($newSubjectIndx!="0" || $subjectFromListIndx!="0")
    {
        $sqlStr="select tc.c,st1.ownerId as ownerId1,st1.partnerId as partnerId1,".
                "st2.ownerId as ownerId2,st2.partnerId as partnerId2,".
                "time_to_sec(timediff(Now(),ownerTimeChk)) as ownerLapse,".
                "time_to_sec(timediff(Now(),partnerTimeChk)) as partnerLapse,".
                "sm1.msg as msg1,sm2.msg as msg2,sm1.msgStatus as msgStatus1,sm2.msgStatus as msgStatus2,".
                "sm1.msgOwner as msgOwner1,sm2.msgOwner as msgOwner2,sm1.lng as lng1,sm2.lng as lng2,".
                "sm1.isOwnerTyping as isOwnerTyping1,sm1.isPartnerTyping as isPartnerTyping1,".
                "sm2.isOwnerTyping as isOwnerTyping2,sm2.isPartnerTyping as isPartnerTyping2,".
                "d1.ditchInitiator as ditchInitiator1,d2.ditchInitiator as ditchInitiator2,".
                "d1.ownerDitchStatus as ownerDitchStatus1,d2.ownerDitchStatus as ownerDitchStatus2,".
                "d1.partnerDitchStatus as partnerDitchStatus1,d2.partnerDitchStatus as partnerDitchStatus2 ".
                "from subjectsTimeChk tc ".
                "left join subjectStatus_".$newSubjectIndx." st1 on st1.c=tc.c ".
                "left join subjectStatus_".$subjectFromListIndx." st2 on st2.c=tc.c ".
                "left join subjectMsgs_".$newSubjectIndx." sm1 on st1.c=sm1.c ".
                "left join subjectMsgs_".$subjectFromListIndx." sm2 on st2.c=sm2.c ".
                "left join ditching_".$newSubjectIndx." d1 on tc.c=d1.c ".
                "left join ditching_".$subjectFromListIndx." d2 on tc.c=d2.c ".
                "where tc.c in (".$newSubjectIndx.",".$subjectFromListIndx.")";


//        exit($sqlStr);

        $result=execSql($sqlStr);
    /*
        echo $sqlStr;
        mysql_close($link);
        exit ("bye bye");
    */
    /*
        $sqlStr="select st.c,ownerId,partnerId,time_to_sec(timediff(Now(),ownerTimeChk)) as ownerLapse,".
                "time_to_sec(timediff(Now(),partnerTimeChk)) as partnerLapse,msg,msgStatus,msgOwner,ditchInitiator".                        ",ownerDitchStatus,partnerDitchStatus from subjectStatus st ".
                "inner join subjectsMsgs sm on st.c=sm.c ".
                "inner join subjectsTimeChk tc on tc.c=st.c ".
                "left join ditchings d on st.c=d.c ".
                "where st.c in (".$newSubjectIndx.",".$subjectFromListIndx.")";
        if ($newSubjectIndx=="-1") $sqlStr=$sqlStr." or (ownerId='".$sessionId."' and msg is not null)";
        $result=execSql($sqlStr);
    */


        $newSubjectMsg="";
        $fromListSubjectMsg="";

        $sqlStr1="";
        $sqlStr3="";
        $sqlStr5="";
        while($row = mysql_fetch_array($result))
        {
           if ($newSubjectIndx==$row['c'])$isNewSbjFnd=true;
           if ($subjectFromListIndx==$row['c'])$isSbjListFnd=true;

           if ($row["ownerId1"]!=$sessionId && $row["ownerId2"]!=$sessionId &&
               $row["partnerId1"]!=$sessionId && $row["partnerId2"]!=$sessionId)
           {
                if ($row['c']==$newSubjectIndx)
                  $newSubjectMsg="ditch333*^*1*^*";
                elseif ($row['c']==$subjectFromListIndx)
                  $fromListSubjectMsg="ditch333*^*2*^*";
           }
           /* if a ditch request was inserted into ditchings table*/
           elseif (($row["ditchInitiator1"]!=NULL && $row['ownerDitchStatus1']==1 && $row['partnerDitchStatus1']==1) ||
                   ($row["ditchInitiator2"]!=NULL && $row['ownerDitchStatus2']==1 && $row['partnerDitchStatus2']==1))
           {
              if (($row["ditchInitiator1"]==$row['ownerId1'] && $sessionId==$row['partnerId1']) ||
                  ($row["ditchInitiator2"]==$row['ownerId2'] && $sessionId==$row['partnerId2']))
              {
                  $fromListSubjectMsg="ditchRequest222*^*";
              }
              elseif (($row["ditchInitiator1"]==$row['partnerId1'] && $sessionId==$row['ownerId1']) ||
                      ($row["ditchInitiator2"]==$row['partnerId2'] && $sessionId==$row['ownerId2']))
              {
                  $newSubjectMsg="ditchRequest222*^*";
              }
           }
           elseif (
                    (
                        ( ($row['msgStatus1']==0 || $row['msgStatus1']==1) && $row['ownerId1']==$sessionId ) ||
                        ( ($row['msgStatus1']==0 || $row['msgStatus1']==2) && $row['partnerId1']==$sessionId )
                    ) ||
                    (
                        ( ($row['msgStatus2']==0 || $row['msgStatus2']==1) && $row['ownerId2']==$sessionId ) ||
                        ( ($row['msgStatus2']==0 || $row['msgStatus2']==2) && $row['partnerId2']==$sessionId )
                    )

                  )
           {

               $suff="1";
               if (
                        ( ($row['msgStatus2']==0 || $row['msgStatus2']==1) && $row['ownerId2']==$sessionId ) ||
                        ( ($row['msgStatus2']==0 || $row['msgStatus2']==2) && $row['partnerId2']==$sessionId )
                  ) $suff="2";
               if ($row["msg".$suff]!=NULL)
               {
                   $msgStatusToUpdate=$row["msgStatus".$suff];
                   $msgToUpdate=" msg='".str_replace("'","\'",$row["msg".$suff])."' ";
            //       $subject="";

                   $msgElm="";
                   $msgOwner="1";
                   if ($row["msgOwner".$suff]!=$sessionId)$msgOwner="2";
                   $msgElm=$row["msg".$suff]."*$*".$msgOwner."*$*".$row["lng".$suff]."*$*";

                   if ($row["ownerId".$suff]==$sessionId)
                   {
                        $msgStatusToUpdate=$msgStatusToUpdate+2;
                        $newSubjectMsg=$row["c"]."*^*".$msgElm;
            //            $subject=$newSubject;
                   }
                   elseif ($row["partnerId".$suff]==$sessionId)
                   {
                        $msgStatusToUpdate=$msgStatusToUpdate+1;
                        $fromListSubjectMsg=$row["c"]."*^*".$msgElm;
            //            $subject=$subjectFromList;
                   }
                    if ($msgStatusToUpdate<=3)
                        $sqlStr1="update subjectMsgs_".$row['c']." set msgStatus=".$msgStatusToUpdate." where c=".$row['c'];
                }
            }
            elseif ($row["msgStatus1"]==1 || $row["msgStatus1"]==2 || $row["msgStatus2"]==1 || $row["msgStatus2"]==2)
            {
                  if ($row["ownerLapse"]>30 || $row["partnerLapse"]>30)
               {
                  if ($row["ownerLapse"]>30 && ($row["partnerId1"]==$sessionId || $row["partnerId2"]==$sessionId))
                      $fromListSubjectMsg="ditch111*^*2*^*";
                  elseif ($row["partnerLapse"]>30 && ($row["ownerId1"]==$sessionId || $row["ownerId2"]==$sessionId))
                      $newSubjectMsg="ditch111*^*2*^*";
         
                  if ($fromListSubjectMsg!="" || $newSubjectMsg!="")
                  {
                      $sqlStr5="update subjectStatus_".$row['c']." set partnerId=NULL where c=".$row['c'];
                      $result1=execSql($sqlStr5);
                      $sqlStr5="update subjectMsgs_".$row['c']." set msg=NULL,msgStatus=0,msgOwner=NULL where c=".$row['c'];
                      $result1=execSql($sqlStr5);
                      $sqlStr5="update subjectsTimeChk set partnerTimeChk=NULL where c in ( ".
                        "select c from subjects where ownerId=(select ownerId from subjects where c=".
                        $row["c"].") )";
                      $result1=execSql($sqlStr5);
                      $sqlStr5="update ditching_".$row['c']." set ditchInitiator=NULL,ownerDitchStatus=NULL,".
                               "partnerDitchStatus=NULL where c=".$row['c'];
                  }
               }
           } 
           elseif (($row["partnerDitchStatus1"]==3 && $row["ownerDitchStatus1"]==3) ||
                ($row["partnerDitchStatus2"]==3 && $row["ownerDitchStatus2"]==3))
           {
               $sqlStr3="update subjectStatus_".$row['c']." set partnerId=NULL where c=".$row['c'];
               $result1=execSql($sqlStr3);
               $sqlStr3="update subjectsTimeChk set partnerTimeChk=NULL where c in ( ".
                "select c from subjects where ownerId=(select ownerId from subjects where c=".
                $row["c"].") )";

               $result1=execSql($sqlStr3);
               $sqlStr3="update ditching_".$row['c']." set ditchInitiator=null,ownerDitchStatus=null,".
                        "partnerDitchStatus=null";
           }
           elseif (($row["ownerDitchStatus1"]==2 || $row["partnerDitchStatus1"]==2) ||
                   ($row["ownerDitchStatus2"]==2 || $row["partnerDitchStatus2"]==2))
           {
                if (($row["ownerDitchStatus1"]==2 && $row['ownerId1']==$sessionId) ||
                    ($row["ownerDitchStatus2"]==2 && $row['ownerId2']==$sessionId))
                {
                    $sqlStr3="update ditching_".$row['c']." set ownerDitchStatus=3 where c=".$row["c"];
                    if ($row["ditchInitiator1"]==$sessionId || $row["ditchInitiator2"]==$sessionId)
                        $newSubjectMsg="ditch111*^*1*^*";
                    else $newSubjectMsg="ditch111*^*2*^*";
                }
                if (($row["partnerDitchStatus1"]==2 && $row['partnerId1']==$sessionId) ||
                    ($row["partnerDitchStatus2"]==2 && $row['partnerId2']==$sessionId))
                {
                    $sqlStr3="update ditching_".$row['c']." set partnerDitchStatus=3 where c=".$row["c"];
                    if ($row["ditchInitiator1"]==$sessionId || $row["ditchInitiator2"]==$sessionId)
                        $fromListSubjectMsg="ditch111*^*1*^*";
                    else $fromListSubjectMsg="ditch111*^*2*^*";
                }
           }
           elseif ($row['isPartnerTyping1']==1 && $row['ownerId1']==$sessionId)
           {
               $newSubjectMsg="partnerTyping444*^*";
           }
           elseif ($row['isOwnerTyping2']==1 && $row['partnerId2']==$sessionId)
           {
               $fromListSubjectMsg="partnerTyping444*^*";
           }
        }

        if ($sqlStr1!="")$result1=execSql($sqlStr1);
        if ($sqlStr3!="")$result1=execSql($sqlStr3);
        if ($sqlStr5!="")$result1=execSql($sqlStr5);
    }
    mysql_close($link);

    if (!$isNewSbjFnd)
    {
        $newSubjectMsg="ditch333*^*1*^*";
//        $fromListSubjectMsg=mysql_error()." @@@ ".$sqlStr;
    }
    if (!$isSbjListFnd)
    {
        $fromListSubjectMsg="ditch333*^*2*^*";
//        $newSubjectMsg=mysql_error()." @@@ ".$sqlStr;
    }

//    $newSubjectMsg=$sqlStr;

    $msg=$newSubjectMsg."*&*".$fromListSubjectMsg;
    echo $msg;

    function execSql($sqlStr)
    {
    	$result = mysql_query($sqlStr);
    	if (!$result){
            die("something here is fishy: ".$sqlStr." *** " . mysql_error());
    	}
    	return $result;
    }

?>

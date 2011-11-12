<?php

    $link = mysql_connect('localhost', 'tolkab5_shayuna', 'abra!2206');
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }

    $result=mysql_select_db("tolkab5_1");
    if (!$result){
    	die(mysql_error());
    }

    $query=execSql("select c from subjectsTimeChk where time_to_sec(timediff(Now(),ownerTimeChk))>60 ");
    $sbjIdList="";
    

    if(mysql_num_rows($query)>0)
    {
        while ($row=mysql_fetch_array($query))
        {
            if ($sbjIdList!="") $sbjIdList=$sbjIdList.",";
            $sbjIdList=$sbjIdList.$row["c"];
       }
        $query=execSql("delete from subjects where c in (".$sbjIdList.")");
        $query=execSql("delete from subjectsTimeChk where c in (".$sbjIdList.")");
        $query=execSql("delete from tweets where c in (".$sbjIdList.")");
        $sbjIdListAr=explode(",",$sbjIdList);
        for ($jj=0;$jj<count($sbjIdListAr);$jj++)
        {
            if ($sbjIdListAr[$jj]!="" and is_numeric($sbjIdListAr[$jj]))
            {
                $query=execSql("drop table subjectStatus_".$sbjIdListAr[$jj]);
                $query=execSql("drop table ditching_".$sbjIdListAr[$jj]);
                $query=execSql("drop table subjectMsgs_".$sbjIdListAr[$jj]);
            }
        }
        mysql_close($link);
    }

    function execSql($sqlStr)
    {
    	$result = mysql_query($sqlStr);
    	if (!$result){
            die("something here is fishy: ".$sqlStr." *** " . mysql_error());
    	}
    	return $result;
    }

?>
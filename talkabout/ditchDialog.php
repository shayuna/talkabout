<?php

    session_start();
    $subjectIndx=$_GET["subjectIndx"];
    $sessionId=session_id();
    $isFinal=$_GET["isFinal"];
    $ajaxRslt=1;

    $link = mysql_connect('localhost', 'tolkab5_shayuna', 'abra!2206');
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }

    $result=mysql_select_db("tolkab5_1");
    if (!$result){
    	die(mysql_error());
    }

/* 
    let's check first the msg status. if a message is in progress, get out. 
    if not then go with the isFinal. update or insert. remember to insert only if the record doesn't exist (taking care of exception that may result if two users exit at the same time. one has to be first, so an insert will be made on an existing unique index)' 
    
    

 */
    $sqlStr="select msgStatus from subjectMsgs_".$subjectIndx;
    $result=execSql($sqlStr);
    $msgStatus=-1;
    if ($row=mysql_fetch_array($result)) $msgStatus=$row["msgStatus"];
        
    if ($msgStatus==0 || $msgStatus==3)
    {
          if ($isFinal=="1")
          {
            $sqlStr=" update ditching_".$subjectIndx.
                    " set ownerDitchStatus=2,partnerDitchStatus=2";
          }
          else
          {
                $sqlStr="select c from ditching_".$subjectIndx." where ditchInitiator is null";
                $result=execSql($sqlStr);

                if (mysql_num_rows($result)==1)
                    $sqlStr=" update ditching_".$subjectIndx.
                        " set ditchInitiator='".$sessionId."',ownerDitchStatus=1,partnerDitchStatus=1";
          }
          $result=execSql($sqlStr);
    }
    else
        $ajaxRslt=0;

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
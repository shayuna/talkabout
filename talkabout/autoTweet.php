<?php

    $maxChars=139;
    $uniqueKey=rand(1,9999);
    $msgsNumToTweet=50;
    
    //there is a lot of db work done in vain.
    // only if there are tweets to deliver or tweets to delete you should proceed
    // otherwise go to sleep
    $isTweetsToSend=false;
    $isTweetsToDel=false;
    
    
    $link = mysql_connect('localhost', 'tolkab5_shayuna', 'abra!2206');
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }

    $result=mysql_select_db("tolkab5_1");

    if (!$result) {
        die('Could not connect: ' . mysql_error());
    }

    $result=execSql("select c from tweets where status is null");
    if (mysql_num_rows($result)>0) $isTweetsToSend=true;
        
    $result=execSql("select c from tweetedMsgs");
    if (mysql_num_rows($result)>0) $isTweetsToDel=true;
        
    if (!$isTweetsToSend && !$isTweetsToDel)
    {
        mysql_close($link);
        die("nothing to do here");    
    }
    
                                 
    require("twitteroauth/twitteroauth.php");

    define("CONSUMER_KEY", "qYFP661wFcGulakfbOgFA");
    define("CONSUMER_SECRET", "fsuNHIYuFReJkHm1QMKIBWauxpDuNPRbnm9yRVqBI");
    define("OAUTH_TOKEN", "175704500-lNEOECLZeGV9Prc8pflWgBApYa7Mgob6TYfgIBJO");
    define("OAUTH_SECRET", "F6brooM32WVkmv3TLQD344KqKJveQbLAurCBjl8gfU");

    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);
    $content = $connection->get('account/verify_credentials');
    
    if ($isTweetsToSend)
    {
        $query=execSql("update tweets set status=".$uniqueKey." where status is null limit ".$msgsNumToTweet);
        $query=execSql("select c,subject from tweets where status=".$uniqueKey." order by c");
        while ($row=mysql_fetch_array($query))
        {
            $subTxtAr[]=$row["subject"];
            $subStAr[]=0;
            $subIndxAr[]=$row["c"];
        }
        
        
        $strToTweet="want to tolkabout ? click on http://www.tolkabout.com";
        $jj=0;
    
        for ($kk=1;$kk<=10;$kk++)
        {
            if ($jj<count($subStAr))
            {
                $subjectsList="";
                while ( (strlen($strToTweet)+strlen($subjectsList)<$maxChars) and ($jj<count($subStAr)) )
                {
                   $subjectsList=$subjectsList." #".str_replace(" ","_",$subTxtAr[$jj]);
                   $subStAr[$jj]=1;
                   $jj=$jj+1;        
                } 
            
                if (strlen($strToTweet)+strlen($subjectsList)>$maxChars)
                {
                    $jj=$jj-1;
                    if ($jj==0)
                    {
                        $subjectsList=substr($subjectsList,1,strlen($subjectsList)-(strlen($strToTweet)
                            +strlen($subjectsList)-$maxChars));
                    }
                    else
                    {
                       $tmpSubjectsListAr=split(" ",$subjectsList);
                       unset($tmpSubjectsListAr[count($tmpSubjectsListAr)-1]);
                       $subjectsList=join(" ",$tmpSubjectsListAr);
                       $subStAr[$jj]=0;
                    }
                        
                }    
                $msgToTweet[]=str_replace("?",$subjectsList." ?",$strToTweet);
            }
            else
                break;
        }
    
        if ($jj<count($subStAr))
        {
            execSql("update tweets set status=null where status=".$uniqueKey." and c>".$subIndxAr[$jj-1]);
        }
    //    print_r($msgToTweet);
        for ($jj=0;$jj<count($msgToTweet);$jj++)
        {
            $rslt = $connection->post('statuses/update', array('status' => $msgToTweet[$jj]));
            if ($rslt)                                                                     
                execSql("insert into tweetedMsgs (id,birthDt) values ('".$rslt->id."',Now())");
        }
    }    

    if ($isTweetsToDel)
    {
        $query=execSql("select c,id from tweetedMsgs where time_to_sec(timediff(Now(),birthDt))>290");
            while ($row=mysql_fetch_array($query))
        {
            $connection->post("statuses/destroy/".$row["id"]);
            execSql("delete from tweetedMsgs where c=".$row["c"]);    
        }
    }

    mysql_close($link);

/*

    $timeline=$connection->get("statuses/home_timeline");
//    print_r($timeline[0]->id);
    print_r($timeline[0]->created_at);
    exit;

*/

    function execSql($sqlStr)
    {
    	$result = mysql_query($sqlStr);
    	if (!$result){
    		die(mysql_error());
    	}
    	return $result;
    }


?>
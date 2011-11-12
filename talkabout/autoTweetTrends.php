<?php

    require("twitteroauth/twitteroauth.php");

    define("CONSUMER_KEY", "qYFP661wFcGulakfbOgFA");
    define("CONSUMER_SECRET", "fsuNHIYuFReJkHm1QMKIBWauxpDuNPRbnm9yRVqBI");
    define("OAUTH_TOKEN", "175704500-lNEOECLZeGV9Prc8pflWgBApYa7Mgob6TYfgIBJO");
    define("OAUTH_SECRET", "F6brooM32WVkmv3TLQD344KqKJveQbLAurCBjl8gfU");

    $link = mysql_connect('localhost', 'tolkab5_shayuna', 'abra!2206');
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }

    $result=mysql_select_db("tolkab5_1");

    if (!$result) {
        die('Could not connect: ' . mysql_error());
    }

    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);
    $content = $connection->get('account/verify_credentials');

    // every tweet older than 4 hours should be wiped off the face of the earth                                                 
    $query=execSql("select id from tweetedTrends where time_to_sec(timediff(Now(),birthDt))>14400");
    while ($row=mysql_fetch_array($query))
    {
        $connection->post("statuses/destroy/".$row["id"]);
        execSql("delete from tweetedTrends where id='".$row["id"]."'");
    }



    $trends = file_get_contents("http://search.twitter.com/trends/current.json");
    $trendsAr=json_decode($trends);
    
    $tweetTemplate="want to tolkabout *&* ? click on http://www.tolkabout.com?s=*&*";
    
    foreach ($trendsAr->trends as $itm)
    {
        for ($jj=0;$jj<count($itm);$jj++)
        {
           $trendySubject=str_replace("#","",$itm[$jj]->name);
//           $trendySubject="no one here";
           $trendySubject=str_replace(" ","_",$trendySubject);
           $tweetStr=str_replace("*&*",$trendySubject,$tweetTemplate);
           if (strlen($tweetStr)<=140)
            {
                $rslt = $connection->post('statuses/update', array('status' => $tweetStr));
                if ($rslt) execSql("insert into tweetedTrends (id,birthDt) values ('".$rslt->id."',Now())");
            }
        }
    }

    mysql_close($link);

    function execSql($sqlStr)
    {
    	$result = mysql_query($sqlStr);
    	if (!$result){
    		die(mysql_error());
    	}
    	return $result;
    }

?>
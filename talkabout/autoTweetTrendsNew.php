<?php

    // the entire mechanism should adjust itself to one severe restriction dictated by twitter: max of 150 requests per hour
    // i have to shate this quantity between several tasks:
    // 1. get account timeline and delete all tweets in account (they are old news)
    // 2. get weekly trends and add them to the list of tweets to send
    // 3. get locations list.
    // 4. iterate over all the trends in each location and add them to the list
    // 5. post all the tweets in the list
    
    // this variable monitors the number of requests made so far
    $dancesMade=0;

    require("twitteroauth/twitteroauth.php");

    define("CONSUMER_KEY", "PjJRtZEgCke1aZaXyxA6NA");
    define("CONSUMER_SECRET", "5TaisJ93utRlMDTPI6TSSuqSbzoxrezgboadbh2As");
    define("OAUTH_TOKEN", "281726545-89GtT6z6hEPjVsDR5lfipuNd8jVSkxa9UkPhVcxa");
    define("OAUTH_SECRET", "0MV6zvgLpVsg9oikniiGiggtYCupU13oImclOqik");

    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);
    $content = $connection->get('account/verify_credentials');

    $myStatuses = getFromTwitter("http://api.twitter.com/1/statuses/user_timeline.json?screen_name=tolkabout22&count=65");
    $myStatusesAr=json_decode($myStatuses);

    // first remove all the tweets in my account
    foreach ($myStatusesAr as $itm)
    {
        $connection->post("statuses/destroy/".$itm->id);
        sleep(4);
        $dancesMade++;
    }
  
  $dt=mktime(0,0,0,date("m"),date("d")-10,date("Y"));
  $dtFrmt=date("Y",$dt)."-".date("m",$dt)."-".date("d",$dt);

  
  // get the most popular tweets of the week starting 10 days ago
  $weekly=getFromTwitter("http://api.twitter.com/1/trends/weekly.json?date=".$dtFrmt);
  $weeklyAr=json_decode($weekly);

  exitOnRateLimitExcess($weeklyAr->trends);    
   
  foreach ($weeklyAr->trends as $curWeek)
  {
      foreach ($curWeek as $itm)
      {
         if (!isSubjectInAr($itm->name))addSubjectToAr($itm->name);            
      }
      break;
  }

/*    
    for ($j=0;$j<10;$j++)
    {
        echo rand(1,100)." *** ";
    }
    
    
    exit();
*/    
    
    
    
    // get the id of all available places, for use later on
    $locations = getFromTwitter("http://api.twitter.com/1/trends/available.json");
    $locationsAr=json_decode($locations);

    // there is a per hour rate limit on using the above url. so if we crossed it, let's get out nicely
    exitOnRateLimitExcess($locationsAr);
    
    foreach ($locationsAr as $itm)
    {
        $woeid=$itm->woeid;
        if (strtolower($itm->placeType->name)=="country")
        {
            if ($dancesMade>85)break;
            
            // get the trending tweets for a specific location
            $addr= "http://api.twitter.com/1/trends/".$woeid.".json";
            $tweets=getFromTwitter($addr);
            $tweetsAr=json_decode($tweets);
        
            // there is a per hour rate limit on using the above url. so if we crossed it, let's get out nicely
            exitOnRateLimitExcess($tweetsAr);
    
            foreach ($tweetsAr as $itm1)
            {
                if ($itm1->trends)
                {
                    foreach ($itm1->trends as $itm2)
                    {
                        if (!isSubjectInAr($itm2->name))addSubjectToAr($itm2->name);
                    }
                    
                }
            }
        }        
    }


    $tweetTemplate="want to tolkabout *#* ? click on http://www.tolkabout.com?s=*&*";

    // we want to keep the num of tweets posted vs num of tweets destroyed balanced. we don't want a belly.
    for ($jj=0;$jj<count($subjectsAr) && $dancesMade<=150 && $jj<65;$jj++)
    {
       $trendySubject=str_replace("#","",$subjectsAr[$jj]);
       $trendySubject=str_replace(" ","_",$trendySubject);
       $tweetStr=str_replace("*#*",$subjectsAr[$jj],$tweetTemplate);
       $tweetStr=str_replace("*&*",$trendySubject,$tweetStr);
       if (strlen($tweetStr)<=140)
        {
            sleep(4);
            $rslt = $connection->post('statuses/update', array('status' => $tweetStr));
            $dancesMade++;
        }
    }

    function isSubjectInAr($str)
    {
        global $subjectsAr;
        $isInArray=false;

        if (count($subjectsAr)>0)
        {
            foreach ($subjectsAr as $itm)
            {
                if  ($itm==$str)
                {
                    $isInArray=true;
                    break;
                }
            }
            return $isInArray;
        }
    }
    
    function addSubjectToAr($str)
    {
        global $subjectsAr;
        $subjectsAr[]=$str;
    }

    function exitOnRateLimitExcess($jsonStrct)
    {
          // there is a per hour rate limit on using the above url. so if we crossed it, let's get out nicely
//          if (count($jsonStrct)<=1) exit("rate limit of 150 tweets per hour made by twitter was exceeded. must wait");
    }
    
    function getFromTwitter($url)
    {
        global $dancesMade;
        $dancesMade++;
        return file_get_contents($url);        
    }

?>
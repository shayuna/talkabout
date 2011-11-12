<?php

    session_start();
    $subjectToTweet=$_GET['subjectToTweet'];
    $subjectToTweet=str_replace(" ","_",$subjectToTweet);
    $subjectToTweet="want to tolkabout #".$subjectToTweet."? click on http://www.tolkabout.com?s=".$subjectToTweet;
    require("twitteroauth/twitteroauth.php");


       // TwitterOAuth instance, with two new parameters we got in twitter_login.php
    if(!empty($_SESSION['username'])){
        $twitteroauth = new TwitterOAuth('qYFP661wFcGulakfbOgFA', 'fsuNHIYuFReJkHm1QMKIBWauxpDuNPRbnm9yRVqBI', $_SESSION['oauth_token'], $_SESSION['oauth_secret']);
        $twitteroauth->post('statuses/update', array('status' => $subjectToTweet));
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Twitter update</title>
    </head>
    <body onload="onLoad()">
    </body>
    <script language="javascript">
        function onLoad()
        {
            window.close();
        }    
    </script>
</html>
<?php
    session_start();
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
        document.location.href="twitter_update.php?subjectToTweet="+localStorage.getItem("subjectToTweet");        
    }
</script>

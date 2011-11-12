<?php

     $subjectToTweet=$_GET["subjectToTweet"];
?>
<!doctype html>
<html>
    <head>
        <title>tweet launching page</title>
        <script language="javascript">
            function onLoad()
            {
                var subjectToTweet="<?=$subjectToTweet?>";
                localStorage.setItem("subjectToTweet",subjectToTweet);
                window.open ("twitter_login.php","_self","width:500px;height:500px;")
            }
        </script>
    </head>
    <body onload="onLoad()">
    </body>



</html>
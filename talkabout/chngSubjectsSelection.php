<?php

    session_start();
    $newSubject=$_GET["newSubject"];
    $subjectFromList=$_GET["subjectFromList"];

    $_SESSION["newSubject"]=$newSubject;
    $_SESSION["subjectFromList"]=$subjectFromList;

?>
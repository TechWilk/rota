<?php
include('includes/config.php');
include('includes/functions.php');

 // you have to open the session first
 session_start();

    //if ($debug) notifyInfo(__FILE__,"logout",$_SESSION['userid']);	//only_for_testing//
    if ($debug) {
        insertStatistics("user", __FILE__, "logout");
    }



 //remove all the variables in the session
 session_unset();

 // destroy the session
 session_destroy();

 header("Location: login.php");

<?php
   $host = "localhost";
            $username = "trolyao2";
            $pass = "Sv@cusc2001";
            $db = "trolyao";
            $conn = mysqli_connect ($host,$username,$pass,$db);
            mysqli_query($conn,"SET character_set_results=utf8");
            mb_language('uni');
            mb_internal_encoding('UTF-8');       
            mysqli_query($conn,"set names 'utf8'");
?>
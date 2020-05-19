<?php
    session_start();
    //var_dump($_SESSION['dates']);
    //session_destroy();
    if(isset($_POST['keyword'])){
        $_SESSION['keyword'] = $_POST['keyword'];

        // if(isset($_POST['daterange'])){
        //     $_SESSION['dates'] = $_POST['dates'];
        //     //var_dump($_POST['dates']);
        // }
        //var_dump($_POST['dates']);
        //echo $key;
        //echo "SELECT `media-type`, `published` FROM `dataset` WHERE `Keywords` LIKE '%".$key."%' AND `published` BETWEEN '2015-09-01' AND '2015-09-30' ORDER BY `published`";
        // header("Location: new.php");
    }
    if(isset($_POST['daterange'])){
        $dates = explode("-", $_POST['daterange']);
        $_SESSION['start_date'] = $dates[0];
        //var_dump($dates);
        $_SESSION['end_date'] = $dates[1];
        // $_SESSION['dates'] = $_POST['dates'];
        // var_dump($_POST['dates']);
    }
    //var_dump($_POST['dates']);
    //echo $key;
    //echo "SELECT `media-type`, `published` FROM `dataset` WHERE `Keywords` LIKE '%".$key."%' AND `published` BETWEEN '2015-09-01' AND '2015-09-30' ORDER BY `published`";
    header("Location: index.php");
?>
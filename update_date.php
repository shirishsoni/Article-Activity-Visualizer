<?php
    session_start();
    
    if(isset($_POST['date'])){
        $dates = $_POST['date'].split(" ");
        $_SESSION['start_date'] = $dates[0];
        $_SESSION['end_date'] = $dates[1];
    }
?>
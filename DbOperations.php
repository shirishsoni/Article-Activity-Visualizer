<?php
    
    session_start();
    class DbOperation
    {
        //Database connection link
        private $con;
    
        //Class constructor
        function __construct()
        {
            //Getting the DbConnect.php file
            require_once 'DbConnection.php';
    
            //Creating a DbConnect object to connect to the database
            $db = new DbConnect();
    
            //Initializing our connection link of this class
            //by calling the method connect of DbConnect class
            $this->con = $db->connect();
            if (!mysqli_set_charset($this->con, "utf8")) {
                printf("Error loading character set utf8: %s\n", mysqli_error($this->con));
                exit();
            }
        }
        
        /*
        * The read operation
        * When this method is called it is returning all the existing row of the database
        */
        function getData(){
            $old_startdate = explode('/', $_SESSION['start_date']); 
            $start = rtrim($old_startdate[2]).'-'.$old_startdate[0].'-'.$old_startdate[1];
            
            $old_enddate = explode('/', $_SESSION['end_date']);
            $end = $old_enddate[2].'-'.ltrim($old_enddate[0]).'-'.$old_enddate[1];

            if(isset($_SESSION['keyword'])){
                $key = $_SESSION['keyword'];
                $stmt = $this->con->prepare("SELECT `Sentiment`, `published` FROM `dataset` WHERE `Keywords` LIKE '%".$key."%' AND `published` BETWEEN '".$start."' AND '".$end."' ORDER BY `published` LIMIT 10000"); 
            } else{
                $stmt = $this->con->prepare("SELECT `Sentiment`, `published` FROM `dataset` WHERE `published` BETWEEN '".$start."' AND '".$end."' ORDER BY `published` LIMIT 10000");
            }
            
            $stmt->execute();
            $stmt->bind_result($sentiment, $p_date);//, $open, $mid);
            
            $data = array(); 
            $date = NULL;
            $positive = 0;
            $negative = 0;
            $neutral = 0;
            //$unmatched = 0;
            while($stmt->fetch()){
                if(is_null($date)){
                    $date = $p_date;
                }
                if($date == $p_date){
                    if($sentiment > 0.10){
                        $positive++;
                    } else if ($sentiment < -0.10){
                        $negative++;
                    } else {
                        $neutral++;
                    }
                    //$unmatched++;
                } else {
                    $row = array();
                    $row['date'] = $date;
                    $row['positive'] = $positive;
                    $row['negative'] = $negative;
                    $row['neutral'] = $neutral;
                    array_push($data, $row);

                    $positive = 0;
                    $negative = 0;
                    $neutral = 0;
                    $date = $p_date;
                    if($sentiment > 0.10){
                        $positive++;
                    } else if ($sentiment < -0.10){
                        $negative++;
                    } else {
                        $neutral++;
                    }
                }
                
            }
            //$row = array();
            //$row['date'] = "2015-06-25";
            //$row['unmatched'] = $unmatched;

            //array_push($data, $row);
            return json_encode($data);
        }

        function getMediaData(){
            $old_startdate = explode('/', $_SESSION['start_date']); 
            $start = rtrim($old_startdate[2]).'-'.$old_startdate[0].'-'.$old_startdate[1];
            
            $old_enddate = explode('/', $_SESSION['end_date']);
            $end = $old_enddate[2].'-'.ltrim($old_enddate[0]).'-'.$old_enddate[1];

            if(isset($_SESSION['keyword'])){
                $key = $_SESSION['keyword'];
                $stmt = $this->con->prepare("SELECT `media-type`, `published` FROM `dataset` WHERE `Keywords` LIKE '%".$key."%' AND `published` BETWEEN '".$start."' AND '".$end."' ORDER BY `published` LIMIT 10000"); 
            } else{
                $stmt = $this->con->prepare("SELECT `media-type`, `published` FROM `dataset` WHERE `published` BETWEEN '".$start."' AND '".$end."' ORDER BY `published` LIMIT 10000");
            }
            
            $stmt->execute();
            $stmt->bind_result($media, $p_date);
            
            $data = array(); 
            $date = NULL;
            $news = 0;
            $blog = 0;
            
            
            while($stmt->fetch()){
                if(is_null($date)){
                    $date = $p_date;
                }
                if($date == $p_date){
                    switch($media){
                        case "News":
                            $news++;
                            break;
                        case "Blog":
                            $blog++;
                            break;
                    }
                
                } else {
                    $row = array();
                    $row['date'] = $date;
                    $row['blog'] = $blog;
                    $row['news'] = $news;
                    array_push($data, $row);

                    $blog = 0;
                    $news = 0;
                    $neutral = 0;
                    $date = $p_date;
                    switch($media){
                        case "News":
                            $news++;
                            break;
                        case "Blog":
                            $blog++;
                            break;
                    }
                }
                
            }
            return json_encode($data);
        }

        function getTableData(){
            $old_startdate = explode('/', $_SESSION['start_date']); 
            $start = rtrim($old_startdate[2]).'-'.$old_startdate[0].'-'.$old_startdate[1];
            
            $old_enddate = explode('/', $_SESSION['end_date']);
            $end = $old_enddate[2].'-'.ltrim($old_enddate[0]).'-'.$old_enddate[1];
            //echo "SELECT `Summarization`, `media-type`, `published`,`content`, `source`, `title` FROM `dataset` WHERE `published` BETWEEN '".$start."' AND '".$end."' ORDER BY `published` LIMIT 100";
            //var_dump($end);
            
            if(isset($_SESSION['keyword'])){
                $key = $_SESSION['keyword'];
                $stmt = $this->con->prepare("SELECT `id`,`Summarization`, `media-type`, `published`,`content`, `source`, `title`, `Sentiment` FROM `dataset` WHERE `Keywords` LIKE '%".$key."%' AND `published` BETWEEN '".$start."' AND '".$end."' ORDER BY `published`"); 
                //echo "SELECT `Summarization`, `media-type`, `published`,`content`, `source`, `title` FROM `dataset` WHERE `Keywords` LIKE '%".$key."%' AND `published` BETWEEN '".$start."' AND '".$end."' ORDER BY `published` LIMIT 10";
            } else{
                $stmt = $this->con->prepare("SELECT `id`,`Summarization`, `media-type`, `published`,`content`, `source`, `title`, `Sentiment` FROM `dataset` WHERE `published` BETWEEN '".$start."' AND '".$end."' ORDER BY `published`");
            } 
            // $stmt = $this->con->prepare("SELECT `Summarization`, `media-type`, `published`,`content`, `source`, `title` FROM `dataset` WHERE `published` BETWEEN '2015-09-01' AND '2015-09-30' ORDER BY `published` LIMIT 100");
            $stmt->execute();
            $stmt->bind_result($id, $summary, $media, $p_date, $content, $source, $title, $sentiment);
            
            $data = array(); 
            $i=0;
            while($stmt->fetch()){
                $row = array();
                // array_push($row, $title);
                // //array_push($row, $summary);
                // array_push($row, $p_date);
                // array_push($row, $source);
                // array_push($row, $media);

                // $row->title = $title;
                // $row->summary = $summary;
                // $row->date = $p_date;
                // $row->source = $source;
                // $row->media = $media;

                $row = array();
                $row['DT_RowId'] = "id_".$id;
                $row['title'] = $title;
                // if(!isnull($summary)){ 
                    $row['summary'] = $summary;
                // } else {
                    // $row['summary'] = $content;
                // }
                $row['date'] = $p_date;
                $row['source'] = $source;
                $row['media'] = $media;
                $row['content'] = $content;
                $row['sentiment'] = $sentiment;
                array_push($data, $row);     
            }
           
            $retData = new stdClass();
            $retData->data = $data;
            return json_encode($retData);
        }
    }

?>
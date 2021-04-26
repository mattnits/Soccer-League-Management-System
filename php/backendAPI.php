<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "leaguemanagement";

$api = $_REQUEST["api"];

switch ($api) {
    case 'getSchedule':
        $leagueID = $_REQUEST["leagueID"];

        if (!isset($leagueID) || !is_numeric($leagueID) || !is_int(intval($leagueID))) {
            // echo $leagueID;
            echo 500;
            break;
        }

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
    
        $sql = "SELECT * FROM Game WHERE leagueID = $leagueID ORDER BY datePlayed ASC";

        $result = $conn->query($sql);
        $resultArray = [];

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $resultArray[] = $row;
            }
            $jsonS = json_encode($resultArray);

            echo $jsonS;
            break;
        } else {
            // No DATA
            echo 204;
            break;
        }
        break;
    
        case 'getLeagues':    
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
        
            $sql = "SELECT *, t.teamCount 
                    FROM League as l
                    LEFT JOIN
                        (SELECT _LeagueID, count(*) as teamCount
                         FROM Team
                         GROUP BY _LeagueID) 
                        AS t ON t._LeagueID = l.LeagueID";
    
            $result = $conn->query($sql);
            $resultArray = [];
    
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    $resultArray[] = $row;
                }
                $jsonS = json_encode($resultArray);
    
                echo $jsonS;
                break;
            } else {
                // No DATA
                echo 204;
                break;
            }

    default:
        echo 500;
        break;
}
?>
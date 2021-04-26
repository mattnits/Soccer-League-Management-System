<?php
    $values = $_POST["values"];

    // Decode the JSON and decide what to do with incoming data
    $data = json_decode($values, true);
    if ($data["purpose"] == "sortLeaderBoard") {
        adjust_leaders($data["name"],$data["leagueid"]);
    }
    else if ($data["purpose"] == "init") {
        opening($data["leagueid"]);
    }

    function adjust_leaders($name,$leagueID){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "leaguemanagement";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);


        
        if ($name=="Team Name"){
            $name = "TeamName";
            $sql = "SELECT * FROM team WHERE _LeagueID = $leagueID ORDER BY $name";
        }
        else if ($name=="Points" || $name =="Team Record" ){
            $sql = "SELECT * FROM team WHERE _LeagueID = $leagueID ORDER BY Win*2+Tie DESC";
        }
        else if ($name=="Team ID"){
            $sql = "SELECT * FROM team WHERE _LeagueID = $leagueID ORDER BY Teamid";
        }

      
        
        $result = $conn->query($sql);
        

        $out="";
        if ($result = $conn->query($sql)) {
            // Insertion Complete
        
            
            while($row = $result->fetch_assoc()){
                $win = $row["Win"];
                $tie = $row["Tie"];
                $points = $win*3+$tie;
                $out .= "<tr class='ID'> <td id='teamID'>". $row["Teamid"]. "</td> <td id ='teamName'>". $row['TeamName']. "<td id = 'record'>". $row['Win']. "-". $row['Loss'].
                 "-". $row['Tie']. "<td id = 'points'>". $points. "</td></tr>";  
            }
        //  </td> <td id='teamPoint'>". $wins["Wins"]. "</td> </tr>
        $arr = array("Teams" => $out);
        $myjson = json_encode($arr);
        
        echo $myjson;

        }
        else {
            // Otherwise show error message
            echo "<script>alert('error!');</script>";
            
            
        }  
        $conn->close();
    }    
    function opening($leagueID){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "leaguemanagement";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);


       


        $sql = "SELECT * FROM team WHERE _LeagueID = $leagueID ORDER BY Win*2+Tie DESC";
        
        $result = $conn->query($sql);
        
        $counter = 1;
        $out="";
        if ($result = $conn->query($sql)) {
            // Insertion Complete
        
            
            while($row = $result->fetch_assoc()){
                $win = $row["Win"];
                $tie = $row["Tie"];
                $points = $win*3+$tie;
                $out .= "<tr class='ID'> <td id='teamID'>". $counter. "</td> <td id ='teamName'>". $row['TeamName']. "<td id = 'record'>". $row['Win'].
                 "-". $row['Loss']. "-". $row['Tie']. "<td id = 'points'>". $points. "</td></tr>";  
                $counter +=1 ;
            }
        //  </td> <td id='teamPoint'>". $wins["Wins"]. "</td> </tr>
        $arr = array("Teams" => $out);
        $myjson = json_encode($arr);
        
        echo $myjson;

        }
        else {
            // Otherwise show error message
            echo "<script>alert('error!');</script>";
            
            
        }  
        $conn->close();
    }    

?>
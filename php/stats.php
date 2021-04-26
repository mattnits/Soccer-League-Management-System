<?php
    $values = $_POST["values"];

    // Decode the JSON and decide what to do with incoming data
    $data = json_decode($values, true);
    if ($data["purpose"] == "sortTable") {
        adjust_table($data["name"],$data["leagueid"]);
    }
    else if ($data["purpose"] == "init") {
        opening($data["leagueid"]);
    }

    function adjust_table($name, $leagueID){
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
        else if ($name=="Wins"){
            $name = "Win";
            $sql = "SELECT * FROM team WHERE _LeagueID = $leagueID ORDER BY $name DESC";
        }
        else if ($name == "Losses"){
            $name = "Loss";
            $sql = "SELECT * FROM team WHERE _LeagueID = $leagueID ORDER BY $name DESC";
        }
        else if ($name=="Ties"){
            $name = "Tie";
            $sql = "SELECT * FROM team WHERE _LeagueID = $leagueID ORDER BY $name DESC";
        }
        else if($name=="Games Played"){
            $sql = "SELECT * FROM team WHERE _LeagueID = $leagueID ORDER BY Win+Loss+Tie DESC";
        }
        else if ($name=="Points"){
            $sql = "SELECT * FROM team WHERE _LeagueID = $leagueID ORDER BY Win*2+Tie DESC";
        }
        else if($name=="Goals For"){
            $sql ="SELECT * FROM team WHERE _LeagueID = $leagueID ORDER BY (SELECT SUM(homeScore) FROM game WHERE _LeagueID = $leagueID AND homeTeamID = Teamid)+(SELECT SUM(awayScore) FROM game WHERE _LeagueID = $leagueID AND awayTeamID = Teamid)DESC"; 
        }
        else if($name=="Goals Against"){
            $sql ="SELECT * FROM team WHERE _LeagueID = $leagueID ORDER BY (SELECT SUM(awayScore) FROM game WHERE homeTeamID = Teamid)+(SELECT SUM(homeScore) FROM game WHERE awayTeamID = Teamid)DESC"; 
        }
        // $sql = "SELECT * FROM team WHERE _LeagueID = $leagueID ORDER BY $name";
        
        $result = $conn->query($sql);


        

        $out="";
        if ($result = $conn->query($sql)) {
            if ($result->num_rows > 0) {
                // Insertion Complete
            
                while($row = $result->fetch_assoc()){
                    $teamid=$row['Teamid'];
                    //calcualte goals for
                    $sql2 = "SELECT SUM(homeScore) AS 'Hgoals' FROM game WHERE homeTeamID = '$teamid'";
                    $goalsfor = $conn->query($sql2);
                    $hgoals=$goalsfor->fetch_assoc();
                    
                    $sql2 = "SELECT SUM(awayScore) AS 'Agoals' FROM game WHERE awayTeamID = '$teamid'";
                    $goalsfor = $conn->query($sql2);
                    $agoals=$goalsfor->fetch_assoc();
                    $goalsfor = $hgoals['Hgoals'] + $agoals['Agoals'];

                    //salc goals against
                    $sql2 = "SELECT SUM(awayScore) AS 'goalsa' FROM game WHERE homeTeamID = '$teamid'";
                    $goalsagainst = $conn->query($sql2);
                    $goalsa=$goalsagainst->fetch_assoc();
                    
                    $sql2 = "SELECT SUM(homeScore) AS 'goalsh' FROM game WHERE awayTeamID = '$teamid'";
                    $goalsagainst = $conn->query($sql2);
                    $goalsh=$goalsagainst->fetch_assoc();
                    $goalsagainst = $goalsa['goalsa'] + $goalsh['goalsh'];

                    $win = $row['Win'];
                    $tie = $row["Tie"];
                    $loss = $row["Loss"];
                    $points = $win*3 + $tie; 
                    $gameplayed = $win + $loss + $tie;
                    // $goalfor = 0;
                    
                    $out .= "<tr class='ID'> <td id='teamName'>". $row["TeamName"]. "</td> <td id='gp'>". $gameplayed. "</td> <td id='points'>". $points. "</td> <td id='wins'>". $win. "</td>
                    <td id='loss'>". $loss. "</td> <td id='tie'>". $tie. "</td> <td id='goalfor'>". $goalsfor. "</td> <td id='goalagainst'>". $goalsagainst . "</td> </tr>";  
                }
            //  </td> <td id='teamPoint'>". $wins["Wins"]. "</td> </tr>
            $arr = array("Teams" => $out);
            $myjson = json_encode($arr);
            
            echo $myjson;
            }
        }
        else {
            // Otherwise show error message
            echo "('error in adjust table')";
            
            
        }  
        $conn->close();
        
    }
    function opening ($leagueID){


        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "leaguemanagement";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

        //need to get from cookies
        


        $sql = "SELECT * FROM team WHERE _LeagueID = $leagueID";
        
        $result = $conn->query($sql);


        

        $out="";
        if ($result = $conn->query($sql)) {
            // Insertion Complete
        
            while($row = $result->fetch_assoc()){
                $teamid=$row['Teamid'];
                //calcualte goals for
                $sql2 = "SELECT SUM(homeScore) AS 'Hgoals' FROM game WHERE homeTeamID = '$teamid'";
                $goalsfor = $conn->query($sql2);
                $hgoals=$goalsfor->fetch_assoc();
                
                $sql2 = "SELECT SUM(awayScore) AS 'Agoals' FROM game WHERE awayTeamID = '$teamid'";
                $goalsfor = $conn->query($sql2);
                $agoals=$goalsfor->fetch_assoc();
                $goalsfor = $hgoals['Hgoals'] + $agoals['Agoals'];

                //salc goals against
                $sql2 = "SELECT SUM(awayScore) AS 'goalsa' FROM game WHERE homeTeamID = '$teamid'";
                $goalsagainst = $conn->query($sql2);
                $goalsa=$goalsagainst->fetch_assoc();
                
                $sql2 = "SELECT SUM(homeScore) AS 'goalsh' FROM game WHERE awayTeamID = '$teamid'";
                $goalsagainst = $conn->query($sql2);
                $goalsh=$goalsagainst->fetch_assoc();
                $goalsagainst = $goalsa['goalsa'] + $goalsh['goalsh'];

                $win = $row['Win'];
                $tie = $row["Tie"];
                $loss = $row["Loss"];
                $points = $win*3 + $tie; 
                $gameplayed = $win + $loss + $tie;
                // $goalfor = 0;
                
                $out .= "<tr class='ID'> <td id='teamName'>". $row["TeamName"]. "</td> <td id='gp'>". $gameplayed. "</td> <td id='points'>". $points. "</td> <td id='wins'>". $win. "</td>
                <td id='loss'>". $loss. "</td> <td id='tie'>". $tie. "</td> <td id='goalfor'>". $goalsfor. "</td> <td id='goalagainst'>". $goalsagainst . "</td> </tr>";  
            }
        //  </td> <td id='teamPoint'>". $wins["Wins"]. "</td> </tr>
        $arr = array("Teams" => $out);
        $myjson = json_encode($arr);
        
        echo $myjson;

        }
        else {
            // Otherwise show error message
            echo "error on start up";
            
            
        }  
        $conn->close();

    }
?>
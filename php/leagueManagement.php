<?php
    $values = $_POST["values"];

    // Decode the JSON and decide what to do with incoming data
    $data = json_decode($values, true);
    if ($data["purpose"] == "submitScore") {
        @submitScore($data["gameID"], $data["gameDate"], $data["hName"], $data["hScore"], $data["aName"], $data["aScore"], $data["league"], $data["username"]);
    }
    else if ($data["purpose"] == "onloading") {
        @updateAll("ok", $data["league"], $data["username"]);
    }
    else if ($data["purpose"] == "submitTeam") {
        @submitTeam($data["teamID"], $data["teamName"], $data["league"], $data["username"]);
    }
    else if ($data["purpose"] == "updateTeam") {
        @updateTeam($data["teamID"], $data["teamName"], $data["league"], $data["username"]);
    }
    else if ($data["purpose"] == "submitLeague") {
        @createLeague($data["leagueName"], $data["league"], $data["username"]);
    }
    else if ($data["purpose"] == "submitSchedule") {
        @submitSchedule($data["leagueName"], $data["sid"], $data["sdate"], $data["hteam"], $data["ateam"], $data["league"], $data["username"]);
    }
    else if ($data["purpose"] == "changeLeague") {
        @updateAll("ok", $data["league"], $data["username"]);
    }
    else if ($data["purpose"] == "updateScore") {
        @updateScore($data["gid"], $data["gdate"], $data["hname"], $data["hscore"], $data["aname"], $data["ascore"], $data["league"], $data["username"]);
    }
    else if ($data["purpose"] == "updateSchedule") {
        @updateSchedule($data["gid"], $data["gdate"], $data["hname"], $data["aname"], $data["league"], $data["username"]);
    }

    // Submit score
    function submitScore($gid, $gdate, $hname, $hscore, $aname, $ascore, $league, $user) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "leaguemanagement";

        $homeID = "";
        $awayID = "";
        $leagueID = "";

        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

        // Get home ID
        $sql = "SELECT * FROM team WHERE TeamName = '$hname'";
        
        // If theres results, get the team ID
        if ($result = $conn->query($sql)) {
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $homeID = $row["Teamid"];
            }
        }
        // Otherwise show error message
        else {
            $errStr = "Error in sql statement (Submit Score - 1)";
            updateAll($errStr, $league, $user);
        }

        // Get away ID

        $sql = "SELECT * FROM team WHERE TeamName = '$aname'";
        
        // If theres results, get the team name
        if ($result = $conn->query($sql)) {
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $awayID = $row["Teamid"];
            }
        }
        else {
            // Otherwise show error message
            updateAll("Error in sql statement (Submit Score - 2)" , $league, $user);
        }

        // Get league ID
        $sql = "SELECT * FROM league WHERE LeagueName = '$league'";

        if ($result = $conn->query($sql)) {
            // Insertion Complete
            $row = $result->fetch_assoc();
            $leagueID = $row["LeagueID"];
            // array_push($tests, $leagueID);
            // array_push($tests, $league);
        }
        else {
            // Otherwise show error message
            $error = ("Error in sql statement (Submit Score admin)");
        }

        // Add wins losses and ties to the table
        if ($hscore > $ascore) {
            $sql1 = "UPDATE team SET Win = Win + 1 WHERE TeamName = '$hname'";
            $sql2 = "UPDATE team SET Loss = Loss + 1 WHERE TeamName = '$aname'";
        }
        else if ($hscore < $ascore) {
            $sql1 = "UPDATE team SET Win = Win + 1 WHERE TeamName = '$aname'";
            $sql2 = "UPDATE team SET Loss = Loss + 1 WHERE TeamName = '$hname'";
        }
        else {
            $sql1 = "UPDATE team SET Tie = Tie + 1 WHERE TeamName = '$aname'";
            $sql2 = "UPDATE team SET Tie = Tie + 1 WHERE TeamName = '$hname'";
        }

        // // If theres results, get the team name
        if ($result = $conn->query($sql1)) {
            
        }
        else {
            // Otherwise show error message
            updateAll("Error in sql statement (Submit Score - Update WLT1)", $league, $user);
        }

        if ($result = $conn->query($sql2)) {
            
        }
        else {
            // Otherwise show error message
            updateAll("Error in sql statement (Submit Score - Update WLT2)", $league, $user);
        }
        
        // Insert into the table
        $sql = "INSERT INTO game(leagueID, datePlayed, homeTeam, homeTeamID, awayTeam, awayTeamID, homeScore, awayScore) VALUES ('$leagueID', '$gdate', '$hname', '$homeID', '$aname', '$awayID', '$hscore', '$ascore')";
            
        if ($result = $conn->query($sql)) {
            // Insertion Complete
            updateAll("ok", $league, $user);
        }
        else {
            // Otherwise show error message
            updateAll("Error in sql statement (Submit Score - 3)", $league, $user);
            
        }
        
        $conn->close();
        
    }

    // Submit team
    function submitTeam($tid, $tname, $league, $user) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "leaguemanagement";

        $leagueID = "";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);


        // Get league ID
        $sql = "SELECT * FROM league WHERE LeagueName = '$league'";

        if ($result = $conn->query($sql)) {
            // Insertion Complete
            $row = $result->fetch_assoc();
            $leagueID = $row["LeagueID"];
            // array_push($tests, $leagueID);
            // array_push($tests, $league);
        }
        else {
            // Otherwise show error message
            $error = ("Error in sql statement (Submit Score admin)");
        }

        // Insert into the table
        $sql = "INSERT INTO team(TeamName, _LeagueID, Win, Loss, Tie) VALUES ('$tname', '$leagueID', '0', '0', '0')";
            
        if ($result = $conn->query($sql)) {
            // Insertion Complete
            updateAll("ok", $league, $user);
        }
        else {
            // Otherwise show error message
            updateAll("Error in sql statement (Submit Team)", $league, $user);
        }
        
        $conn->close();
    }

    // Updates data in the team table
    function updateTeam($tid, $tname, $league, $user) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "leaguemanagement";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

        $sql = "SELECT Teamid FROM team WHERE TeamName = '$tid'";

        if ($result = $conn->query($sql)) {
            // Insertion Complete
            $row = $result->fetch_assoc();
            $tid = $row["Teamid"];
        }
        else {
            // Otherwise show error message
            updateAll("Error in sql statement (Update Team 1)", $league, $user);
        }

        // Insert into the table
        $sql = "UPDATE team SET TeamName = '$tname' WHERE Teamid = '$tid'";
            
        if ($result = $conn->query($sql)) {
            // Insertion Complete
            updateAll("ok", $league, $user);
        }
        else {
            // Otherwise show error message
            updateAll("Error in sql statement (Update Team) $tid, $tname", $league, $user);
        }
        
        $conn->close();
    }

    // Creates a league in the league table
    function createLeague($lname, $league, $user) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "leaguemanagement";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

        $adminUsername = $user;
        $adminID = "";

        $sql = "SELECT Adminid as adminid FROM admin WHERE AdminName = '$adminUsername'";

        if ($result = $conn->query($sql)) {
            // Insertion Complete
            $row = $result->fetch_assoc();
            $adminID = $row["adminid"];
        }
        else {
            // Otherwise show error message
            updateAll("Error in sql statement (Create League)", $league, $user);
        }



        $sql = "INSERT INTO league(LeagueName, AdminID) VALUES ('$lname', '$adminID')";
            
        if ($result = $conn->query($sql)) {
            // Insertion Complete
            updateAll("ok", $league, $user);
        }
        else {
            // Otherwise show error message
            updateAll("Error in sql statement (Create League)", $league, $user);
        }
        
        
        
        $conn->close();
    }


    // Insert data into the schedule table
    function submitSchedule($lname, $gid, $gdate, $hname, $aname, $league, $user) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "leaguemanagement";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

        $adminUsername = $user;
        $adminID = "";
        $homeID ="";
        $awayID="";
        $leagueID="";

        // Get home ID
        $sql = "SELECT * FROM team WHERE TeamName = '$hname'";
        
        // If theres results, get the team ID
        if ($result = $conn->query($sql)) {
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $homeID = $row["Teamid"];
            }
        }
        // Otherwise show error message
        else {
            $errStr = "Error in sql statement (Submit Schedule - 1)";
            updateAll($errStr, $league, $user);
        }

        // Get away ID

        $sql = "SELECT * FROM team WHERE TeamName = '$aname'";
        
        // If theres results, get the team name
        if ($result = $conn->query($sql)) {
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $awayID = $row["Teamid"];
            }
        }
        else {
            // Otherwise show error message
            updateAll("Error in sql statement (Submit Schedule - 2)", $league, $user);
        }

        // Get league ID
        $sql = "SELECT * FROM league WHERE LeagueName = '$league'";

        if ($result = $conn->query($sql)) {
            // Insertion Complete
            $row = $result->fetch_assoc();
            $leagueID = $row["LeagueID"];
            // array_push($tests, $leagueID);
            // array_push($tests, $league);
        }
        else {
            // Otherwise show error message
            $error = ("Error in sql statement (Update All Admin)");
        }

        
        // Insert into the table
        $sql = "INSERT INTO game(leagueID, datePlayed, homeTeam, homeTeamID, awayTeam, awayTeamID, homeScore, awayScore) VALUES ('$leagueID', '$gdate', '$hname', '$homeID', '$aname', '$awayID', '0', '0')";
            
        if ($result = $conn->query($sql)) {
            // Insertion Complete
            updateAll("ok", $league, $user);
        }
        else {
            // Otherwise show error message
            updateAll("Error in sql statement (Submit Schedule - 3)", $league, $user);
            
        }
        
        $conn->close();
    }

    function updateScore($gid, $gdate, $hname, $hscore, $aname, $ascore, $league, $username) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "leaguemanagement";

        $homeID = "";
        $awayID = "";
        $leagueID = "";

        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

        // Get home ID
        $sql = "SELECT * FROM team WHERE TeamName = '$hname'";
        
        // If theres results, get the team ID
        if ($result = $conn->query($sql)) {
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $homeID = $row["Teamid"];
            }
        }
        // Otherwise show error message
        else {
            $errStr = "Error in sql statement (Update Score - 1)";
            updateAll($errStr, $league, $user);
        }

        // Get away ID

        $sql = "SELECT * FROM team WHERE TeamName = '$aname'";
        
        // If theres results, get the team name
        if ($result = $conn->query($sql)) {
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $awayID = $row["Teamid"];
            }
        }
        else {
            // Otherwise show error message
            updateAll("Error in sql statement (Update Score - 2)" , $league, $user);
        }

        // // Get league ID
        // $sql = "SELECT * FROM league WHERE LeagueName = '$league'";

        // if ($result = $conn->query($sql)) {
        //     // Insertion Complete
        //     $row = $result->fetch_assoc();
        //     $leagueID = $row["LeagueID"];
        //     // array_push($tests, $leagueID);
        //     // array_push($tests, $league);
        // }
        // else {
        //     // Otherwise show error message
        //     $error = ("Error in sql statement (Update Score admin)");
        // }
        

        // Add wins losses and ties to the table
        $sql1 = "UPDATE game SET homeTeam = '$hname', homeTeamID = '$homeID', awayTeam = '$aname', awayTeamID = '$awayID', homeScore = '$hscore', awayScore = '$ascore', datePlayed = '$gdate' WHERE GameID = '$gid'";

        // // If theres results, get the team name
        if ($result = $conn->query($sql1)) {
            updateAll("ok", $league, $user);
        }
        else {
            // Otherwise show error message
            updateAll("Error in sql statement (Update Score - Update)", $league, $user);
        }
        
        
        $conn->close();
    }

    function updateSchedule($gid, $gdate, $hname, $aname, $league, $username) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "leaguemanagement";

        $homeID = "";
        $awayID = "";
        $leagueID = "";

        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

        // Get home ID
        $sql = "SELECT * FROM team WHERE TeamName = '$hname'";
        
        // If theres results, get the team ID
        if ($result = $conn->query($sql)) {
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $homeID = $row["Teamid"];
            }
        }
        // Otherwise show error message
        else {
            $errStr = "Error in sql statement (Update Schedule - 1)";
            updateAll($errStr, $league, $user);
        }

        // Get away ID

        $sql = "SELECT * FROM team WHERE TeamName = '$aname'";
        
        // If theres results, get the team name
        if ($result = $conn->query($sql)) {
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $awayID = $row["Teamid"];
            }
        }
        else {
            // Otherwise show error message
            updateAll("Error in sql statement (Update Schedule - 2)" , $league, $user);
        }

        // // Get league ID
        // $sql = "SELECT * FROM league WHERE LeagueName = '$league'";

        // if ($result = $conn->query($sql)) {
        //     // Insertion Complete
        //     $row = $result->fetch_assoc();
        //     $leagueID = $row["LeagueID"];
        //     // array_push($tests, $leagueID);
        //     // array_push($tests, $league);
        // }
        // else {
        //     // Otherwise show error message
        //     $error = ("Error in sql statement (Update Score admin)");
        // }
        

        // Add wins losses and ties to the table
        $sql1 = "UPDATE game SET homeTeam = '$hname', homeTeamID = '$homeID', awayTeam = '$aname', awayTeamID = '$awayID', datePlayed = '$gdate' WHERE GameID = '$gid'";

        // // If theres results, get the team name
        if ($result = $conn->query($sql1)) {
            updateAll("ok", $league, $user);
        }
        else {
            // Otherwise show error message
            updateAll("Error in sql statement (Update Schedule - Update)", $league, $user);
        }
        
        
        $conn->close();
    }

    function formatDateTime($dt) {
        $arr = array();

        $temp = explode(" ",$dt);

        array_push($arr, $temp[0]);
        
        $temp2 = explode(":", $temp[1]);
        $tstr = $temp2[0]. ":". $temp2[0];
        array_push($arr, $tstr);

        return $arr;
    }

    function updateAll($error, $league, $user) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "leaguemanagement";
        $htmlOutput = "";
        $htmlOutput2 = "";
        $htmlOutput3 = "";
        $leagues = array();
        $tests = array();
        $leagueID = "";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

        // Get the admins ID
        $adminUsername = $user;
        // array_push($tests, $user);
        
        $adminID = "";

        // Get the admin ID
        $sql = "SELECT Adminid as adminid FROM admin WHERE AdminName = '$adminUsername'";

        if ($result = $conn->query($sql)) {
            // Insertion Complete
            $row = $result->fetch_assoc();
            $adminID = $row["adminid"];
            
        }
        else {
            // Otherwise show error message
            $error = ("Error in sql statement (Update All Admin)");
        }

        // Get league ID
        $sql = "SELECT * FROM league WHERE LeagueName = '$league'";

        if ($result = $conn->query($sql)) {
            // Insertion Complete
            $row = $result->fetch_assoc();
            $leagueID = $row["LeagueID"];
            // array_push($tests, $leagueID);
            // array_push($tests, $league);
        }
        else {
            // Otherwise show error message
            $error = ("Error in sql statement (Update All Admin)");
        }

        // Get all queries for scores
        $actdate = date("Y-m-d h:i:s");
        $sql = "SELECT * FROM game WHERE datePlayed < '$actdate' AND leagueID = '$leagueID' ORDER BY datePlayed DESC";

        $cnt = 0;
        
        // UPDATE SCORES
        if ($result = $conn->query($sql)) {
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($tests, $row['datePlayed']);
                    $tarr = formatDateTime($row['datePlayed']);
                    array_push($tests, $tarr);
                    

                    $htmlOutput .= "<tr id='newRow'>". "<td id='gameIDData'>".  $row['GameID']. " </td>". "<td id='gameDateData'>".  $tarr[0]. " </td>". 
                    "<td id='gameHTData'>".  $row['homeTeam']. " </td>". "<td id='gameScoreData'>".  $row['homeScore']. "-". $row['awayScore']. 
                    " </td>". "<td id='gameATData'>".  $row['awayTeam']. " </td>" . "<td><button id='editButton1' style='position: relative;  border-radius: 20px; border-style: solid; background-color: #fff; margin-top: 5px; cursor: pointer;' onclick='editScore(this)'>Edit</button></td>". "</tr> ";
                    
                    // echo $htmlOutput;
                    
                    // if ($cnt == 9) {
                    //     break;
                    // }
                    $cnt++;
                }
                // test
            }

        }
        else {
            $error = "Error in sql statement (UpdateAll - Scores)";
            
        }
        $actdate = date("Y-m-d h:i:s");

        // Get all queries for schedule
        $sql = "SELECT * FROM game WHERE datePlayed > '$actdate' AND LeagueID = '$leagueID' ORDER BY datePlayed ASC";

        $cnt = 0;
        // UPDATE SCHEDULE
        if ($result = $conn->query($sql)) {
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $tarr = formatDateTime($row['datePlayed']);

                    $htmlOutput2 .= "<tr id='newRow'>".  "<td id='scheduleIDData' >".  $row['GameID']. " </td>".
                    "<td id='scheduleDateData' >".  $tarr[0]. " </td>".
                    "<td id='scheduleTimeData' >".  $tarr[1]. " </td>".
                    "<td id='scheduleHomeData' >".  $row['homeTeam']. " </td>".
                    "<td id='scheduleAwayData' >".  $row['awayTeam']. " </td>".
                    "<td><button id='editButton2' style='position: relative;  border-radius: 20px; border-style: solid; background-color: #fff; margin-top: 5px; cursor: pointer;' onclick='editSchedule(this)'>Edit</button></td>". "</tr> ";

                    // echo $htmlOutput3;
                    // if ($cnt == 9) {
                    //     break;
                    // }
                    $cnt++;
                }
                // array_push($arr, "test2");
            }

        }
        else {
            $error = "Error in sql statement (UpdateAll - Schedule)";
        }

        // Get all queries for team
        $sql = "SELECT * FROM team WHERE _LeagueID = '$leagueID'";

        $cnt = 0;
        // UPDATE TEAMS
        if ($result = $conn->query($sql)) {
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $htmlOutput3 .= "<tr id='newRow'>". "<td id='teamIDData' >".  
                    $row['Teamid']. " </td>". "<td id='teamNameData' >".  $row['TeamName']. " </td>". 
                    "<td><button id='editButton3' style='position: relative;  border-radius: 20px; border-style: solid; background-color: #fff; margin-top: 5px; cursor: pointer;' onclick='editTeam(this)'>Edit</button></td>". "</tr> ";

                    // echo $htmlOutput3;
                    // if ($cnt == 9) {
                    //     break;
                    // }
                    $cnt++;
                }
                // array_push($arr, "test2");
            }

        }
        else {
            $error = "Error in sql statement (UpdateAll - Teams)";
        }


        
        $sql = "SELECT LeagueName AS ln FROM league WHERE AdminID = '$adminID'";

        // Return admin user leagues
        if ($result = $conn->query($sql)) {
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($leagues, $row['ln']);
                }
                
            }

        }
        else {
            $error = "Error in sql statement (UpdateAll - Teams)";
        }

        
        $arr = array("scores" => $htmlOutput, "schedule" => $htmlOutput2, "teams" => $htmlOutput3, "leagues" => $leagues, "errors" => $error, "tests" => $tests, "leagueID" => $leagueID);

        $myJSON = json_encode($arr);
        echo $myJSON;
    }
    

?>


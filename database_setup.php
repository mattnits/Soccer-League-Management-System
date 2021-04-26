<?php 



// Default MySQL connection credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "LeagueManagement";


// MySQL server connection 
try {
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
    else echo "Connected successfully\n";
    $conn->close();
}
catch (Exception $e) {
    echo "Error: ". $e->getMessage(). "\n";
}

//  create a database
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
else echo "Connected successfully\n";
$sql = "CREATE DATABASE ".$dbname;
if ($conn->query($sql) === TRUE) {
  echo "Database created successfully";
} else {
  echo "Error: " . $conn->error;
}
$conn->close();

// League Table
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
else echo "Connected successfully\n";
$sql = "CREATE TABLE League (
    LeagueID INT AUTO_INCREMENT PRIMARY KEY,
    LeagueName char(50), 
    AdminID INT
)";
if ($conn->query($sql) === TRUE) {
  echo "Table created successfully";
} else {
  echo "Error: " . $conn->error;
}
$conn->close();

// SEED league table
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
else echo "Connected successfully\n";
$sql = "INSERT INTO League (LeagueName, AdminID) VALUES 
        ('testLeague', 1),
        ('testLeague2', 1),
        ('testLeague3', 1)";
if ($conn->query($sql) === TRUE) {
  echo "Table created successfully";
} else {
  echo "Error: " . $conn->error;
}
$conn->close();

// Team Table
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
else echo "Connected successfully\n";
$sql = "CREATE TABLE Team (
    Teamid INT AUTO_INCREMENT PRIMARY KEY,
    TeamName char(50),
    _LeagueID INT,
    Win INT DEFAULT 0,
    Loss INT DEFAULT 0,
    Tie INT DEFAULT 0
)";
if ($conn->query($sql) === TRUE) {
  echo "Table created successfully\n";
} else {
  echo "Error: " . $conn->error;
}
$conn->close();

// SEED team table
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
else echo "Connected successfully\n";
$sql = "INSERT INTO Team (TeamName, _LeagueID, Win, Loss, Tie) VALUES 
        ('WLU Hawks', 1, 3, 1, 2),
        ('WU Mustang', 1, 0, 2, 0),
        ('UW Warriors', 1, 0, 1, 1),
        ('UoT Varsity Blues', 1, 1, 0, 1),
        ('Test', 2, 0, 0, 0)";
if ($conn->query($sql) === TRUE) {
  echo "Table created successfully";
} else {
  echo "Error: " . $conn->error;
}
$conn->close();

// Game Table
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
else echo "Connected successfully\n";
$sql = "CREATE TABLE Game (
    GameID INT AUTO_INCREMENT PRIMARY KEY,
    leagueID INT,
    datePlayed DATETIME, 
    homeTeam char(20),
    homeTeamID INT,
    awayTeam char(20),
    awayTeamID INT,
    homeScore INT,
    awayScore INT
)";
if ($conn->query($sql) === TRUE) {
  echo "Table created successfully";
} else {
  echo "Error: " . $conn->error;
}
$conn->close();

// SEED game table
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
else echo "Connected successfully\n";
$sql = "INSERT INTO Game (leagueID, datePlayed, homeTeam, homeTeamID, awayTeam, awayTeamID, homeScore, awayScore) VALUES 
        (1, '2021-01-01 16:00:00', 'WLU Hawks', 1, 'WU Mustang', 2, 3, 1),
        (1, '2021-01-21 16:00:00', 'WLU Hawks', 1, 'WU Mustang', 2, 3, 0),
        (1, '2021-02-01 16:00:00', 'WLU Hawks', 1, 'WU Mustang', 2, null, null),
        (1, '2021-02-21 16:00:00', 'WLU Hawks', 1, 'WU Mustang', 2, null, null),
        (1, '2021-01-05 18:00:00', 'WLU Hawks', 1, 'UW Warriors', 3, 3, 0),
        (1, '2021-01-25 18:00:00', 'WLU Hawks', 1, 'UW Warriors', 3, 3, 3),
        (1, '2021-02-05 18:00:00', 'WLU Hawks', 1, 'UW Warriors', 3, null, null),
        (1, '2021-02-25 18:00:00', 'WLU Hawks', 1, 'UW Warriors', 3, null, null),
        (1, '2021-01-05 14:00:00', 'UoT Varsity Blues', 4, 'WLU Hawks', 1, 1, 1),
        (1, '2021-01-25 14:00:00', 'UoT Varsity Blues', 4, 'WLU Hawks', 1, 2, 1),
        (1, '2021-02-05 14:00:00', 'UoT Varsity Blues', 4, 'WLU Hawks', 1, null, null),
        (1, '2021-02-25 14:00:00', 'UoT Varsity Blues', 4, 'WLU Hawks', 1, null, null)";
if ($conn->query($sql) === TRUE) {
  echo "Table created successfully";
} else {
  echo "Error: " . $conn->error;
}
$conn->close();



// Admin Table
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
else echo "Connected successfully\n";
$sql = "CREATE TABLE Admin (
    Adminid INT AUTO_INCREMENT PRIMARY KEY,
    AdminName char(50),
    AdminPassword char(50)
)";
if ($conn->query($sql) === TRUE) {
  echo "Table created successfully";
} else {
  echo "Error: " . $conn->error;
}
$conn->close();

// SEED Admin table
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
else echo "Connected successfully\n";
$sql = "INSERT INTO Admin (AdminName, AdminPassword) VALUES 
        ('testAdmin', 1234)";
if ($conn->query($sql) === TRUE) {
  echo "Table created successfully";
} else {
  echo "Error: " . $conn->error;
}
$conn->close();
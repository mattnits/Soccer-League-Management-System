<?php
    $values = $_POST["values"];
    $initusername = "";
    $initpassword = "";

    // Decode the JSON and decide what to do with incoming data
    $data = json_decode($values, true);
    if ($data["purpose"] == "login") {
        $initusername = $data["username"];
        $initpassword = $data["password"];
        login($initusername, $initpassword);
    }
    else if ($data["purpose"] == "signup") {
        $initusername = $data["username"];
        $initpassword = $data["password"];
        signup($initusername, $initpassword);
    }


    // This checks if cookies are still active
    function login($initusername, $initpassword) {
        $tests = "";
        $error = "ok";
        $output = "";

        if (isset($_COOKIE["userCookie"]) && isset($_COOKIE["passCookie"])) {
        
            // ==========================================================================
            //
            //                              CHANGE THIS
            //
            // ==========================================================================
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "leaguemanagement";
    
    
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
    
            $username = $_COOKIE['userCookie'];
            $password = $_COOKIE['passCookie'];
    
            $regPassword = sha1($password);
            
    
            $sql = "SELECT * FROM admin WHERE AdminName = '$username' AND AdminPassword = '$regPassword'";
            
            if ($result = $conn->query($sql)) {
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                }
            }
            else {
                $error = "SQL error (Login 1)";
            }
        
            $arr = array("scores" => $output, "errors" => $error, "tests" => $tests);

            $myJSON = json_encode($arr);
            echo $myJSON;
        
        $conn->close();
            
        }
        // If cookies are not active go here
        else {
    
                // ==========================================================================
                //
                //                              CHANGE THIS
                //
                // ==========================================================================
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "leaguemanagement";
    
    
    
                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
    
                $username = $initusername;
                $password = $initpassword;
    
                if (!isset($_COOKIE["userCookie"])) {
                    setcookie("userCookie", $username, time() + (3600), "/");
                }
                if (!isset($_COOKIE["passCookie"])) {
                    setcookie("passCookie", $password, time() + (3600), "/");
                }
                if (!isset($_COOKIE["loggedin"])) {
                    setcookie("loggedin", $username, time() + (3600), "/");
                }
                
                $regPassword = sha1($password);
                $sql = "SELECT * FROM admin WHERE AdminName = '$username' AND AdminPassword = '$regPassword'";
                
                if ($result = $conn->query($sql)) {
                    
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo "ok";
                    }
                    else {
                        $error = "Invalid username or password";
                    }
                }
                else {
                    $error = "SQL error (Login 2)";
                }

                $arr = array("scores" => $output, "errors" => $error, "tests" => $tests);

                $myJSON = json_encode($arr);
                echo $myJSON;
            
    
            $conn->close();
        }
    }
    
    function signup($initusername, $initpassword) {
        
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "leaguemanagement";

        $tests = "";
        $error = "ok";
        $output = "";


        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

        $username = $initusername;
        $password = $initpassword;

        $encPassword = sha1($password);

        if (!isset($_COOKIE["userCookie"])) {
            setcookie("userCookie", $username, time() + (15), "/");
        }
        if (!isset($_COOKIE["passCookie"])) {
            setcookie("passCookie", $password, time() + (15), "/");
        }
        

        $sql = "INSERT INTO admin(AdminName, AdminPassword) VALUES ('$username', '$encPassword')";
        
        if ($result = $conn->query($sql)) {
            
            if ($result = $conn->query($sql)) {
                
            }
            
        }
        else {
            $error = "SQL error (Signup)";
        }

        $arr = array("scores" => $output, "errors" => $error, "tests" => $tests);

        $myJSON = json_encode($arr);
        echo $myJSON;

        $conn->close();

        
    }
        
?>
    
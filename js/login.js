function newAccount() {
    document.getElementsByClassName("loginItems")[0].style.visibility = "hidden";
    document.getElementsByClassName("creationItems")[0].style.visibility = "visible";
}

function login() {

    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    var jsonVals = {
        purpose: "login",
        username: username,
        password: password
    };
    var values = JSON.stringify(jsonVals);

    // Check if field is empty
    if (username.length > 0 && password.length > 0) {
        $.ajax({
            url:"../php/login.php",    
            type: "post",
            data: {values},
            success:function(data){

                console.log(data);
                // var obj = JSON.parse(data);
                //console.log(obj); 
                sessionStorage.setItem('loggedin', username);
                sessionStorage.removeItem('leagueID');
                
                window.location.replace("../html/home.html");               
    
            },
            error:function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText
                alert('Error - ' + errorMessage);
            }
        });
    }
    else {
        alert("Please enter each field");
        // document.getElementById("errScheduleMsg").innerHTML = "Error, please fill out all fields";
    }
}

function createAccount() {
    var username = document.getElementById("username2").value;
    var password = document.getElementById("password2").value;
    var password2 = document.getElementById("passwordVar").value;

    if (password != password2) {
        alert("Passwords don't match");
        return;
    }

    var jsonVals = {
        purpose: "signup",
        username: username,
        password: password
    };
    var values = JSON.stringify(jsonVals);

    // Check if field is empty
    if (username.length > 0 && password.length > 0) {
        $.ajax({
            url:"../php/login.php",    
            type: "post",
            data: {values},
            success:function(data){

                //console.log(data);
                var obj = JSON.parse(data);
                //console.log(obj);
                sessionStorage.setItem('loggedin', username);
                sessionStorage.removeItem('leagueID');
                window.location.replace("../html/leagueManagement.html");
    
            },
            error:function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText
                alert('Error - ' + errorMessage);
            }
        });
    }
    else {
        alert("Please enter each field");
        // document.getElementById("errScheduleMsg").innerHTML = "Error, please fill out all fields";
    }
}




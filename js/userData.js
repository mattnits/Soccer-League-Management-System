// This checks to see if a user is logged in
// Add to each page

function getLoginData() {
    var loggedin = sessionStorage.getItem('loggedin');
    console.log(loggedin);
    // If user is logged in, show log out button
    if (loggedin != null) {
        document.getElementById("logInButton").style.display = "none";
        document.getElementById("logOutButton").style.display = "block";
        document.getElementById("adminManage").style.display = "block";
    }
    // Otherwise show log in button
    else {
        document.getElementById("logInButton").style.display = "block";
        document.getElementById("logOutButton").style.display = "none";
        document.getElementById("adminManage").style.display = "none";
    }
}

// Logs the user out
function logout() {

    sessionStorage.removeItem('loggedin');
    
    document.getElementById("logInButton").style.display = "block";
    document.getElementById("logOutButton").style.display = "none";
    document.getElementById("adminManage").style.display = "none";
    sessionStorage.removeItem('leagueID');

    window.location.replace("../html/home.html"); 
}

// update the dropdown menu
function updateText(leagueName) {
    var obj = document.getElementsByClassName("dropbtn");

    if (leagueName != "+" || leagueName != "Select a League") {
        obj[0].innerHTML = leagueName;
    }
    

    updateLeague(leagueName);
    
}

// Open up the score menu
function enterScore() {
    var data = sessionStorage.getItem('leagueID');

    if (data == null) {
        alert("Please select a league first!");
    }
    else {
        document.getElementsByClassName("addScores")[0].style.display = "block";
        document.getElementsByClassName("contentItems")[0].style.visibility = "hidden";
        
        var obj = document.getElementsByClassName("dropbtn");
        obj[0].style.visibility = "hidden";
    }
    
}

// Takes user score data, uploads it to the server and then displays it if possible
function submitScore() {
    // Get all the info the user entered
    var gid = document.getElementById("gameID");
    var gDate = document.getElementById("gameDate");
    var homeName = document.getElementById("homeName");
    var homeScore = document.getElementById("homeScore");
    var awayName = document.getElementById("awayName");
    var awayScore = document.getElementById("awayScore");

    var obj = document.getElementsByClassName("dropbtn");
    var val = obj[0].innerHTML;
    var user = sessionStorage.getItem("loggedin");
    
    // Puts the above info into JSON format for server
    var jsonVals = {
        purpose: "submitScore",
        gameID: gid.value,
        gameDate: gDate.value, 
        hName: homeName.value, 
        hScore: homeScore.value, 
        aName: awayName.value, 
        aScore: awayScore.value,
        league: val,
        username: user
    };
    var values = JSON.stringify(jsonVals);

    console.log(homeScore.value, awayScore.value)

    // If all fields are filled, enter to server, otherwise throw error
    if (gDate.value.length > 0 && homeName.value.length > 0 &&
        homeScore.value.length > 0 && awayName.value.length > 0 && awayScore.value.length > 0) {
        $.ajax({
            url:"../php/leagueManagement.php",    
            type: "post",
            data: {values},
            success:function(data){
                // Remove the data in the tables
                $('#scoresTable tbody').empty();
                $('#scheduleTable tbody').empty();
                $('#teamsTable tbody').empty();

                var obj = JSON.parse(data);
                
                if (obj.errors != "ok") {
                    alert(obj.errors);
                }
                
                // Updates the score table
                var output = $("#scoresTable");
                var html = $.parseHTML(obj.scores);
                output.append(html);

                // Updates the score table
                var output = $("#scheduleTable");
                var html = $.parseHTML(obj.schedule);
                output.append(html);

                // Updates the teams table
                var output = $("#teamsTable");
                var html = $.parseHTML(obj.teams);
                output.append(html);

                document.getElementsByClassName("addScores")[0].style.display = "none";
                document.getElementsByClassName("contentItems")[0].style.visibility = "visible";
                document.getElementById("errScoreMsg").innerHTML = "";

                var obj = document.getElementsByClassName("dropbtn");
                obj[0].style.visibility = "visible";

            },
            error:function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText
                alert('Error - ' + errorMessage);
            }
        });
        
    }
    else {
        document.getElementById("errScoreMsg").innerHTML = "Error, please fill out all fields";
    }
    
}

// Closes the score menu
function cancelScore() {
    document.getElementsByClassName("addScores")[0].style.display = "none";
    document.getElementsByClassName("contentItems")[0].style.visibility = "visible";
    document.getElementById("errScoreMsg").innerHTML = "";


    var curButton = document.getElementById("enterScoreButton");

    // Change the onclick to the function that updates the team
    curButton.setAttribute('onclick','submitScore()');

    var obj = document.getElementsByClassName("dropbtn");
    obj[0].style.visibility = "visible";
}

// Allows user to edit the schedule of a particular game
function editSchedule(curButton) {
    // Open the edit menu
    document.getElementsByClassName("addSchedule")[0].style.display = "block";
    document.getElementsByClassName("contentItems")[0].style.visibility = "hidden";

    // Puts data from selected team into the fields
    var gameID = curButton.parentNode.parentNode.firstChild.firstChild.nodeValue;
    var date = curButton.parentNode.parentNode.firstChild.nextSibling.firstChild.nodeValue;
    var hname = curButton.parentNode.parentNode.firstChild.nextSibling.nextSibling.nextSibling.firstChild.nodeValue;
    var aname = curButton.parentNode.parentNode.firstChild.nextSibling.nextSibling.nextSibling.nextSibling.firstChild.nodeValue;

    document.getElementById("scheduleID").value = gameID;
    document.getElementById("scheduleDate").value = date;
    document.getElementById("scheduleHome").value = hname;
    document.getElementById("scheduleAway").value = aname;


    var curButton = document.getElementById("enterScheduleButton");

    // Change the onclick to the function that updates the team
    curButton.setAttribute('onclick','updateSchedule()');
    console.log("test: ", curButton);

}

// Allows user to edit the score of a particular game
function updateSchedule() {
    var gid = document.getElementById("scheduleID").value;
    var gdate = document.getElementById("scheduleDate").value;
    var hname = document.getElementById("scheduleHome").value;
    var aname = document.getElementById("scheduleAway").value;

    var obj = document.getElementsByClassName("dropbtn");
    var val = obj[0].innerHTML;
    var user = sessionStorage.getItem("loggedin");
    
    var jsonVals = {
        purpose: "updateSchedule",
        gid: gid,
        gdate: gdate,
        hname: hname,
        aname: aname,
        league: val,
        username: user
    };
    var values = JSON.stringify(jsonVals);

    // If all fields are filled, enter to server, otherwise throw error
    if (gdate.length > 0 && hname.length > 0 && aname.length > 0) {
        
        $.ajax({
            url:"../php/leagueManagement.php",    
            type: "post",
            data: {values},
            success:function(data){
                // Remove the data in the tables
                $('#scoresTable tbody').empty();
                $('#scheduleTable tbody').empty();
                $('#teamsTable tbody').empty();
                console.log(data);
                var obj = JSON.parse(data);
                // console.log(obj.errors);
                if (obj.errors != "ok") {
                    alert(obj.errors);
                }

                // Updates the score table
                var output = $("#scoresTable");
                var html = $.parseHTML(obj.scores);
                output.append(html);

                var output = $("#scheduleTable");
                var html = $.parseHTML(obj.schedule);
                output.append(html);

                // Updates the teams table
                var output = $("#teamsTable");
                var html = $.parseHTML(obj.teams);
                output.append(html);

                // Close the schedule menu
                document.getElementsByClassName("addSchedule")[0].style.display = "none";
                document.getElementsByClassName("contentItems")[0].style.visibility = "visible";
                document.getElementById("errScheduleMsg").innerHTML = "";

                var curButton = document.getElementById("enterScheduleButton");
                curButton.setAttribute('onclick','submitSchedule()');

                var obj = document.getElementsByClassName("dropbtn");
                obj[0].style.visibility = "visible";
            },
            error:function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText
                alert('Error - ' + errorMessage);
            }
        });
    }
    else {
        document.getElementById("errScheduleMsg").innerHTML = "Error, please fill out all fields";
    }

    

    // Change the onclick to the function that updates the team
    


}

// Allows user to edit the score of a particular game
function editScore(curButton) {
    // Open the edit menu
    document.getElementsByClassName("addScores")[0].style.display = "block";
    document.getElementsByClassName("contentItems")[0].style.visibility = "hidden";

    // Puts data from selected team into the fields
    var gameID = curButton.parentNode.parentNode.firstChild.firstChild.nodeValue;
    var date = curButton.parentNode.parentNode.firstChild.nextSibling.firstChild.nodeValue;
    var hname = curButton.parentNode.parentNode.firstChild.nextSibling.nextSibling.firstChild.nodeValue;
    var score = curButton.parentNode.parentNode.firstChild.nextSibling.nextSibling.nextSibling.firstChild.nodeValue;
    var aname = curButton.parentNode.parentNode.firstChild.nextSibling.nextSibling.nextSibling.nextSibling.firstChild.nodeValue;

    var temp = score.split("-");

    document.getElementById("gameID").value = gameID;
    document.getElementById("gameDate").value = date;
    document.getElementById("homeName").value = hname;
    document.getElementById("homeScore").value = temp[0];
    document.getElementById("awayName").value = aname;
    document.getElementById("awayScore").value = temp[1];

    var curButton = document.getElementById("enterScoreButton");

    // Change the onclick to the function that updates the team
    curButton.setAttribute('onclick','updateScore()');
    //console.log("test: ", curButton);

}

// Allows user to edit the score of a particular game
function updateScore() {
    var gid = document.getElementById("gameID").value;
    var gdate = document.getElementById("gameDate").value;
    var hname = document.getElementById("homeName").value;
    var hscore = document.getElementById("homeScore").value;
    var aname = document.getElementById("awayName").value;
    var ascore = document.getElementById("awayScore").value;

    var obj = document.getElementsByClassName("dropbtn");
    var val = obj[0].innerHTML;
    var user = sessionStorage.getItem("loggedin");
    
    var jsonVals = {
        purpose: "updateScore",
        gid: gid,
        gdate: gdate,
        hname: hname,
        hscore: hscore,
        aname: aname,
        ascore: ascore,
        league: val,
        username: user
    };
    var values = JSON.stringify(jsonVals);

    // If all fields are filled, enter to server, otherwise throw error
    if (gdate.length > 0 && hname.length > 0 &&
        hscore.length > 0 && aname.length > 0 && ascore.length > 0) {
        
        $.ajax({
            url:"../php/leagueManagement.php",    
            type: "post",
            data: {values},
            success:function(data){
                // Remove the data in the tables
                $('#scoresTable tbody').empty();
                $('#scheduleTable tbody').empty();
                $('#teamsTable tbody').empty();
                //console.log(data);
                var obj = JSON.parse(data);
                // console.log(obj.errors);
                if (obj.errors != "ok") {
                    alert(obj.errors);
                }

                // Updates the score table
                var output = $("#scoresTable");
                var html = $.parseHTML(obj.scores);
                output.append(html);

                var output = $("#scheduleTable");
                var html = $.parseHTML(obj.schedule);
                output.append(html);

                // Updates the teams table
                var output = $("#teamsTable");
                var html = $.parseHTML(obj.teams);
                output.append(html);

                // Close the score menu
                document.getElementsByClassName("addScores")[0].style.display = "none";
                document.getElementsByClassName("contentItems")[0].style.visibility = "visible";
                document.getElementById("errScoreMsg").innerHTML = "";

                var curButton = document.getElementById("enterScoreButton");

                // Change the onclick to the function that updates the team
                curButton.setAttribute('onclick','submitScore()');

            },
            error:function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText
                alert('Error - ' + errorMessage);
            }
        });
    }
    else {
        document.getElementById("errScoreMsg").innerHTML = "Error, please fill out all fields";
    }

    


}

// Opens the team menu
function enterTeam() {

    var data = sessionStorage.getItem('leagueID');

    if (data == null) {
        alert("Please select a league first!");
    }
    else {
        document.getElementsByClassName("addTeams")[0].style.display = "block";
        document.getElementsByClassName("contentItems")[0].style.visibility = "hidden";
        document.getElementById("errTeamMsg").innerHTML = "";

        var obj = document.getElementsByClassName("dropbtn");
        obj[0].style.visibility = "hidden";
    }
    
    
}

// Takes user team data, uploads it to the server and then displays it if possible
function submitTeam() {
    var tid = "";
    var tName = document.getElementById("teamName");

    var obj = document.getElementsByClassName("dropbtn");
    var val = obj[0].innerHTML;
    var user = sessionStorage.getItem("loggedin");
    

    var jsonVals = {
        purpose: "submitTeam",
        teamID: tid,
        teamName: tName.value,
        league: val,
        username: user
    };
    var values = JSON.stringify(jsonVals);

    // If all fields are filled, enter to server, otherwise throw error
    if (tName.value.length > 0) {
        
        $.ajax({
            url:"../php/leagueManagement.php",    
            type: "post",
            data: {values},
            success:function(data){
                // Remove the data in the tables
                $('#scoresTable tbody').empty();
                $('#scheduleTable tbody').empty();
                $('#teamsTable tbody').empty();
                //console.log(data);
                var obj = JSON.parse(data);
                // console.log(obj.errors);
                if (obj.errors != "ok") {
                    alert(obj.errors);
                }

                // Updates the score table
                var output = $("#scoresTable");
                var html = $.parseHTML(obj.scores);
                output.append(html);

                var output = $("#scheduleTable");
                var html = $.parseHTML(obj.schedule);
                output.append(html);

                // Updates the teams table
                var output = $("#teamsTable");
                var html = $.parseHTML(obj.teams);
                output.append(html);

                // Close the team menu
                document.getElementsByClassName("addTeams")[0].style.display = "none";
                document.getElementsByClassName("contentItems")[0].style.visibility = "visible";
                document.getElementById("errTeamMsg").innerHTML = "";

                var obj = document.getElementsByClassName("dropbtn");
                obj[0].style.visibility = "visible";

            },
            error:function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText
                alert('Error - ' + errorMessage);
            }
        });
    }
    else {
        document.getElementById("errTeamMsg").innerHTML = "Error, please fill out all fields";
    }
}

// Closes the team menu
function cancelTeam() {
    document.getElementsByClassName("addTeams")[0].style.display = "none";
    document.getElementsByClassName("contentItems")[0].style.visibility = "visible";
    document.getElementById("errTeamMsg").innerHTML = "";

    var curButton = document.getElementById("enterTeamButton");
    curButton.setAttribute('onclick','submitTeam()');

    var obj = document.getElementsByClassName("dropbtn");
    obj[0].style.visibility = "visible";
}

// Edit a specific teams
function editTeam(curButton) {
    // Open the edit menu
    document.getElementsByClassName("addTeams")[0].style.display = "block";
    document.getElementsByClassName("contentItems")[0].style.visibility = "hidden";

    // Puts data from selected team into the fields
    var teamName = curButton.parentNode.parentNode.firstChild.nextSibling.firstChild.nodeValue;
    document.getElementById("teamName").value = teamName;
    sessionStorage.setItem('lastName', teamName);

    var curButton = document.getElementById("enterTeamButton");
    // Change the onclick to the function that updates the team
    curButton.setAttribute('onclick','updateTeam()');
    //console.log("test: ", curButton);
    
}

// Updates the team information sent from above
function updateTeam() {

    // Change the onclick back to the original function
    var curButton = document.getElementById("enterTeamButton");
    

    var tid = sessionStorage.getItem('lastName');
    sessionStorage.removeItem('lastName');
    var tName = document.getElementById("teamName");

    var obj = document.getElementsByClassName("dropbtn");
    var val = obj[0].innerHTML;
    var user = sessionStorage.getItem("loggedin");

    var jsonVals = {
        purpose: "updateTeam",
        teamID: tid,
        teamName: tName.value,
        league: val,
        username: user
    };
    var values = JSON.stringify(jsonVals);

    // Update the server with the correct team
    $.ajax({
        url:"../php/leagueManagement.php",    
        type: "post",
        data: {values},
        success:function(data){
            $('#scoresTable tbody').empty();
            $('#scheduleTable tbody').empty();
            $('#teamsTable tbody').empty();
            //console.log(data);
            
            var obj = JSON.parse(data);
            // console.log(obj.errors);
            if (obj.errors != "ok") {
                alert(obj.errors);
            }
            // Updates the score table
            var output = $("#scoresTable");
            var html = $.parseHTML(obj.scores);
            output.append(html);

            var output = $("#scheduleTable");
            var html = $.parseHTML(obj.schedule);
            output.append(html);

            // Updates the teams table
            var output = $("#teamsTable");
            var html = $.parseHTML(obj.teams);
            output.append(html);

            // Close the team menu
            document.getElementsByClassName("addTeams")[0].style.display = "none";
            document.getElementsByClassName("contentItems")[0].style.visibility = "visible";
            document.getElementById("errTeamMsg").innerHTML = "";
            var curButton = document.getElementById("enterTeamButton");
            curButton.setAttribute('onclick','submitTeam()'); 
        },
        error:function(xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText
            alert('Error - ' + errorMessage);
        }
    });

       
}

// Create a league
function createLeague() {
    document.getElementsByClassName("addLeague")[0].style.display = "block";
    document.getElementsByClassName("contentItems")[0].style.visibility = "hidden";
    document.getElementById("errLeagueMsg").innerHTML = "";
}

// Submit a league
function submitLeague() {

    var lName = document.getElementById("leagueName");

    var obj = document.getElementsByClassName("dropbtn");
    var val = obj[0].innerHTML;
    var user = sessionStorage.getItem("loggedin");

    var jsonVals = {
        purpose: "submitLeague",
        leagueName: lName.value,
        league: val,
        username: user
    };
    var values = JSON.stringify(jsonVals);

    // Check if field is empty
    if (lName.value.length > 0) {
        $.ajax({
            url:"../php/leagueManagement.php",    
            type: "post",
            data: {values},
            success:function(data){
                $('#scoresTable tbody').empty();
                $('#scheduleTable tbody').empty();
                $('#teamsTable tbody').empty();
                //console.log(data);
                var obj = JSON.parse(data);
                
                if (obj.errors != "ok") {
                    alert(obj.errors);
                }
    
                // Updates the score table
                var output = $("#scoresTable");
                var html = $.parseHTML(obj.scores);
                output.append(html);

                var output = $("#scheduleTable");
                var html = $.parseHTML(obj.schedule);
                output.append(html);
    
                // Updates the teams table
                var output = $("#teamsTable");
                var html = $.parseHTML(obj.teams);
                output.append(html);
    
                document.getElementsByClassName("addLeague")[0].style.display = "none";
                document.getElementsByClassName("contentItems")[0].style.visibility = "visible";
                document.getElementById("errLeagueMsg").innerHTML = "";
                
                location.reload();

    
            },
            error:function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText
                alert('Error - ' + errorMessage);
            }
        });
    }
    else {
        document.getElementById("errLeagueMsg").innerHTML = "Error, please fill out all fields";
    }
    
    
}

// Close create league window
function cancelLeague() {
    document.getElementsByClassName("addLeague")[0].style.display = "none";
    document.getElementsByClassName("contentItems")[0].style.visibility = "visible";
    document.getElementById("errLeagueMsg").innerHTML = "";

    
}

// Updates the page with the correct league or opens up league creation
function updateLeague(val) {
    // If user wants to add a league
    if (val == "+") {
        createLeague();
    }
    else if (val == "Choose a League") {

    }
    else {
        // Change the league and update the page
        var user = sessionStorage.getItem("loggedin");
        var jsonVals = {
            purpose: "changeLeague",
            league: val,
            username: user
        };
        var values = JSON.stringify(jsonVals);

        // MAKE SURE TO GET LEAGUE ID BACK!!!!!!!!!

        $.ajax({
            url:"../php/leagueManagement.php",    
            type: "post",
            data: {values},
            success:function(data){
                //console.log(data);
                $('#scoresTable tbody').empty();
                $('#scheduleTable tbody').empty();
                $('#teamsTable tbody').empty();
                
                var obj = JSON.parse(data);
                //console.log(obj.tests);
                if (obj.errors != "ok") {
                    alert(obj.errors);
                }

                sessionStorage.setItem('leagueID', obj.leagueID);
    
                // Updates the score table
                var output = $("#scoresTable");
                var html = $.parseHTML(obj.scores);
                output.append(html);

                // Updates the schedule table
                var output = $("#scheduleTable");
                var html = $.parseHTML(obj.schedule);
                output.append(html);
    
                // Updates the teams table
                var output = $("#teamsTable");
                var html = $.parseHTML(obj.teams);
                output.append(html);
    
                document.getElementsByClassName("addSchedule")[0].style.display = "none";
                document.getElementsByClassName("contentItems")[0].style.visibility = "visible";
                document.getElementById("errScheduleMsg").innerHTML = "";
                
    
            },
            error:function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText
                alert('Error - ' + errorMessage);
            }
        });

        
    }
}

function enterSchedule() {
    var data = sessionStorage.getItem('leagueID');

    if (data == null) {
        alert("Please select a league first!");
    }
    else {
        document.getElementsByClassName("addSchedule")[0].style.display = "block";
        document.getElementsByClassName("contentItems")[0].style.visibility = "hidden";
        document.getElementById("errScheduleMsg").innerHTML = "";

        var obj = document.getElementsByClassName("dropbtn");
        obj[0].style.visibility = "hidden";
    }
    
}

function cancelSchedule() {
    document.getElementsByClassName("addSchedule")[0].style.display = "none";
    document.getElementsByClassName("contentItems")[0].style.visibility = "visible";
    document.getElementById("errScheduleMsg").innerHTML = "";

    var curButton = document.getElementById("enterScheduleButton");
    curButton.setAttribute('onclick','submitSchedule()');

    var obj = document.getElementsByClassName("dropbtn");
    obj[0].style.visibility = "visible";
}

function submitSchedule() {
    var lName = document.getElementsByClassName("dropbtn")[0].innerHTML;
    var sID = document.getElementById("scheduleID");
    var sDate = document.getElementById("scheduleDate");
    var hTeam = document.getElementById("scheduleHome");
    var aTeam = document.getElementById("scheduleAway");

    var obj = document.getElementsByClassName("dropbtn");
    var val = obj[0].innerHTML;
    var user = sessionStorage.getItem("loggedin");

    console.log(sID.value, sDate.value, hTeam.value, aTeam.value);

    var jsonVals = {
        purpose: "submitSchedule",
        leagueName: lName,
        sid: sID.value,
        sdate: sDate.value,
        hteam: hTeam.value,
        ateam: aTeam.value,
        league: val,
        username: user
    };
    var values = JSON.stringify(jsonVals);

    // Check if field is empty
    if (sDate.value.length > 0 && hTeam.value.length > 0 && aTeam.value.length > 0) {
        $.ajax({
            url:"../php/leagueManagement.php",    
            type: "post",
            data: {values},
            success:function(data){
                console.log(data);
                $('#scoresTable tbody').empty();
                $('#scheduleTable tbody').empty();
                $('#teamsTable tbody').empty();
                
                var obj = JSON.parse(data);
                console.log(obj.tests);
                if (obj.errors != "ok") {
                    alert(obj.errors);
                }
    
                // Updates the score table
                var output = $("#scoresTable");
                var html = $.parseHTML(obj.scores);
                output.append(html);

                // Updates the schedule table
                var output = $("#scheduleTable");
                var html = $.parseHTML(obj.schedule);
                output.append(html);
    
                // Updates the teams table
                var output = $("#teamsTable");
                var html = $.parseHTML(obj.teams);
                output.append(html);
    
                document.getElementsByClassName("addSchedule")[0].style.display = "none";
                document.getElementsByClassName("contentItems")[0].style.visibility = "visible";
                document.getElementById("errScheduleMsg").innerHTML = "";

                var obj = document.getElementsByClassName("dropbtn");
                obj[0].style.visibility = "visible";
                
    
            },
            error:function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText
                alert('Error - ' + errorMessage);
            }
        });
    }
    else {
        document.getElementById("errScheduleMsg").innerHTML = "Error, please fill out all fields";
    }
}

// When the window loads, update the data on screen based off the users leagues
function loadData() {
    // Create JSON to tell server what to do
    var obj = document.getElementsByClassName("dropbtn");
    var val = obj[0].innerHTML;
    var user = sessionStorage.getItem("loggedin");

    var jsonVals = {
        purpose: "onloading",
        league: val,
        username: user
    };
    var values = JSON.stringify(jsonVals);

    $.ajax({
        url:"../php/leagueManagement.php",    
        type: "post",
        data: {values},
        success:function(data){
            // console.log(data);
            var obj = JSON.parse(data);
            console.log(obj.tests);
            
            // If session expires, send to home page
            if (obj.errors == "ExpiredSession") {
                alert("Session expired, you will be sent to the home page");
                window.location.replace("../html/home.html");
            }
            // If other errors
            else if (obj.errors != "ok") {
                alert(obj.errors);
            }

            document.getElementsByClassName("dropbtn")[0].innerHTML = "Choose a League";

            var dropdown = document.getElementsByClassName("dropdownContent")[0];

            for (var i=0; i < obj.leagues.length; i++) {
                var btn = document.createElement("button");
                btn.innerHTML = obj.leagues[i];
                btn.setAttribute('onclick','updateText(this.innerHTML)');  
                dropdown.prepend(btn);
            }

            // Updates the score table
            var output = $("#scoresTable");
            var html = $.parseHTML(obj.scores);
            output.append(html);

            // Updates the schedule table
            var output = $("#scheduleTable");
            var html = $.parseHTML(obj.schedule);
            output.append(html);

            // Updates the teams table
            var output = $("#teamsTable");
            var html = $.parseHTML(obj.teams);
            output.append(html);
            

        },
        error:function(xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText
            alert('Error - ' + errorMessage);
        }
    });
    
}
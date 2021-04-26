
function clearTableBody(noData) {
    // RESET table
    var scheduleTable = document.getElementById('scheduletable');
    var old_tbody = scheduleTable.getElementsByTagName('tbody')[0];

    var new_tbody = document.createElement('tbody');

    // alert("test");

    if (noData == true) {
        var noResultRow = document.createElement('tr');

        var noResultCol = document.createElement('td');
        // noResultCol.className = "column1";
        noResultCol.appendChild(document.createTextNode("No schedule available for the selected league"));

        noResultRow.appendChild(noResultCol);
        new_tbody.appendChild(noResultRow);
    }

    old_tbody.parentNode.replaceChild(new_tbody, old_tbody);
}

function onLoadFnc() {

    // Session
    var leagueID = sessionStorage.getItem('leagueID') || null;
    var loggedin = sessionStorage.getItem('loggedin') || false;
    loggedin = false;
    // leagueID = null;

    if (!loggedin) {
        var adminTab = document.getElementById('adminManage');
        adminTab.style.display = 'none';

        var loggoutTab = document.getElementById('logOutButton');
        loggoutTab.style.display = 'none';
    }
    else {
        var logginTab = document.getElementById('logInButton');
        logginTab.style.display = 'none';
    }

    if (leagueID !== null) {
        $.ajax({
            url: "../php/backendAPI.php?api=getSchedule&leagueID=" + leagueID,    
            type: "GET",
            success:function(result){
                var data = JSON.parse(result);
                

                if (Number.isInteger(data) || data === undefined || data.length == 0) {
                    clearTableBody(true);
                }
                else {
                    clearTableBody(false);
                    fillTableBody(data);
                    
                }
         
            },
            error:function(xhr, status, error) {
                // var errorMessage = xhr.status + ': ' + xhr.statusText
                // alert('Error - ' + errorMessage);
                clearTableBody(true);
            }
        });
    }
    else {
        var leaderboardTab = document.getElementById('leaderboard');
        leaderboardTab.style.display = 'none';

        var statsTab = document.getElementById('stats');
        statsTab.style.display = 'none';

        // alert("test");
        clearTableBody(true);
    }
    
}

function fillTableBody(data) {
    
    data.forEach((row, index) => {

        var scheduleRow = document.createElement('tr');

        var dateColumn = document.createElement('td');
        dateColumn.className = "column1";
        dateColumn.appendChild(document.createTextNode(row.datePlayed.slice(0, -3)));

        var homeTeamColumn = document.createElement('td');
        homeTeamColumn.className = "column2";
        homeTeamColumn.appendChild(document.createTextNode(row.homeTeam));
        
        var awayTeamColumn = document.createElement('td');
        awayTeamColumn.className = "column3";
        awayTeamColumn.appendChild(document.createTextNode(row.awayTeam));

        var scoreColumn = document.createElement('td');
        scoreColumn.className = "column4";
        var homeGoals = row.homeScore || "";
        var awayGoals = row.awayScore || "";
        scoreColumn.appendChild(document.createTextNode(homeGoals + '-' + awayGoals));
        
        scheduleRow.appendChild(dateColumn);
        scheduleRow.appendChild(homeTeamColumn);
        scheduleRow.appendChild(awayTeamColumn);
        scheduleRow.appendChild(scoreColumn);
        
        var scheduleTable = document.getElementById('scheduletable');
        var tbody = scheduleTable.getElementsByTagName('tbody')[0];
        tbody.appendChild(scheduleRow);
    });
}


function sortTable(n) {
// Taken from https://www.w3schools.com/howto/howto_js_sort_table.asp
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("scheduletable");
    switching = true;
    dir = "asc";
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                shouldSwitch = true;
                break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                shouldSwitch = true;
                break;
                }
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount ++;
        } else {
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}
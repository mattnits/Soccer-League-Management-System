
function clearTableBody(noData) {
    // RESET table
    var scheduleTable = document.getElementById('leaguetable');
    var old_tbody = scheduleTable.getElementsByTagName('tbody')[0];

    var new_tbody = document.createElement('tbody');
    
    if (noData === true) {
        var noResultRow = document.createElement('tr');

        var noResultCol = document.createElement('td');
        noResultCol.appendChild(document.createTextNode("No leagues available"));

        noResultRow.appendChild(noResultCol);
        new_tbody.appendChild(noResultRow);
    }

    old_tbody.parentNode.replaceChild(new_tbody, old_tbody);
}

function onLoadFnc() {
    
    // Session
    var leagueID = sessionStorage.getItem('leagueID') || null;
    var loggedin = sessionStorage.getItem('loggedin') || false;
    // loggedin = false;

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

    if (leagueID == null) {
        var leaderboardTab = document.getElementById('leaderboard');
        leaderboardTab.style.display = 'none';
        var scheduleTab = document.getElementById('schedule');
        scheduleTab.style.display = 'none';
        var statsTab = document.getElementById('stats');
        statsTab.style.display = 'none';
    }

    $.ajax({
        url: "../php/backendAPI.php?api=getLeagues",    
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
            clearTableBody(true);
        }
    });

}

function fillTableBody(data) {
    
    data.forEach((row, index) => {

        var leagueRow = document.createElement('tr');
        leagueRow.style.cursor = 'pointer';

        var createClickHandler = function(rowParam) {
            return function() {
              sessionStorage.setItem('leagueID', rowParam.LeagueID);
              window.location.replace("../html/leaderboard.html");
            };
          };

        leagueRow.onclick = createClickHandler(row);

        var leagueName = document.createElement('td');
        leagueName.className = "column1";
        leagueName.appendChild(document.createTextNode(row.LeagueName));

        var teamCount = document.createElement('td');
        teamCount.className = "column2";
        teamCount.appendChild(document.createTextNode(row.teamCount || 0));
        
        
        leagueRow.appendChild(leagueName);
        leagueRow.appendChild(teamCount);

        
        var scheduleTable = document.getElementById('leaguetable');
        var tbody = scheduleTable.getElementsByTagName('tbody')[0];
        tbody.appendChild(leagueRow);
    });
}

function sortTable(n) {
// Taken from https://www.w3schools.com/howto/howto_js_sort_table.asp
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("leaguetable");
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
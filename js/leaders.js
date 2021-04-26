function leaders() {

    var leagueID = sessionStorage.getItem('leagueID') || null;
    var loggedin = sessionStorage.getItem('loggedin') || false;
    
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
    if (leagueID!=null){
        var myjson = {
            name: "",
            purpose: "init",
            leagueid: leagueID
        };

    var values = JSON.stringify(myjson)
    $.ajax({
        url:"../php/leaders.php",    
        type: "post",
        data: {values},
        success:function(data){
            // Remove the data in the tables
           
            var obj = JSON.parse(data);
           

            // Updates the score table
            var output = $("#leaguetable");
            var html = $.parseHTML(obj.Teams);
            console.log(data);
            output.append(html);



        },
        error:function(xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText
            alert('Error - ' + errorMessage);
        }
    });
    }
}

function adjust_leaders(name) {
    var leagueID = sessionStorage.getItem('leagueID') || null;
    var loggedin = sessionStorage.getItem('loggedin') || false;
    
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

    var myjson = {
        name: name,
        purpose: "sortLeaderBoard",
        leagueid: leagueID
    };
    var values = JSON.stringify(myjson)
   
    $.ajax({
        url:"../php/leaders.php",    
        type: "post",
        data: {values},
        success:function(data){
            // Remove the data in the tables
            console.log(data);
            $(".ID").remove();
            console.log(data);
            var obj = JSON.parse(data);

            // Updates the score table
            var output = $("#leaguetable");
            var html = $.parseHTML(obj.Teams);
            
            output.append(html);



        },
        error:function(xhr, status, error) {
            var errorMessage = xhr.status + ': ' + xhr.statusText
            alert('Error - ' + errorMessage);
        }
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
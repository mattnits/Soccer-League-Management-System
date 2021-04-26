function getStats() {
    
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
    if (leagueID!=null){
        var myjson = {
            name: "",
            purpose: "init",
            leagueid: leagueID 
        };


        var values = JSON.stringify(myjson)
        $.ajax({
            url:"../php/stats.php",    
            type: "post",
            data: {values},
            success:function(data){
                // Remove the data in the tables
                
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
}

function adjust_table(name) {
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
        purpose: "sortTable",
        leagueid: leagueID
    };
    
    var values = JSON.stringify(myjson)
    
    $.ajax({
        url:"../php/stats.php",    
        type: "post",
        data: {values},
        success:function(data){
            // Remove the data in the tables
            console.log(data);
            $(".ID").remove();
            
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
    // Taken and slightly modified from https://www.w3schools.com/howto/howto_js_sort_table.asp
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("leaguetable");
        switching = true;
        dir = "asc";
        while (switching) {
            switching = false;
            rows = table.rows;
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("td")[n];
                y = rows[i + 1].getElementsByTagName("td")[n];
                if (isNaN(x.innerHTML)==true){
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } 
                    else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                else{

                    if (dir == "asc") {
                        if (parseInt(x.innerHTML) > parseInt(y.innerHTML)) {
                            shouldSwitch = true;
                            break;
                        }
                    } 
                    else if (dir == "desc") {
                        if (parseInt(x.innerHTML) < parseInt(y.innerHTML)) {
                            shouldSwitch = true;
                            break;
                        }
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
    // if (dir == "asc") {
                   
    //     if (isNaN(x)==false){
    //         if (x > y) {
    //             shouldSwitch = true;
    //             break;
    //             }
    //     }
    //     else if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
    //     shouldSwitch = true;
    //     break;
    //     }
    // } else if (dir == "desc") {
    //     if (isNaN(x)==false){
    //         if (x < y) {
    //             shouldSwitch = true;
    //             break;
    //             }
    //     }
    //     else if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
    //     shouldSwitch = true;
    //     break;
    //     }
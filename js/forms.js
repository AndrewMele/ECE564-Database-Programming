function formhash(form, password, error) {
    // Create a new element input, this will be our hashed password field. 
    var p = document.createElement("input");
 
    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);
 
    // Make sure the plaintext password doesn't get sent. 
    password.value = "";
 
    // Finally submit the form. 
    form.submit();
}
 
function regformhash(form, username, guild, password, conf, error) {
     // Check each field has a value
    if (username.value == ''         || 
          guild.value == ''     || 
          password.value == ''  || 
          conf.value == '') {
        error.value = "You must provide all the requested details. Please try again"
        form.submit();
        return false;
    }
 
    // Check the username
 
    re = /^\w+$/; 
    if(!re.test(form.username.value)) { 
        error.value = "Username must contain only letters, numbers and underscores. Please try again"
        form.submit();
        form.username.focus();
        return false; 
    }
 
    // Check that the password is sufficiently long (min 6 chars)
    // The check is duplicated below, but this is included to give more
    // specific guidance to the user
    if (password.value.length < 6) {
        error.value = "Passwords must be at least 6 characters long.  Please try again";
        form.submit();
        form.password.focus();
        return false;
    }
 
    // Check password and confirmation are the same
    if (password.value != conf.value) {
        error.value = "Your password and confirmation do not match. Please try again";
        form.submit();
        form.password.focus();
        return false;
    }
 
    // Create a new element input, this will be our hashed password field. 
    var p = document.createElement("input");
 
    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);
    //alert('Creating hash');
    // Make sure the plaintext password doesn't get sent. 
    password.value = "";
    conf.value = "";
    // Finally submit the form. 
    form.submit();
    return true;
}

function createPieChart(chart_title,titles,values,h=false,p=0){
    var dps = [];
    for (index = 0; index < titles.length; index++)
        dps.push({y: values[index], indexLabel: titles[index]});
    var container_name = chart_title + "Chart"

    if (h)
        container_name = "Home" + chart_title + "Chart"
    if (p == 1)
        container_name = "PlayerChart";
    if (h && p == 1)
        container_name = "HomePlayerChart";
    if (p == 2)
        container_name = "PlayerTiersChart";
    if (h && p == 2)
        container_name = "HomePlayerTiersChart";

    container_name = container_name.replace(/\s+/g, '');
    var chart = new CanvasJS.Chart(container_name,
    {
        title:{
            text: chart_title
        },
        legend: {
            maxWidth: 350,
            itemWidth: 120
        },
        data: [
        {
            type: "pie",
            legendText: "{indexLabel}",
            dataPoints: dps
        }
        ],
        backgroundColor: null
    });
    chart.render();
}

function searchFilter(input_id, table_id) {
    var input, filter, table, tr, td, i;

    input = document.getElementById(input_id);
    filter = input.value.toUpperCase();
    table = document.getElementById(table_id);
    console.log(input_id);
    console.log(table_id);
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[0];
      if (td) {
        if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }       
    }
  }
function showHome(){
    current_tab = document.getElementById('tab').value
    document.getElementById(current_tab).style.visibility = 'hidden';
    document.getElementById('home').style.visibility = 'visible';
    document.getElementById('tab').value = 'home';
}
function showGather(){
    current_tab = document.getElementById('tab').value
    document.getElementById(current_tab).style.visibility = 'hidden';
    document.getElementById('gather').style.visibility = 'visible';
    document.getElementById('tab').value = 'gather';
}
function showCraft(){
    current_tab = document.getElementById('tab').value
    document.getElementById(current_tab).style.visibility = 'hidden';
    document.getElementById('craft').style.visibility = 'visible';
    document.getElementById('tab').value = 'craft';
}
function showFarm(){
    current_tab = document.getElementById('tab').value
    document.getElementById(current_tab).style.visibility = 'hidden';
    document.getElementById('farm').style.visibility = 'visible';
    document.getElementById('tab').value = 'farm';
}
function showGvG(){
    current_tab = document.getElementById('tab').value
    document.getElementById(current_tab).style.visibility = 'hidden';
    document.getElementById('gvg').style.visibility = 'visible';
    document.getElementById('tab').value = 'gvg';
}
function showAccount(){
    current_tab = document.getElementById('tab').value
    document.getElementById(current_tab).style.visibility = 'hidden';
    document.getElementById('account').style.visibility = 'visible';
    document.getElementById('tab').value = 'account';
}

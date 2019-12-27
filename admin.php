<!DOCTYPE html>
<?php
require 'db.php';
session_start();

if($_SESSION['rank'] == "Admin" )
{

    
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="css/admin.css" rel="stylesheet">
    <link rel="icon" href="css/logo.png">
      
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    
    <title>Administrator</title>

  </head>
    
    
<body onload="onloadFunctions()">
      
<script>
        
        function _(el)
        {
            return document.getElementById(el);
        }
    
        function onloadFunctions()
        {
            
            showTable(1);
            overview();
        }
    
    
        //Get account table data using ajax
        function showTable(view)
        {
        //Calling Ajax
        var ajax = new XMLHttpRequest();
        var method = "GET";
        var url = "userData.php?view="+view;
        var asynchronous ="true";
            
            
            
        //Sending Ajax request
        ajax.open(method, url, asynchronous);
        ajax.send();
            
        ajax.onreadystatechange = function()
        {
            if (this.readyState ==4 && this.status == 200)
            {
                var data = JSON.parse(this.responseText); 
                console.log(data);
                
                var html = "";
                    
                if(view == 1 || view == 2)
                {
                    if(view == 1 ){ _("status").innerHTML="Active Users";  }
                    if(view == 2 ){ _("status").innerHTML="Pending Users"; }
                    
                    _("th1").innerHTML = "ID";
                    _("th2").innerHTML = "Username";
                    _("th3").innerHTML = "First Name";
                    _("th4").innerHTML = "Last Name";
                    _("th5").innerHTML = "Email";
                    _("th6").innerHTML = "Action";
                    
                    for(var a= 0; a < data.length; a++)
                    {
                        var id = data[a].id;
                        var username = data[a].username;
                        var firstname = data[a].first_name;
                        var lastname = data[a].last_name;
                        var email = data[a].email;
                        
                        html += "<tr>";
                            html += "<td>" + id + "</td>";
                            html += "<td>" + username + "</td>";
                            html += "<td>" + firstname + "</td>";
                            html += "<td>" + lastname + "</td>";
                            html += "<td>" + email + "</td>";
                            html += "<td><a href='#myModal' data-toggle='modal' data-id="+username+" data-target='#myModal'>Action</a</td>"
                        html += "</tr>";
                    
                  
                    }
                }
                    
                    
                if(view == 3)
                {
                    _("status").innerHTML="Log";
                    _("th1").innerHTML = "ID";
                    _("th2").innerHTML = "Date";
                    _("th3").innerHTML = "Action";
                    _("th4").innerHTML = "Description";
                    _("th5").innerHTML = "";
                    _("th6").innerHTML = "";
                    
                    for(var a= 0; a < data.length; a++)
                    {
                        var id = data[a].id;
                        var date = data[a].date;
                        var action = data[a].action;
                        var description = data[a].description;
                        
                        html += "<tr>";
                            html += "<td>" + id + "</td>";
                            html += "<td>" + date + "</td>";
                            html += "<td>" + action + "</td>";
                            html += "<td>" + description + "</td>";
                        html += "</tr>";
                    
                  
                    }
                    
                    
                        
                }
                
                    _("fileData").innerHTML = html;  //Inserting into tbody of the table
                }
            }
        }
    
    //Files and accounts overview data
    function overview()
    {
        $.ajax
        ({ 
            url: 'adminOverview.php',
            type: 'post',
            success: function(data) 
            {   
                //console.log(data);
                var data = JSON.parse(data);
                
                //Files Overview
                _("files").innerHTML = data[0][0];
                _("completed").innerHTML = data[0][1];
                _("pending").innerHTML = data[0][2];
                _("deleted").innerHTML = data[0][3];
                _("public").innerHTML = data[0][4];
                _("private").innerHTML = data[0][5];
                
                //Accounts Overview
                _("totaluser").innerHTML = data[1][0];
                _("activeuser").innerHTML = data[1][1];
                _("pendinguser").innerHTML = data[1][2]; 
            }
        }); 
        
    }
    
    //Activate or disable accounts
    function userConfigure(id,status,rank)
    {
        
        $.ajax
        ({ 
            url: 'userConfigure.php',
            data: {id:id, status:status, rank:rank},
            type: 'post',
            success: function(data) 
            {   
               
                
                if(data == "disabled"){
                    swal("Disabled!", "You have successfully disabled this account", "success");
                }
                else if(data == "activated"){
                    swal("Activated!", "You have successfully activated this account", "success");
                }
                else if(data == "admin"){
                    swal("Admin Rights!", "You cannot deactivate an admin account", "error");
                }
                else{
                    swal ( "Oops" ,  "Something went wrong!" ,  "error" )
                } 
            }
        }); 
    }
    
    //Sort Table Data
    function sortTable(n)
    {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("tableData");
        switching = true;
        dir = "asc"; 
        while (switching)
        {
            switching = false;
            rows = table.rows;
            
            for (i = 1; i < (rows.length - 1); i++) 
            {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
      
                if(n > 0)
                {
                    if (dir == "asc") 
                    {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) 
                        { shouldSwitch= true; break; }
                    }
                    else if (dir == "desc") 
                    {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) 
                        { shouldSwitch = true; break; }
                    } 
                }
                else
                {
                    if (dir == "asc") 
                    {
                        if (Number(x.innerHTML) > Number(y.innerHTML)) 
                        { shouldSwitch = true; break; }
                    }
                    else if (dir == "desc") 
                    {
                        if (Number(x.innerHTML) < Number(y.innerHTML)) 
                        { shouldSwitch = true; break; }
                    }   
                }
  
            }
    
            if (shouldSwitch) 
            {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount ++;      
            } 
            else 
            {
                if (switchcount == 0 && dir == "asc") 
                { dir = "desc"; switching = true; }
            }
        }
    }
    
    //Search function for accounts
    $(document).ready(function(){
        $("#search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#fileData tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });});});
      
    //Display account attributes in BS Modal
    $(document).ready(function(){
            $('#myModal').on('show.bs.modal', function (e){
             
            var username = $(e.relatedTarget).data('id');
              
            $.ajax({
                type : 'post',
                url : 'userAttributes.php', //Here you will fetch records 
                data :  'username='+ username, //Pass $id
                success : function(data){
                    
                    console.log(data);
                    var data = JSON.parse(data);
                    $('.fetched-id').html(data[0][0]);
                    $('.fetched-username').html(data[0][1]);
                    $('.fetched-first').html(data[0][2]);
                    $('.fetched-last').html(data[0][3]);
                    $('.fetched-email').html(data[0][4]);
                    $('.fetched-status').html(data[0][5]);
                   
                    $('.fetched-files').html(data[1][0]);
                    $('.fetched-completed').html(data[1][1]);
                    $('.fetched-pending').html(data[1][2]);
                    $('.fetched-deleted').html(data[1][3]);
                    $('.fetched-public').html(data[1][4]);
                    $('.fetched-private').html(data[1][5]);
                    
                    var rank = data[0][6];
                    if(data[0][5] == 'Active')
                    {
                        $("#fetched-btn").html('Disable');
                        $("#fetched-btn").attr("onclick","userConfigure("+data[0][0]+",'disable','"+data[0][6]+"')");
                    }
                    if(data[0][5] == 'Disable')
                    {
                        $("#fetched-btn").html('Activate');
                        $("#fetched-btn").attr("onclick","userConfigure("+data[0][0]+",'activate', ' ')");
                    }
                      
            } }); }); });      
    
    //return to previous page
    function goBack(){ window.history.back(); }
    
    function empty(){
        
        $.ajax
        ({ 
            url: 'emptyTemporary.php',
            type: 'post',
            success: function(data) 
            {   
               swal("Emptied","","success");
                
            }
        });
        
    }
      
</script>   
    

    
    
<!--Page header-->
<header id="header">
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <h1><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>Administrator Dashboard <small></small></h1>
            </div>
            <div class="col-md-2">
                <button class="btn btn-default" id="return-btn" onclick="goBack()">Return</button>
                 
            </div>
        </div>
    </div>
</header>
  
<section class="alert alert-info">
    <div class="container">
        <b>NOTICE:</b> Adminstration is only for authorized personnel only!
    </div>
</section>

<!--Main content section -->
<section id="main">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a onclick=showTable(1) class="list-group-item">Active Users</a>
                    <a onclick=showTable(2) class="list-group-item">Pending Users </a>
                    <a onclick=showTable(3) class="list-group-item">Log </a>
                    <br>
                    <a onclick=empty() class="list-group-item">Empty Web Server </a>
                </div>
            <!--Account Overview-->
                <div class="well">
                    <h4><span class="glyphicon glyphicon-user">&nbsp;</span><b>Accounts Overview</b></h4>
                    <hr>
                    <table>
                        <tr><td style="width:225px;"><h4>Total</h4></td><td id="totaluser"></td></tr>
                        <tr><td><h4>Active</h4></td><td id="activeuser"></td></tr>
                        <tr><td><h4>Pending</h4></td><td id="pendinguser"></td></tr>
                
                    </table>
                </div>
          </div>
          <div class="col-md-9">
              
            <!-- File Overview -->
                <div class="panel panel-default">
                    <div class="panel-heading panel-header">
                        <h3 class="panel-title">Files Overview</h3>
                    </div>
                        <div class="panel-body">
                            <div class="col-md-4">
                                <div class="well dash-box">
                                    <h2><span class="glyphicon glyphicon-stats"></span></h2>
                                    <h4>Files</h4>
                                    <h5 id="files"></h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="well dash-box">
                                    <h2><span class="glyphicon glyphicon-folder-close"></span></h2>
                                    <h4>Completed</h4>
                                    <h5 id="completed"></h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="well dash-box">
                                    <h2><span class="glyphicon glyphicon-folder-open"></span></h2>
                                    <h4>Pending</h4>
                                    <h5 id="pending"></h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="well dash-box">
                                    <h2><span class="glyphicon glyphicon-trash"></span></h2>
                                    <h4>Deleted</h4>
                                    <h5 id="deleted"></h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="well dash-box">
                                    <h2><span class="glyphicon glyphicon-eye-open"></span></h2>
                                    <h4>Public</h4>
                                    <h5 id="public"></h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="well dash-box">
                                    <h2><span class="glyphicon glyphicon-eye-close"></span></h2>
                                    <h4>Private</h4>
                                    <h5 id="private"></h5>
                                </div>
                            </div>
                        </div>
                    </div>

              <!-- Latest Users -->
                <div class="panel panel-default">
                    <div class="panel-heading panel-header">
                        <h3 id="status" class="panel-title ">Active Users</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <input type="text" class="form-control" id="search" placeholder="Search/Filter">
                        </div>
                    <div class="tableData">
                        <table id="tableData"  class='table table-hover'>
                        <tr>
                            <th onclick="sortTable(0)" id=th1></th>
                            <th onclick="sortTable(1)" id=th2></th>
                            <th onclick="sortTable(2)" id=th3></th>
                            <th onclick="sortTable(3)" id=th4></th>
                            <th onclick="sortTable(4)" id=th5></th>
                            <th id=th6></th>
                        </tr>
                            <tbody id="fileData"></tbody>
                        </table>
                    </div>
                </div>
              </div>
          </div>
        </div>
    </div>
</section>

<!--Account Modal-->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>ACCOUNT ATTRIBUTES</b>
                <button type="button" class="close" data-dismiss="modal">&times;</button></h4>
            </div>
            <div class="modal-body">
                <div class="fetched-data"></div>
                <table id="">
                    <tr><th style="width:125px;">Identification</th><td class="fetched-id"></td></tr>
                    <tr><th>Username</th><td class="fetched-username"></td></tr>
                    <tr><th>First Name</th><td class="fetched-first"></td></tr>
                    <tr><th>Last Name</th><td class="fetched-last"></td></tr>
                    <tr><th>Email</th><td class="fetched-email"></td></tr>
                    <tr><th>Status</th><td class="fetched-status"></td></tr>
                   </table>
                <hr>
                <table>
                    <tr><th style="width:125px;">Total Files</th><td class="fetched-files"></td></tr>
                    <tr><th>Completed</th><td class="fetched-completed"></td></tr>
                    <tr><th>Pending</th><td class="fetched-pending"></td></tr>
                    <tr><th>Deleted</th><td class="fetched-deleted"></td></tr>
                    <tr><th>Public</th><td class="fetched-public"></td></tr>
                    <tr><th>Private</th><td class="fetched-private"></td></tr>
                </table>
            </div>
            <div class="modal-footer">   
                <button type="button" class="btn btn-default" id="fetched-btn" onclick="" value="test"></button>
            </div>
        </div>
    </div>
</div>




</body>
</html>

<?php
    
}
else
{
    header('Location: index.php'); 
}
?>

<?php
require 'db.php';
session_start();

if(isset($_SESSION['username']))
{

?>

<html>
<head>
    <title>File Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="css/downloadPage.css">
    <link rel="icon" href="css/logo.png">
    
    
    
    
    
</head>

<body onload="showTable(1)">
        
    <script>
        
        
        function _(el)
        {
            return document.getElementById(el);
        }
        
        //PRINT FILE TABLE START//
        function showTable(view)
        {
        //Calling Ajax
        var ajax = new XMLHttpRequest();
        var method = "GET";
        var url = "tableData.php?view="+view;
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
                    
                for(var a= 0; a < data.length; a++)
                {
                    if(view == 2){
                        var id = data[a].fileId;
                    }
                    else{
                        var id = data[a].id;
                    }
                    
                    var owner = data[a].owner;
                    var name = data[a].name;
                    var date = data[a].date;
                        
                    html += "<tr>";
                        html += "<td>" + id + "</td>";
                        html += "<td>" + name + "</td>";
                        html += "<td>" + owner + "</td>";
                        html += "<td>" + date + "</td>";
                                
                        if( view == 1 || view == 2 )
                        {html += "<td><a href='#myModal' data-toggle='modal' data-id="+id+" data-target='#myModal'>View</a></td>";}
                        if( view == 3 || view == 4 )
                        {html += "<td><a href='#uploaderModal' data-toggle='modal' data-id="+id+">Status</a></td>";}
                    html += "</tr>";
                    
                }
                    if(view == 4){_("fetched-delete").disabled = true;}
                    if(view == 3){_("fetched-delete").disabled = false;}
                
                    document.getElementById("fileData").innerHTML = html;  //Inserting into tbody of the table
                }
            }
        }
            //PRINT TABLE END//
        
            //PRINT INFORMATION TO BOOTSTRAP MODAL START//
            //Fetch information for "View all public and available files
            $(document).ready(function(){
            $('#myModal').on('show.bs.modal', function (e){
             $('.fetched-data').html(''); 
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type : 'post',
                url : 'fetch_record.php', //Here you will fetch records 
                data :  'rowid='+ rowid, //Pass $id
                success : function(data){

                    attributes = JSON.parse(data);
                   
                    $('.fetched-id').html(attributes[0]);           //id
                    $('#fetched-iden').val(attributes[0]); 
                    $('.fetched-name').html(attributes[1]);         //name
                    $('.fetched-description').html(attributes[2]);  //description
                    $('.fetched-type').html(attributes[3]);         //type
                    $('.fetched-size').html(attributes[4]);         //size
                    $('.fetched-owner').html(attributes[5]);        //owner
                    $('.fetched-date').html(attributes[6]);         //date
                    $('.fetched-level').html(attributes[7]);        //level
                    $('.fetched-md5').html(attributes[9]);          //md5
                    $('.fetched-sha1').html(attributes[10]);        //sha1
                    $("#fetched-path").val(attributes[8]);          //path
                    var path = attributes[8];
                   
            } }); }); });
        
            //Fetch information for "View my uploads"
            $(document).ready(function(){
            
            $('#uploaderModal').on('show.bs.modal', function (e){
            var rowid = $(e.relatedTarget).data('id');
                
            $.ajax({
                
                type : 'post',
                url : 'fetch_access.php', //Here you will fetch records 
                data :  'rowid='+ rowid, //Pass $id
                success : function(data){
                    
                    var data =JSON.parse(data);
                     
                    $('.fetched-id').html(data[0][0]);           //id 
                    $('.fetched-name').html(data[0][1]);         //name
                    $('.fetched-description').html(data[0][2]);  //description
                    $('.fetched-type').html(data[0][3]);         //type
                    $('.fetched-size').html(data[0][4]);         //size
                    $('.fetched-date').html(data[0][5]);         //date
                    $('.fetched-level').html(data[0][6]);        //level
                    $('.fetched-status').html(data[0][7]);        //status
                    $('.fetched-publicity').html(data[0][8]);
                    $("#fetched-path").val(data[0][9]);
                    $('#fetched-iden').val(data[0][0]);  
                
            $('#userStatus').html("<tr><th>Username</th><th>Status</th></tr>");     
           table = document.getElementById("userStatus");      
           for(var i = 0; i < data[1].length; i++)
           {
               //Create New Row
               var newRow = table.insertRow(table.length);
               for(var j = 0; j < 1; j++)
               {
                //Create New Cell
                var status = newRow.insertCell(j);
                var accountId = newRow.insertCell(j);
                   
                status.innerHTML = data[1][i]["status"];
                accountId.innerHTML = data[1][i]["username"];
                
               }
           }
                   
                
                    
        } }); }); });
        //PRINT INFORMATION TO BOOTSTRAP MODAL END//
        
        
        //DOWNLOAD FILE INSIDE BOOTSTRAP MODAL//
        function downloadFile()
        {
        
        var id = document.getElementById("fetched-iden").value;
        var path = document.getElementById("fetched-path").value;
        var password = document.getElementById("fetched-password").value;
        
        
          
        $.ajax
        ({ 
            url: 'accessCheck.php',
            data: {id:id, path: path, password: password},
            type: 'post',
            success: function(data) 
            {   
                if(data == "true"){
                $('.fetched-data').html('<div class="alert alert-success"><strong>SUCCESS: </strong>Your file will be downloaded shortly</div>');
                
                location.href = "getObject.php?download="+path;
                
                }
                else if(data == "error_1"){
                $('.fetched-data').html('<div class="alert alert-danger"><strong>WARNING: </strong>Wrong Password. Please try again</div>');   
                }
                else if(data == "error_2"){
                $('.fetched-data').html('<div class="alert alert-info"><strong>Info: </strong>You have already downloaded this file.</div>');
                }
                else if(data == "error_3"){
                $('.fetched-data').html('<div class="alert alert-danger"><strong>WARNING: </strong>No Access Detected!</div>');
                }
                else if(data == "error_4"){
                $('.fetched-data').html('<div class="alert alert-danger"><strong>WARNING: </strong>Something Went Wrong</div>');
                }
                else{
                $('.fetched-data').html('<div class="alert alert-danger"><strong>Error Detected!</strong></div>');
                }
                
                
            }
        }); 
        }
        
        //DELETE FROM UPLOADERS MODAL//
        function deleteFile()
        {
        swal({
            title: "Are you sure?",
            text: "Once the file is deleted, you will not be able to recover this file",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
        .then((willDelete) => {
            if (willDelete) 
            {
            swal("Your file has been removed from the cloud storage", {
            icon: "success", });
            var path = document.getElementById("fetched-path").value;
            var id = document.getElementById("fetched-iden").value;
                
            $.ajax({ 
                url: 'delete.php',
                data: {path: path, id: id},
                type: 'post',
            });     
                     
            } 
            else 
            {
                swal("Request Aborted!", "Your file remains in the cloud storage");
              
            }
            });
        }
        
        //SEARCH FILE DATA
        $(document).ready(function(){
            $("#search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#fileData tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });});});
        
        
        //FILE DATA SORTING
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
        
        //Export SQL data to Excel
        function exportExcel() {
            
            $.ajax({ 
                url: 'exportData.php',
                type: 'post',
                success(data)
                {
                    swal(data);
                }
            });
            
            
        }
        
        //Admin login
        function adminLogin()
        {
            var username = _("username").value;
            var password = _("password").value;
            
            $.ajax({ 
                url: 'adminLogin.php',
                type: 'post',
                data: {username:username, password:password},
                success(data)
                {
                    if(data == "true"){
                        window.location.href = "admin.php";
                    }
                    else if(data == "false"){
                        swal ( "Oops" ,  "Something went wrong!" ,  "error" );
                    }
                    else{
                        swal ( "Oops" ,  "Something went wrong!" ,  "error" );
                    }
                }
            });
        }
    
        </script>      
        
        
        
<!-- Nav Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">THE FILE SOLUTION</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <span class="sr-only">(current)</span>
            <a class="nav-item nav-link" href="uploadPage.php">Upload</a>
            <a class="nav-item nav-link active" href="downloadPage.php">Download</a>
            <a class="nav-item nav-link" href="verifyPage.php">Verify</a>
            <a class="nav-item nav-link" href="guestPage.php">Guest</a>
            <a class="nav-item nav-link" href="#" data-toggle="modal" data-target="#adminLogin">Admin</a>
            <a class="nav-item nav-link" href="logout.php">Log Out</a>
        </div>
    </div>
</nav>
    
        
<!--Main Content Header -->  
<div class="container">
    <h1 class="brand">FILE DASHBOARD</h1>
    <div class="form">
        <div class="setting">        
            <div class="input-group">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">
                        Action
                    </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="exportData.php">Export Data to Excel</a>
                </div>
                </div>
                
                <input type="text" name="search" id="search" class="form-control" placeholder="Search File" />
                <select name="files" class="form-control" onchange="showTable(this.value)">
                    <option value="1">Public Files</option>
                    <option value="2">Available Files</option>
                    <option value="3">My Pending Uploads</option>
                    <option value="4">My Completed Uploads</option>
                </select>
            </div>
        </div>
        <br>
        <div class="tableData">
            <table id="tableData"  class='table table-hover'>
            <tr>
                <th onclick="sortTable(0)">ID</th>
                <th onclick="sortTable(1)">FILE NAME</th>
                <th onclick="sortTable(2)">OWNER</th>
                <th onclick="sortTable(3)">DATE</th>
                <th>ACTION</th>
            </tr>
            <tbody id="fileData"></tbody>
            </table>
        </div>
    </div>
</div>
    
<!--BOOTSTRAP MODAL START-->
    
<!--View all public/available files "Bootstrap Modal"-->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">FILE ATTRIBUTES</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="fetched-data"></div>
                <table id="">
                    <tr><th style="width:125px;">Identification</th><td class="fetched-id"></td></tr>
                    <tr><th>File Name</th><td class="fetched-name"></td></tr>
                    <tr><th>Description</th><td class="fetched-description"></td></tr>
                    <tr><th>Format</th><td class="fetched-type"></td></tr>
                    <tr><th>Size</th><td class="fetched-size"></td></tr>
                    <tr><th>Owner</th><td class="fetched-owner"></td></tr>
                    <tr><th>Date & Time</th><td class="fetched-date"></td></tr>
                    <tr><th>Sensitivity</th><td class="fetched-level"></td></tr>
                    <tr><th>MD5</th><td class="fetched-md5"></td></tr>
                    <tr><th>SHA1</th><td class="fetched-sha1"></td></tr>
                    <div id=fetch-access></div>
                </table>
            </div>
            <div class="modal-footer">
                <input type="password" class="form-control" placeholder="File Password" id="fetched-password" value="" autocomplete="new-password"/>
                <input type="hidden" value="" id="fetched-path">
                <input type="hidden" value="" id="fetched-iden">
                <button type="button" class="btn btn-outline-info" id="fetched-download" onclick="downloadFile()">Download</button>

            
            </div>
        </div>
    </div>
</div>
    
<!--View My Uploads "Bootstrap Modal"-->
<div class="modal fade" id="uploaderModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><strong>FILE STATUS</strong></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table>
                    <tr><th style="width:125px;">Identification</th><td class="fetched-id"></td></tr>
                    <tr><th>File Name</th><td class="fetched-name"></td></tr>
                    <tr><th>Description</th><td class="fetched-description"></td></tr>
                    <tr><th>Format</th><td class="fetched-type"></td></tr>
                    <tr><th>Size</th><td class="fetched-size"></td></tr>
                    <tr><th>Date</th><td class="fetched-date"></td></tr>
                    <tr><th>Status</th><td class="fetched-status"></td></tr>
                    <tr><th>Publicity</th><td class="fetched-publicity"></td></tr>
                    <tr><th>Sensitivity</th><td class="fetched-level"></td></tr> 
                </table>
                <br>
                <div class="statusTable">
                    <table id="userStatus" class="table"></table>
                </div>
                   
            </div>
            <div class="modal-footer">
                <input type="hidden" value="" id="fetched-path">
                <input type="hidden" value="" id="fetched-iden">
                <button type="button" class="btn btn-outline-danger" id="fetched-delete"  onclick="deleteFile()">Delete</button>
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
            </div>
        </div>
    </div>
</div>
    
<!-- Admin Login-->
<div class="modal fade" id="adminLogin" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><strong>ADMINISTRATOR VERIFICATION</strong></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form autocomplete="off">
                    <input autocomplete="false" name="hidden" type="text" style="display:none;">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="username" placeholder="Username" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password" placeholder="Password" autocomplete="new-password">
                        </div>
                    </div>
                </form>   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" id="fetched-delete"  onclick="adminLogin()">Login</button>
            </div>
        </div>
    </div>
</div>
    
<!--BOOTSTRAP MODAL END-->
       
</body>
</html>

<?php 

}
else
{ 
    header('Location: index.php'); 
} 

?>
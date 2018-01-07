<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="pics/gvclogo.png" sizes="16x16">
<?php
	if(!isset($_GET['page'])) {
?>
	<title>Greenville College Library</title>
<?php
	}
?>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="greenville.css">
	<!--<link rel="stylesheet" href="gvcmediaqueries.css">-->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Ubuntu" rel="stylesheet">
	<script src="jquery-3.2.0.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>   
</head>
<body>
<div id="container">
<?php
	ob_start();
	session_start();
?>
<?php
	include "navbar.php";
?>
	<div id="modalscripts">
		<?php
			include "modals.php";
		?>
		<script>
		$(document).ready(function(){
			$("#loginform").submit(function(e) {
				e.preventDefault();
				var username = $("#username").val();
				var password = $("#password").val();
				if(username == '' || password == '') {
					$("#loginalert").html("<b>Please type your username and password.</b>").css("color","red");
				} else {
					$.ajax ({
						url:"sessioncheck.php",
						method:"POST",
						data:{username:username, password:password},
						success:function(data) {
							if(data=="Invalid") {
								$("#loginalert").html("<b>Invalid username or password.</b>").css("color","red");
							} else if(data=="Librarian Login"){
								$("#login").hide();
								window.location.replace("index.php");
							} else if(data=="Borrower Login") {
								$("#login").hide();
								location.reload();
							}
						}
					});
				}
			});

			$("#login").on("hide.bs.modal",function(){
				$(this).find("#username").val("").end();
				$(this).find("#password").val("").end();
				$(this).find("#loginalert").html("").end();
			});

			$("#username").keypress(function() {
				$("#loginalert").html("");
			});

			$("#password").keypress(function() {
				$("#loginalert").html("");
			});

			$("#changepasswordform").submit(function(e){
				e.preventDefault();
				var currentpassword = $("#currentpassword").val();
				var newpassword = $("#newpassword").val();
				var confirmnewpassword = $("#confirmnewpassword").val();

				if(currentpassword=="" || newpassword=="" || confirmnewpassword=="") {
					$("#changepasswordalert").html("<b>Please fill in all fields.</b>").css("color", "red");
				} else if(newpassword!=confirmnewpassword){ 
					$("#changepasswordalert").html("<b>Passwords does not match.</b>").css("color", "red");
					$("#newpassword").val("").focus();
					$("#confirmnewpassword").val("").focus();
				} else {
					$.ajax({
						url:"changeborrowerpassword.php",
						method:"POST",
						data:{currentpassword:currentpassword, newpassword:newpassword, confirmnewpassword:confirmnewpassword},
						success:function(data) {
							if(data=="Invalid") {
								$("#changepasswordalert").html("<b>Invalid current password.</b>").css("color", "red");
								$("#currentpassword").val("").focus();
							} else {
								$("#changepasswordalert").html("<b>Password was successfully changed.</b>").css("color", "#1C8A43");
								$("#currentpassword").val("");
								$("#newpassword").val("");
								$("#confirmnewpassword").val("");
							}	
						}
					});
				}
			});

			$("#currentpassword").keypress(function() {
				$("#changepasswordalert").html("");
			});

			$("#newpassword").keypress(function() {
				$("#changepasswordalert").html("");
			});

			$("#confirmnewpassword").keypress(function() {
				$("#changepasswordalert").html("");
			});

			$("#changepassword").on("hide.bs.modal", function() {
				$(this).find("#currentpassword").val("").end();
				$(this).find("#newpassword").val("").end();
				$(this).find("#confirmnewpassword").val("").end();
				$(this).find("#changepasswordalert").html("").end();
			});

			$("#editprofileform").submit(function(e){
				e.preventDefault();
				var lastname = $("#lastname").val();
				var firstname = $("#firstname").val();
				var mi = $("#mi").val();
				var contactnumber = $("#contactnumber").val();

				if(lastname=="" || firstname=="" || mi=="" || contactnumber=="") {
					$("#editprofilealert").html("<b>Please fill in all fields.</b>").css("color", "red");
				} else {
					$.ajax({
						url:"editborrowerprofile.php",
						method:"POST",
						data:{lastname:lastname, firstname:firstname, mi:mi, contactnumber:contactnumber},
						success:function(data) {
							if(data=="Invalid contact number") {
								$("#contactnumberalert").html("Invalid contact number.").css({"color":"red", "font-size":"0.7em"});
							} else if(data=="Invalid middle initial") {
								$("#mialert").html("Middle initials only.").css({"color":"red", "font-size":"0.7em"});
							} else {
								$("#editprofilealert").html("<b>Profile updated.</b>").css("color", "#1C8A43");
								$("#contactnumberalert").html("");
								$("#mialert").html("");
							}
						}
					});
				}
			});

			$("#lastname").keypress(function() {
				$("#editprofilealert").html("");
				$("#mialert").html("");
				$("#contactnumberalert").html("");
			});

			$("#firstname").keypress(function() {
				$("#editprofilealert").html("");
				$("#mialert").html("");
				$("#contactnumberalert").html("");
			});

			$("#mi").keypress(function() {
				$("#editprofilealert").html("");
				$("#mialert").html("");
				$("#contactnumberalert").html("");
			});

			$("#contactnumber").keypress(function() {
				$("#editprofilealert").html("");
				$("#mialert").html("");
				$("#contactnumberalert").html("");
			});

			$("#editprofile").on("hide.bs.modal", function(){
				$(this).find("#editprofilealert").html("").end();
				$(this).find("#mialert").html("").end();
				$(this).find("#contactnumberalert").html("").end();
			});
		});
		</script>
	</div>

		<!--<div class="dashboard">
			<?php 
				/*if(!isset($_GET['page']) && !isset($_GET['basicsearch']) && !isset($_GET['collectionssearch']) && !isset($_GET['homesearch']) && !isset($_GET['mngbooksearch']) && !isset($_GET['classificationselect']) && !isset($_GET['mngborrowersearch']) && !isset($_GET['asearch']) && !isset($_GET['psearch']) && !isset($_GET['csearch']) && !isset($_GET['reservedate']) && !isset($_GET['expdate']) && !isset($_GET['dateborrowed']) && !isset($_GET['duedate']) && !isset($_GET['borrower']) && !isset($_GET['book']) && !isset($_GET['datereturned'])  && !isset($_GET['logintime']) && !isset($_GET['logouttime']) && !isset($_GET['logindate']) && !isset($_GET['logoutdate']) && !isset($_GET['archivedcsearch']) && !isset($_GET['archivedasearch']) && !isset($_GET['archivedpsearch']) && !isset($_GET['archivedborrowersearch']) && !isset($_GET['archivedbooksearch']) && !isset($_GET['archivedbookclassificationselect']) && !isset($_GET['archivedreservedate']) && !isset($_GET['archivedexpdate']) && !isset($_GET['archivedborrower']) && !isset($_GET['archivedbook']) && !isset($_GET['archiveddateborrowed']) && !isset($_GET['archivedatereturned']) && !isset($_GET['archivedlogindate']) && !isset($_GET['archivedlogintime']) && !isset($_GET['archivedlogoutdate']) && !isset($_GET['archivedlogouttime'])) {
					if(isset($_SESSION['librarian'])) { 
						include "dashboard.php";
					}
				}*/
			 ?>
		</div>-->
	<div id="adminsidebar">
		<?php
			if(isset($_SESSION['librarian'])) {
				include "sidebar.php";
			}
		?>
	</div>
	<div class="wrapper">
		<div id="mainpages">
			<?php
				if(!isset($_GET['page']) && !isset($_GET['basicsearch']) && !isset($_GET['collectionssearch']) && !isset($_GET['homesearch']) && !isset($_GET['mngbooksearch']) && !isset($_GET['classification']) && !isset($_GET['mngborrowersearch']) && !isset($_GET['asearch']) && !isset($_GET['psearch']) && !isset($_GET['csearch']) && !isset($_GET['reservedate']) && !isset($_GET['expdate']) && !isset($_GET['dateborrowed']) && !isset($_GET['duedate']) && !isset($_GET['borrower']) && !isset($_GET['book']) && !isset($_GET['datereturned']) && !isset($_GET['logintime']) && !isset($_GET['logouttime']) && !isset($_GET['logindate']) && !isset($_GET['logoutdate']) && !isset($_GET['archivedcsearch']) && !isset($_GET['archivedasearch']) && !isset($_GET['archivedpsearch']) && !isset($_GET['archivedborrowersearch']) && !isset($_GET['archivedbooksearch']) && !isset($_GET['archivedbookclassificationselect']) && !isset($_GET['archivedreservedate']) && !isset($_GET['archivedexpdate']) && !isset($_GET['archivedborrower']) && !isset($_GET['archivedbook']) && !isset($_GET['archiveddateborrowed']) && !isset($_GET['archivedatereturned']) && !isset($_GET['archivedlogindate']) && !isset($_GET['archivedlogintime']) && !isset($_GET['archivedlogoutdate']) && !isset($_GET['archivedlogouttime'])) {
					if(isset($_SESSION['borrower']) || empty($_SESSION)) {
						include "homepageslider.php";					
						include "mainsidebar.php";
						include "basicsearch.php";
						//include "gvcmap.php";
					} else if(isset($_SESSION['librarian'])) {
						include "dashboard.php";
					}
				} 
			?>

			<?php 
				if(isset($_GET['page'])) {
					$page=$_GET['page'];
					if($page=='basicsearch') {
						include "mainsidebar.php";
						include "basicsearch.php";
					} else if($page=='advancedsearch') {
						include "mainsidebar.php";
						include "advancedsearch.php";
					} else if($page=='newcollections') {
						include "newcollections.php";
					} else if($page=='top10books') {
						include "top10books.php";
					}
				}

			?>
		</div>
			

		<div class="collection">
			<?php
				if(isset($_GET['page'])) {
					$page=$_GET['page'];
					if($page=="collections") {
						include "collectionssearch.php";
						include "collections.php";
					} 
				} 
			?>
		</div>
		
		<div class="searchresult">
			<?php
				 if(isset($_GET['basicsearch'])) {
				 	include "mainsidebar.php";
				 	include "basicsearchresult.php";
				 } else if(isset($_GET['mngbooksearch']) || isset($_GET['classification'])) {
				 	include "booktblsearchresult.php";
				 } else if(isset($_GET['mngborrowersearch'])) {
				 	include "borrowertblsearchresult.php";
				 } else if(isset($_GET['asearch'])) {
				 	include "manageauthorsearchresults.php";
				 } else if(isset($_GET['psearch'])) {
				 	include "managepublishersearchresults.php";
				 } else if(isset($_GET['csearch'])) {
				 	include "manageclassificationsearchresults.php";
				 } else if(isset($_GET['book']) && isset($_GET['borrower']) && isset($_GET['reservedate']) && isset($_GET['expdate'])) {
				 	include "viewreservationssearchresults.php";
				 } else if(isset($_GET['book']) && isset($_GET['borrower']) && isset($_GET['dateborrowed']) && isset($_GET['duedate'])) {
				 	include "viewborrowedsearchresults.php";
				 } else if(isset($_GET['book']) && isset($_GET['borrower']) && isset($_GET['dateborrowed']) && isset($_GET['datereturned'])) {
				 	include "booklogssearchresults.php";
				 } else if(isset($_GET['book']) && isset($_GET['duedate']) && isset($_GET['dateborrowed']) && isset($_GET['datereturned'])) {
				 	include "borrowerbooklogssearchresults.php";
				 } else if (isset($_GET['borrower']) && isset($_GET['logintime']) && isset($_GET['logouttime']) && isset($_GET['logindate']) && isset($_GET['logoutdate'])) {
				 	include "borrowerlogssearchresults.php";
				 } else if(isset($_GET['archivedcsearch'])) {
				 	include "archivedclassificationssearchresults.php";
				 } else if(isset($_GET['archivedasearch'])) {
				 	include "archivedauthorssearchresults.php";
				 } else if(isset($_GET['archivedpsearch'])) {
				 	include "archivedpublisherssearchresults.php";
				 } else if(isset($_GET['archivedborrowersearch'])) {
				 	include "archivedborrowerssearchresults.php";
				 }  else if(isset($_GET['archivedbooksearch']) || isset($_GET['archivedbookclassificationselect'])) {
				 	include "archivedbookssearchresults.php";
				 } else if(isset($_GET['archivedreservedate']) && isset($_GET['archivedexpdate']) && isset($_GET['archivedborrower']) && isset($_GET['archivedbook'])) {
				 	include "archivedreservationssearchresults.php";
				 } else if(isset($_GET['archiveddateborrowed']) && isset($_GET['archiveddatereturned']) && isset($_GET['archivedborrower']) && isset($_GET['archivedbook'])) {
				 	include "archivedbooklogssearchresults.php";
				 } else if(isset($_GET['archivedborrower']) && isset($_GET['archivedlogindate']) && isset($_GET['archivedlogintime']) && isset($_GET['archivedlogoutdate']) && isset($_GET['archivedlogouttime'])) {
				 	include "archivedborrowerlogssearchresults.php";
				 } else if(isset($_GET['collectionssearch'])) {
					include "collectionssearchresult.php";
				 }
			?>
		</div>

		<div class="adminforms">
			<?php
				if(isset($_GET['page'])) {
					$page=$_GET['page'];
					if($page=='addbook') {
						include "managebook.php";
					} else if($page=='editbook') {
						include "editbook.php";
					} else if($page=='addborrower') {
						include "manageborrower.php";
					} else if($page=='addborrower') {
						include "addborrower.php";
					} else if($page=='editupdateborrower') {
						include "editupdateborrower.php";
					} else if($page=='editborrower') {
						include "editborrower.php";
					} else if($page=='deleteborrower') {
						include "deleteborrower.php";
					} else if($page=='vrs') {
						include "viewreservations.php";
					} else if($page=='bklogs') {
						include "booklogs.php";
					} else if($page=='classifications') {
						include "manageclassifications.php";
					} else if($page=='authors') {
						include "manageauthors.php";
					} else if($page=='publishers') {
						include "managepublishers.php";
					} else if($page=='editclassification') {
						include "editupdateclassification.php";
					} else if($page=='editauthor') {
						include "editupdateauthor.php";
					} else if($page=='editpublisher') {
						include "editupdatepublisher.php";
					} else if($page=='vbr') {
						include "viewborrowed.php";
					} else if($page=='archvs') {
						include "managearchives.php";
					} else if($page=='archvsc') {
						include "archivedclassifications.php";
					} else if($page=='archvsa') {
						include "archivedauthors.php";
					} else if($page=='archvsp') {
						include "archivedpublishers.php";
					} else if($page=='archvsbklogs') {
						include "archivedbooklogs.php";
					} else if($page=='archvsrs') {
						include "archivedreservations.php";
					} else if($page=='brlogs') {
						include "borrowerlogs.php";
					} else if($page=='archvsbrlogs') {
						include "archivedborrowerlogs.php";
					} else if($page=='archvsbooks') {
						include "archivedbooks.php";
					} else if($page=='archvsborrowers') {
						include "archivedborrowers.php";
					} else if($page=='librarysettings') {
						include "settings.php";
					} else if($page=='editupdateholiday') {
						include "editupdateholiday.php";
					} else if($page=='updatebook') {
						include "updatebook.php";
					} else if($page=='genbc') {
						include "barcodegenerator.php";
					} else if($page=='books') {
						include "booktbl.php";
					} else if($page=='borrowers') {
						include "borrowertbl.php";
					}
				}
			?>

		</div>

		<div class="transactions">
			<?php
				if(isset($_GET['page'])) {
					$page=$_GET['page'];
					if($page=='chkreservebook') {
						include "chkreservebook.php";
					} else if($page=='reservations') {
						include "borrowerreservations.php";
					} else if($page=='borrowerbooklogs') {
						include "borrowerbooklogs.php";
					} else if($page=='chkcancelreserve') {
						include "chkborrowercancelreserve.php";
					} else if($page=='borrowbook') {
						include "manageborrowbook.php";
						include "reserveexpire.php";
					} else if($page=='chkborrowbook') {
						include "chkborrowbook.php";
					} else if($page=='returnbook') {
						include "managereturnbook.php";
					} 
				}


			?>
			<script>
				$(document).ready(function(){
					$.ajax({
						url:"truncateborrowcart.php"
					});

					$.ajax({
						url:"truncatereturncart.php"
					});
					
					$.ajax({
						url:"reserveexpire.php"
					});
				
				});
			</script>
		</div>
	</div>
<?php
	if(isset($_SESSION['borrower']) || empty($_SESSION)) {
?>
	<!--<footer class="footer">
		<div id="copyright">
			<span>&copy; Copyright 2017</span>
		</div>
		<div id="address">
			<div>
				<span>
					<span style="font-weight:bold;">Greenville College</span><br>
					112 Belfast Street<br>
					Corner San Salvador<br>
					Greenpark Village<br>
					Manggahan, Pasig City<br>
				</span>
			</div>
		</div>
		<div id="contact">
			<span>
				Call us:<br>
				682-37-12|681-35-54
			</span>
		</div>
	</footer>-->
<?php
	}
?>
</div>
</body>
</html>
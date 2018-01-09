<div class="modal fade" id="bookInfo">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<span class="close" data-dismiss="modal">&times;</span>
				<h4 class="modal-title">CATALOG INFO</h4>
			</div>
			<div class="modal-body" id="content">

			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="borrowerinfomodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<span class="close" data-dismiss="modal">&times;</span>
				<h4 class="modal-title">BORROWER INFO</h4>
			</div>
			<div class="modal-body" id="borrowerinfodata">

			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="barcodemodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<span class="close" data-dismiss="modal">&times;</span>
				<h4 class="modal-title">Print Barcode</h4>
			</div>
			<div class="modal-body" id="barcode">

			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button">
					Print Barcode <span class="glyphicon glyphicon-print"> </span>
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="login">
	<div class="modal-dialog">
		<div class="modal-content">
		<form id="loginform">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title">LOG IN</h3>
			</div>
			<div class="modal-body">
				<div id="loginalert"> </div>
				<div class="form-group">
					<label for="username">ID Number/Username: </label>
					<input class="form-control" type="text" name="username" id="username">
				</div>
				<div class="form-group">
					<label for="password">Password: </label>
					<input class="form-control" type="password" name="password" id="password">
				</div>
				<div class="form-group">
					<input class="btn btn-success btn-md button"  type="submit" name="login" value="Log In">
				</div>
			</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="changepassword">
	<div class="modal-dialog">
		<div class="modal-content">
		<form id="changepasswordform">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
				<h4>CHANGE PASSWORD</h4>
			</div>
			<div class="modal-body">
				<div id="changepasswordalert"> </div>
				<label>Current Password:</label>
				<input type="password" name="currentpassword" id="currentpassword" class="form-control">
				<label>New Password:</label>
				<input type="password" name="newpassword" id="newpassword" class="form-control">
				<label>Confirm New Password:</label>
				<input type="password" name="confirmnewpassword" id="confirmnewpassword" class="form-control">
			</div>
			<div class="modal-footer">
				<input type="submit" class="btn btn-success btn-md button" name="changepassword" value="Change Password">
			</div>
		</form>
		</div>
	</div>
</div>

<div class="modal fade" id="addbookcopy">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4>ADD BOOK COPY</h4>
			</div>
			<div class="modal-body">
				<div id="addbookcopyalert"></div>
				<div id="addcopybookdata"></div>
			</div>
			<div class="modal-footer">

			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="paymentmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4>PAYMENT</h4>
			</div>
			<div class="modal-body">
				<div id="takepaymentalert"></div>
				<div id="takepaymentdata"></div>
			</div>
			<div class="modal-footer">

			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="notallowreserve">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php
					$settingsSQL = "SELECT * FROM settings";
					$settingsQuery = mysqli_query($dbconnect, $settingsSQL);
					$settings = mysqli_fetch_assoc($settingsQuery);
				?>
				<p>You already reserved <?php echo $settings['reservelimit'];?>  books. Only a maximum of <?php echo $settings['reservelimit']; ?> books is allowed.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="notallowborrow">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php
					$settingsSQL = "SELECT * FROM settings";
					$settingsQuery = mysqli_query($dbconnect, $settingsSQL);
					$settings = mysqli_fetch_assoc($settingsQuery);
				?>
				<p>You already borrowed <?php echo $settings['reservelimit'];?>  books. Please return any borrowed books to be able to reserve online again.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="limitborrow">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php
					$settingsSQL = "SELECT * FROM settings";
					$settingsQuery = mysqli_query($dbconnect, $settingsSQL);
					$settings = mysqli_fetch_assoc($settingsQuery);
				?>
				<p>Only a maximum of <?php echo $settings['borrowlimit'];?> books is allowed to be borrowed.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="notallowedborrow">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php
					$settingsSQL = "SELECT * FROM settings";
					$settingsQuery = mysqli_query($dbconnect, $settingsSQL);
					$settings = mysqli_fetch_assoc($settingsQuery);
				?>
				<p>This borrower already borrowed  <?php echo $settings['borrowlimit'];?> books. Only a maximum of <?php echo $settings['borrowlimit'];?> books is allowed to be borrowed.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="notallowedborrower">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>This borrower must settle overdue loans before being allowed to borrow again.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="feature">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Feature is not done yet. We are now working on it.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="notallowedborrower2">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>You must settle overdue loans before being allowed to reserve online again.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="didnotborrow">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>This borrower did not borrow any book.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="onlyonetitle">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Only one copy per title is allowed for a borrower.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="noduplicate">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Books with the same accession number or title are not allowed.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="alreadyincart">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>This book was already assigned to a cart for another borrower.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>




<div class="modal fade" id="noreserve">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>There were no reservations for this borrower.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="weekends">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Borrowing of books is not allowed during <b>weekends</b> or <b>holidays</b>.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="weekends2">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Returning of books is not allowed during <b>weekends</b> or <b>holidays</b>.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="weekends3">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Reserving of books is not allowed during <b>weekends</b> or <b>holidays</b>.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="invalididnum">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Invalid ID Number. Please try another one.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="invalidsearch">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>No results found. Try searching again.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="invalidbookid">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Invalid barcode or accession number. Please try another one.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="invalidreservedate">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Reserve date should not be greater than the expiration date.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="invalidstartdate">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Start date should not be greater than the end date.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="invaliddateborrowed1">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Date borrowed should not be greater than the due date.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="invaliddateborrowed2">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Date borrowed should not be greater than the date returned.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="invalidduedate">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Due date should not be greater than the date returned.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="invalidlogindate">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Login datetime should not be greater than the logout datetime.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="invalidlogintime">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Login time should not be greater than the logout time.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="notavailable">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Book is not available.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="notborrowed">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>This book was not borrowed.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="cancelreservefirst">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>This borrower seems to have reservations. Must cancel other reservations first before borrowing other books.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="notallow">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Book with the same ID is not allowed.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="addmsg">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Book record added.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="addmsg2">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Borrower record added.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="addmsg3">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>A new classification is added.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="addmsg4">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>A new author is added.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="addmsg5">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>A new publisher is added.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="addmsg6">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>A new holiday was added.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editmsg">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Classification was successfully updated.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editmsg2">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Author was successfully updated.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editmsg3">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Publisher was successfully updated.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editmsg4">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Holiday was successfully updated.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editmsg5">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Book was successfully updated.</p>
			</div>
			<div class="modal-footer">
				<a href="?page=mngBooks">
					<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
				</a>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editmsg6">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Borrower was successfully updated.</p>
			</div>
			<div class="modal-footer">
				<a href="?page=mngBooks">
					<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
				</a>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="deletemsg">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Classification was successfully deleted.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="deletemsg2">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Author was successfully deleted.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="deletemsg3">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Publisher was successfully deleted.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="settingsupdated">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Settings were successfully updated.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="emptysearch">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Please type some search keyword.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="providedateandtime">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Please provide both date and time.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="emptyidnum">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Please type an ID Number.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="emptybookid">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Please type an accession number or scan a barcode.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="emptyinput">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Please fill in all fields.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="bookemptyinput">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Some fields are required. (Book Title and Number of Copies)</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="invalidbookinput">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Some fields need numeric values. (ISBN, year, pages, copies, price)</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="invalidborrowerinput">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Some fields need numeric values. (ID Number, Contact Number)</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="emptyinputbook">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Please provide an accession number, title and call number.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="emptyacc">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Please provide an accession number.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="emptybarcode1">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Please provide a beginning accession number.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="emptyidnumber">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Please provide an ID Number.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="emptyclassification">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Please input a classification.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="emptyauthor">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Please input an author.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="emptypublisher">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Please input a publisher.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-md button" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="confirmreturn">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<form id="confirmreturnform">
			<div class="modal-body" id="deletebody">
				<p>Are you sure you want to return this book?</p>
				<div class="radio">
					<label><input type="radio" name="condition" class="condition" value="onshelf" checked>On Shelf</label>
				</div>
				<div class="radio">
					<label><input type="radio" name="condition" class="condition" value="lost">Lost</label>
				</div>
				<div class="radio">
					<label><input type="radio" name="condition" class="condition" value="damaged">Damaged</label>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmreturn">Confirm</button>
				<button type="button" class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="deleteconfirm">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" id="deletebody">
				<p>Are you sure you want to delete this book?</p>
			</div>
			<div class="modal-footer">
				<button id="confirmdelete" class="btn btn-success btn-sm button">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="archiveconfirm">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" id="deletebody">
				<p>Are you sure you want to archive this book?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmarchivebook">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="confirmborrowercancelreserve">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to cancel reservation of this book?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmcancelreserve">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="confirmadmincancelreserve">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to cancel reservation of this book?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmcancelreserve">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="confirmarchivebooklog">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to archive this record?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmarchiverecord">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="confirmarchiveborrowerlog">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to archive this record?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button archiveborrowerlog">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="confirmdeleteclassification">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to delete this classification?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmdeleteclassification">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="confirmdeleteauthor">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to delete this author?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmdeleteauthor">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="confirmdeletepublisher">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to delete this publisher?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmdeletepublisher">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="archiveclassification">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to archive this classification?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmarchiveclassification">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="archiveauthor">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to archive this author?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmarchiveauthor">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="archivepublisher">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to archive this publisher?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmarchivepublisher">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="deactivateborrower">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to deactivate this borrower?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmdeactivateborrower">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="activateborrower">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to activate this borrower?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmactivateborrower">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="restorebooklog">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to restore this book log record?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmrestorebooklog">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="restoreborrowerlog">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to restore this borrower log record?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmrestoreborrowerlog">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="restoreclassification">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to restore this classification?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmrestoreclassification">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="restoreauthor">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to restore this author?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmrestoreauthor">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="restorepublisher">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to restore this publisher?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmrestorepublisher">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="restorebook">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to restore this book?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmrestorebook">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="restoreborrower">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to restore this borrower?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmrestoreborrower">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="permanentdeletereservation">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="passwordalert"></div>
				<p>Are you sure you want to permanently delete this reservation record?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmpermanentdeletereservation">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="permanentdeleteauthor">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to permanently delete this author?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmpermanentdeleteauthor">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="permanentdeletebooklog">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to permanently delete this book log?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmpermanentdeletebooklog">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="permanentdeletebook">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to permanently delete this book?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmpermanentdeletebook">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="permanentdeleteborrower">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to permanently delete this borrower?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmpermanentdeleteborrower">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="permanentdeleteborrowerlog">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to permanently delete this borrower log?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmpermanentdeleteborrowerlog">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="permanentdeleteclassification">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to permanently delete this classification?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmpermanentdeleteclassification">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="permanentdeletepublisher">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to permanently delete this publisher?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmpermanentdeletepublisher">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="deletefromborrowcart">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to remove this book for borrowing?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmdeletefromborrowcart">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="deletefromreturncart">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to remove this book for returning?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmdeletefromreturncart">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="deleteholiday">
	<div class="modal-dialog alertmodal">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to delete this holiday?</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm button confirmdeleteholiday">Confirm</button>
				<button class="btn btn-success btn-sm button" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>


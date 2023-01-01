<?php
include_once("database.php");
include("header.php");
?>
<title>Palm Tree</title>
<script type="text/javascript" src="js/jquery.tabledit.js"></script>
<link rel="stylesheet" href="css/main.css">
<?php include('container.php');
?>
<div class="container home">
	<?php

	// Get Company
	$sql_query = "SELECT 
					comp_title, 
					comp_subtitle 
				  FROM company 
				  WHERE comp_id = 1";

	$stmnt = mysqli_prepare($conn, $sql_query);
	mysqli_stmt_execute($stmnt);

	$result_set = mysqli_stmt_get_result($stmnt);
	while ($company = mysqli_fetch_assoc($result_set)) {
	?>
		<h1><img src="img/logo/logo.png" alt="logo" width="50" height="50" />&nbsp;<?php echo $company['comp_title']; ?></h1>
		<h3><?php echo $company['comp_subtitle']; ?></h3>
	<?php } ?>
	<h5 style="color:#FF0000;line-height: 35px; font-size:13px"><b>Manage Customers</b>
		<button id="scrollDown" class="btn btn-default read-more" style="background:#000081;color:white;float:right;" title="Scroll Down in the Table"><img src="img/arrow_down.png" alt=""></button>
		<button id="scrollUp" class="btn btn-default read-more" style="background:#000081;color:white;float:right;" title="Scroll Up in the Table"><img src="img/arrow_up.png" alt=""></button>
		<button id='refreshPage' onClick="window.location.reload();" class="btn btn-default read-more" style="background:#000081;color:white;float:right;" title="Refresh Page"><img src="img/refresh.png" alt=""></button>
		<button class="btn btn-default read-more" style="background:#000081;color:white;float:right;" title="Download Table as a CSV File" onclick="download_table_as_csv('data_table');"><img src="img/export.png" alt=""></button>
		<button id="insertRow" class="btn btn-default read-more" style="background:#000081;color:white;float:right;" title="Create New Customer"><img src="img/create.png" alt=""></button>
	</h5>
	<br>
	<div class="scrollable">
		<input class="form-control rounded-0" type="text" id="myInput" onkeyup="searchCustomers()" placeholder="Search for Customers...">
		<form id="custAddForm">
			<div id="parent-element">
				<table id="data_table" class="table table-striped">
					<thead>
						<tr>
							<th>Customer ID</th>
							<th id="sort_first_name" class="headerSortUp">First Name</th>
							<th id="sort_last_name" class="headerSortUp">Last Name</th>
							<th id="sort_email" class="headerSortUp">Email</th>
							<th id="sort_phone" class="headerSortUp">Phone</th>
							<th id="sort_created" class="headerSortUp">Created Date</th>
							<th style="display:none;">Send Email</th>
							<th style="display:none;">Notes</th>
							<th style="text-align:center; vertical-align:middle">Send Email</th>
							<th style="text-align:center; vertical-align:middle">Notes</th>
							<th style="text-align:center; vertical-align:middle">Delete Customer</th>
						</tr>
					</thead>
					<tbody>
						<?php

						// Get Customers
						$sql_query = "SELECT 
										cust_id, 
										cust_first_name, 
										cust_last_name, 
										cust_email, 
										cust_phone, 
										cust_notes, 
										cust_email_sent, 
										DATE_FORMAT(cust_created,'%m/%d/%Y %l:%i %p') AS cust_created 
									  FROM customers a 
									  ORDER BY a.cust_created ASC";

						$stmnt = mysqli_prepare($conn, $sql_query);
						mysqli_stmt_execute($stmnt);

						$result_set = mysqli_stmt_get_result($stmnt);
						while ($customer = mysqli_fetch_assoc($result_set)) {
						?>
							<tr id="<?php echo $customer['cust_id']; ?>">
								<td><?php echo $customer['cust_id']; ?></td>
								<td><?php echo $customer['cust_first_name']; ?></td>
								<td><?php echo $customer['cust_last_name']; ?></td>
								<td><?php echo $customer['cust_email']; ?></td>
								<td><?php echo $customer['cust_phone']; ?></td>
								<td><?php echo $customer['cust_created']; ?></td>
								<td class="hiddenFlag" style="display:none;"><?php echo ($customer['cust_email_sent'] == 1) ? "Sent" : "Not Sent"; ?></td>
								<td class="hiddenNotes" style="display:none;"><?php echo $customer['cust_notes']; ?></td>
								<td style="text-align:center; vertical-align:middle" title="Checked=Email Sent, Unchecked=Email Not Sent">
									<?php
									if ($customer['cust_email_sent'] == 1) {
										echo '<input name="cust_id" id="flag" value="' . $customer['cust_id'] . '" type="checkbox" checked="yes" onchange="send_single_email(this)">';
									} else {
										echo '<input name="cust_id" id="flag" value="' . $customer['cust_id'] . '" type="checkbox" onchange="send_single_email(this)">';
									}
									?>
								</td>
								<td style="text-align:center; vertical-align:middle">
									<?php
									if ($customer['cust_notes'] != NULL) {
										echo '<button id="custNotes" class="custNotes" style="background-image: url(img/notes_exist.png)" type="button" title="Customer Notes"></button>';
									} else {
										echo '<button id="custNotes" class="custNotes" type="button" title="Customer Notes"></button>';
									}
									?>
									<div class="popup-container">
										<div id="overlay" style="display:none;"></div>
										<div id="popup" style="display:none;">
											<textarea id="custNotesText" class="custNotesText" name="freeform" rows="20" cols="1" style="text-align: left;" placeholder="Log some Notes about the Customer here..." data-cust-id="<?php echo $customer['cust_id']; ?>"><?php echo $customer['cust_notes']; ?></textarea>
											<input type="hidden" id="notes_id" name="notes_id" value="<?php echo $customer['cust_id']; ?>">
											<button id="submitNotes" class="submitNotes" type="button" style="background:#228B22;color:white;width:269px;height:35px;border:0;" title="Save Customer Notes">Save Notes</button>
											<button id="cancelNotes" class="cancelNotes" type="button" style="background:#FF0000;color:white;width:270px;height:35px;border:0;" title="Cancel Entering Notes">Cancel</button>
										</div>
									</div>
								<td style="text-align:center; vertical-align:middle">
									<input id="custDelete" type="button" title="Delete Customer">
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript" src="js/main.js"></script>
<script>
	// Table Edit Function
	$("#data_table").Tabledit({
		deleteButton: false,
		editButton: false,
		columns: {
			identifier: [0, "cust_id"],
			editable: [
				[1, "cust_first_name"],
				[2, "cust_last_name"],
				[3, "cust_email"],
				[4, "cust_phone"],
			],
		},
		hideIdentifier: true,
		url: "table_edit.php",
	});
</script>
<?php include('footer.php'); ?>
<?php
include_once("database.php");
include_once("commands.php");

// Get Tabledit Field Information
$input = filter_input_array(INPUT_POST);
if ($input['action'] == 'edit') {
	$cust_id = $input['cust_id'];
	$update_field = '';
	$update_value = '';

	if (isset($input['cust_first_name'])) {
		$update_field = 'cust_first_name';
		$update_value = mysqli_real_escape_string($conn, $input['cust_first_name']);
	} else if (isset($input['cust_last_name'])) {
		$update_field = 'cust_last_name';
		$update_value = mysqli_real_escape_string($conn, $input['cust_last_name']);
	} else if (isset($input['cust_email'])) {
		$update_field = 'cust_email';
		$update_value = mysqli_real_escape_string($conn, $input['cust_email']);
	} else if (isset($input['cust_phone'])) {
		$update_field = 'cust_phone';
		$update_value = mysqli_real_escape_string($conn, $input['cust_phone']);
	}

	if ($update_field && $cust_id && $update_value) {
		// Update Customer in local DB
		$sql_query = "UPDATE customers SET $update_field = ? WHERE cust_id = ?";
		$stmnt = mysqli_prepare($conn, $sql_query);
		mysqli_stmt_bind_param($stmnt, 'si', $update_value, $cust_id);
		mysqli_stmt_execute($stmnt);

		// Sync to HubSpot
		if (isHubspotEnabled($conn)) {
			$allowedHubspotFields = ['cust_first_name', 'cust_last_name', 'cust_email', 'cust_phone'];
			if (in_array($update_field, $allowedHubspotFields)) {
				updateInHubspot($cust_id, $update_field, $update_value, $conn);
			}
		}
	}
}


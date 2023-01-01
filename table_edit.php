<?php
include_once("database.php");

// Get Tabledit Field Information
$input = filter_input_array(INPUT_POST);
if ($input['action'] == 'edit') {

	$cust_id = $input['cust_id'];
	$update_field = '';

	if (isset($input['cust_first_name'])) {
		$cust_first_name = mysqli_real_escape_string($conn, $input['cust_first_name']);
		$update_field .= "cust_first_name='" . $cust_first_name . "'";
	  } else if (isset($input['cust_last_name'])) {
		$cust_last_name = mysqli_real_escape_string($conn, $input['cust_last_name']);
		$update_field .= "cust_last_name='" . $cust_last_name . "'";
	  } else if (isset($input['cust_email'])) {
		$cust_email = mysqli_real_escape_string($conn, $input['cust_email']);
		$update_field .= "cust_email='" . $cust_email . "'";
	  } else if (isset($input['cust_phone'])) {
		$cust_phone = mysqli_real_escape_string($conn, $input['cust_phone']);
		$update_field .= "cust_phone='" . $cust_phone . "'";
	  }

	if ($update_field && $cust_id) {

		// Update Customer
		$sql_query = "UPDATE customers 
			SET $update_field
			WHERE cust_id = ?";

		$stmnt = mysqli_prepare($conn, $sql_query);

		mysqli_stmt_bind_param($stmnt, 'i', $cust_id);
		mysqli_stmt_execute($stmnt);
	}
}

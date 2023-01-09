<?php
include_once("database.php");
include("encryption.php");

// Main Switch
switch ($_POST["command"]) {

		// Add Customer
	case $case = "createCustomer";

		$cust_first_name_new = $_POST['cust_first_name_new'];
		$cust_last_name_new = $_POST['cust_last_name_new'];
		$cust_email_new = $_POST['cust_email_new'];
		$cust_phone_new = $_POST['cust_phone_new'];

		// Insert new Customer
		$sql_query = "INSERT 
					  INTO customers 
						(cust_first_name, cust_last_name, cust_email, cust_phone) 
					  VALUES 
						(?, ?, ?, ?)";

		$stmnt = mysqli_prepare($conn, $sql_query);
		mysqli_stmt_bind_param($stmnt, 'ssss', $cust_first_name_new, $cust_last_name_new, $cust_email_new, $cust_phone_new);
		mysqli_stmt_execute($stmnt);

		// Get the newly inserted Customer ID
		$last_id = mysqli_insert_id($conn);

		// Select relevant rows pertaining to the newly inserted Customer
		$sql_query = "SELECT 
						cust_id, 
						cust_first_name, 
						cust_last_name, 
						cust_email, 
						cust_phone, 
						cust_notes, 
						cust_email_sent, 
						DATE_FORMAT(cust_created,'%m/%d/%Y %l:%i %p') AS cust_created 
					  FROM customers 
					  WHERE cust_id = ? 
					  ORDER BY cust_created ASC";

		$stmnt = mysqli_prepare($conn, $sql_query);

		mysqli_stmt_bind_param($stmnt, 'i', $last_id);
		mysqli_stmt_execute($stmnt);

		$result_set = mysqli_stmt_get_result($stmnt);
		$customer = mysqli_fetch_assoc($result_set);

		echo json_encode($customer);

		break;

		// Delete Customer
	case $case = "deleteCustomer";

		$cust_id = $_POST['cust_id'];

		// Delete Customer by Customer ID
		$sql_query = "DELETE 
		              FROM customers 
					  WHERE cust_id = ?";

		$stmnt = mysqli_prepare($conn, $sql_query);

		mysqli_stmt_bind_param($stmnt, 'i', $cust_id);
		mysqli_stmt_execute($stmnt);

		break;

		// Update Company
	case $case = "updateCompany";

		$comp_title = $_POST['comp_title'];
		$comp_subtitle = $_POST['comp_subtitle'];
		$comp_owner = $_POST['comp_owner'];
		$comp_address = $_POST['comp_address'];
		$comp_phone = $_POST['comp_phone'];
		$comp_email = $_POST['comp_email'];
		$comp_google_place_id = $_POST['comp_google_place_id'];
		$comp_google_url = $_POST['comp_google_url'];
		$comp_facebook_url = $_POST['comp_facebook_url'];
		$comp_twitter_url = $_POST['comp_twitter_url'];
		$comp_linkedin_url = $_POST['comp_linkedin_url'];
		$comp_instagram_url = $_POST['comp_instagram_url'];
		$comp_youtube_url = $_POST['comp_youtube_url'];
		$comp_amazon_url = $_POST['comp_amazon_url'];
		$comp_pinterest_url = $_POST['comp_pinterest_url'];
		$comp_etsy_url = $_POST['comp_etsy_url'];
		$comp_shopify_url = $_POST['comp_shopify_url'];

		/*
		if (!empty($comp_email) && !filter_var($comp_email, FILTER_VALIDATE_EMAIL)) {

			header('HTTP/1.1 400 Bad Request');
			header('Content-Type: application/json');

			$error = array('error' => 'Your Company Email Address format is invalid!<br>Please adjust the Company Email Address in the Configuration Menu and try again (It should be in a format of name@domain.com).');

			echo json_encode($error);
			exit;
		}
		*/

		$file = $_FILES['files']['tmp_name'];

		// Set the desired location for the uploaded file
		$target_dir = __DIR__ . '/img/logo/';
		$target_file = $target_dir . 'logo.png';

		// Move the uploaded file to the desired location
		if (move_uploaded_file($file, $target_file)) {
			echo "The file " . basename($_FILES["files"]["name"]) . " has been uploaded and renamed as logo.png.";
		} else {
			echo "There was an error uploading your file, make sure its an image file!";
		}

		// Update Company Information
		$sql_query = "UPDATE company 
					  SET comp_title = ?, 
						  comp_subtitle = ?, 
						  comp_owner = ?, 
						  comp_address = ?, 
					      comp_phone = ?, 
						  comp_email = ?, 
						  comp_google_place_id = ?, 
						  comp_google_url = ?, 
						  comp_facebook_url = ?,
						  comp_twitter_url = ?, 
						  comp_linkedin_url = ?, 
						  comp_instagram_url = ?, 
						  comp_youtube_url = ?, 
						  comp_amazon_url = ?,
						  comp_pinterest_url = ?, 
						  comp_etsy_url = ?, 
						  comp_shopify_url = ?  
					  WHERE comp_id = 1";

		$stmnt = mysqli_prepare($conn, $sql_query);

		mysqli_stmt_bind_param(
			$stmnt,
			'sssssssssssssssss',
			$comp_title,
			$comp_subtitle,
			$comp_owner,
			$comp_address,
			$comp_phone,
			$comp_email,
			$comp_google_place_id,
			$comp_google_url,
			$comp_facebook_url,
			$comp_twitter_url,
			$comp_linkedin_url,
			$comp_instagram_url,
			$comp_youtube_url,
			$comp_amazon_url,
			$comp_pinterest_url,
			$comp_etsy_url,
			$comp_shopify_url
		);

		mysqli_stmt_execute($stmnt);

		// Redirect Page back to the Home Menu	
		header("Location: customers.php");

		die();

		break;

		// Update Email Template
	case $case = "updateEmailTemplate";

		$mail_smtp = $_POST['mail_smtp'];
		$mail_from = $_POST['mail_from'];
		$mail_from_password = $_POST['mail_from_password'];
		$mail_cc = $_POST['mail_cc'];
		$mail_bcc = $_POST['mail_bcc'];
		$mail_subject = $_POST['mail_subject'];
		$mail_body = htmlspecialchars($_POST['mail_body'] ?? '');

		if (empty($mail_from_password)) {
			$encrypted_password = NULL;
		} else {
			$encrypted_password = encrypt_decrypt('encrypt', $mail_from_password);
		}

		// Update Email Template Information
		$sql_query = "UPDATE email SET 
						mail_smtp = ?, 
						mail_from = ?, 
						mail_from_password = ?, 
						mail_cc = ?, 
						mail_bcc = ?, 
						mail_subject = ?, 
						mail_body = ? 
					  WHERE mail_id = 1";

		$stmnt = mysqli_prepare($conn, $sql_query);

		mysqli_stmt_bind_param($stmnt, 'sssssss', $mail_smtp, $mail_from, $encrypted_password, $mail_cc, $mail_bcc, $mail_subject, $mail_body);

		mysqli_stmt_execute($stmnt);

		// Redirect Page back to the Home Menu
		header("Location: customers.php");
		die();

		break;

		// Update Customer Notes
	case $case = "updateCustomerNotes";

		$cust_notes = $_POST['cust_notes'];
		$cust_id = $_POST['cust_id'];

		$sql_query = "UPDATE customers 
					  SET cust_notes = ? 
					  WHERE cust_id = ?";

		$stmnt = mysqli_prepare($conn, $sql_query);

		mysqli_stmt_bind_param($stmnt, 'si', $cust_notes, $cust_id);

		mysqli_stmt_execute($stmnt);

		if (mysqli_stmt_affected_rows($stmnt) > 0) {

			http_response_code(200);
			header('Content-Type: application/json');

			$message = array("message" => "Successfully Added Notes!");

			// Return the message as a JSON encoded string
			echo json_encode($message);
		} else {

			header('HTTP/1.1 400 Bad Request');
			header('Content-Type: application/json');

			$error = mysqli_stmt_error($stmnt);

			// Return the error as a JSON encoded string
			echo json_encode($error);
		}

		break;
}

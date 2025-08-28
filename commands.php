<?php
include_once("database.php");
include("encryption.php");

//HubSpot API
function isHubspotEnabled($conn) {
    $sql = "SELECT enabled FROM integrations WHERE integration_name = 'HubSpot' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (!$result || mysqli_num_rows($result) === 0) {
        return false;
    }

    $row = mysqli_fetch_assoc($result);
    return (int)$row['enabled'] === 1;
}

function sendToHubSpot($firstName, $lastName, $email, $phone, $cust_id, $conn) {
    // Fetch API credentials and base endpoint
    $sql = "SELECT api_key, endpoint_url FROM integrations WHERE integration_name = 'HubSpot' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (!$result || mysqli_num_rows($result) == 0) {
        file_put_contents(__DIR__ . '/hubspot_log.txt', "Missing or invalid HubSpot integration credentials.\n", FILE_APPEND);
        return;
    }

    $integration = mysqli_fetch_assoc($result);
    $apiKey = $integration['api_key'];
    $baseUrl = rtrim($integration['endpoint_url'], '/'); // e.g., https://api.hubapi.com

    // Construct full create contact URL
    $url = "$baseUrl/crm/v3/objects/contacts";

    // Prepare payload WITH custom_external_id
    $data = [
        "properties" => [
            "firstname" => $firstName,
            "lastname" => $lastName,
            "email" => $email,
            "phone" => $phone,
            "lifecyclestage" => "customer",
            "custom_external_id" => (string)$cust_id
        ]
    ];

    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    // Log the result
    file_put_contents(__DIR__ . '/hubspot_log.txt', 
        "=== Send to HubSpot ===\n" .
        "HTTP: $httpCode\n" .
        "URL: $url\n" .
        "Payload: " . json_encode($data) . "\n" .
        "Response: $response\n" .
        "Error: $error\n\n", 
        FILE_APPEND
    );
}

function updateInHubspot($cust_id, $field, $value, $conn) {
    $log = "=== ATTEMPT UPDATE ===\nCust ID: $cust_id\nField: $field\nValue: $value\n";

    if (empty($value) || empty($cust_id)) {
        $log .= "Skipped: Missing value or cust_id\n\n";
        file_put_contents('hubspot_log.txt', $log, FILE_APPEND);
        return;
    }

    $sql = "SELECT api_key, endpoint_url FROM integrations WHERE integration_name = 'HubSpot' AND enabled = 1 LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (!$result || mysqli_num_rows($result) === 0) {
        $log .= "Error: Missing HubSpot credentials.\n\n";
        file_put_contents('hubspot_log.txt', $log, FILE_APPEND);
        return;
    }

    $integration = mysqli_fetch_assoc($result);
    $apiKey = $integration['api_key'];
    $endpointUrl = $integration['endpoint_url'];

    $parsed = parse_url($endpointUrl);
    $baseUrl = $parsed['scheme'] . '://' . $parsed['host'];

    $propertyMap = [
        'cust_first_name' => 'firstname',
        'cust_last_name' => 'lastname',
        'cust_email' => 'email',
        'cust_phone' => 'phone',
    ];
    $hubspotField = $propertyMap[$field] ?? null;

    if (!$hubspotField) {
        $log .= "Skipped: Unmapped field '$field'\n\n";
        file_put_contents('hubspot_log.txt', $log, FILE_APPEND);
        return;
    }

    $data = [
        "properties" => [
            $hubspotField => $value
        ]
    ];

    $url = "$baseUrl/crm/v3/objects/contacts/$cust_id?idProperty=custom_external_id";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    $log .= "HTTP: $httpCode\nResponse: $response\nError: $error\n\n";
    file_put_contents(__DIR__ . '/hubspot_log.txt', $log, FILE_APPEND);
}

function deleteFromHubSpot($cust_id, $conn) {
    $log = "=== ATTEMPT DELETE ===\nCust ID (external): $cust_id\n";

    if (empty($cust_id)) {
        $log .= "Skipped: Missing cust_id\n\n";
        file_put_contents(__DIR__ . '/hubspot_log.txt', $log, FILE_APPEND);
        return;
    }

    $sql = "SELECT api_key, endpoint_url FROM integrations WHERE integration_name = 'HubSpot' AND enabled = 1 LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (!$result || mysqli_num_rows($result) == 0) {
        $log .= "Error: Missing HubSpot credentials.\n\n";
        file_put_contents(__DIR__ . '/hubspot_log.txt', $log, FILE_APPEND);
        return;
    }

    $row = mysqli_fetch_assoc($result);
    $apiKey = $row['api_key'];
    $endpointUrl = $row['endpoint_url'];
    $parsed = parse_url($endpointUrl);
    $baseUrl = $parsed['scheme'] . '://' . $parsed['host'];

    $searchUrl = "$baseUrl/crm/v3/objects/contacts/search";
    $searchPayload = [
        "filterGroups" => [[
            "filters" => [[
                "propertyName" => "custom_external_id",
                "operator" => "EQ",
                "value" => (string)$cust_id
            ]]
        ]],
        "properties" => ["email"]
    ];

    $ch = curl_init($searchUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($searchPayload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $searchResponse = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $searchError = curl_error($ch);
    curl_close($ch);

    $log .= "Search Response: $searchResponse\nSearch HTTP: $httpCode\nSearch Error: $searchError\n";

    $parsed = json_decode($searchResponse, true);
    $hubspotId = $parsed['results'][0]['id'] ?? null;

    if (!$hubspotId) {
        $log .= "Error: Could not find matching HubSpot contact.\n\n";
        file_put_contents(__DIR__ . '/hubspot_log.txt', $log, FILE_APPEND);
        return;
    }

    $deleteUrl = "$baseUrl/crm/v3/objects/contacts/$hubspotId";
    $ch = curl_init($deleteUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $apiKey"
    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $deleteResponse = curl_exec($ch);
    $deleteHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $deleteError = curl_error($ch);
    curl_close($ch);

    $log .= "Delete URL: $deleteUrl\n";
    $log .= "HTTP: $deleteHttpCode\n";
    $log .= "Response: $deleteResponse\n";
    $log .= "Error: $deleteError\n\n";

    file_put_contents(__DIR__ . '/hubspot_log.txt', $log, FILE_APPEND);
}

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

		// Send to HubSpot using cust_id as custom_external_id
		if (isHubspotEnabled($conn)) {
			sendToHubSpot($cust_first_name_new, $cust_last_name_new, $cust_email_new, $cust_phone_new, $last_id, $conn);
		}
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

		// Delete from HubSpot
		if (isHubspotEnabled($conn)) {
			deleteFromHubSpot($cust_id, $conn);
		}

		break;

	// Update Company
	case $case = "updateCompany";

		// Redirect Page back to the Home Menu	
		header("Location: customers.php");

		$comp_title = $_POST['comp_title'];
		$comp_subtitle = $_POST['comp_subtitle'];
		$comp_owner = $_POST['comp_owner'];
		$comp_address = $_POST['comp_address'];
		$comp_phone = $_POST['comp_phone'];
		$comp_email = $_POST['comp_email'];
		$comp_website = $_POST['comp_website'];
		$comp_google_place_id = $_POST['comp_google_place_id'];
		$comp_google_url = $_POST['comp_google_url'];
		$comp_facebook_url = $_POST['comp_facebook_url'];
		$comp_x_url = $_POST['comp_x_url'];
		$comp_linkedin_url = $_POST['comp_linkedin_url'];
		$comp_instagram_url = $_POST['comp_instagram_url'];
		$comp_youtube_url = $_POST['comp_youtube_url'];
		$comp_amazon_url = $_POST['comp_amazon_url'];
		$comp_pinterest_url = $_POST['comp_pinterest_url'];
		$comp_etsy_url = $_POST['comp_etsy_url'];
		$comp_shopify_url = $_POST['comp_shopify_url'];
		$comp_hubspot_url = $_POST['comp_hubspot_url'];

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
						  comp_website = ?, 
						  comp_google_place_id = ?, 
						  comp_google_url = ?, 
						  comp_facebook_url = ?,
						  comp_x_url = ?, 
						  comp_linkedin_url = ?, 
						  comp_instagram_url = ?, 
						  comp_youtube_url = ?, 
						  comp_amazon_url = ?,
						  comp_pinterest_url = ?, 
						  comp_etsy_url = ?, 
						  comp_shopify_url = ?,
						  comp_hubspot_url = ?   
					  WHERE comp_id = 1";

		$stmnt = mysqli_prepare($conn, $sql_query);

		mysqli_stmt_bind_param(
			$stmnt,
			'sssssssssssssssssss',
			$comp_title,
			$comp_subtitle,
			$comp_owner,
			$comp_address,
			$comp_phone,
			$comp_email,
			$comp_website,
			$comp_google_place_id,
			$comp_google_url,
			$comp_facebook_url,
			$comp_x_url,
			$comp_linkedin_url,
			$comp_instagram_url,
			$comp_youtube_url,
			$comp_amazon_url,
			$comp_pinterest_url,
			$comp_etsy_url,
			$comp_shopify_url,
			$comp_hubspot_url
		);

		mysqli_stmt_execute($stmnt);

		die();

		break;

	// Update Email Template
	case $case = "updateEmailTemplate";

		// Redirect Page back to the Home Menu
		header("Location: customers.php");

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

	// Save Integration Info
	case $case = "updateIntegration":

		$integration_name = $_POST['integration_name'];
		$api_key = $_POST['api_key'];
		$endpoint_url = $_POST['endpoint_url'];
		$enabled = isset($_POST['enabled']) ? 1 : 0; // <-- Check if the checkbox was ticked
	
		$sql_query = "REPLACE INTO integrations (integration_name, api_key, endpoint_url, enabled)
					  VALUES (?, ?, ?, ?)";
	
		$stmnt = mysqli_prepare($conn, $sql_query);
		mysqli_stmt_bind_param($stmnt, 'sssi', $integration_name, $api_key, $endpoint_url, $enabled);
	
		if (mysqli_stmt_execute($stmnt)) {
			header("Location: customers.php");
			die();
		} else {
			die("Failed to save integration: " . mysqli_error($conn));
		}
	
		break;
}

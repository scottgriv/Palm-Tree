<?php
include("database.php");
include("encryption.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require dirname(__FILE__) . '/lib/PHPMailer-master/src/Exception.php';
require dirname(__FILE__) . '/lib/PHPMailer-master/src/PHPMailer.php';
require dirname(__FILE__) . '/lib/PHPMailer-master/src/SMTP.php';

// Send Email Function
// Parameters:
// cust_id = Customer ID
// flag = Email Sent Flag (0 = Not Sent, 1 = Sent)
// type = Type of Sent ('Single' = Single Email, 'Mass' = Mass Email)
function sendEmail($cust_id, $flag, $type)
{

  // Get SQL Connection
  global $conn;

  // Check if Send Flag is set
  if ($flag) {

    $variables = array();
    $email_build = array();

    // Select columns to Build the Email
    $sql_query = "SELECT
                    comp_title,
                    comp_subtitle,
                    comp_owner,
                    comp_address,
                    comp_phone,
                    comp_email,
                    comp_website,
                    comp_google_place_id,
                    comp_google_url,
                    comp_facebook_url,
                    comp_x_url,
                    comp_linkedin_url,
                    comp_instagram_url,
                    comp_youtube_url,
                    comp_amazon_url,
                    comp_pinterest_url,
                    comp_etsy_url,
                    comp_shopify_url,
                    mail_smtp,
                    mail_from,
                    mail_from_password,
                    mail_cc,
                    mail_bcc,
                    mail_subject,
                    mail_body,
                    cust_first_name,
                    cust_last_name,
                    cust_email
                  FROM v_email_build    
                  WHERE cust_id = ?";

    $stmnt = mysqli_prepare($conn, $sql_query);

    mysqli_stmt_bind_param($stmnt, 'i', $cust_id);
    mysqli_stmt_execute($stmnt);

    $result_set = mysqli_stmt_get_result($stmnt);
    $email_build = mysqli_fetch_assoc($result_set);

    // Check if From Email Address or Password is Missing
    if (empty($email_build['mail_from']) || empty($email_build['mail_from_password'])) {

      header('HTTP/1.1 400 Bad Request');
      header('Content-Type: application/json');

      $error = array('error' => 'Your Company does not have an Email Address or Password to use as a Sender!<br>Please add an Email Address in the Configure Menu and try again.');

      // Return the error as a JSON encoded string
      echo json_encode($error);
      exit;

      // Check if From Email Address is in a Valid Format
    } else if (!filter_var($email_build['mail_from'], FILTER_VALIDATE_EMAIL)) {

      header('HTTP/1.1 400 Bad Request');
      header('Content-Type: application/json');

      $error = array('error' => 'Your Sender Email Address format is invalid!<br>Please adjust the Email Address in the Email Template Menu and try again (It should be in a format of name@domain.com).');

      // Return the error as a JSON encoded string
      echo json_encode($error);
      exit;

      // Check if Email Subject, Body, or SMTP Address is Missing
    } else if (empty($email_build['mail_subject']) || empty($email_build['mail_body']) || empty($email_build['mail_smtp'])) {

      header('HTTP/1.1 400 Bad Request');
      header('Content-Type: application/json');

      $error = array('error' => 'Your Company does not have an Email Subject, Body, or SMTP Server!<br>Please add an Email Address in the Email Template Menu and try again.');

      // Return the error as a JSON encoded string
      echo json_encode($error);
      exit;

      // Check if Customer Email Address is Missing
    } else if (empty($email_build['cust_email'])) {

      header('HTTP/1.1 400 Bad Request');
      header('Content-Type: application/json');

      $error = array('error' => 'The Customer you\'re trying to send an Email to is missing an Email Address!<br>Please add an Email Address and try again.');

      // Return the error as a JSON encoded string
      echo json_encode($error);
      exit;

      // Check if Customer Email Address is in a Valid Format
    } else if (!filter_var($email_build['cust_email'], FILTER_VALIDATE_EMAIL)) {

      header('HTTP/1.1 400 Bad Request');
      header('Content-Type: application/json');

      $error = array('error' => 'The Customers Email Address format is invalid!<br>Please adjust the Email Address and try again (It should be in a format of name@domain.com).');

      // Return the error as a JSON encoded string
      echo json_encode($error);
      exit;

    } else {

      // Set Variables
      $variables['customer_first_name'] = $email_build['cust_first_name'];
      $variables['customer_last_name'] = $email_build['cust_last_name'];
      $variables['customer_email'] = $email_build['cust_email'];
      $variables['company_title'] = $email_build['comp_title'];
      $variables['company_subtitle'] = $email_build['comp_subtitle'];
      $variables['company_owner'] = $email_build['comp_owner'];
      $variables['company_address'] = $email_build['comp_address'];
      $variables['company_phone'] = $email_build['comp_phone'];
      $variables['company_email'] = $email_build['comp_email'];
      $variables['company_website'] = $email_build['comp_website'];
      $variables['company_google_place_id'] = $email_build['comp_google_place_id'];
      $variables['url_google'] = $email_build['comp_google_url'];
      $variables['url_facebook'] = $email_build['comp_facebook_url'];
      $variables['url_x'] = $email_build['comp_x_url'];
      $variables['url_linkedin'] = $email_build['comp_linkedin_url'];
      $variables['url_instagram'] = $email_build['comp_instagram_url'];
      $variables['url_youtube'] = $email_build['comp_youtube_url'];
      $variables['url_amazon'] = $email_build['comp_amazon_url'];
      $variables['url_pinterest'] = $email_build['comp_pinterest_url'];
      $variables['url_etsy'] = $email_build['comp_etsy_url'];
      $variables['url_shopify'] = $email_build['comp_shopify_url'];

      // Set Email Subject and Body
      $email_subject = $email_build['mail_subject'];
      $email_body = htmlspecialchars_decode($email_build['mail_body'] ?? '');

      // Replace Template Variables with Database Values
      foreach ($variables as $key => $value) {

        $email_subject = str_replace('{{ ' . $key . ' }}', $value, $email_subject);
        $email_body = str_replace('{{ ' . $key . ' }}', $value, $email_body);
      }

      // Send Verification Email
      $mail = new PHPMailer(true); // Passing `true` enables exceptions

      try {

        //Decrypt Email Password
        $decrypted_password = encrypt_decrypt('decrypt', $email_build['mail_from_password']);

        $mail->SMTPDebug = false; // Enable verbose debug output
        $mail->IsSMTP(); // Set mailer to use SMTP
        $mail->Host = $email_build['mail_smtp']; //SMTP Host
        $mail->Username = $email_build['mail_from']; //Email User
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Password = $decrypted_password; // Email Password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465; // Port
        $mail->setFrom($email_build['mail_from'], $email_build['comp_title']); // From Email Address

        // Split the string into an array using the semicolon as the delimiter
        $emails_cc = explode(';', $email_build['mail_cc']);

        // Loop through the CC array and process each email address
        foreach ($emails_cc as $email_cc) {

          // Trim any leading or trailing whitespace from the email address
          if ($email_cc == '') continue;

          $email_cc = trim($email_cc);
          $mail->addCC($email_cc);
        }

        // Split the string into an array using the semicolon as the delimiter
        $emails_bcc = explode(';', $email_build['mail_bcc']);

        // Loop through the BCC array and process each email address
        foreach ($emails_bcc as $email_bcc) {

          if ($email_bcc == '') continue;

          // Trim any leading or trailing whitespace from the email address
          $email_bcc = trim($email_bcc);
          $mail->addBCC($email_bcc);
        }

        $mail->addAddress($email_build['cust_email']); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $email_subject; // Set Email Subject
        $mail->Body = $email_body; // Set Email Body
        //$mail->AltBody='This is the body in plain text for non-HTML mail clients'; // Set Plain Text Email Body

        // Send Email
        if ($mail->Send()) {

          // Update Single/Mass Customer Flag = 1
          $sql_query = "UPDATE customers 
                        SET cust_email_sent = ? 
                        WHERE cust_id = ?";

          $stmnt = mysqli_prepare($conn, $sql_query);

          mysqli_stmt_bind_param($stmnt, 'ii', $flag, $cust_id);
          mysqli_stmt_execute($stmnt);

          if (mysqli_stmt_affected_rows($stmnt) > 0) {

            if ($type == 'Single') {

              http_response_code(200);
              header('Content-Type: application/json');

              $message = array("message" => "Successfully Sent Email!");

              // Return the message as a JSON encoded string
              echo json_encode($message);
            }

          } else {

            if ($type == 'Single') {

              header('HTTP/1.1 400 Bad Request');
              header('Content-Type: application/json');

              $error = mysqli_stmt_error($stmnt);

              // Return the error as a JSON encoded string
              echo json_encode($error);
            }
            
          }
        } else {

          if ($type == 'Single') {

            //echo "Error while sending Email.";
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json');

            $error = array('error' => 'Error Sending Email!');

            // Return the error as a JSON encoded string
            echo json_encode($error);
          }
        }
      } catch (Exception $e) {

          header('HTTP/1.1 400 Bad Request');
          header('Content-Type: application/json');

          $error = array('error' => '' . $mail->ErrorInfo . '');

          // Return the error as a JSON encoded string
          echo json_encode($error);
          exit;
        
      }
    }
  } else {

    // Update Single Customer Flag = 0
    $sql_query = "UPDATE customers 
                  SET cust_email_sent = ? 
                  WHERE cust_id = ?";

    $stmnt = mysqli_prepare($conn, $sql_query);

    mysqli_stmt_bind_param($stmnt, 'ii', $flag, $cust_id);
    mysqli_stmt_execute($stmnt);

    if (mysqli_stmt_affected_rows($stmnt) > 0) {

      http_response_code(200);
      header('Content-Type: application/json');

      $message = array("message" => "Successfully Removed Flag!");

      // Return the message as a JSON encoded string
      echo json_encode($message);
    } else {

      header('HTTP/1.1 400 Bad Request');
      header('Content-Type: application/json');

      $error = mysqli_stmt_error($stmnt);

      // Return the error as a JSON encoded string
      echo json_encode($error);
    }
  }
}

// Main Switch
switch ($_POST["command"]) {

    // Send Single Email
  case $case = "sendEmail";

    $cust_id = $_POST["cust_id"];
    $flag = $_POST["flag"];

    // Call Send Email Function
    sendEmail($cust_id, $flag, 'Single');

    break;

  case $case = "sendMassEmail";

    // Retrieve all valid Email Addresses
    $sql_query = "SELECT 
                    cust_id 
                  FROM v_email_build 
                  WHERE cust_email != '' 
                  AND (cust_email REGEXP '^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$')";

    $stmnt = mysqli_prepare($conn, $sql_query);
    mysqli_stmt_execute($stmnt);

    $result_set = mysqli_stmt_get_result($stmnt);

    // Check if the query returned any rows
    if (mysqli_num_rows($result_set) > 0) {

      // Loop through the rows of the result set
      while ($row = mysqli_fetch_assoc($result_set)) {

        // Access the columns of the row as variables
        $cust_id = $row['cust_id'];

        // Call Send Email Function for each row
        sendEmail($cust_id, 1, 'Mass');
      }

    } else {

      header('HTTP/1.1 400 Bad Request');
      header('Content-Type: application/json');

      $error = array('error' => 'No Customer Emails Found!');

      // Return the error as a JSON encoded string
      echo json_encode($error);
      exit;

    }

    break;

    //Update Email Flags
  case $case = "updateEmailFlags";

    // Update Email Flags
    $sql_query = "UPDATE customers 
                    SET cust_email_sent = 0";

    $stmnt = mysqli_prepare($conn, $sql_query);
    mysqli_stmt_execute($stmnt);

    break;
}

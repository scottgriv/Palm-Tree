<?php
include_once("database.php");
include("header.php");
include("encryption.php");
?>
<title>Palm Tree</title>
<?php include('container.php'); ?>
<div class="container home">
	<form method="post" action="commands.php">
		<h5 style="color:#FF0000;line-height: 35px;font-size:13px"><b>Email Template</b></h5>
		<?php

		$sql_query = "SELECT 
						mail_id, 
						mail_smtp, 
						mail_from, 
						mail_from_password, 
						mail_cc, 
						mail_bcc, 
						mail_subject, 
						mail_body 
					FROM v_email_template";

		$stmnt = mysqli_prepare($conn, $sql_query);
		mysqli_stmt_execute($stmnt);

		$result_set = mysqli_stmt_get_result($stmnt);
		while ($mail = mysqli_fetch_assoc($result_set)) {

		?>
			<label style="font-size: 1rem;color: gray"><b>Recommendation:</b> Add yourself as the first Customer in the system to run test Emails against</label><br>
			<label for="mail_smtp"><img src="img/config/config_password.png" alt="links" width="25" height="25" /> SMTP Address:</label><br>
			<label style="font-size: 1rem;color: gray">This will be be the SMTP email server address for the mail server you're using</label><br>
			<label style="font-size: 1rem;color: gray">If you're using Gmail, please review the daily sending limits <a target="_blank" rel="noopener noreferrer" href="https://support.google.com/a/answer/166852?hl=en">here</a></label><br>
			<input class="form-control rounded-0" type="text" id="mail_smtp" name="mail_smtp" style="width: 400px;" placeholder="smtp.gmail.com" value="<?php echo $mail['mail_smtp']; ?>"><br>
			<label for="mail_from"><img src="img/config/config_email.png" alt="links" width="25" height="25" /> From Email:</label><br>
			<label style="font-size: 1rem;color: gray">From Email Address</label><br>
			<label style="font-size: 1rem;color: gray">This will be the Email Address used to send Emails (it may be different than your Contact Email Address in the Config Menu)</label><br>
			<input class="form-control rounded-0" type="text" id="mail_from" name="mail_from" style="width: 400px;" placeholder="noreply@yourcompany.com" value="<?php echo $mail['mail_from']; ?>"><br>
			<label for="mail_from_password"><img src="img/config/config_password.png" alt="links" width="25" height="25" /> From Email Password:</label><br>
			<label style="font-size: 1rem;color: gray">This will be the password for your Email Account above. <b>NOTE:</b> It will be encypted prior to saving it to the database</label><br>
			<label style="font-size: 1rem;color: gray">If you're using Gmail, you need to generate an App Password (not your actual Email Password) to be used in this field by following the instructions in this link <a target="_blank" rel="noopener noreferrer" href="https://support.google.com/accounts/answer/185833?hl=en">here</a></label><br>
			<input class="form-control rounded-0" type="password" id="mail_from_password" name="mail_from_password" style="width: 400px;" placeholder="•••••••••••••••••••••••" value="<?php echo encrypt_decrypt('decrypt', $mail['mail_from_password']); ?>"><br>
			<label for="mail_to"><img src="img/config/config_email.png" alt="links" width="25" height="25" /> To Email:</label><br>
			<label style="font-size: 1rem;color: gray">To Email Address</label><br>
			<label style="font-size: 1rem;color: gray">This will be your Customers Email Address passed from the Send Email function in the Home Menu<menu></menu></label><br>
			<input class="form-control rounded-0" type="text" disabled="disabled" id="mail_to" name="mail_to" style="width: 400px;" placeholder="Customer Email Address" value="customer@company.com"><br>
			<label for="mail_cc"><img src="img/config/config_email.png" alt="links" width="25" height="25" /> Cc Email(s):</label><br>
			<label style="font-size: 1rem;color: gray">Carbon Copy Email Address</label><br>
			<label style="font-size: 1rem;color: gray">Use a semicolon (;) between multiple Email Addresses</label><br>
			<input class="form-control rounded-0" type="text" id="mail_cc" name="mail_cc" style="width: 400px;" placeholder="email_1@yourcompany.com; email_2@yourcompany.com" value="<?php echo $mail['mail_cc']; ?>"><br>
			<label for="mail_bcc"><img src="img/config/config_email.png" alt="links" width="25" height="25" /> Bcc Email(s):</label><br>
			<label style="font-size: 1rem;color: gray">Blind Carbon Copy Email Address</label><br>
			<label style="font-size: 1rem;color: gray">Use a semicolon (;) between multiple Email Addresses</label><br>
			<input class="form-control rounded-0" type="text" id="mail_bcc" name="mail_bcc" style="width: 400px;" placeholder="email_1@yourcompany.com; email_2@yourcompany.com" value="<?php echo $mail['mail_bcc']; ?>"><br>
			<label for="mail_subject"><img src="img/config/config_subject.png" alt="links" width="25" height="25" /> Subject:</label><br>
			<label style="font-size: 1rem;color: gray">This will be the Subject of your Email</label><br>
			<textarea id="mail_subject" class="mailSubjectText" name="mail_subject" rows="3" cols="52" style="text-align: left;" placeholder="Hey {{ customer_first_name }}! How was your experience with {{ company_title }}?" data-mail-id="<?php echo $mail['mail_id']; ?>"><?php echo $mail['mail_subject']; ?></textarea><br>
			<label for="mail_body"><img src="img/config/config_subject.png" alt="links" width="25" height="25" /> Body:</label><br>
			<label style="font-size: 1rem;color: gray">This will be the Body of your Email</label><br>
			<label style="font-size: 1rem;color: gray">You can save your HTML template directly in this box</label><br>
			<label style="font-size: 1rem;color: gray">Depending on where this software sits, you may need to host email images using an outside source like <a target="_blank" rel="noopener noreferrer" href="https://imgur.com/">imgur.com</a> for the images to render properly in the Email</label><br>
			<textarea id="mail_body" class="mailBodyText" name="mail_body" rows="20" cols="52" style="text-align: left;" placeholder="<!DOCTYPE html><br><html><br><body><br><p>You can use HTML for a more professional, responsive email.</p><p>Paste the entire HTML message here.</p></body></html>" data-mail-id="<?php echo $mail['mail_id']; ?>"><?php echo htmlspecialchars_decode($mail['mail_body'] ?? ''); ?></textarea><br>
		<?php } ?>
		<br>
		<button type="submit" class="btn btn-default read-more" style="background:#000081;color:white;" name="command" value="updateEmailTemplate">Update Email Template</button>
	</form>
	<br>
	<h5 style="color:#FF0000;line-height: 35px;font-size:13px"><b>Other Email Settings and Features</b></h5>
	<br>
	<label for="mail_functions"><img src="img/config/config_functions.png" alt="links" width="25" height="25" /> Email Functions:</label><br>
	<form>
		<label style="font-size: 1rem;color: gray">This will send Emails to <span style="color:#228B22;font-weight:bolder">ALL</span> Customers in the Database at once</label><br>
		<input name="sendMassEmails" class="btn btn-default read-more" style="background:#228B22;color:white;" id="sflag" value="Send Mass Emails" onclick="send_mass_email(this)">
	</form>
	<form>
		<label style="font-size: 1rem;color: gray">This will remove <span style="color:red;font-weight:bolder">ALL</span> Send Email Flags in the Database</label><br>
		<input name="removeEmailFlags" class="btn btn-default read-more" style="background:#FF0000;color:white;" id="rflag" value="Remove Email Flags" onclick="update_email_flags(this)">
	</form>
	<br>
	<label for="mail_variables"><img src="img/config/config_variables.png" alt="links" width="25" height="25" /> Email Variable Information:</label><br>
	<label style="font-size: 1rem;color: gray">Place the following variables in your Email Subject or Body to have them replaced with your saved information</label><br>
	<label style="font-size: 1rem;color: gray">Be sure to include both opening '{{' and closing '}}' brackets and everything in between without spaces or line breaks as well</label><br>
	<label style="font-size: 1rem;color: gray">You may need to alter the email.php file directly to change Port information and Authentication methods</label><br>
	<div class="block_demo" style="border: 2px solid blue;width: 400px;display: block;">
		<ul>
			<li id="var_cust_first_name">{{ customer_first_name }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_cust_first_name')"></button></li>
			<li id="var_cust_last_name">{{ customer_last_name }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_cust_last_name')"></button></li>
			<li id="var_cust_email">{{ customer_email }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_cust_email')"></button></li>
			<li id="var_comp_title">{{ company_title }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_comp_title')"></button></li>
			<li id="var_comp_subtitle">{{ company_subtitle }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_comp_subtitle')"></button></li>
			<li id="var_comp_owner">{{ company_owner }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_comp_owner')"></button></li>
			<li id="var_comp_address">{{ company_address }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_comp_address')"></button></li>
			<li id="var_comp_phone">{{ company_phone }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_comp_phone')"></button></li>
			<li id="var_comp_email">{{ company_email }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_comp_email')"></button></li>
			<li id="var_comp_google_place_id">{{ company_google_place_id }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_comp_google_place_id')"></button></li>
			<li id="var_url_google">{{ url_google }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_url_google')"></button></li>
			<li id="var_url_facebook">{{ url_facebook }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_url_facebook')"></button></li>
			<li id="var_url_twitter">{{ url_twitter }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_url_twitter')"></button></li>
			<li id="var_url_linkedin">{{ url_linkedin }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_url_linkedin')"></button></li>
			<li id="var_url_instagram">{{ url_instagram }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_url_instagram')"></button></li>
			<li id="var_url_youtube">{{ url_youtube }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_url_youtube')"></button></li>
			<li id="var_url_amazon">{{ url_amazon }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_url_amazon')"></button></li>
			<li id="var_url_pinterest">{{ url_pinterest }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_url_pinterest')"></button></li>
			<li id="var_url_etsy">{{ url_etsy }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_url_etsy')"></button></li>
			<li id="var_url_shopify">{{ url_shopify }}<button title="Copy Variable" class="copyButton" onclick="copy_to_clipboard(event, '#var_url_shopify')"></button></li>
		</ul>
	</div>
</div>
<script type="text/javascript" src="js/main.js"></script>
<?php include('footer.php'); ?>
<?php
include_once("database.php");
include("header.php");;	
?>
<title>Palm Tree</title>
<?php include('container.php'); ?>
<div class="container home">
	<form method="post" action="commands.php" enctype="multipart/form-data">
		<h5 style="color:#FF0000;line-height: 35px;font-size:13px"><b>Configure Company</b></h5>
		<label for="fname"><img src="img/config/config_logo.png" alt="links" width="25" height="25" /> Company Logo:</label><br>
		<label style="font-size: 1rem;color: gray">This will be the Logo on the Home landing page</label><br>
		<label style="font-size: 1rem;color: gray">Recommended Size is: 50px x 50px</label><br>
		<!--default html file upload button-->
		<img src="img/logo/logo.png" id="output" width="50" height="50" />
		<label class="btn btn-default read-more" style="background:#000081;color:white;" for="files">Select Image</label>
		<input name="files" id="files" type="file" style="visibility:hidden;" accept="image/*" onchange="loadFile(event)">
		<?php
		$sql_query = "SELECT 
							comp_title, 
							comp_subtitle, 
							comp_owner, 
							comp_address, 
							comp_phone, 
							comp_email, 
							comp_google_place_id, 
							comp_google_url, 
							comp_facebook_url, 
							comp_twitter_url, 
							comp_linkedin_url, 
							comp_instagram_url, 
							comp_youtube_url, 
							comp_amazon_url,
							comp_pinterest_url,
							comp_etsy_url,
							comp_shopify_url 
						FROM company WHERE comp_id = 1";

		$stmnt = mysqli_prepare($conn, $sql_query);
		mysqli_stmt_execute($stmnt);

		$result_set = mysqli_stmt_get_result($stmnt);
		while ($company = mysqli_fetch_assoc($result_set)) {
		?>
			<label for="fname"><img src="img/config/config_business.png" alt="links" width="25" height="25" /> Company Title:</label><br>
			<label style="font-size: 1rem;color: gray">This will be the Title on the Home landing page</label><br>
			<input class="form-control rounded-0" type="text" id="comp_title" name="comp_title" style="width: 400px;" placeholder="Acme Corp." value="<?php echo $company['comp_title']; ?>"><br>
			<label for="fname"><img src="img/config/config_business.png" alt="links" width="25" height="25" /> Company Subtitle:</label><br>
			<label style="font-size: 1rem;color: gray">This will be the Subtitle on the Home landing page</label><br>
			<input class="form-control rounded-0" type="text" id="comp_subtitle" name="comp_subtitle" style="width: 400px;" placeholder="Serving you reliable, explosive products since 1920" value="<?php echo $company['comp_subtitle']; ?>"><br>
			<label for="fname"><img src="img/config/config_owner.png" alt="links" width="25" height="25" /> Company Owner:</label><br>
			<label style="font-size: 1rem;color: gray">This will be the name used for your email signature (i.e. Thanks, Johnny Appleseed).</label><br>
			<input class="form-control rounded-0" type="text" id="comp_owner" name="comp_owner" style="width: 400px;" placeholder="Johnny Appleseed" value="<?php echo $company['comp_owner']; ?>"><br>
			<label for="fname"><img src="img/config/config_address.png" alt="links" width="25" height="25" /> Company Address:</label><br>
			<label style="font-size: 1rem;color: gray">This will be your Business Address used in your email template</label><br>
			<input class="form-control rounded-0" type="text" id="comp_address" name="comp_address" style="width: 400px;" placeholder="1600 Amphitheatre Pkwy, Mountain View, CA 94043" value="<?php echo $company['comp_address']; ?>"><br>
			<label for="fname"><img src="img/config/config_phone.png" alt="links" width="25" height="25" /> Company Contact Phone:</label><br>
			<label style="font-size: 1rem;color: gray">This will be the Phone Number used in the email template</label><br>
			<input class="form-control rounded-0" type="text" id="comp_phone" name="comp_phone" style="width: 400px;" placeholder="+1 (202) 555-0176" value="<?php echo $company['comp_phone']; ?>"><br>
			<label for="fname"><img src="img/config/config_email.png" alt="links" width="25" height="25" /> Company Contact Email:</label><br>
			<label style="font-size: 1rem;color: gray">This will be the Email Address your Customers will be able to contact you with, also used in the email template</label><br>
			<input class="form-control rounded-0" type="text" id="comp_email" name="comp_email" style="width: 400px;" placeholder="noreply@company.com" value="<?php echo $company['comp_email']; ?>"><br>
			<label for="fname"><img src="img/config/config_google_places.png" alt="links" width="25" height="25" /> Google Business Place ID:</label><br>
			<label style="font-size: 1rem;color: gray">The Place ID is used to build a link to review your Google Business Profile in the Email Template</label><br>
			<label style="font-size: 1rem;color: gray">Follow this link <a target="_blank" rel="noopener noreferrer" href="https://companys.google.com/maps/documentation/places/web-service/place-id">here</a> and find your business, then copy and paste your Place ID in the following field</label><br>
			<input class="form-control rounded-0" type="text" id="comp_google_place_id" name="comp_google_place_id" style="width: 400px;" placeholder="ChIJj61dQgK6j4AR4GeTYWZsKWw" value="<?php echo $company['comp_google_place_id']; ?>"><br>
			<label for="fname"><img src="img/link/link_google.png" alt="links" width="25" height="25" /> Google Business URL:</label><br>
			<label style="font-size: 1rem;color: gray">Click Share on your Google Business Account, then Copy and Paste the Web Address here</label><br>
			<input class="form-control rounded-0" type="text" id="comp_google_url" name="comp_google_url" style="width: 400px;" placeholder="https://g.co/kgs/aBcdef" value="<?php echo $company['comp_google_url']; ?>"><br>
			<label for="fname"><img src="img/link/link_facebook.png" alt="links" width="25" height="25" /> Facebook Business URL:</label><br>
			<label style="font-size: 1rem;color: gray">Web Address for your Facebook Business Profile</label><br>
			<input class="form-control rounded-0" type="text" id="comp_facebook_url" name="comp_facebook_url" style="width: 400px;" placeholder="https://www.facebook.com/Google" value="<?php echo $company['comp_facebook_url']; ?>"><br>
			<label for="fname"><img src="img/link/link_twitter.png" alt="links" width="25" height="25" /> Twitter Business URL:</label><br>
			<label style="font-size: 1rem;color: gray">Web Address for your Twitter Business Profile</label><br>
			<input class="form-control rounded-0" type="text" id="comp_twitter_url" name="comp_twitter_url" style="width: 400px;" placeholder="https://twitter.com/Google" value="<?php echo $company['comp_twitter_url']; ?>"><br>
			<label for="fname"><img src="img/link/link_linkedin.png" alt="links" width="25" height="25" /> LinkedIn Business URL:</label><br>
			<label style="font-size: 1rem;color: gray">Web Address for your LinkedIn Business Profile</label><br>
			<input class="form-control rounded-0" type="text" id="comp_linkedin_url" name="comp_linkedin_url" style="width: 400px;" placeholder="https://www.linkedin.com/company/google" value="<?php echo $company['comp_linkedin_url']; ?>"><br>
			<label for="fname"><img src="img/link/link_instagram.png" alt="links" width="25" height="25" /> Instagram Business URL:</label><br>
			<label style="font-size: 1rem;color: gray">Web Address for your Instagram Business Profile</label><br>
			<input class="form-control rounded-0" type="text" id="comp_instagram_url" name="comp_instagram_url" style="width: 400px;" placeholder="https://www.instagram.com/google/" value="<?php echo $company['comp_instagram_url']; ?>"><br>
			<label for="fname"><img src="img/link/link_youtube.png" alt="links" width="25" height="25" /> YouTube Business URL:</label><br>
			<label style="font-size: 1rem;color: gray">Web Address for your YouTube Business Profile</label><br>
			<input class="form-control rounded-0" type="text" id="comp_youtube_url" name="comp_youtube_url" style="width: 400px;" placeholder="https://www.youtube.com/user/google" value="<?php echo $company['comp_youtube_url']; ?>"><br>
			<label for="fname"><img src="img/link/link_amazon.png" alt="links" width="25" height="25" /> Amazon Business URL:</label><br>
			<label style="font-size: 1rem;color: gray">Web Address for your Amazon Business Profile</label><br>
			<input class="form-control rounded-0" type="text" id="comp_amazon_url" name="comp_amazon_url" style="width: 400px;" placeholder="https://www.amazon.com/your-business" value="<?php echo $company['comp_amazon_url']; ?>"><br>
			<label for="fname"><img src="img/link/link_pinterest.png" alt="links" width="25" height="25" /> Pinterest Business URL:</label><br>
			<label style="font-size: 1rem;color: gray">Web Address for your Pinterest Business Profile</label><br>
			<input class="form-control rounded-0" type="text" id="comp_pinterest_url" name="comp_pinterest_url" style="width: 400px;" placeholder="https://www.pinterest.com/amazon/" value="<?php echo $company['comp_pinterest_url']; ?>"><br>
			<label for="fname"><img src="img/link/link_etsy.png" alt="links" width="25" height="25" /> Etsy Business URL:</label><br>
			<label style="font-size: 1rem;color: gray">Web Address for your Etsy Business Profile</label><br>
			<input class="form-control rounded-0" type="text" id="comp_etsy_url" name="comp_etsy_url" style="width: 400px;" placeholder="https://www.etsy.com/shop/your-shop" value="<?php echo $company['comp_etsy_url']; ?>"><br>
			<label for="fname"><img src="img/link/link_shopify.png" alt="links" width="25" height="25" /> Shopify Business URL:</label><br>
			<label style="font-size: 1rem;color: gray">Web Address for your Shopify Business Profile</label><br>
			<input class="form-control rounded-0" type="text" id="comp_shopify_url" name="comp_shopify_url" style="width: 400px;" placeholder="https://www.your-shop.myshopify.com" value="<?php echo $company['comp_shopify_url']; ?>"><br>
		<?php } ?>
		<button type="submit" class="btn btn-default read-more" style="background:#000081;color:white;" name="command" value="updateCompany">Update Company Information</button>
	</form>
</div>
<script type="text/javascript" src="js/main.js"></script>
<?php include('footer.php'); ?>
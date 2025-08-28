<?php
include_once("database.php");
include("header.php");
include('container.php');

// Fetch current integration (if it exists)
$sql = "SELECT * FROM integrations WHERE integration_name = 'HubSpot' LIMIT 1";
$result = mysqli_query($conn, $sql);
$integration = mysqli_fetch_assoc($result);
?>

<div class="container home">
  <form method="post" action="commands.php">
    <h5 style="color:#FF0000;line-height: 35px;font-size:13px"><b>Configure Integrations</b></h5>
    <h4 style="color:#000000; line-height: 28px;">
      <b><img src="img/link/link_hubspot.png" width="20" height="20" style="vertical-align:middle;"> HubSpot</b>
    </h4>

    <label>
      <input type="checkbox" name="enabled" value="1" <?php if (!empty($integration['enabled'])) echo 'checked'; ?>>
      Enable Integration
    </label>
    <br>
    <br>

    <input type="hidden" name="command" value="updateIntegration">
    <input type="hidden" name="integration_name" value="HubSpot">

    <label for="api_key">API Key:</label><br>
    <label style="font-size: 1rem;color: gray">Found in your HubSpot account under Settings → Integrations → Private Apps</label><br>
    <input class="form-control rounded-0" type="password" name="api_key" style="width: 400px;" placeholder="Your API key" value="<?php echo htmlspecialchars($integration['api_key'] ?? ''); ?>"><br>

    <label for="endpoint_url">API Endpoint URL:</label><br>
    <label style="font-size: 1rem;color: gray">i.e. <code>https://api.hubapi.com</code></label><br>
    <input class="form-control rounded-0" type="text" name="endpoint_url" style="width: 400px;" placeholder="https://api.hubapi.com" value="<?php echo htmlspecialchars($integration['endpoint_url'] ?? ''); ?>"><br>
    <br>
    <button type="submit" class="btn btn-default read-more" style="background:#000081;color:white;">Save Integration</button>
  </form>
</div>

<script type="text/javascript" src="js/main.js"></script>
<?php include('footer.php'); ?>

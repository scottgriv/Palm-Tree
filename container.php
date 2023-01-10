</head>

<body class="">
  <script>
    /* Keep track of the navigation bar selection */
    jQuery(document).ready(function() {
      jQuery(".nav.navbar-nav li:not(#googleBusiness)").click(function() {
        jQuery(".nav.navbar-nav li:not(#googleBusiness)").removeClass('active');
        jQuery(this).addClass('active');
      })
      var loc = window.location.href;
      jQuery(".nav.navbar-nav li:not(#googleBusiness)").removeClass('active');
      jQuery(".nav.navbar-nav li a").each(function() {
        if (loc.indexOf(jQuery(this).attr("href")) != -1) {

          jQuery(this).closest('li:not(#googleBusiness)').addClass("active");
        }
      });
    });
  </script>
  <div role="navigation" class="navbar navbar-default navbar-static-top">
    <div class="container">
      <ul class="nav navbar-nav">
        <li class="active"><a href="http://<?php echo $hostIP; ?>/customers.php">Customers</a></li>
      </ul>
      <ul class="nav navbar-nav">
        <li><a href="http://<?php echo $hostIP; ?>/email.php">Email</a></li>
      </ul>
      <ul class="nav navbar-nav">
        <li><a href="http://<?php echo $hostIP; ?>/configure.php">Configure</a></li>
      </ul>
      <?php
      // Get the Google Business URL for the Google Business navigation button
      $sql_query = "SELECT 
                          comp_google_url 
                        FROM company 
                        WHERE comp_id = 1";

      $stmnt = mysqli_prepare($conn, $sql_query);
      mysqli_stmt_execute($stmnt);

      $result_set = mysqli_stmt_get_result($stmnt);
      while ($company = mysqli_fetch_assoc($result_set)) {
      ?>
        <ul class="nav navbar-nav">
          <li id="googleBusiness"><a target="_blank" href="<?php echo $company['comp_google_url']; ?>">Google Business</a></li>
        </ul>
      <?php } ?>
    </div>
  </div>
  <div class="container" style="min-height:500px;">
    <div class=''>
    </div>
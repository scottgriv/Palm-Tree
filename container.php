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
      <ul class="nav navbar-nav dropdown">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
          Settings <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li><a href="http://<?php echo $hostIP; ?>/email.php">Email Notifications</a></li>
          <li><a href="http://<?php echo $hostIP; ?>/configure.php">Company</a></li>
          <li><a href="http://<?php echo $hostIP; ?>/integrations.php">Integrations</a></li>
        </ul>
      </li>
    </ul>

    <ul class="nav navbar-nav dropdown">
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      Shortcuts <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
      <?php
      // Get the Company Website URL for the Website navigation button
      $sql_query = "SELECT comp_website FROM company WHERE comp_id = 1";
      $stmnt = mysqli_prepare($conn, $sql_query);
      mysqli_stmt_execute($stmnt);
      $result_set = mysqli_stmt_get_result($stmnt);
      while ($company = mysqli_fetch_assoc($result_set)) {
        echo '<li><a target="_blank" href="' . htmlspecialchars($company['comp_website']) . '">Company Website</a></li>';
      }
      ?>
    </ul>
  </li>
</ul>

    </div>
  </div>
  <div class="container" style="min-height:500px;">
    <div class=''>
    </div>
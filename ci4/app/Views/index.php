<?php

if (session()->get('id')) {
  $form =
    '<ul>' .
    '<li><a href="/account"><button type="button" class="btn btn-info my-btn">My Account</button></a></li>
    <li><a href="/account/logout"><button type="button" class="btn btn-info my-btn">Logout</button></a></li>' .
    '</ul>';
} else {
  $form = '<a href="/login"><button type="button" id="navbar-button" class="btn btn-info my-btn">Log in</button></a>';
}

if (session()->get('admin') == 'yes') {
  $test = '<li class="nav-item"><a id="link" class="nav-link" href="/resources/view/all_test_resources">Test</a>';
} else {
  $test = '';
}

?>

<!doctype html>
<html lang="en">

<head>
  <!-- Osano GDPR Compliance -->
  <title>
    <?php echo $title;
    ?>
  </title>


  <meta name="description" content="Exciting resources that can be personalised to your class and regenerated. Teacherpedia are here for teachers, so lets do what 
    we do best; teach.">
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- bootstrap and Jquery -->
  <?php include 'assets/css/bootstrap.html'; ?>
  <!-- end bootstrap and Jquery -->


  <!-- All styling for header/html and body container -->
  <link {csp-style-nonce} rel="stylesheet" type="text/css" href="/assets/css/htmlbody.css" />
  <!-- All styling for images -->
  <link {csp-style-nonce} rel="stylesheet" type="text/css" href="/assets/css/images.css" />
  <!--Search results css -->
  <link {csp-style-nonce} rel="stylesheet" type="text/css" href="/assets/css/searchresults.css" />
  <!-- admin page css -->
  <link {csp-style-nonce} rel="stylesheet" type="text/css" href="/assets/css/admin-page.css" />
  <link {csp-style-nonce} rel="stylesheet" type="text/css" media="(min-width: 601px)" href="/assets/css/admin-page-large.css" />
  <link {csp-style-nonce} rel="stylesheet" type="text/css" media="(max-width: 600px)" href="/assets/css/admin-page-small.css" />

  <!-- navbar -->
  <link {csp-style-nonce} rel="stylesheet" type="text/css" media="" href="/assets/css/navbar.css" />



  <link {csp-style-nonce} rel="stylesheet" type="text/css" href="/assets/css/user_page.css" />
  <link {csp-style-nonce} rel="stylesheet" type="text/css" href="/assets/css/searchbar.css" />

  <!-- login form -->
  <!--===============================================================================================-->
  <link {csp-style-nonce} rel="icon" type="image/png" href="/assets/css/login_form/images/icons/favicon.ico" />
  <!--===============================================================================================-->
  <!--===============================================================================================-->
  <link {csp-style-nonce} rel="stylesheet" type="text/css" href="/assets/css/login_form/css/util.css">
  <link {csp-style-nonce} rel="stylesheet" type="text/css" href="/assets/css/login_form/css/main.css">
  <!--===============================================================================================-->

  <!-- end login form css-->

  <!-- resources view -->
  <link {csp-style-nonce} rel="stylesheet" type="text/css" href="/assets/css/resources.css">

  <!-- end resources view -->

  <!-- fonts -->
  <link {csp-style-nonce} href="https://fonts.googleapis.com/css?family=Maven+Pro|Montserrat|Signika&display=swap" rel="stylesheet">
  <link {csp-style-nonce} href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!-- end fonts -->

  <!-- Global site tag (gtag.js) - Google Analytics -->

  <script {csp-script-nonce} async src="https://www.googletagmanager.com/gtag/js?id=UA-125055859-2"></script>
  <script {csp-script-nonce}>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-125055859-2');
  </script>
  <script 'unsafe-inline' data-ad-client="ca-pub-1833155544964145" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

</head>

<body>
  <nav class="navbar navbar-expand-md navbar-dark header-footer d-flex flex-row">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item ">
          <a id="link" class="nav-link" href="/home">Home</a>
        </li>
        <li id="link" class="nav-item">
          <a id="link" class="nav-link" href="/keystage">Resources</a>
        </li>

        <?php if (isset($test)) {
          echo $test;
        } ?>

      </ul>
    </div>
    <div class="mx-auto order-0">

      <ul class="navbar-nav flex-row mr-lg-0">
        <li>
          <a class="navbar-brand" href="/">
            <div class="col-12"><img id="navbar-logo" alt="website logo" src="https://res.cloudinary.com/teacherpedia/image/upload/v1598610983/branding/logo-white_cjbyg0.png"></div>
          </a>
        </li>
        <li>
          <button aria-label="Dropdown menu" class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
            <span class="navbar-toggler-icon"></span>
          </button>
        </li>
      </ul>
    </div>

    <div class="navbar-collapse collapse w-100 order-2 dual-collapse2">

      <div class="input-group">

      </div>

      <ul class="navbar-nav flex-row mr-lg-0">
        <li class="nav-item">
          <a class="nav-link dropdown-toggle  mr-lg-0" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z" />
              <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z" />
            </svg><span class="caret"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            <form class="" method="post" role=" search" action="/resources/search/search_results">
              <input id="search-input" type="text" name="search_input" class="" placeholder="" required></input>
              <button type="submit" class="btn btn-info my-btn form-inline">Search</button>
            </form>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle mr-3 mr-lg-0" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
            </svg></i><span class="caret"></span>
          </a>

          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            <?php echo $form ?>
          </div>
        </li>
      </ul>
    </div>

  </nav>
  <div class="cookie_container"></div>

  <main class="wrapper">
    <div class="wrapper__inner">
      <div class="content">
        <div class="content__inner">
          <div class="container">

            <?php echo $main ?>

          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="navbar navbar-default navbar-fixed-bottom text-center header-footer header-footer">
    <!-- Grid column -->
    <div class="col-md-4 mt-md-0 mt-3">
      <!-- Content -->
      <h3>Support us!</h3>
      <p>I currently rely on donations to keep this page going. If you like using our tools, a donation would be very much appreciated. </p>
      <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="VZG2NUB6P28XA">
        <input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
        <img alt="Paypal donate" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
      </form>
    </div>
    <!-- Grid column -->
    <div class="col-md-4 mt-md-0 mt-3 text-center">
      <!-- Content -->
      <h3 class="">Follow us on Social Media</h3>
      <a href="https://www.instagram.com/teacherpedia.co.uk"><img alt="instagram" class="favicons" src="/images/social-media-icons/insta.png"></a>
      <a href="https://www.facebook.com/teacherpediacouk-106790920773503/"><img alt="facebook" class=" favicons" src="/images/social-media-icons/facebook.png"></a>
      <a href="https://twitter.com/Teacherpedia_uk"><img alt="twitter" class="favicons" src="/images/social-media-icons/twitter.png"></a>
      <img alt="pinterest" class="favicons" src="/images/social-media-icons/pinterest.png">
    </div>
    <div class="col-md-4 mt-md-0 mt-3 text-center">
      <!-- Content -->
      <h3 class="">The Legals</h3>
      <a href="/privacy-policy">Privacy Policy</a>

      <h3 class="">Contact Us</h3>
      <a href="/contact">Contact Us</a>

      <h3 class="">Get to know us</h3>
      <a href="/vision">Vision</a>


    </div>
  </footer>
  <!-- Footer Text -->

  </div>
</body>

</html>
<?php

if (session()->get('id')) {
    $form = '<a href="/account"><button type="button" class="btn btn-info my-btn" style="color: #FDFF7F;">My Account</button></a>';
} else {
    $form = '<a href="/login"><button type="button" id="navbar-button" class="btn btn-info my-btn">Log in</button></a>';
}

if (session()->get('admin') == 'yes') {
    $test = '<li class="nav-item"><a style="color: white" id="link" class="nav-link" href="/resources/view/all_test_resources">Test</a>';
} else {
    $test = '';
}

?>

<!doctype html>
<html lang="en">

<head>
    <title>
        <?php echo $title;
        ?>
    </title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- bootstrap and Jquery -->
    <?php include 'assets/css/bootstrap.html'; ?>
    <!-- end bootstrap and Jquery -->


    <!-- All styling for header/html and body container -->
    <link rel="stylesheet" type="text/css" href="/assets/css/htmlbody.css" />
    <!-- All styling for images -->
    <link rel="stylesheet" type="text/css" href="/assets/css/images.css" />
    <!--Search results css -->
    <link rel="stylesheet" type="text/css" href="/assets/css/searchresults.css" />
    <!-- admin page css -->
    <link rel="stylesheet" type="text/css" href="/assets/css/admin-page.css" />
    <link rel="stylesheet" type="text/css" media="(min-width: 601px)" href="/assets/css/admin-page-large.css" />
    <link rel="stylesheet" type="text/css" media="(max-width: 600px)" href="/assets/css/admin-page-small.css" />

    <!-- navbar -->
    <link rel="stylesheet" type="text/css" media="" href="/assets/css/navbar.css" />



    <link rel="stylesheet" type="text/css" href="/assets/css/user_page.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/searchbar.css" />

    <!-- login form -->
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="/assets/css/login_form/images/icons/favicon.ico" />
    <!--===============================================================================================-->
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/assets/css/login_form/css/util.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/login_form/css/main.css">
    <!--===============================================================================================-->

    <!-- end login form css-->

    <!-- resources view -->
    <link rel="stylesheet" type="text/css" href="/assets/css/resources.css">

    <!-- end resources view -->

    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css?family=Maven+Pro|Montserrat|Signika&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- end fonts -->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-125055859-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-125055859-2');
    </script>
    <script data-ad-client="ca-pub-1833155544964145" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

</head>

<body>


    <main class="wrapper" style="overflow: auto;">
        <div class="wrapper__inner">
            <div class="content">
                <div class="content__inner">
                    <div class="container">

                        <?php echo $main_content ?>

                    </div>
                </div>
            </div>
        </div>
    </main>


</body>

</html>
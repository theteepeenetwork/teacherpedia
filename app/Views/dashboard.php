<!DOCTYPE html>
<html>

<head>


    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/dashboard/css/main.css">
    <link rel="stylesheet" href="/assets/css/dashboard/css/admin_gallery.css">

    <link rel="shortcut icon" type="image/png" href="#">

    <!-- bootstrap and Jquery -->
    <?php include 'assets/css/bootstrap.html'; ?>
    <!-- end bootstrap and Jquery -->



    <script src="assets/css/dashboard/css/js.js"></script>

</head>

<body>

    <div class="grid">
        <header class="header">
            <i class="fas fa-bars header__menu"></i>
            <div class="header__search">
                <input class="header__input" placeholder="Search..." />
            </div>
            <div class="header__avatar">
                <div class="dropdown">
                    <ul class="dropdown__list">
                        <li class="dropdown__list-item">
                            <span class="dropdown__icon"><i class="far fa-user"></i></span>
                            <span class="dropdown__title">my profile</span>
                        </li>
                        <li class="dropdown__list-item">
                            <span class="dropdown__icon"><i class="fas fa-clipboard-list"></i></span>
                            <span class="dropdown__title">my account</span>
                        </li>
                        <li class="dropdown__list-item">
                            <span class="dropdown__icon"><i class="fas fa-sign-out-alt"></i></span>
                            <span class="dropdown__title"><a href=<?php echo base_url() . 'user/account/logout'; ?>>Log out</a></span>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <aside class="sidenav">
            <div class="sidenav__brand">
                <i class="fas fa-feather-alt sidenav__brand-icon"></i>
                <a class="sidenav__brand-link" href="<?php echo base_url() . '/' . 'admin'; ?>">Teacher<span class="text-light">Pedia</span></a>
                <i class="fas fa-times sidenav__brand-close"></i>
            </div>
            <div class="sidenav__profile">
                <div class="sidenav__profile-avatar"></div>
                <div class="sidenav__profile-title text-light"><?php //echo $this->session->name; 
                                                                ?></div>
            </div>
            <div class="row row--align-v-center row--align-h-center">
                <ul class="navList">
                    <li class="navList__heading">resources<i class="far fa-file-alt"></i></li>
                    <li>
                        <div class="navList__subheading row row--align-v-center">
                            <span class="navList__subheading-icon"><i class="fas fa-briefcase-medical"></i></span>
                            <span class="navList__subheading-title">Edit Live Resources</span>
                        </div>
                        <ul class="subList subList">
                            <a href="/admin/resources/add_resource">
                                <li class="subList__item">
                                    Add Resource
                                </li>
                            </a>
                            <a href="/admin/resources/list_resources">
                                <li class=" subList__item">
                                    Edit Resource
                                </li>
                            </a>

                        </ul>
                    </li>
                    <li>
                        <div class="navList__subheading row row--align-v-center">
                            <span class="navList__subheading-icon"><i class="fas fa-briefcase-medical"></i></span>
                            <span class="navList__subheading-title">Edit Test Resources</span>
                        </div>
                        <ul class="subList subList">
                            <a href="/admin/resources/test_resource">
                                <li class=" subList__item">
                                    Add Test Resource
                                </li>
                            </a>
                            <a href="admin/resources/list_test_resources">
                                <li class=" subList__item">
                                    Edit Test Resource
                                </li>
                            </a>
                        </ul>
                    </li>
                    <!-- 
                    ***************************************************
                    ***************************************************
                    ***************************************************
                    **                                               **
                    **                    Gallery                    **
                    **                                               **
                    ***************************************************
                    ***************************************************
                    ***************************************************
                    -->
                    <li class="navList__heading">Gallery<i class="far fa-file-alt"></i></li>
                    <li>
                        <div class="navList__subheading row row--align-v-center">
                            <span class="navList__subheading-icon"><i class="fas fa-briefcase-medical"></i></span>
                            <span class="navList__subheading-title">Gallery</span>
                        </div>
                        <ul class="subList subList">
                            <a href="/admin/manage_gallery/index">
                                <li class="subList__item">
                                    Gallery
                                </li>
                            </a>
                        </ul>
                    </li>
                    <li class="navList__heading">Exit<i class="far fa-file-alt"></i></li>
                    <li>
                        <div class="navList__subheading row row--align-v-center">
                            <span class="navList__subheading-icon"><i class="fas fa-briefcase-medical"></i></span>
                            <span class="navList__subheading-title">Exit Dashboard</span>
                        </div>
                        <ul class="subList subList">
                            <a href="/user/users/index/account">
                                <li class="subList__item">
                                    Exit Dashboard
                                </li>
                            </a>
                        </ul>
                        <ul class="subList subList">
                            <a href="/user/users/logout">
                                <li class="subList__item">
                                    Logout Dashboard
                                </li>
                            </a>
                        </ul>
                    </li>
                </ul>


            </div>
        </aside>

        <main class="main">
            <?php echo $main_content;
            ?>

        </main>

        <footer class="footer">
            <p><span class="footer__copyright">&copy;</span> 2020 Teacherpedia</p>
        </footer>
    </div>

</body>

</html>

<!--

<li class="navList__heading">messages<i class="far fa-envelope"></i></li>
                    <li>
                        <div class="navList__subheading row row--align-v-center">
                            <span class="navList__subheading-icon"><i class="fas fa-envelope"></i></span>
                            <span class="navList__subheading-title">inbox</span>
                        </div>
                        <ul class="subList subList--hidden">
                            <li class="subList__item">primary</li>
                            <li class="subList__item">social</li>
                            <li class="subList__item">promotional</li>
                        </ul>
                    </li>
                    <li>
                        <div class="navList__subheading row row--align-v-center">
                            <span class="navList__subheading-icon"><i class="fas fa-eye"></i></span>
                            <span class="navList__subheading-title">unread</span>
                        </div>
                        <ul class="subList subList--hidden">
                            <li class="subList__item">primary</li>
                            <li class="subList__item">social</li>
                            <li class="subList__item">promotional</li>
                        </ul>
                    </li>
                    <li>
                        <div class="navList__subheading row row--align-v-center">
                            <span class="navList__subheading-icon"><i class="fas fa-book-open"></i></span>
                            <span class="navList__subheading-title">archives</span>
                        </div>
                        <ul class="subList subList--hidden">
                            <li class="subList__item">primary</li>
                            <li class="subList__item">social</li>
                            <li class="subList__item">promotional</li>
                        </ul>
                    </li>

                    <li class="navList__heading">photo album<i class="far fa-image"></i></li>
                    <li>
                        <div class="navList__subheading row row--align-v-center">
                            <span class="navList__subheading-icon"><i class="fas fa-mountain"></i></span>
                            <span class="navList__subheading-title">vacation</span>
                        </div>
                        <ul class="subList subList--hidden">
                            <li class="subList__item">cambodia</li>
                            <li class="subList__item">new york</li>
                        </ul>
                    </li>
                    <li>
                        <div class="navList__subheading row row--align-v-center">
                            <span class="navList__subheading-icon"><i class="fas fa-wine-glass-alt"></i></span>
                            <span class="navList__subheading-title">anniversary</span>
                        </div>
                        <ul class="subList subList--hidden">
                            <li class="subList__item">dive trip</li>
                            <li class="subList__item">hikathon</li>
                            <li class="subList__item">buffalo river</li>
                        </ul>
                    </li>
                    <li>
                        <div class="navList__subheading row row--align-v-center">
                            <span class="navList__subheading-icon"><i class="fas fa-graduation-cap"></i></span>
                            <span class="navList__subheading-title">university</span>
                        </div>
                        <ul class="subList subList--hidden">
                            <li class="subList__item">wild horse saloon</li>
                            <li class="subList__item">service corps</li>
                            <li class="subList__item">graduation</li>
                            <li class="subList__item">internships</li>
                        </ul>
                    </li>

                    <li class="navList__heading">statistics<i class="fas fa-chart-bar"></i></li>
                    <li>
                        <div class="navList__subheading row row--align-v-center">
                            <span class="navList__subheading-icon"><i class="fas fa-credit-card"></i></span>
                            <span class="navList__subheading-title">finances</span>
                        </div>
                        <ul class="subList subList--hidden">
                            <li class="subList__item">mortgage</li>
                            <li class="subList__item">investments</li>
                            <li class="subList__item">spend log</li>
                            <li class="subList__item">owed</li>
                        </ul>
                    </li>
                    <li>
                        <div class="navList__subheading row row--align-v-center">
                            <span class="navList__subheading-icon"><i class="fas fa-phone"></i></span>
                            <span class="navList__subheading-title">call stats</span>
                        </div>
                        <ul class="subList subList--hidden">
                            <li class="subList__item">last month</li>
                            <li class="subList__item">bi-weekly</li>
                            <li class="subList__item">yesterday</li>
                            <li class="subList__item">today</li>
                        </ul>
                    </li>
                    <li>
                        <div class="navList__subheading row row--align-v-center">
                            <span class="navList__subheading-icon"><i class="fas fa-plane"></i></span>
                            <span class="navList__subheading-title">trip logs</span>
                        </div>
                        <ul class="subList subList--hidden">
                            <li class="subList__item">amsterdam</li>
                            <li class="subList__item">buenos aires</li>
                            <li class="subList__item">cambodia</li>
                            <li class="subList__item">greenland</li>
                        </ul>
                    </li>

                    <li class="navList__heading">Events<i class="fas fa-calendar-alt"></i></li>
                    <li>
                        <div class="navList__subheading row row--align-v-center">
                            <span class="navList__subheading-icon"><i class="fas fa-wine-glass-alt"></i></span>
                            <span class="navList__subheading-title">weddings</span>
                        </div>
                        <ul class="subList subList--hidden">
                            <li class="subList__item">past</li>
                            <li class="subList__item">present</li>
                            <li class="subList__item">future</li>
                        </ul>
                    </li>
                    <li>
                        <div class="navList__subheading row row--align-v-center">
                            <span class="navList__subheading-icon"><i class="fas fa-school"></i></span>
                            <span class="navList__subheading-title">playdates</span>
                        </div>
                        <ul class="subList subList--hidden">
                            <li class="subList__item">weirdos</li>
                            <li class="subList__item">smarties</li>
                            <li class="subList__item">nerds</li>
                        </ul>
                    </li>
                    <li>
                        <div class="navList__subheading row row--align-v-center">
                            <span class="navList__subheading-icon"><i class="fas fa-users"></i></span>
                            <span class="navList__subheading-title">networking</span>
                        </div>
                        <ul class="subList subList--hidden">
                            <li class="subList__item">tech</li>
                            <li class="subList__item">automotive</li>
                            <li class="subList__item">UX research</li>
                            <li class="subList__item">development</li>
                        </ul>
                    </li>
-->

<!--https://codepen.io/trooperandz/pen/EOgJvg-->
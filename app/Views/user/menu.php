<?php

if (session()->get('admin') == 'yes') {
    $dashboard = '<li class="header">Admin Dasboard</li>' . '<li><a href="/admin">' . '<i class="fa fa-info-circle" aria-hidden="true"></i>Dashboard' . '</a>' . '</li>';
}

?>

<div class="sidebar-container">
    <div class="sidebar-logo">
        Settings
    </div>
    <ul id="menu" class="sidebar-navigation">
        <li class="header">Account Settings</li>
        <li>
            <a href="/account">
                <i class="fa fa-home" aria-hidden="true"></i> Account
            </a>
        </li>
        <li>
            <a href="/account/subscription">
                <i class="fa fa-tachometer" aria-hidden="true"></i> Subscription
            </a>
        </li>
        <li class="header">Settings</li>
        <li>
            <a href="/account/communication">
                <i class="fa fa-users" aria-hidden="true"></i> Communication
            </a>
        </li>
        <li>
            <a href="/account/change_password">
                <i class="fa fa-cog" aria-hidden="true"></i> Change Password
            </a>
        </li>
        <?php if (isset($dashboard)) {
            echo $dashboard;
        }
        ?>
    </ul>
</div>


<div class="content-container">

    <div class="container-fluid">

        <!-- Main component for a primary marketing message or call to action -->
        <div class="jumbotron">
            <h1 id="header1"><?php echo $title; ?></h1>
        </div>
        <div>
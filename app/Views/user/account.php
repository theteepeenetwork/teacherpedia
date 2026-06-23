<?php
include 'menu.php';

?>
<ul style="list-style: none;">
    <li>
        <h3>Name:</h3>
        <?php echo session()->get('first_name') . ' ' . session()->get('second_name') ?>
    </li>
    <br />

    <li>
        <h3>Email:</h3>
        <?php echo session()->get('email') ?>
    </li>
    <br />

    <li>
        <h3>Membership:</h3>
        <?php echo session()->get('subscriber') ?>
    </li>
    </h3>
    <br />

    <li><a href="/account/logout">Logout</a></li>
    <br />
    </div>
</ul>

</div>
</div>
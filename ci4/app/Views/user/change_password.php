<?php
include 'menu.php';


?>

<ul style="list-style: none;">

    <li>

        <?= \Config\Services::validation()->listErrors(); ?>
        <?= $session = session()->getFlashdata('success'); ?>
        <form method="post" action="<?php echo base_url(); ?>/user/users/change_password">

            <div class="form-control">
                <label for="old_password">Old Password</label>
                <input class="form-control" type="password" id="old_password" name="old_password" value="" />

            </div>
            <br />
            <div class="form-control">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" value="">

            </div>
            <br />
            <div class="form-control">
                <label for="new_password">Confirm New Password</label>
                <input type="password" class="form-control" id="passconf" name="passconf" value="">

            </div>
            <br />
            <div class="form-group">
                <input type="submit" name="register" value="Change" class="btn btn-info" />
            </div>
        </form>

    </li>
    </h3>
    <br />
    <br />
    </div>
</ul>

</div>
</div>
<?php
include 'menu.php';

//set radio buttons from database
$yes = "";
$no = "";

if (session()->get('communication') == 'yes') {
    $yes = 'checked';
} elseif (session()->get('communication') == 'no') {
    $no = 'checked';
} else {
    $yes = "";
    $n0 = "";
}

if (session()->getFlashdata('message')) {
    $confirm = '
                    <div class="alert alert-success">
                        ' . session()->getFlashdata("message") . '
                    </div>
                    ';
}
//end set radio buttons

?>
<?php
if (session()->getFlashdata('message')) {
    echo $confirm;
} ?>
<ul style="list-style: none;">

    <li>
        <p>I am happy to receive communication from Teacherpedia:</p>

        <?php echo form_open('user/users/update_communication'); ?>
        <form name="update_communication" method="post">

            <div class="form-control">
                <div class="form-grup">
                    <input type="radio" id="yes" name="communication" value="yes" <?php echo $yes ?>>
                    <label for="yes">Yes</label><br>
                    <input type="radio" id="no" name="communication" value="no" <?php echo $no ?>>
                    <label for="no">No</label><br>
                </div>
                <div class="form-group">
                    <input type="submit" value="Submit" class="btn btn-info" />
                </div>
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
<?php 
include 'menu.php';

//set radio buttons from database
$yes = "";
$no = "";

if($communication == 'yes') {
    $yes = 'checked';
} elseif($communication == 'no') {
    $no = 'checked';
} else {
    $yes = "";
    $n0 = "";
}

if ($this->session->flashdata('message')) {
    $confirm = '
                    <div class="alert alert-warning">
                        ' . $this->session->flashdata("message") . '
                    </div>
                    ';
}
//end set radio buttons

?>
                    <ul style="list-style: none;">

                        <li>
                            <?php
                            if ($this->session->flashdata('message')) {
                                echo $confirm;
                            } ?>
                            <form method="post" action="<?php echo base_url(); ?>user/account/delete_account">

                                <div class="form-control">
                                    <label for="old_password">Enter your password and click delete to close your account.</label>
                                    <input class="form-control" type="password" id="password" name="password" value="<?php echo set_value('password'); ?>" />
                                    <span class="text-danger"><?php echo form_error('password'); ?></span>
                                </div>

                                <br/>
                                <div class="form-group">
      <input type="submit" name="delete" value="Delete" class="btn btn-info" />
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

  
<?php


?>


<div class="container">

    <?php if (isset($_SESSION['success'])) {
    } ?>


    <div class="container">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="margin: auto; margin-top:20px">

            <div class="limiter">
                <div class="container-login100">
                    <div class="wrap-login100">
                        <form class="login100-form validate-form p-l-55 p-r-55 p-t-178" method="post" action="/user/users/register">
                            <span class="login100-form-title">
                                Register
                            </span>

                            <div class="col-12">
                                <?php if (isset($validation)) : ?>
                                    <?php echo '<div class="alert alert-danger" role="alert">'; ?>
                                    <?= $validation->listErrors() ?>
                                    <?php echo '</div>'; ?>
                                <?php endif; ?>
                            </div>
                            <div class="col-12">
                                <?php if (isset($success)) : ?>
                                    <?php echo '<div class="alert alert-danger" role="alert">'; ?>
                                    <?php echo $success; ?>
                                    <?php echo '</div>'; ?>
                                <?php endif; ?>
                            </div>


                            <div class="form-group wrap-input100">
                                <input class="input100" type="text" id="first_name" name="first_name" placeholder="First Name" value="<?php echo set_value('first_name');
                                                                                                                                        ?>" />
                                <span class="text-danger"><?php //echo form_error('first_name'); 
                                                            ?></span>
                            </div>
                            <div class="form-group">
                                <input class="input100" type="text" id="second_name" name="second_name" placeholder="Second Name" value="<?php echo set_value('second_name');
                                                                                                                                            ?>" />
                                <span class="text-danger"><?php //echo form_error('second_name'); 
                                                            ?></span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="user_name" class="input100" placeholder="Username" value="<?php echo set_value('user_name');
                                                                                                                    ?>" />
                                <span class="text-danger"><?php //echo form_error('user_name'); 
                                                            ?></span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="user_email" class="input100" placeholder="Email" value="<?php echo set_value('user_email');
                                                                                                                    ?>" />
                                <span class="text-danger"><?php //echo form_error('user_email'); 
                                                            ?></span>
                            </div>
                            <div class="form-group">
                                <input type="password" name="user_password" placeholder="Password" class="input100" />
                                <span class="text-danger"><?php //echo form_error('user_password'); 
                                                            ?></span>
                            </div>
                            <div class="form-grup">
                                <label for="communication">Receive emails from Teacherpedia</label><br>
                                <input type="radio" id="yes" name="communication" value="yes" checked>
                                <label for="yes">Yes</label><br>
                                <input type="radio" id="no" name="communication" value="no">
                                <label for="no">No</label><br>
                            </div>

                            <div class="container-login100-form-btn">
                                <button class="login100-form-btn">
                                    Sign Up!
                                </button>
                            </div>
                            <div class="text-center p-t-136">

                            </div>
                            <div class="col-md-2"></div>
                        </form>

                    </div>
                </div>
            </div>
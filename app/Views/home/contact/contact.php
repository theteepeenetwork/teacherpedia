<?php
$session = \Config\Services::session();
if ($session->getFlashdata('message')) {
	$message = '
                    <div class="alert alert-success">
                        ' . $session->getFlashdata("message") . '
                    </div>
                    ';
}
if ($session->getFlashdata('login_error') !== NULL) {
	$login_error = $session->getFlashdata("login_error");
}

//$validation = \Config\Services::validation();
?>

<div class="limiter">
	<div class="container-login100">
		<div class="wrap-login100">

			<form class="login100-form validate-form p-l-55 p-r-55 p-t-178" method="post" action="/home/contact">
				<span class="login100-form-title">
					Sign In
				</span>

				<div class="col-12">
					<?php
					if (isset($session->warning_msg)) {
						echo '<div class="alert alert-warning" role="alert">';
						echo $session->getFlashdata('warning_msg');
						echo '</div>';
					}
					?>
				</div>
				<div class="col-12">
					<?php
					if (isset($session->success)) {
						echo '<div class="alert alert-success" role="alert">';
						echo $session->getFlashdata('success');
						echo '</div>';
					}
					?>

				</div>
				<div class="wrap-input100 validate-input m-b-16" data-validate="Name">

					<input class="input100" type="text" name="user_name" placeholder="Name" value="">
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 validate-input" data-validate="Email">
					<input class="input100" type="text" name="user_email" placeholder="Email">
					<span class="focus-input100"></span>
				</div>
				<br />
				<div class="wrap-input200 validate-input" data-validate="Message">
					<textarea class="input200" type="text" name=" message" rows="4" cols="50" placeholder="Tell us your thoughts..."></textarea>
					<span class="focus-input200"></span>
				</div>


				<div class="container-login100-form-btn">
					<button class="login100-form-btn p-t-50">
						Send
					</button>
				</div>

		</div>
		</form>
	</div>
</div>
</div>
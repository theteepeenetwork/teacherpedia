<div class="container">

<?php if(isset($_SESSION['success'])) {} ?>
<div class="row">
		<div class="col-6 col-md-3"><img style="width:80%" src="<?php echo base_url() . 'images/characters/boy1.png'; ?>" /></div>
		<div class="col-6 col-md-3"><img style="width:80%" src="<?php echo base_url() . 'images/characters/girl1.png'; ?>"  /></div>
		<div class="col-6 col-md-3"><img style="width:80%" src="<?php echo base_url() . 'images/characters/boy2.png'; ?>"  /></div>
		<div class="col-6 col-md-3"><img style="width:80%" src="<?php echo base_url() . 'images/characters/girl2.png'; ?>"  /></div>
	</div>
<form>
    <div class="container">
  <div class="col-md-2"></div>
     <div class="col-md-8" style="margin: auto; margin-top:20px">
	    <?php
		   echo form_open(base_url() . 'user/user_authentication/new_user_registration');
		   echo validation_errors();
		   if (isset($success))
		   echo '<p>'.$success.'</p>';
        ?>
		<div class="form-group">
			<label for="email">Name:</label>
			<input class="form-control" type="text" id="first_name" name="first_name" value="" />
        </div>
        <div class="form-group">
			<label for="email">Surname:</label>
			<input class="form-control" type="text" id="second_name" name="second_name" value="" />
        </div>
        <div class="form-group">
			<label for="username">Username:</label>
			<input class="form-control" type="text" id="username" name="username" value="" />
		</div>
		<div class="form-group">
			<label for="email">Email:</label>
			<input class="form-control" type="text" id="email" name="email" value="" />
		</div>
		<div class="form-group">
			<label for="password">Password:</label>
			<input class="form-control" type="password" id="password" name="password" />
		</div>
		
		<button type="submit" class="btn btn-success">Submit</button>
		<?php 
		echo form_close(); 
		?>
		</div>
     <div class="col-md-2"></div>
</form>

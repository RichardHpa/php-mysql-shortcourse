<?php 
	$errors = $user->errors;
	var_dump($errors);
 ?>

<div class="row">
	<div class="col-xs-12">
		<form action="./?page=auth.store" method="POST" class="form-horizontal">
			<h3>Register User</h3>

			<div class="form-group">
				<label for="Name" class="control-label">Name</label>
				<input class="form-control" type="text" name="name" placeholder="Enter your Name">
			</div>

			<div class="form-group">
				<label for="email" class="control-label">Email</label>
				<input class="form-control" type="email" name="email" placeholder="Enter Your Email">
			</div>

			<div class="form-group">
				<label for="password" class="control-label">Password</label>
				<input class="form-control" type="password" name="password" placeholder="Enter your Password">
			</div>

			<div class="form-group">
				<button class="btn btn-success">Register</button>
			</div>

		</form>
	</div>
</div>
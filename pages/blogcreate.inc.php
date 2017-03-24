<!-- All of the input names has to match the names of the columns in the database -->

<?php 

$errors = $blogPost->errors;
 ?>

<div class="row">
	<div class="col-xs-12">
		<h1>Add New Blog Post</h1>
		<form id="blogcreate" action=".\?page=blog.store" method="POST" class="form-horizontal" enctype="multipart/form-data">

			<div class="form-group <?php if($errors['title']): ?> has-error <?php endif; ?> ">
				<label for="title" class="control-label">Blog Title</label>
				<input class="form-control" type="text" name="title" placeholder="The title of your Blog" value="<?php echo $blogPost->title ?>">
				<div class="help-block"><?php echo $errors['title']; ?></div>
			</div>

			<div class="form-group <?php if($errors['description']): ?> has-error <?php endif; ?>">
				<label for="description" class="control-label">Description</label>	
				<textarea class="form-control" name="description" placeholder="Description for your blog post" row="5"><?php echo $blogPost->description ?></textarea>
				<div class="help-block"><?php echo $errors['description']; ?></div>
			</div>

			<div class="form-group">
				<label for="image" class="control-label">Image</label>
				<input type="file" name="image" class="form-control">
			</div>

			<div class="form-group">
				<button class="btn btn-success">Submit Blog Post</button>
			</div>

		</form>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<h1>Add New Blog Post</h1>
		<form id="blogcreate" action=".\?page=blog.store" method="POST" class="form-horizontal" enctype="multipart/form-data">

			<div class="form-group">
				<label for="blogtitle" class="control-label">Blog Title</label>
				<input class="form-control" type="text" name="blogtitle" placeholder="The title of your Blog">
			</div>

			<div class="form-group">
				<label for="blogDescription" class="control-label">Description</label>	
				<textarea class="form-control" name="blogDescription" placeholder="Description for your blog post" row="5"></textarea>
			</div>

			<div class="form-group">
				<label for="blogImage" class="control-label">Image</label>
				<input type="file" name="blogImage" class="form-control">
			</div>

			<div class="form-group">
				<button class="btn btn-success">Submit Blog Post</button>
			</div>

		</form>
	</div>
</div>
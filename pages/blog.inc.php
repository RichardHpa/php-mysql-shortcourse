<h1>This is the blog</h1>

<p><a class="btn btn-primary" href=".\?page=blog.create">Add new Blog post</a></p>

<?php if(count($blogs) > 0): ?>
<ul>
	<?php foreach($blogs as $blog): ?>
		<li><a href=".\?page=blog.post&id=<?= $blog->id ?>"><?= $blog->title ?></a></li>
	<?php endforeach; ?>
</ul>
<?php else: ?>
	<p>There are no blog posts</p>
<?php endif; ?>
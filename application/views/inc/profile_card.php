<div class="row">
	<div class="col">
		<div class="card">
			<!-- user profile image -->
			<img class="card-img-top" src="<?=$user['picture']['data']['url'];?>" alt="Profile">
			<div class="card-body text-center">
				<h4 class="card-title"><?=$user['first_name'] .' ' . $user['last_name'];?> </h4>
				<p class="card-text text-muted"><?=$user['email'];?></p>
				<p><a href="https://facebook.com/<?=$user['id'];?>"  target="_blank" class="btn btn-primary"> Show profile </a></p>
			</div>
		</div>
	</div> <!-- ./ col -->
</div> <!-- ./ row -->
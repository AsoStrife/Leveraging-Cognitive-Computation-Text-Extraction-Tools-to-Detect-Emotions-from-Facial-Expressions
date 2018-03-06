<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<a class="navbar-brand" href="/">Groups Moderator </a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarNavDropdown">
		<ul class="navbar-nav">
			<li class="nav-item active"> <a class="nav-link" href="/"> Home </a> </li>
			<li class="nav-item active"> <a class="nav-link" href="/home/privacy_policy"> Privacy Policy </a> </li>
		</ul>

		<? if(isset($user)): ?>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?= $user['first_name']; ?>
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
						<a class="dropdown-item" href="https://facebook.com/<?=$user['id'];?>" target="_blank"> Facebook Profile</a>
						<a class="dropdown-item" href="/home/logout">Logout</a>
					</div>
				</li>
			</ul>
		<? endif;?>
	</div> <!-- ./ collapse navbar-collapse -->
</nav>
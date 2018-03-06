<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<? $this->load->view('inc/header'); ?>

<div class="container">

	<? $this->load->view('inc/navbar'); ?>

	<div class="row marginTop25">
		<div class="col-md-9">

			<div class="card">
				<div class="card-header"> Albums </div>
				
				<div class="card-body">
					<div class="list-group">
						<? foreach($albums['albums']['data'] as $album): ?>
							<a href="/album/<?=$album['id'];?>" class="list-group-item list-group-item-action"> <?=$album['name'];?> </a>
						<? endforeach; ?>
					</div>
				</div>
			</div>

			<!--
			<div class="card marginTop25">
				<div class="card-header"> Debug </div>
				
				<div class="card-body">
					<pre><? //print_r($albums); ?></pre>
				</div>
			</div>
			-->

		</div>
		<div class="col-md-3">

			<div class="row marginBottom25">
				<div class="col">
					<div class="card">
						<!-- user profile image -->
						<div class="card-header"> Groups info </div>
						<div class="card-body ">
							<p>Admin: <span class="badge badge-pill badge-warning"> <?=$group_summary['admins']['summary']['total_count'];?> </span></p>
							<p>Users: <span class="badge badge-pill badge-primary"> <?=$group_summary['members']['summary']['total_count'];?> </span></p>
						</div>
					</div>
				</div> <!-- ./ col -->
			</div> <!-- ./row -->
			
			<? $this->load->view('inc/profile_card.php'); ?>

		</div> <!-- ./ col-md-4 -->
	</div> <!-- ./ row -->
</div> <!-- ./ container -->

		

<? $this->load->view('inc/footer'); ?>
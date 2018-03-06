<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<? $this->load->view('inc/header'); ?>

	<div class="container">
		<div class="row">
			<div class="col">
				<div class="card">
					<div class="card-header"> Groups Moderator </div>
					<div class="card-body">
						<h5 class="card-title"> Log in with Facebook </h5>
						<p class="card-text"> You need to be authenticated with your Facebook account in order to use this application. </p>
						<a href="<?=$authUrl;?>" class="btn btn-primary"> Login </a>
						<a href="/" class="btn btn-default"> Refresh Home </a>
						<a href="/home/privacy_policy" class="btn btn-default"> Privacy Policy </a>
					</div>
				</div>
			</div> <!-- ./ col -->
		</div> <!-- ./ row -->
	</div> <!-- ./ container -->

<? $this->load->view('inc/footer'); ?>
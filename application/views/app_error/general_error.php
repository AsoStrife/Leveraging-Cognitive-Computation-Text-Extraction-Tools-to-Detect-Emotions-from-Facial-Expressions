<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<? $this->load->view('inc/header'); ?>

<div class="container">

	<? $this->load->view('inc/navbar'); ?>

	<div class="row marginTop25">
		<div class="col">

			<div class="card">
				<div class="card-header"> General Error </div>
				
				<div class="card-body">
					<div class="list-group">
						<div class="alert alert-danger" role="alert">
							<h4 class="alert-heading">Error <?=$error['error'];?></h4>
							<p><?=$error['message'];?></p>
							<hr>
							<a href="/" class="btn btn-danger"> Home </a>
						</div>
					</div>
				</div>
			</div>
			
		</div>

	</div> <!-- ./ row -->
</div> <!-- ./ container -->

		

<? $this->load->view('inc/footer'); ?>
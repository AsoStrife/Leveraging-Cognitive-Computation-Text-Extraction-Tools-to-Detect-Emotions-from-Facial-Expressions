<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<? $this->load->view('inc/header'); ?>

<div class="container">

	<? $this->load->view('inc/navbar'); ?>

	<div class="row marginTop25">
		<div class="col-md-9">

			<div class="card">
				<div class="card-header"> Error </div>
				
				<div class="card-body">

					<? if(isset($error)): ?>
						<div class="alert alert-danger" role="alert"> 
							<?=$error;?> 
						</div>
					<? endif; ?>
				</div>
			</div>
			
		</div>
		<div class="col-md-3">

			<? $this->load->view('inc/profile_card.php'); ?>
		
		</div> <!-- ./ col-md-3 -->
	</div> <!-- ./ row -->
</div> <!-- ./ container -->

		

<? $this->load->view('inc/footer'); ?>
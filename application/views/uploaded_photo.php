<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<? $this->load->view('inc/header'); ?>

<div class="container">

	<? $this->load->view('inc/navbar'); ?>

	<div class="row marginTop25">
		<div class="col-md-9">

			<div class="card">
				<div class="card-header"> Photo Analysis </div>
				
				<div class="card-body">
					<div class="row">
						<div class="col-md-3">
							<img src="<?= base_url() . $photo->path;?>" class="img-fluid img-thumbnail"/>
						</div>
						<div class="col-md-9">
							<p> <b> Tags: </b> <?=$tags;?> </p>
							<p> <b> Our classifier: </b>  <?=$photo->our_class;?> </p>
							<p> <b> Azure tags: </b> <br> <? printCsEmotion($photo); ?> </p>
							<p> <b> Is porn: </b> <? getPornValue($photo); ?> </p>
							<p> <b> Is racist: </b> <? getRacistValue($photo); ?> </p>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		<div class="col-md-3">

			<? $this->load->view('inc/profile_card.php'); ?>
		
		</div> <!-- ./ col-md-3 -->
	</div> <!-- ./ row -->
</div> <!-- ./ container -->

		

<? $this->load->view('inc/footer'); ?>
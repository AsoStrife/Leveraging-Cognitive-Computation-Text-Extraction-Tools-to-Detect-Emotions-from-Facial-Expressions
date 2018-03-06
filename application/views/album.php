<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<? $this->load->view('inc/header'); ?>

<div class="container">

	<? $this->load->view('inc/navbar'); ?>

	<div class="row marginTop25">
		<div class="col-md-9">

			<div class="card">
				<div class="card-header"> <?=$album['name'];?> </div>
				
				<div class="card-body">
					<ul class="row" id="album-gallery">
						<? foreach($photosAlbum['data'] as $photo): ?>
							<? $cs = $this->cognitiveservices->moderateImage($photo['source']); ?>
							<li>
								<img alt="Night away"  src="<?=$photo['source'];?>">
								<p> 
									ID: <?=$photo['id'];?> <br>
									Porn <span class="badge badge-<?=getClassScore($cs->AdultClassificationScore);?>"> <?=$cs->AdultClassificationScore;?></span> <br>
									Racist <span class="badge badge-<?=getClassScore($cs->RacyClassificationScore);?>"> <?=$cs->RacyClassificationScore; ?> </span> <br>
								</p>
							</li>
						<? endforeach; ?>
					</ul>
				</div> <!-- ./ card-body -->
			</div> <!-- ./ card -->
 
			<!--
			<div class="card marginTop25">
				<div class="card-header"> Debug </div>
				
				<div class="card-body">
					<pre><? //print_r($photosAlbum); ?></pre>
				</div>
			</div>-->
			

		</div>
		<div class="col-md-3">

			<? $this->load->view('inc/profile_card.php'); ?>

		</div> <!-- ./ col-md-4 -->
	</div> <!-- ./ row -->
</div> <!-- ./ container -->

		

<? $this->load->view('inc/footer'); ?>
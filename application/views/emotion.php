<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<? $this->load->view('inc/header'); ?>

<div class="container">

	<? $this->load->view('inc/navbar'); ?>

	<div class="row marginTop25">
		<div class="col-md-9">

			<div class="card">
				<div class="card-header"> Emotion </div>
				
				<div class="card-body">
					<div class="row">
						<div class="col">
							<!--<p>
								<button class="btn btn-outline-danger" id="delete-button">
									<i class="fa fa-trash" aria-hidden="true"></i> Delete data 
								</button>
							</p>-->
						</div> <!-- ./col pull-left  -->

						<div class="col">
							<p class="float-right">
								<button class="btn btn-primary" id="azure-classifier-button">
									<i class="fa fa-refresh" aria-hidden="true" id="azure-classifier-icon"></i> Azure Classifier 
								</button>

								<button class="btn btn-default" id="custom-classifier-button">
									<i class="fa fa-refresh" aria-hidden="true" id="custom-classifier-icon"></i> Custom Classifier 
								</button>
							</p>
						</div> <!-- ./col pull-right -->

					</div> <!-- ./ row -->	
					
					<div class="row marginBottom25" style="display: none;" id="row-progress">
						<div class="col">
							<div class="progress">
								<div class="progress-bar bg-success" id="progress-success" role="progressbar" style="width: 0%" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
						</div> <!-- ./ col -->
					</div> <!-- ./ row -->

					<table class="table table-striped table-bordered">
						<thead class="thead-dark">
							<tr>
								<th> ID </th>
								<th style="width: 20%;"> Image </th>
								<th> Class </th>
								<th style="width: 20%"> Azure Vision</th>
								<th style="width: 30%;"> Azure Emotion</th>
								<th style="width: 25%;"> Our classifier</th>
							</tr>
						</thead>
						<tbody>

							<? foreach($images as $key => $image): ?>
								<tr>
									<td> <?=$image->id; ?> </td>
									<td> <img src="<?=$image->path;?>" alt="<?=$image->filename;?>" class="img-fluid img-thumbnail" /> </td>
									<td> <?=$image->class;?> </td>
									<td> 
										<? printCsVision($image); ?>
									</td>
									<td> 
										<? printCsEmotion($image); ?> 
									</td>
									<td> <?=$image->our_class ? $image->our_class : '-';?> </td>
								</tr>
							<? endforeach; ?>
						</tbody>
					</table>
						
				</div>
			</div>
			
		</div>
		<div class="col-md-3">

			<? $this->load->view('inc/profile_card.php'); ?>
		
		</div> <!-- ./ col-md-4 -->
	</div> <!-- ./ row -->
</div> <!-- ./ container -->

		

<? $this->load->view('inc/footer'); ?>
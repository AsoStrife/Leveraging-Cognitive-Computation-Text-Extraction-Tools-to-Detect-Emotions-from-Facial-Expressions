<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<? $this->load->view('inc/header'); ?>

<div class="container">

	<? $this->load->view('inc/navbar'); ?>

	<div class="row marginTop25">
		<div class="col-md-9">

			<div class="card">
				<div class="card-header"> Datasets Emotion </div>
				
				<div class="card-body">
					<div class="list-group">
						<?  $key = 0;
							foreach($datasets as $d): ?>
							<? 
								$dataContent = "";
								if($key == 2)
									$dataContent = "Dataset 1 contains photo with facial expression and not. 428 images.";

								if($key == 1)
									$dataContent = "Dataset 2 is a subset of the Dataset 1. It contains only facial expression. 20 images.";


								if($key == 0)
									$dataContent = "Dataset 3 is the  Microoft FER+ dataset that is used to test and train our classification model. It cointains only facial expression. 700 images.";
							?>
							<a href="/emotion/<?=$d;?>" class="list-group-item list-group-item-action"> 

								<span class="badge badge-primary pull-right"
									data-container="body" data-toggle="popover" data-placement="top" data-trigger="hover"
									data-content="<?=$dataContent;?>"> ? </span>

								Dataset <?=$d;?>
									
							</a>
						<? $key++;
							endforeach; ?>
					</div>
				</div>
			</div>

			<div class="card marginTop25">
				<div class="card-header"> Upload your photo </div>
				
				<div class="card-body">

					<? if(isset($upload_error)): ?>
						<div class="alert alert-danger alert-dismissible" role="alert"> <?=$upload_error;?> 
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
					<? endif; ?>

					<?php echo form_open_multipart('/home/do_upload');?>

						<div class="form-group">
							<input type="file" class="form-control-file" id="uploadPic" name="uploadPic" />
						</div>

						<input type="submit" value="upload" class="btn btn-primary" />

					</form>

					<hr> 
					<div class="mygallery">
						<? foreach($uploadedPhotos as $photo): ?>
							<a href="/photo/uploaded/<?=$photo->id;?>">
								<img src="<?=$photo->path;?>"/>
							</a>
						<? endforeach; ?>
					</div>
				</div>
			</div>
			
			<div class="card marginTop25">
				<div class="card-header"> Your Facebook Photos </div>
				
				<div class="card-body">
					<p> Your last 25 photos on Facebook </p>
					<div class="mygallery">
						<? foreach( $facebookPhotos['data'] as $photo): ?>
							<a href="/photo/facebook/<?=$photo['id'];?>">
								<img src="<?=$photo['images'][3]['source'];?>"/>
							</a>
						<? endforeach; ?>
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
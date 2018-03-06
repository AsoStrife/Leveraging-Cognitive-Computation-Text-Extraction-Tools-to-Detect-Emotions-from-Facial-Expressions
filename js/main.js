// AZURE CLASSIFIER ONCLICK
$("#azure-classifier-button").click(function(){
	$("#azure-classifier-icon").toggleClass("gly-spin"); // Start/Stop twist
	disableAllButtons();
	$("#row-progress").fadeIn(); // Show progress bar azure

	var datasetID =  getSegment(4);
	var datasetUrl = '/emotion/ajax_get_dataset_for_azure';
	console.log("Launching ajax request on ajax_get_dataset_for_azure");

	$.ajax({
		url: datasetUrl,
		type: 'POST',
		data: { 
			'datasetID': datasetID, 
		}
	})
	.done(function(data) {
		console.log("Get all dataset images");
		
		var images = JSON.parse(data);
		var dimension = images.length;
		var index = 0;

		images.forEach(function(element) {
			
			var classificationUrl = '/emotion/ajax_azure_classifier';
			$.ajax({
				url: classificationUrl,
				type: 'POST',
				data: { 
					'image': JSON.stringify(element), 
					'datasetID': datasetID 
				}
			})
			.done(function(data) {
				index++;
				var percentage = getPercentage(index, dimension);
				$('#progress-success').css('width', percentage + '%');
				$("#progress-success").html(percentage + "%");
				console.log(data);
				
				if(index == dimension){
					//enableAllButtons();
					$("#azure-classifier-icon").toggleClass("gly-spin"); // Start/Stop twist
					//$("#row-progress").fadeOut(); // Show progress bar azure
					//$('#progress-success').css('width', '0%');
					//$("#progress-success").html("0%");

					// Wait to see the changes and then refresh
					setTimeout(function(){
						location.reload(); 
					}, 3000);
				}
			})
		}); // foreach	
	}) // .done
    .fail(function() {
		console.log("Ajax failed the call");
		enableAllButtons();
		$("#azure-classifier-icon").toggleClass("gly-spin"); // Start/Stop twist
	});	
}); // azure classifier onclick

// CUSTOM CLASSIFIER ONCLICK
$("#custom-classifier-button").click(function(){
	$("#custom-classifier-icon").toggleClass("gly-spin"); // Start/Stop twist
	disableAllButtons();
	$("#row-progress").fadeIn(); // Show progress bar azure

	var datasetID =  getSegment(4);
	var datasetUrl = '/emotion/ajax_get_dataset_for_custom_classifier';
	console.log("Launching ajax request on ajax_get_dataset_for_custom_classifier");

	$.ajax({
		url: datasetUrl,
		type: 'POST',
		data: { 
			'datasetID': datasetID, 
		}
	})
	.done(function(data) {
		console.log("Get all dataset images");
		
		var images = JSON.parse(data);
		var dimension = images.length;
		var index = 0;

		images.forEach(function(element) {
			
			var classificationUrl = 'http://192.167.155.71/acorriga/classifier';
			console.log("Launghing request on http://192.167.155.71/acorriga/classifier");
			$.ajax({
				url: classificationUrl,
				crossDomain: true,
				headers: {  'Access-Control-Allow-Origin': 'http://192.167.155.71/acorriga/classifier' },
				type: 'POST',
				data: { 
					'imageID': element.id, 
					'tags': element.tags
				}
			})
			.done(function(data) { // data is already in json format, no need to parese
				console.log(data)
				var final_result = data;
				
				var savingDataUrl = '/emotion/ajax_save_custom_classification';
				console.log("Launching saving custom classification request")
				$.ajax({
					url: savingDataUrl,
					type: 'POST',
					data: { 
						'imageID': data.id, 
						'class': data.class
					}
				})
				.done(function(data){
					index++;
					var percentage = getPercentage(index, dimension);
					$('#progress-success').css('width', percentage + '%');
					$("#progress-success").html(percentage + "%");
					console.log(data);
					
					if(index == dimension){
						//enableAllButtons();
						$("#azure-classifier-icon").toggleClass("gly-spin"); // Start/Stop twist

						// Wait to see the changes and then refresh
						setTimeout(function(){
							location.reload(); 
						}, 3000);
					}
				})
				
			}) // done
		}); // foreach	
	}) // .done
    .fail(function() {
		console.log("Ajax failed the call");
		enableAllButtons();
		$("#azure-classifier-icon").toggleClass("gly-spin"); // Start/Stop twist
	});
});

/**
 * Manage deleting data
 */
$("#delete-button").click(function(){
	var datasetID =  getSegment(4);
	var datasetUrl = '/emotion/ajax_delete_all_data';
	console.log("Launching ajax request on ajax_delete_all_data: ");

	disableAllButtons();
	$.ajax({
		url: datasetUrl,
		type: 'POST',
	    data: { 
    	    'datasetID': datasetID, 
		}
    })
    .done(function(data) {
		console.log(data);
		location.reload();
	})
	.fail(function() {
		console.log("Ajax failed calling ajax_delete_all_data");
		enableAllButtons();
    });
});

/**
 * Return the segment of the URL
 * 0 => null
 * 1 => domain
 * ... => segment
 */
function getSegment(id){
	var url = $(location).attr('href')
	var segments = url.split( '/' );
	var value = segments[id];
	return value; 
}

// Disable all buttons in the page
function disableAllButtons(){
	$("#azure-classifier-button").addClass('disabled'); // Disabling button
	$("#azure-classifier-button").prop('disabled', true); // Disabling button

	$("#custom-classifier-button").addClass('disabled'); // Disabling button
	$("#custom-classifier-button").prop('disabled', true); // Disabling button

	$("#delete-button").addClass('disabled'); // Disabling button
	$("#delete-button").prop('disabled', true); // Disabling button

}

// Enable all buttons in the page
function enableAllButtons(){
	$("#azure-classifier-button").removeClass('disabled'); // Disabling button
	$("#azure-classifier-button").prop('disabled', false); // Disabling button

	$("#custom-classifier-button").removeClass('disabled'); // Disabling button
	$("#custom-classifier-button").prop('disabled', false); // Disabling button

	$("#delete-button").removeClass('disabled'); // Disabling button
	$("#delete-button").prop('disabled', false); // Disabling button

}

function getPercentage(current, total){
	percentage =  ((current * 100) / total);

	return Math.round(percentage);
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('printCsEmotion')){
	function printCsEmotion($image){

		if($image->cs_emotion){
			$emotion = json_decode($image->cs_emotion);
			if(!empty($emotion)){
				echo "Anger: " . $emotion[0]->scores->anger . '<br>';
				echo "Contempt: " . $emotion[0]->scores->contempt . '<br>';
				echo "Disgust: " . $emotion[0]->scores->disgust . '<br>';
				echo "Fear: " . $emotion[0]->scores->fear . '<br>';
				echo "Happiness: " . $emotion[0]->scores->happiness . '<br>';
				echo "Neutral: " . $emotion[0]->scores->neutral . '<br>';
				echo "Sadness: " . $emotion[0]->scores->sadness . '<br>';
				echo "Surprise: " . $emotion[0]->scores->surprise . '<br>';
			}
			else
				echo '-';
		}
		else
			echo '-';
    }   
}

if ( ! function_exists('printCsVision')){
	function printCsVision($image){
		if($image->cs_vision){
			$vision = json_decode($image->cs_vision);

			foreach($vision->tags as $tag)
				echo $tag->name .', ';

			$size = count($vision->description->tags);
			$count = 1;

			foreach($vision->description->tags as $tag){
				if($size <= $count)
					echo $tag ;
				else
					echo $tag .', ';
				$count++;
			}
		}
		else
			echo '-';
	}
}

if ( ! function_exists('getPornValue')){
	function getPornValue($image){
		if($image->cs_vision){
			$vision = json_decode($image->cs_vision);

			print_r($vision->adult->adultScore);
		}
	}
}

if ( ! function_exists('getRacistValue')){
	function getRacistValue($image){
		if($image->cs_vision){
			$vision = json_decode($image->cs_vision);

			print_r($vision->adult->racyScore);
		}
	}
}
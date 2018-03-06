<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('getClassScore')){
	function getClassScore($value){
		if($value < 0)
			return "info";

	if($value >= 0 && $value < 0.4)
			return "success";

		if($value >= 0.4 && $value <= 0.6)
			return "warning";

		if($value > 0.6)
			return "danger";
	}
}
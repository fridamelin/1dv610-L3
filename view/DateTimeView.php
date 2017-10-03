<?php

class DateTimeView {


	public function show() {
		$day = date("l");
		$dayOfMonth = date("j");
		$characters = date("S");
		$month = date("F");
		$year = date("Y");
		$time = date("H:i:s");
		


		$timeString = $day . ", the " . $dayOfMonth . $characters . " of " . $month . " " . $year . ", " . "The time is " . $time; 

		return '<p>' . $timeString . '</p>';
	}
}
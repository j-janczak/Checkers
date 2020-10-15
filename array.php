<?php
	$array = [
		"succes" => 1,
		"response" => "nick1",
		"list" => []
	];
	
	$a = [
		"id" => 1,
		"nick" => "nick1"
	];
	
	array_push($array["list"], $a);
	array_push($array["list"], $a);
	array_push($array["list"], $a);

	print_r($array); echo "<br><br>";
	echo json_encode($array);
?>
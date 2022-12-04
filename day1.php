<?php

$data = nl2br(file_get_contents('data/day1.txt'));

$array = [];
$count = 0;
array_push($array, $count);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $data) as $line){

	$line = (int) $line;

	if ($line === 0) {
		$array[$count] -= $count;
		$count++;
		array_push($array, $count);
	} else {
		$array[$count] += $line;
	}

	// $number = (int) $line;
} 

$array[$count] -= $count;

echo "<br>";
echo "<strong>Single highest number of calories</strong><br>";
echo max($array);
echo "<br>";

rsort($array);
$top3 = array_reverse(array_slice($array, 0, 3));
$total = $top3[0] + $top3[1] + $top3[2];

echo "<br>";
echo "<strong>Top 3 combined calories</strong><br>";
echo $total;

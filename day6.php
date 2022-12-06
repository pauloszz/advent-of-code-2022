<?php

$data = nl2br(file_get_contents('data/' . basename(__FILE__, '.php') . '.txt'));
$data_array = str_split($data);
$marker_found = false;

foreach ($data_array as $index => $letter) {
	if ($marker_found) {
		continue;
	}

	if ($index < 3) {
		continue;
	}

	$chunk = [];

	array_push($chunk, $data_array[$index-3]);
	array_push($chunk, $data_array[$index-2]);
	array_push($chunk, $data_array[$index-1]);
	array_push($chunk, $data_array[$index]);
	
	$chunk = array_unique($chunk);

	if (count($chunk) < 4) {
		continue;
	}

	$marker = $index + 1;
	$marker_found = true;
}

echo "<br>";

echo "<strong>Part 1 - First 4 character marker</strong><br>";
echo $marker;
echo "<br>";

$marker_found = false;

foreach ($data_array as $index => $letter) {
	if ($marker_found) {
		continue;
	}

	if ($index < 13) {
		continue;
	}

	$chunk = [];

	array_push($chunk, $data_array[$index-13]);
	array_push($chunk, $data_array[$index-12]);
	array_push($chunk, $data_array[$index-11]);
	array_push($chunk, $data_array[$index-10]);
	array_push($chunk, $data_array[$index-9]);
	array_push($chunk, $data_array[$index-8]);
	array_push($chunk, $data_array[$index-7]);
	array_push($chunk, $data_array[$index-6]);
	array_push($chunk, $data_array[$index-5]);
	array_push($chunk, $data_array[$index-4]);
	array_push($chunk, $data_array[$index-3]);
	array_push($chunk, $data_array[$index-2]);
	array_push($chunk, $data_array[$index-1]);
	array_push($chunk, $data_array[$index]);
	
	$chunk = array_unique($chunk);

	if (count($chunk) < 14) {
		continue;
	}

	$marker = $index + 1;
	$marker_found = true;
}

echo "<br>";

echo "<strong>Part 2 - First 14 character marker</strong><br>";
echo $marker;
echo "<br>";

<?php

$data = nl2br(file_get_contents('data/' . basename(__FILE__, '.php') . '.txt'));
$containers_temp = [];
$containers = [];
$moves_temp = [];
$moves = [];
$count = 0;
$rowNumbersRow = 0;

foreach(preg_split("/((\r?\n)|(\r\n?))/", $data) as $line){
	$line = urlencode($line);
	if (strpos($line, "%3") != null) {
		$line = substr($line, 0, strpos($line, "%3"));
	}
	$line = urldecode($line);

	if (str_starts_with($line, " ") || str_starts_with($line, "[")) {
		$containers_temp[$count] = $line;
		if (str_contains($line, "1")) {
			$rowNumbersRow = $count;
		}
	}

	if (str_starts_with($line, "move")) {
		$moves_temp[$count] = $line;
	}
	$count++;
}

$stackNumbers = array_pop($containers_temp);
foreach (str_split($stackNumbers) as $number) {
	if ($number != " ") {
		$containers[$number] = [];
	}	
}

foreach (array_reverse($containers_temp) as $container_row) {
	$start_pos = 0;
	for ($i=1; $i < count($containers)+1; $i++) { 
		$container = substr($container_row, $start_pos, 3);
		if ($container != "   ") {
			array_push($containers[$i], $container);
		}
		$start_pos = $start_pos + 4;
	}
}

foreach ($moves_temp as $move) {
	$move_number = substr($move, 0, 7);
	$move_number = str_replace(" ", "", $move_number);
	$move_number = (int) str_replace("move", "", $move_number);

	$start_pos = strpos($move, "from");
	$from_number = substr($move, $start_pos, 7);
	$from_number = str_replace(" ", "", $from_number);
	$from_number = (int) str_replace("from", "", $from_number);

	$start_pos = strpos($move, "to");
	$to_number = substr($move, $start_pos, 5);
	$to_number = str_replace(" ", "", $to_number);
	$to_number = (int) str_replace("to", "", $to_number);

	$move_row = array($move_number, $from_number, $to_number);
	array_push($moves, $move_row);
}

$containers_part1 = $containers;

foreach ($moves as $key => $move) {
	for ($i=0; $i < $move[0]; $i++) { 
		$move_container = array_pop($containers_part1[$move[1]]);
		array_push($containers_part1[$move[2]], $move_container);
	}
}

$top_containers = "";

foreach ($containers_part1 as $container_row) {
	if (count($container_row) == 0) {
		continue;
	}
	$last_container = $container_row[count($container_row)-1];
	$last_container = str_replace("[", "", $last_container);
	$last_container = str_replace("]", "", $last_container);
	$top_containers .= $last_container;
}

echo "<strong>Part 1 - Top row of containers</strong><br>";
echo $top_containers;
echo "<br>";

$containers_part2 = $containers;

foreach ($moves as $move) {
	$temp_row = [];
	for ($i=0; $i < $move[0]; $i++) {
		$move_container = array_pop($containers_part2[$move[1]]);
		array_push($temp_row, $move_container);
	}

	foreach (array_reverse($temp_row) as $moving_container) {
		array_push($containers_part2[$move[2]], $moving_container);	
	}
}

$top_containers = "";

foreach ($containers_part2 as $container_row) {
	if (count($container_row) == 0) {
		continue;
	}
	$last_container = $container_row[count($container_row)-1];
	$last_container = str_replace("[", "", $last_container);
	$last_container = str_replace("]", "", $last_container);
	$top_containers .= $last_container;
}

echo "<br>";

echo "<strong>Part 2 - Top row of containers</strong><br>";
echo $top_containers;
echo "<br>";

<?php

$data = nl2br(file_get_contents('data/' . basename(__FILE__, '.php') . '.txt'));
$prioTotal = 0;
$lines = [];

foreach(preg_split("/((\r?\n)|(\r\n?))/", $data) as $line){
	$line = urlencode($line);
	if (strpos($line, "%") != null) {
		$line = substr($line, 0, strpos($line, "%"));
	}
	array_push($lines, $line);
}

foreach ($lines as $line) {
	$length = strlen($line);
	$first = substr($line, 0, $length/2);
	$second = substr($line, $length/2);

	$doubles = "";
	foreach (str_split($first) as $character) {
		if (str_contains($second, $character)) {
			$doubles .= $character;
		}
	}
	$array = str_split($doubles);
	$unique_values = array_unique($array);

	$prio = 0;
	foreach ($unique_values as $value) {
		if (ctype_upper($value)) {
			$prio += ord($value) - 38;
		} else {
			$prio += ord($value) - 96;
		}
	}
	$prioTotal += $prio;
}


echo "<br>";
echo "<strong>Part 1 - Total prio</strong><br>";
echo $prioTotal;
echo "<br>";

$groups = array_chunk($lines, 3);
$prioTotal = 0;

foreach ($groups as $group) {
	$commonCharacters = "";
	$commonCharacter = "";
	foreach ($group as $key => $line) {
		foreach (str_split($line) as $character) {
			if ($key === 0) {
				if (str_contains($group[1], $character)) {
					if (str_contains($group[2], $character)) {
						$commonCharacters .= $character;
					}
				}
			}
		}
	}
		$array = str_split($commonCharacters);
		$commonCharacter = array_unique($array);

		$prio = 0;
		foreach ($commonCharacter as $value) {
			if (ctype_upper($value)) {
				$prio += ord($value) - 38;
			} else {
				$prio += ord($value) - 96;
			}
		}
		$prioTotal += $prio;
}

echo "<br>";
echo "<strong>Part 2 - Total points</strong><br>";
echo $prioTotal;
echo "<br>";

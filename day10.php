<?php

$data = nl2br(file_get_contents('data/' . basename(__FILE__, '.php') . '.txt'));
$register[] = ["X" => 1, "signalDuring" => 0, "signalEnd" => 0];
$cycle = 0;

foreach(preg_split("/((\r?\n)|(\r\n?))/", $data) as $line){
	$line = urlencode($line);
	if (strpos($line, "%3") != null) {
		$line = substr($line, 0, strpos($line, "%3"));
	}
	$line = urldecode($line);

	$commandIsAddx = substr($line, 0, 4) === "addx";

	$cycle++;
	$registerX = $register[$cycle-1]["X"];
	$register[$cycle] = ["X" => $registerX, "signalDuring" => $register[$cycle-1]["X"] * $cycle, "signalEnd" => $cycle * $registerX];

	if ($commandIsAddx) {
		$cycle++;
		$registerX = $register[$cycle-1]["X"] + substr($line, 5);
		$register[$cycle] = ["X" => $registerX, "signalDuring" => $register[$cycle-1]["X"] * $cycle, "signalEnd" => $cycle * $registerX];
	}	
}

$totalSignalStrength = 0;

for ($i=20; $i < 240; $i = $i + 40) {
	$signalStrength = $register[$i]["signalDuring"];
	$totalSignalStrength += $signalStrength;
}

echo "<strong>Part 1 - Sum of strength during 20th, 60th, 100th, 140th, 180th and 220th cycle</strong><br>";
echo $totalSignalStrength;
echo "<br><br>";

$pixelStates = [[]];

foreach ($register as $cycle => $cycleData) {
	if ($cycle === 0) {
		$pixelToCheck = 0;
		$row = 1;
		continue;
	}
	if ($pixelToCheck === 40) {
		$pixelToCheck = 0;
		$row++;
	}

	$spritePosStart = $register[$cycle-1]["X"];
	$spritePosEnd = $cycleData["X"];
	$pixelBefore = $spritePosStart - 1;
	$pixel = $spritePosStart;
	$pixelAfter = $spritePosStart +1;

	if ($pixelToCheck === $pixelBefore || $pixelToCheck === $pixel || $pixelToCheck === $pixelAfter) {
		$pixelStates[$row][$pixelToCheck] = true;
	} else {
		$pixelStates[$row][$pixelToCheck] = false;
	}
	$pixelToCheck++;
}

echo "<strong>Part 2 - Letters on display</strong>";
echo "<pre>";
foreach ($pixelStates as $row => $pixels) {
	foreach ($pixels as $pixel) {
		echo $pixel ? "#" : ".";
	}
	echo "<br>";
}
echo "</pre>";

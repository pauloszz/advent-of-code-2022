<?php

$data = nl2br(file_get_contents('data/' . basename(__FILE__, '.php') . '.txt'));
$tail1Positions = ["0,0" => true];
$tail9Positions = ["0,0" => true];
$currentPosition = [
	"head" => ["x" => 0, "y" => 0, "lastMove" => "N"],
	"tail1" => ["x" => 0, "y" => 0, "lastMove" => "N"],
	"tail2" => ["x" => 0, "y" => 0, "lastMove" => "N"],
	"tail3" => ["x" => 0, "y" => 0, "lastMove" => "N"],
	"tail4" => ["x" => 0, "y" => 0, "lastMove" => "N"],
	"tail5" => ["x" => 0, "y" => 0, "lastMove" => "N"],
	"tail6" => ["x" => 0, "y" => 0, "lastMove" => "N"],
	"tail7" => ["x" => 0, "y" => 0, "lastMove" => "N"],
	"tail8" => ["x" => 0, "y" => 0, "lastMove" => "N"],
	"tail9" => ["x" => 0, "y" => 0, "lastMove" => "N"]
];

foreach(preg_split("/((\r?\n)|(\r\n?))/", $data) as $line){
	$line = urlencode($line);
	if (strpos($line, "%3") != null) {
		$line = substr($line, 0, strpos($line, "%3"));
	}
	$line = urldecode($line);

	$direction = substr($line, 0, 1);
	$steps = substr($line, 2);

	switch (substr($line, 0, 1)) {
		case 'R':
			for ($i=0; $i < $steps; $i++) { 
				$currentPosition["head"]["x"]++;
				switchTail($currentPosition, "head", "tail1", $tail1Positions, $tail9Positions);
				for ($k=1; $k < 9; $k++) {
					switchTail($currentPosition, ("tail" . $k), ("tail" . $k+1), $tail1Positions, $tail9Positions);
				}
			}
			break;
		case 'L':
			for ($i=0; $i < $steps; $i++) { 
				$currentPosition["head"]["x"]--;
				switchTail($currentPosition, "head", "tail1", $tail1Positions, $tail9Positions);
				for ($k=1; $k < 9; $k++) { 
					switchTail($currentPosition, ("tail" . $k), ("tail" . $k+1), $tail1Positions, $tail9Positions);
				}
			}
			break;
		case 'U':
			for ($i=0; $i < $steps; $i++) { 
				$currentPosition["head"]["y"]++;
				switchTail($currentPosition, "head", "tail1", $tail1Positions, $tail9Positions);
				for ($k=1; $k < 9; $k++) {
					switchTail($currentPosition, ("tail" . $k), ("tail" . $k+1), $tail1Positions, $tail9Positions);
				}
			}
			break;
		case 'D':
			for ($i=0; $i < $steps; $i++) { 
				$currentPosition["head"]["y"]--;
				switchTail($currentPosition, "head", "tail1", $tail1Positions, $tail9Positions);
				for ($k=1; $k < 9; $k++) {
					switchTail($currentPosition, ("tail" . $k), ("tail" . $k+1), $tail1Positions, $tail9Positions);
				}
			}
			break;
	}
}

echo "<br>";
echo "<strong>Part 1 - Unique spaces visited by tail 1</strong><br>";
echo count($tail1Positions);
echo "<br><br>";

echo "<strong>Part 2 - Unique spaces visited by tail 9</strong><br>";
echo count($tail9Positions);
echo "<br>";

function switchTail(&$currentPosition, $lead, $follow, &$tail1Positions, &$tail9Positions) {
	$currentXlead = $currentPosition[$lead]["x"];
	$currentYlead = $currentPosition[$lead]["y"];
	$currentXfollow = $currentPosition[$follow]["x"];
	$currentYfollow = $currentPosition[$follow]["y"];

	if ($currentXlead > $currentXfollow+1 && $currentYlead == $currentYfollow) {
		moveRight($currentPosition, $follow, $tail1Positions, $tail9Positions);
		return;
	}

	if ($currentXlead > $currentXfollow+1 && $currentYlead > $currentYfollow) {
		moveRight($currentPosition, $follow, $tail1Positions, $tail9Positions, false);
		moveUp($currentPosition, $follow, $tail1Positions, $tail9Positions);
		return;
	}

	if ($currentXlead > $currentXfollow+1 && $currentYlead < $currentYfollow) {
		moveRight($currentPosition, $follow, $tail1Positions, $tail9Positions, false);
		moveDown($currentPosition, $follow, $tail1Positions, $tail9Positions);
		return;
	}

	if ($currentXlead < $currentXfollow-1 && $currentYlead == $currentYfollow) {
		moveLeft($currentPosition, $follow, $tail1Positions, $tail9Positions);
		return;
	}

	if ($currentXlead < $currentXfollow-1 && $currentYlead > $currentYfollow) {
		moveLeft($currentPosition, $follow, $tail1Positions, $tail9Positions, false);
		moveUp($currentPosition, $follow, $tail1Positions, $tail9Positions);
		return;
	}

	if ($currentXlead < $currentXfollow-1 && $currentYlead < $currentYfollow) {
		moveLeft($currentPosition, $follow, $tail1Positions, $tail9Positions, false);
		moveDown($currentPosition, $follow, $tail1Positions, $tail9Positions);
		return;
	}

	if ($currentYlead > $currentYfollow+1 && $currentXlead == $currentXfollow) {
		moveUp($currentPosition, $follow, $tail1Positions, $tail9Positions);
		return;
	}

	if ($currentYlead > $currentYfollow+1 && $currentXlead > $currentXfollow) {
		moveUp($currentPosition, $follow, $tail1Positions, $tail9Positions, false);
		moveRight($currentPosition, $follow, $tail1Positions, $tail9Positions);
		return;
	}

	if ($currentYlead > $currentYfollow+1 && $currentXlead < $currentXfollow) {
		moveUp($currentPosition, $follow, $tail1Positions, $tail9Positions, false);
		moveLeft($currentPosition, $follow, $tail1Positions, $tail9Positions);
		return;
	}

	if ($currentYlead < $currentYfollow-1 && $currentXlead == $currentXfollow) {
		moveDown($currentPosition, $follow, $tail1Positions, $tail9Positions);
		return;
	}

	if ($currentYlead < $currentYfollow-1 && $currentXlead > $currentXfollow) {
		moveDown($currentPosition, $follow, $tail1Positions, $tail9Positions, false);
		moveRight($currentPosition, $follow, $tail1Positions, $tail9Positions);
		return;
	}

	if ($currentYlead < $currentYfollow-1 && $currentXlead < $currentXfollow) {
		moveDown($currentPosition, $follow, $tail1Positions, $tail9Positions, false);
		moveLeft($currentPosition, $follow, $tail1Positions, $tail9Positions);
		return;
	}
}

function moveRight(&$currentPosition, $follow, &$tail1Positions, &$tail9Positions, $saveLocation=true) {
	$currentPosition[$follow]["x"]++;

	if ($follow == "tail1" && $saveLocation) {
		$tail1Positions[($currentPosition[$follow]["x"].",".$currentPosition[$follow]["y"])] = true;
	}
	if ($follow == "tail9" && $saveLocation) {
		$tail9Positions[($currentPosition[$follow]["x"].",".$currentPosition[$follow]["y"])] = true;
	}
}

function moveLeft(&$currentPosition, $follow, &$tail1Positions, &$tail9Positions, $saveLocation=true) {
	$currentPosition[$follow]["x"]--;
	
	if ($follow == "tail1" && $saveLocation) {
		$tail1Positions[($currentPosition[$follow]["x"].",".$currentPosition[$follow]["y"])] = true;
	}
	if ($follow == "tail9" && $saveLocation) {
		$tail9Positions[($currentPosition[$follow]["x"].",".$currentPosition[$follow]["y"])] = true;
	}
}

function moveUp(&$currentPosition, $follow, &$tail1Positions, &$tail9Positions, $saveLocation=true) {
	$currentPosition[$follow]["y"]++;
	
	if ($follow == "tail1" && $saveLocation) {
		$tail1Positions[($currentPosition[$follow]["x"].",".$currentPosition[$follow]["y"])] = true;
	}
	if ($follow == "tail9" && $saveLocation) {
		$tail9Positions[($currentPosition[$follow]["x"].",".$currentPosition[$follow]["y"])] = true;
	}
}

function moveDown(&$currentPosition, $follow, &$tail1Positions, &$tail9Positions, $saveLocation=true) {
	$currentPosition[$follow]["y"]--;
	
	if ($follow == "tail1" && $saveLocation) {
		$tail1Positions[($currentPosition[$follow]["x"].",".$currentPosition[$follow]["y"])] = true;
	}
	if ($follow == "tail9" && $saveLocation) {
		$tail9Positions[($currentPosition[$follow]["x"].",".$currentPosition[$follow]["y"])] = true;
	}
}

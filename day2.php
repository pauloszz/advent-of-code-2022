<?php

$data = nl2br(file_get_contents('data/' . basename(__FILE__, '.php') . '.txt'));
$points = 0;

// Opponent
// A = Rock
// B = Paper
// C = Scissors

// Me
// X = Rock = 1 point
// Y = Paper = 2 points
// Z = Scissors = 3 points


foreach(preg_split("/((\r?\n)|(\r\n?))/", $data) as $line){
	$line = str_replace(" ", "", $line);

	if (str_contains($line, "AY") || str_contains($line, "BZ") || str_contains($line, "CX")) {
		$points += 6;
	}

	if (str_contains($line, "AX") || str_contains($line, "BY") || str_contains($line, "CZ")) {
		$points += 3;
	}

	if (str_contains($line, "X")) {
		$points += 1;
	}

	if (str_contains($line, "Y")) {
		$points += 2;
	}

	if (str_contains($line, "Z")) {
		$points += 3;
	}
} 

echo "<br>";
echo "<strong>Part 1 - Total points</strong><br>";
echo $points;
echo "<br>";

// X = should Lose
// Y = should Draw
// Z = should Win

// Scoring
// Win = 6 points + played  --> A/Y, B/Z, C/X
// Draw = 3 points + played --> A/X, B/Y, C/Z
// Lose = 0 points + played --> A/Z, B/X, C/Y

$points = 0;

foreach(preg_split("/((\r?\n)|(\r\n?))/", $data) as $line){
	$line = str_replace(" ", "", $line);

	if (str_contains($line, "A")) {
		if (str_contains($line, "X")) {
			$points += 3;
		}
		if (str_contains($line, "Y")) {
			$points += 4;
		}
		if (str_contains($line, "Z")) {
			$points += 8;
		}
	}

	if (str_contains($line, "B")) {
		if (str_contains($line, "X")) {
			$points += 1;
		}
		if (str_contains($line, "Y")) {
			$points += 5;
		}
		if (str_contains($line, "Z")) {
			$points += 9;
		}
	}

	if (str_contains($line, "C")) {
		if (str_contains($line, "X")) {
			$points += 2;
		}
		if (str_contains($line, "Y")) {
			$points += 6;
		}
		if (str_contains($line, "Z")) {
			$points += 7;
		}
	}

} 

echo "<br>";
echo "<strong>Part 2 - Total points</strong><br>";
echo $points;

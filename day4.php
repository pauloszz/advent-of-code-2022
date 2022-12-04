<?php

$data = nl2br(file_get_contents('data/' . basename(__FILE__, '.php') . '.txt'));
$lines = [];
$containedCount = 0;

foreach(preg_split("/((\r?\n)|(\r\n?))/", $data) as $line){
	$line = urlencode($line);
	if (strpos($line, "%3") != null) {
		$line = substr($line, 0, strpos($line, "%3"));
	}
	$line = urldecode($line);
	$first = substr($line, 0, strpos($line, ","));
	$first_first = substr($first, 0, strpos($first, "-"));
	$first_second = substr($first, strpos($first, "-")+1);

	$second = substr($line, strpos($line, ",")+1);
	$second_first = substr($second, 0, strpos($second, "-"));
	$second_second = substr($second, strpos($second, "-")+1);

	if ($first_second < $second_first || $first_first > $second_second) {
		continue;
	}

	if ($first_first == $first_second && $first_first >= $second_first && $first_second <= $second_second) {
		$containedCount++;
		continue;
	}

	if ($first_first < $second_first && $first_second < $second_second) {
		continue;
	}

	if ($first_first == $second_first && $first_second <= $second_second) {
		$containedCount++;
		continue;
	}

	if ($first_first < $second_first && $second_second < $first_second) {
		$containedCount++;
		continue;
	}

	if ($first_first > $second_first && $first_second > $second_second) {
		continue;
	}

	if ($first_first > $second_first && $first_second < $second_second) {
		$containedCount++;
		continue;
	}

	if ($first_second == $second_second) {
		$containedCount++;
		continue;
	}

	if ($second_first == $second_second && $first_first <= $second_first && $first_second >= $second_second) {
		$containedCount++;
		continue;
	}

	if ($first_first == $second_first) {
		$containedCount++;
		continue;
	}
}

echo "<br>";
echo "<strong>Part 1 - Total contained workers</strong><br>";
echo $containedCount;
echo "<br>";

$overlap = 0;
echo "<br>";

foreach(preg_split("/((\r?\n)|(\r\n?))/", $data) as $line){
	$line = urlencode($line);
	if (strpos($line, "%3") != null) {
		$line = substr($line, 0, strpos($line, "%3"));
	}
	$line = urldecode($line);
	$first = substr($line, 0, strpos($line, ","));
	$first_first = substr($first, 0, strpos($first, "-"));
	$first_second = substr($first, strpos($first, "-")+1);

	$second = substr($line, strpos($line, ",")+1);
	$second_first = substr($second, 0, strpos($second, "-"));
	$second_second = substr($second, strpos($second, "-")+1);

	$overlapText = "TBD";

	if ($first_second < $second_first) {
		continue;
	}

	if ($first_first == $first_second && ($first_first == $second_first || $first_first == $second_second || $first_second == $second_first || $first_second == $second_second)) {
		$overlap++;
		continue;
	}

	if ($first_first > $second_first && $first_second < $second_second) {
		$overlap++;
		continue;
	}

	if ($first_first < $second_first && $first_second > $second_second) {
		$overlap++;
		continue;
	}

	if ($first_second == $second_first) {
		$overlap++;
		continue;
	}

	if ($first_first == $second_first) {
		$overlap++;
		continue;
	}

	if ($first_second == $second_second) {
		$overlap++;
		continue;
	}

	if ($first_first == $second_second) {
		$overlap++;
		continue;
	}

	if ($first_second > $second_first && $first_first < $second_first) {
		$overlap++;
		continue;
	}

	if ($second_first < $first_first && $second_second > $first_first) {
		$overlap++;
		continue;
	}

	if ($second_second < $first_first) {
		continue;
	}
}

echo "<br>";
echo "<strong>Part 2 - Total overlaps</strong><br>";
echo $overlap;
echo "<br>";

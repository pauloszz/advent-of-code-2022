<?php

$data = nl2br(file_get_contents('data/' . basename(__FILE__, '.php') . '.txt'));
$monkeyData = [];
$inspectCount = [];
$inspectCount2 = [];
$monkey = -1;

foreach(preg_split("/((\r?\n)|(\r\n?))/", $data) as $line){
	$line = urlencode($line);
	if (strpos($line, "%3C") != null) {
		$line = substr($line, 0, strpos($line, "%3C"));
	}
	if ($line === "%3Cbr+%2F%3E") {
		continue;
	}
	$line = trim(urldecode($line));

	if (substr($line, 0, 6) === "Monkey") {
		$monkey++;
		$monkeyData[$monkey] = [
			"items" => [],
			"operation" => ["operator" => "", "number" => 0],
			"test" => 0,
			"actions" => ["true" => 0, "false" => 0]
		];
		$inspectCount[$monkey] = 0;
		$inspectCount2[$monkey] = 0;
	}

	if (substr($line, 0, 9) === "Operation") {
		$monkeyData[$monkey]["operation"]["operator"] = substr($line, 21, 1);
		$monkeyData[$monkey]["operation"]["number"] = substr($line, 23);
	}

	if (substr($line, 0, 4) === "Test") {
		$monkeyData[$monkey]["test"] = (int) substr($line, 19);
	}

	if (substr($line, 0, 7) === "If true") {
		$monkeyData[$monkey]["actions"]["true"] = (int) substr($line, 25);
	}

	if (substr($line, 0, 8) === "If false") {
		$monkeyData[$monkey]["actions"]["false"] = (int) substr($line, 26);
	}

	if (substr($line, 0, 14) === "Starting items") {
		$items = substr($line, 16);
		foreach (explode(",", $items) as $item) {
			$item = (int) trim($item);
			array_push($monkeyData[$monkey]["items"], $item);
		}
	}
}

$monkeyData2 = $monkeyData;

for ($i=0; $i < 20; $i++) {
	// echo "===ROUND " . $i+1 . "===<br>";
	foreach ($monkeyData as $monkey => &$monkeyDetails) {
		// echo "Monkey " . $monkey . ":<br>";
		foreach ($monkeyDetails["items"] as $key => $item) {
			// echo "---Monkey inspects item with worry level " . $item . "<br>";
			$operartor = $monkeyData[$monkey]["operation"]["operator"];
	
			if ($monkeyData[$monkey]["operation"]["number"] === "old") {
				$operationNumber = $item;			
			} else {
				$operationNumber = (int) $monkeyData[$monkey]["operation"]["number"];
			}
			
			switch ($operartor) {
				case '*':
					$operatorText = "multiplied";
					$result = $item * $operationNumber;
					break;
				case '+':
					$operatorText = "increased";
					$result = $item + $operationNumber;
					break;
				case '-':
					$operatorText = "decreased";
					$result = $item - $operationNumber;
					break;
				case '/':
					$operatorText = "divided";
					$result = $item / $operationNumber;
					break;
			}
	
			// echo "------Worry level is " . $operatorText . " by " . $operationNumber . " to " . $result . "<br>";
			$resultWorryLevel = floor($result / 3);
			// echo "------Worry level is divided by 3 to " . $resultWorryLevel . "<br>";
			$testNumber = $monkeyData[$monkey]["test"];
			$resultDivisibleByTestNumber = ($resultWorryLevel % $testNumber === 0);
			// echo "------Worry level can" . ($resultDivisibleByTestNumber ? "" : "not") . " be divided by " . $testNumber . "<br>";
			$throwToMonkey = ($resultDivisibleByTestNumber ? $monkeyData[$monkey]["actions"]["true"] : $monkeyData[$monkey]["actions"]["false"]);
			// echo "------Item with worry level " . $resultWorryLevel . " is thrown to monkey " . $throwToMonkey . "<br>";
	
			array_push($monkeyData[$throwToMonkey]["items"], (int) $resultWorryLevel);
			unset($monkeyData[$monkey]["items"][$key]);
			$inspectCount[$monkey]++;
		}
	}
	// echo "<br>";
}

// echo "<br><pre>";
// echo var_dump($monkeyData);
// echo "</pre>";

arsort($inspectCount);
// var_dump($inspectCount);
// echo "<br>";
$activeMonkeysCount = array_slice($inspectCount, 0, 2);
$activeMonkeys = [];
foreach ($activeMonkeysCount as $count) {
	$monkey = array_search($count, $inspectCount);
	// echo "monkey " . $monkey . "<br>";
	array_push($activeMonkeys, $monkey);
}

echo "<strong>Part 1 - Monkey Business</strong><br>";
echo "Two most active monkeys: " . $activeMonkeys[0] . ", " . $activeMonkeys[1] . "<br>";
echo "Monkey business level: " . $inspectCount[$activeMonkeys[0]] * $inspectCount[$activeMonkeys[1]];
echo "<br><br>";

$superModulo = array_product( array_column( $monkeyData2, 'test' ) );

for ($i=0; $i < 10000; $i++) {
	// echo "===ROUND " . $i+1 . "===<br>";
	foreach ($monkeyData2 as $monkey => &$monkeyDetails2) {
		// echo "Monkey " . $monkey . ":<br>";
		foreach ($monkeyDetails2["items"] as $key => $item) {
			// echo "---Monkey inspects item with worry level " . $item . "<br>";
			$operartor = $monkeyData2[$monkey]["operation"]["operator"];
	
			if ($monkeyData2[$monkey]["operation"]["number"] === "old") {
				$operationNumber = (float) $item;			
			} else {
				$operationNumber = (float) $monkeyData2[$monkey]["operation"]["number"];
			}
			
			switch ($operartor) {
				case '*':
					$operatorText = "multiplied";
					$result = $item * $operationNumber;
					break;
				case '+':
					$operatorText = "increased";
					$result = $item + $operationNumber;
					break;
				case '-':
					$operatorText = "decreased";
					$result = $item - $operationNumber;
					break;
				case '/':
					$operatorText = "divided";
					$result = $item / $operationNumber;
					break;
			}
	
			// echo "------Worry level is " . $operatorText . " by " . $operationNumber . " to " . $result . "<br>";
			$resultWorryLevel = floor($result);
			// echo "------Worry level is divided by 3 to " . $resultWorryLevel . "<br>";
			$testNumber = $monkeyData2[$monkey]["test"];
			$resultDivisibleByTestNumber = ($resultWorryLevel % $superModulo === 0.0);
			// echo "------Worry level can" . ($resultDivisibleByTestNumber ? "" : "not") . " be divided by " . $testNumber . "<br>";
			$throwToMonkey = ($resultDivisibleByTestNumber ? $monkeyData2[$monkey]["actions"]["true"] : $monkeyData2[$monkey]["actions"]["false"]);
			// echo "------Item with worry level " . $resultWorryLevel . " is thrown to monkey " . $throwToMonkey . "<br>";
	
			array_push($monkeyData2[$throwToMonkey]["items"], (int) $resultWorryLevel);
			unset($monkeyData2[$monkey]["items"][$key]);
			$inspectCount2[$monkey]++;
		}
	}
	// echo "<br>";
}

arsort($inspectCount2);
// var_dump($inspectCount);
// echo "<br>";
$activeMonkeysCount = array_slice($inspectCount2, 0, 2);
$activeMonkeys = [];
foreach ($activeMonkeysCount as $count) {
	$monkey = array_search($count, $inspectCount2);
	// echo "monkey " . $monkey . "<br>";
	array_push($activeMonkeys, $monkey);
}

echo "<strong>Part 2 - Monkey Business</strong><br>";
echo "Two most active monkeys: " . $activeMonkeys[0] . ", " . $activeMonkeys[1] . "<br>";
echo "Monkey business level: " . $inspectCount2[$activeMonkeys[0]] * $inspectCount2[$activeMonkeys[1]];

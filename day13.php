<?php

$data = nl2br(file_get_contents('data/' . basename(__FILE__, '.php') . '.txt'));
$pairs = [];
$index = 1;
$elementIndex = 0;
$sumOfRightOrder = 0;

foreach(preg_split("/((\r?\n)|(\r\n?))/", $data) as $line){
	$line = urlencode($line);
	if (strpos($line, "%3C") != null) {
		$line = substr($line, 0, strpos($line, "%3C"));
	}
	if ($line === "%3Cbr+%2F%3E") {
		continue;
	}
	$line = trim(urldecode($line));

	if (!array_key_exists($index, $pairs)) {
		$pairs[$index] = [];
	}

	$lineArray = [];
	eval("\$lineArray = $line;");
	if (count($pairs[$index]) === 0) {
		$pairs[$index]["left"] = $lineArray;
	} else {
		$pairs[$index]["right"] = $lineArray;
		$index++;
	}
}

// echo "<pre>";
// var_dump($pairs);
// echo "</pre>";

foreach ($pairs as $pairKey => $pair) {
	echo "===Pair " . $pairKey . "===<br>";
	// echo "sum before: " . $sumOfRightOrder . "<br>";

	$left = $pair["left"];
	$right = $pair["right"];

	echo "Compare main pair<br>--";
	print_r($left);
	echo "<br>VS<br>--";
	print_r($right);
	echo "<br>";

	$rightOrder = comparePairs($left, $right);

	
	echo "inputs are " . ($rightOrder ? "" : "<strong>not</strong> ") . "in right order";
	if ($rightOrder) {
		$sumOfRightOrder += $pairKey;
	}

	echo "<br>";
	// // echo "sum after: " . $sumOfRightOrder . "<br>";
	echo "<br>";
	
}

echo "<strong>Part 1 - Sum of pair numbers in right order</strong><br>";
echo $sumOfRightOrder;
echo "<br><br>";

function comparePairs($left, $right): ?bool
{
	if (count($left) === 0 && count($right) > 0) {
		echo "END --- Left does not have items (ran out)<br>";
		return true;
	}
	if (count($left) > 0 && count($right) === 0) {
		echo "END --- Right does not have items (ran out)<br>";
		return false;
	}

	if (count($left) === 0 && count($right) === 0) {
		$left[0] = 0;
		$right[0] = 0;
	}

	foreach ($left as $leftKey => $leftItem) {

		for ($i=0; $i < count($leftItem); $i++) { 
			if (!is_array($leftItem[$i]) && !is_array($right[$i])) {
				echo "Left and right are both not array - Comparing numbers<br>";
				return compareNumbersInArray($leftItem, $right[$i]);
			}
		
			if (is_array($left[$i]) && is_array($right[$i])) {
				echo "Left and right are both array - Comparing arrays<br>";
				foreach ($left[$i] as $leftArray) {
					if (is_array($leftArray) && is_array($right[$i])) {
						return comparePairs($leftArray, $right[$i]);
					}
				}
			}
		
			if ((is_array($left[$i]) && !is_array($right[$i])) || (!is_array($left[$i]) && is_array($right[$i]))) {
				echo "One is not array - Comparing mixed<br>";
				if (is_array($left[$i])) {
					$leftArrayMixed = $left[$i];
					$rightArrayMixed = [$right[$i]];
					return comparePairs($leftArrayMixed, $rightArrayMixed);
				} else {
					$leftArrayMixed = [$left[$i]];
					$rightArrayMixed = $right[$i];
					return comparePairs($leftArrayMixed, $rightArrayMixed);
				}
			}
		}
	}

	// foreach ($left as $key => $item) {
	// 	echo "check: " . $counter . "<br>";

	// 	if (!is_array($item) && !is_array($right[$key])) {
	// 		echo "Compare " . $item . " vs " . $right[$key] . "<br>";
	// 		if ($item === $right[$key]) {
	// 			echo "Left equals right. Checking next<br>";

	// 			if (count($left) != count($right)) {
	// 				if ($counter === count($left) - 1) {
	// 					echo "Last check on left<br>";
	// 					if (array_key_exists($key+1, $right)) {
	// 						echo "Left ran out of items<br>";
	// 						return true;
	// 					}
	// 				}
	// 				if ($counter === count($right) - 1) {
	// 					echo "Last check on right<br>";
	// 					if (!array_key_exists($key+1, $right)) {
	// 						echo "Right ran out of items<br>";
	// 						return false;
	// 					}
	// 				}
	// 			}
	// 			$counter++;
	// 			continue;
	// 		}
	// 		if ($item < $right[$key]) {
	// 			echo "Left lower than right. In order<br>";
	// 			return true;
	// 		}
	// 		echo "Left higher than right. Out of order<br>";
	// 		return false;
	// 	}

	// 	if (is_array($item) && is_array($right[$key])) {
	// 		echo "Compare left/right arrays<br>--";
	// 		print_r($item);
	// 		echo "<br>VS<br>--";
	// 		print_r($right[$key]);
	// 		echo "<br>";
	// 		echo "comparing array in array<br>";

	// 		$result = comparePairs($item, $right[$key], $pairKey);
	// 		echo "<br>";
	// 		var_dump($result);
	// 		echo "<br>";
	// 		if ($counter === 0) {
	// 			if (null == $result) {
	// 				echo "array in array returned equal (count 0)<br>";
	// 				$counter++;
	// 				continue;
	// 			} else {
	// 				echo "array in array returned " . ($result ? "in order" : "out of order") . " (count 0)<br>";
	// 				return $result;
	// 			}
	// 		} else {
	// 			if (is_null($result)) {
	// 				echo "array in array returned equal (count > 0)<br>";
	// 				$counter++;
	// 				continue;
	// 			} else {
	// 				echo "array in array returned " . ($result ? "in order" : "out of order") . " (count > 0)<br>";
	// 				return $result;
	// 			}
	// 		}
	// 	}
	// 	if ((!is_array($item) && is_array($right[$key]) || (is_array($item) && !is_array($right[$key])))) {
	// 		if (is_array($item)) {
	// 			$leftArrayMixed = $item;
	// 			$rightArrayMixed = [$right[$key]];
	// 		} else {
	// 			$leftArrayMixed = [$item];
	// 			$rightArrayMixed = $right[$key];
	// 		}
	// 		echo "comparing mixed<br>";
	// 		$resultMixed = comparePairs($leftArrayMixed, $rightArrayMixed, $pairKey);
			
	// 		if ($counter === 0) {
	// 			if (null == $resultMixed) {
	// 				echo "array in array returned equal (count 0)<br>";
	// 				$counter++;
	// 				continue;
	// 			} else {
	// 				echo "array in array returned " . ($resultMixed ? "in order" : "out of order") . " (count 0)<br>";
	// 				return $resultMixed;
	// 			}
	// 		} else {
	// 			if (is_null($resultMixed)) {
	// 				echo "array in array returned equal (count > 0)<br>";
	// 				$counter++;
	// 				continue;
	// 			} else {
	// 				echo "array in array returned " . ($resultMixed ? "in order" : "out of order") . " (count > 0)<br>";
	// 				return $resultMixed;
	// 			}
	// 		}
	// 	}
	// }

	return false;
}

function compareNumbersInArray(array $left, array $right)
{
	$counter = 0;
	foreach ($left as $key => $item) {
		echo "---check " . $counter . "---<br>";
		echo "Compare " . $item . " vs " . $right[$key] . "<br>";
		if ($item === $right[$key]) {
			echo "Left equals right. Checking next<br>";

			if (count($left) != count($right)) {
				if ($counter === count($left) - 1) {
					echo "Last check on left<br>";
					if (array_key_exists($key+1, $right)) {
						echo "END --- Left ran out of items<br>";
						return true;
					}
				}
				if ($counter === count($right) - 1) {
					echo "Last check on right<br>";
					if (!array_key_exists($key+1, $right)) {
						echo "END --- Right ran out of items<br>";
						return false;
					}
				}
			}
			$counter++;
			continue;
		}
		if ($item < $right[$key]) {
			echo "END --- Left lower than right. In order<br>";
			return true;
		}
		echo "END --- Left higher than right. Out of order<br>";
		return false;
	}
}

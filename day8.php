<?php

$data = nl2br(file_get_contents('data/' . basename(__FILE__, '.php') . '.txt'));
$treegrid = [];
$treegridVisibility = [];
$totalTrees = 0;
$hiddenTrees = 0;
$maxTreeScenicScore = 0;

foreach(preg_split("/((\r?\n)|(\r\n?))/", $data) as $line){
	$line = urlencode($line);
	if (strpos($line, "%3") != null) {
		$line = substr($line, 0, strpos($line, "%3"));
	}
	$line = urldecode($line);

	$treesInRow = str_split($line);
	array_push($treegrid, $treesInRow);
	array_push($treegridVisibility, []);
}

foreach ($treegrid as $rowKey => $treeRow) {
	array_push($treegridVisibility[$rowKey], []);

	foreach ($treeRow as $treeKey => $tree) {	
		$treegridVisibility[$rowKey][$treeKey] = "TBD";
		$totalTrees++;

		if ($rowKey === 0 || $rowKey === count($treegrid) - 1 || $treeKey === 0 || $treeKey === count($treeRow) - 1) {
			$treegridVisibility[$rowKey][$treeKey] = true;
			continue;
		}

		$treeVisibleFromTop = true;
		for ($i=0; $i < $rowKey; $i++) { 
			if ($treegrid[$i][$treeKey] >= $tree) {
				$treeVisibleFromTop = false;
				break;
			}
		}

		$treeVisibleFromBottom = true;
		for ($i=$rowKey+1; $i <= count($treegrid)-1 ; $i++) { 
			if ($treegrid[$i][$treeKey] >= $tree) {
				$treeVisibleFromBottom = false;
				break;
			}
		}

		$treeVisibleFromLeft = true;
		for ($i=0; $i < $treeKey ; $i++) { 
			if ($treegrid[$rowKey][$i] >= $tree) {
				$treeVisibleFromLeft = false;
				break;
			}
		}

		$treeVisibleFromRight = true;
		for ($i=$treeKey+1; $i <= count($treeRow)-1 ; $i++) { 
			if ($treegrid[$rowKey][$i] >= $tree) {
				$treeVisibleFromRight = false;
				break;
			}
		}

		if ($treeVisibleFromTop || $treeVisibleFromBottom || $treeVisibleFromLeft || $treeVisibleFromRight) {
			$treegridVisibility[$rowKey][$treeKey] = true;
		} else {
			$treegridVisibility[$rowKey][$treeKey] = false;
			$hiddenTrees++;
		}
	}
}

echo "<strong>Part 1 - Total visible trees</strong><br>";
echo "Total trees: " . $totalTrees . "<br>";
echo "Hidden trees: " . $hiddenTrees . "<br>";
echo "Visible trees: " . $totalTrees - $hiddenTrees . "<br>";
echo "<br>";

foreach ($treegrid as $rowKey => $treeRow) {

	foreach ($treeRow as $treeKey => $tree) {	
		if ($rowKey === 0 || $rowKey === count($treegrid) - 1 || $treeKey === 0 || $treeKey === count($treeRow) - 1) {
			continue;
		}

		$scenicScoreTop = 0;
		for ($i=$rowKey-1; $i >= 0; $i--) { 
			$scenicScoreTop++;
			if ($treegrid[$i][$treeKey] >= $tree) {
				break;
			}
		}

		$scenicScoreBottom = 0;
		for ($i=$rowKey+1; $i <= count($treegrid)-1; $i++) { 
			$scenicScoreBottom++;
			if ($treegrid[$i][$treeKey] >= $tree) {
				break;
			}
		}

		$scenicScoreLeft = 0;
		for ($i=$treeKey-1; $i >= 0; $i--) { 
			$scenicScoreLeft++;
			if ($treegrid[$rowKey][$i] >= $tree) {
				break;
			}
		}

		$scenicScoreRight = 0;
		for ($i=$treeKey+1; $i <= count($treeRow)-1; $i++) { 
			$scenicScoreRight++;
			if ($treegrid[$rowKey][$i] >= $tree) {
				break;
			}
		}

		$treeScenicScore = $scenicScoreTop * $scenicScoreLeft * $scenicScoreBottom * $scenicScoreRight;
		if ($treeScenicScore > $maxTreeScenicScore) {
			$maxTreeScenicScore = $treeScenicScore;
		}
	}
}

echo "<strong>Part 2 - Highest scenic score in grid</strong><br>";
echo $maxTreeScenicScore;
echo "<br>";

<?php

$_fp = fopen('data/' . basename(__FILE__, '.php') . '.txt', 'r');

$_input = [];
while (!feof($_fp) && $s = trim(fgets($_fp))) $_input[] = $s;

// filled out while building the graph...
$s_key = '';
$e_key = '';
$a_key = [];
$graph = build_graph($_input);

$a_steps = [];
foreach (array_keys($a_key) as $a)
    if ($path = BFS($graph, $a, $e_key))
        $a_steps[$a] = count($path) - 1;
asort($a_steps);

echo "part 1: {$a_steps[$s_key]}\n";
echo "part 2: {$a_steps[array_key_first($a_steps)]}\n";

function _v($x, $y) // get value from input, look for special chars...
{
    global $_input, $s_key, $e_key, $a_key;
    $v = $_input[$y][$x];
    if ($v == 'S')
    {
        if (!$s_key)
        {
            $s_key = json_encode([$x,$y]);
            $a_key[$s_key] = 1;
        }
        return 'a';
    }
    elseif ($v == "E")
    {
        if (!$e_key) $e_key = json_encode([$x,$y]);
        return 'z';
    }
    elseif ($v == 'a') $a_key[json_encode([$x,$y])] = 1;

    return $v;
}

function adj($v, $v2): bool
{
    // v2 is one higher or less than v...
    return ord($v2) - ord($v) <= 1;
}

function build_graph(&$_input)
{
    $graph = []; // adjacency list...
    for ($x = 0, $w = strlen($_input[0]); $x < $w; $x++)
        for ($y = 0, $h = count($_input); $y < $h; $y++)
        {
            // key...
            $k = json_encode([$x, $y]);
            if (!isset($graph[$k])) $graph[$k] = [];
            // value...
            $v = _v($x, $y);
            // left...
            if ($x > 0 && adj($v, _v($x - 1, $y)))
                $graph[$k][] = json_encode([$x - 1, $y]);
            // right...
            if ($x < $w - 1 && adj($v, _v($x + 1, $y)))
                $graph[$k][] = json_encode([$x + 1, $y]);
            // up...
            if ($y > 0 && adj($v, _v($x, $y - 1)))
                $graph[$k][] = json_encode([$x, $y - 1]);
            // down...
            if ($y < $h - 1 && adj($v, _v($x, $y + 1)))
                $graph[$k][] = json_encode([$x, $y + 1]);
        }
    return $graph;
}

function BFS($graph, $start, $end = null, array &$visited = null): array
{
    $visited = array();
    $q = new SplQueue();
    $q->enqueue(array($start));
    $visited[$start] = 0;
    while ($q->count())
    {
        $path = $q->dequeue();
        $node = $path[count($path)-1];
        if ($node === $end) return $path;
        foreach ($graph[$node] as $adj)
        {
            if (!isset($visited[$adj]))
            {
                $visited[$adj] = count($path);
                $_p = $path;
                $_p[] = $adj;
                $q->enqueue($_p);
            }
        }
    }
    return [];
}

// <?php

// $data = nl2br(file_get_contents('data/' . basename(__FILE__, '.php') . '.txt'));
// $map = [];
// $row = 0;
// $alphabet = range("a", "z");

// foreach(preg_split("/((\r?\n)|(\r\n?))/", $data) as $line){
// 	$line = urlencode($line);
// 	if (strpos($line, "%3C") != null) {
// 		$line = substr($line, 0, strpos($line, "%3C"));
// 	}
// 	if ($line === "%3Cbr+%2F%3E") {
// 		continue;
// 	}
// 	$line = trim(urldecode($line));

// 	$col = 0;
// 	$map[$row][$col] = [];
// 	$mapStartEnd = ["start" => ["row" => 0, "col" => 0], "end" => ["row" => 0, "col" => 0]];

// 	foreach (str_split($line) as $letter) {
// 		if ($letter === "S") {
// 			$map[$row][$col]["start"] = true;
// 			$map[$row][$col]["end"] = false;
// 			$map[$row][$col]["letter"] = "a";
// 			$map[$row][$col]["height"] = array_search("a", $alphabet);
// 			$map[$row][$col]["next_moves"] = [];
// 			$mapStartEnd["start"]["row"] = $row;
// 			$mapStartEnd["start"]["col"] = $col;
// 		} elseif ($letter === "E") {
// 			$map[$row][$col]["start"] = false;
// 			$map[$row][$col]["end"] = true;
// 			$map[$row][$col]["letter"] = "z";
// 			$map[$row][$col]["height"] = array_search("z", $alphabet);
// 			$map[$row][$col]["next_moves"] = [];
// 			$mapStartEnd["end"]["row"] = $row;
// 			$mapStartEnd["end"]["col"] = $col;
// 		} else {
// 			$map[$row][$col]["start"] = false;
// 			$map[$row][$col]["end"] = false;
// 			$map[$row][$col]["letter"] = $letter;
// 			$map[$row][$col]["height"] = array_search($letter, $alphabet);
// 			$map[$row][$col]["next_moves"] = [];
// 		}
// 		$col++;
// 	}

// 	$row++;
// }

// foreach ($map as $row => $rowDetails) {
// 	foreach ($rowDetails as $col => $gridPoint) {
// 		if ($gridPoint["end"]) {
// 			continue;
// 		}
// 		if (key_exists($row - 1, $map)) {
// 			if ($map[$row - 1][$col]["height"] <= ($gridPoint["height"] + 1)) {
// 				array_push($map[$row][$col]["next_moves"], "^");
// 			}
// 		}

// 		if (key_exists($row + 1, $map)) {
// 			if ($map[$row + 1][$col]["height"] <= ($gridPoint["height"] + 1)) {
// 				array_push($map[$row][$col]["next_moves"], "V");
// 			}
// 		}

// 		if (key_exists($col - 1, $map[$row])) {
// 			if ($map[$row][$col - 1]["height"] <= ($gridPoint["height"] + 1)) {
// 				array_push($map[$row][$col]["next_moves"], "<");
// 			}
// 		}

// 		if (key_exists($col + 1, $map[$row])) {
// 			if ($map[$row][$col + 1]["height"] <= ($gridPoint["height"] + 1)) {
// 				array_push($map[$row][$col]["next_moves"], ">");
// 			}
// 		}
// 	}
// }

// echo "<pre>";
// var_dump($map);
// echo "</pre>";

// $routes = [];

// foreach ($map[$mapStartEnd["start"]["row"]][$mapStartEnd["start"]["col"]]["next_moves"] as $direction) {
// 	$route = [];
// 	$route = getNextDirection($direction, $mapStartEnd["start"]["row"], $mapStartEnd["start"]["col"], $map);

// 	foreach ($route as $routeString) {
// 		array_push($routes, $routeString);
// 	}
// }

// echo "<pre>";
// var_dump($routes);
// echo "</pre>";

// function getNextDirection(string $direction, int $row, int $col, array $map)
// {
// 	$route = [];
// 	array_push($route, $direction);
// 	if ($direction === "V") {
// 		$nextGridPointMoves = $map[$row + 1][$col]["next_moves"];
// 		foreach ($nextGridPointMoves as $key => $nextMove) {
// 			if ($nextMove === "^") {
// 				continue;
// 			} else {
// 				if (array_key_last($nextGridPointMoves) === $key) {
// 					$route[0] .= $nextMove;
// 				} else {
// 					$copy = $route[0] . $nextMove;
// 					array_push($route, $copy);

// 				}
// 			}
// 			foreach (getNextDirection($nextMove, $row + 1, $col, $map) as $nextRoute) {
// 				array_push($route, $nextRoute);
// 			}
// 		}
// 	}
// 	if ($direction === "^") {
// 		$nextGridPointMoves = $map[$row - 1][$col]["next_moves"];
// 		foreach ($nextGridPointMoves as $nextMove) {
// 			if ($nextMove === "V") {
// 				continue;
// 			} else {
// 				//do something with move;
// 			}
// 		}
// 	}
// 	if ($direction === ">") {
// 		$nextGridPointMoves = $map[$row][$col + 1]["next_moves"];
// 		foreach ($nextGridPointMoves as $nextMove) {
// 			if ($nextMove === "<") {
// 				continue;
// 			} else {
// 				//do something with move;
// 			}
// 		}
// 	}
// 	if ($direction === "<") {
// 		$nextGridPointMoves = $map[$row][$col - 1]["next_moves"];
// 		foreach ($nextGridPointMoves as $nextMove) {
// 			if ($nextMove === ">") {
// 				continue;
// 			} else {
// 				//do something with move;
// 			}
// 		}
// 	}

// 	return $route;
// }

<?php

$data = nl2br(file_get_contents('data/' . basename(__FILE__, '.php') . '.txt'));
$currentPath = "/root";
$files = [];
$folders = [];
$part1Size = 0;

foreach(preg_split("/((\r?\n)|(\r\n?))/", $data) as $line){
	$line = urlencode($line);
	if (strpos($line, "%3") != null) {
		$line = substr($line, 0, strpos($line, "%3"));
	}
	$line = urldecode($line);

	$isCommand = substr($line, 0, 1) === "$";

	if ($isCommand) {
		$command = substr($line, 2, 2);
		$commandArg = substr($line, 5);
		if ($command == "cd") {
			switch ($commandArg) {
				case "/":
					$folders["/"] = 0;
					break;
				case "..":
					$currentPath = substr($currentPath, 0, strrpos($currentPath, "/"));
					break;
				default:
					$folders[$commandArg] = 0;
					$currentPath .= "/" . $commandArg;
					break;
			}
		}
	} else {
		if (substr($line, 0, 3) != "dir") {
			array_push($files, $currentPath . "/" . $line);
		}
	}

}

foreach ($files as $file) {
	foreach ($folders as $folderName => $folderSize) {
		if ($folderName === "/") {
			$folderNameLookup = "root";
		} else {
			$folderNameLookup = $folderName;
		}
		if (str_contains($file, "/" . $folderNameLookup . "/")) {
			// echo "File " . $file . " is part of " . $folderNameLookup . "<br>";
			$lastSeparator = strrpos($file, "/")+1;
			// echo "Pos of last /: " . $lastSeparator . "<br>";
			$firstSpace = strpos($file, " ");
			// echo "Pos of space: " . $firstSpace . "<br>";
			$size = (int) substr($file, $lastSeparator, $firstSpace - $lastSeparator);
			// echo "Size of file: " . $size . "<br>";
			$folders[$folderName] += $size;
		}
	}
}

foreach ($folders as $folderSize) {
	echo "Size: " . $folderSize . "<br>";
	if ($folderSize < 100000) {
		echo "Adding " . $folderSize . " to total<br>";
		$part1Size += $folderSize;
	}
}

echo "Folders<br><pre>";
var_dump($folders);
echo "</pre>";

echo "Files<br><pre>";
var_dump($files);
echo "</pre>";

echo "<strong>Part 1 - Size of al folders below 100000</strong><br>";
echo $part1Size;
echo "<br>";

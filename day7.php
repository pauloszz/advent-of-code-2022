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
					$folders["/root"] = 0;
					break;
				case "..":
					$currentPath = substr($currentPath, 0, strrpos($currentPath, "/"));
					break;
				default:
					$currentPath .= "/" . $commandArg;
					$folders[$currentPath] = 0;
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
			$folderNameLookup = "/root";
		} else {
			$folderNameLookup = $folderName;
		}
		if (str_contains($file, $folderNameLookup . "/")) {
			$lastSeparator = strrpos($file, "/")+1;
			$firstSpace = strpos($file, " ");
			$size = (int) substr($file, $lastSeparator, $firstSpace - $lastSeparator);
			$folders[$folderName] += $size;
		}
	}
}

foreach ($folders as $folderSize) {
	if ($folderSize <= 100000) {
		$part1Size += $folderSize;
	}
}

echo "<strong>Part 1 - Size of all folders below 100000</strong><br>";
echo $part1Size;
echo "<br><br>";

$totalSpace = 70000000;
$requiredSpace = 30000000;
$usedSpace = $folders["/root"];
$freeSpace = $totalSpace - $usedSpace;
$spaceToFreeUp = $requiredSpace - $freeSpace;
$possibleFolders = [];

foreach ($folders as $folderName => $folderSize) {
	if ($folderSize >= $spaceToFreeUp) {
		$possibleFolders[$folderName] = $folderSize;
	}
}

$folderToDeleteSize = min($possibleFolders);
$folderTotDeleteName = array_keys($possibleFolders, $folderToDeleteSize, true);

echo "<strong>Part 2 - Folder to free up space</strong><br>";
echo "Folder: " . $folderTotDeleteName[0] . "<br>";
echo "Size: " . $folderToDeleteSize;
echo "<br><br>";

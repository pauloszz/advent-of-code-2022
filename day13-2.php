<?php

$_fp = fopen("data/day13.txt", "r");

$P = []; $i = 1; $sum = 0;

while (!feof($_fp))
{
    array_push($P, $L = trim(fgets($_fp)), $R = trim(fgets($_fp)));
    fgets($_fp); // blank
    if (packet_compare($L, $R) < 1) $sum += $i;
    $i++;
}

array_push($P, '[[2]]', '[[6]]');
usort($P, "packet_compare");
$decoder = (array_search("[[2]]", $P) + 1) * (array_search("[[6]]", $P) + 1);

echo "part 1: {$sum}\n";
echo "part 2: {$decoder}\n";

function packet_compare($l, $r)
{
    $l = json_decode($l);
    $r = json_decode($r);

    if (is_int($l) && is_int($r)) return $l <=> $r;

    if (is_int($l) && is_array($r)) $l = [$l];
    if (is_array($l) && is_int($r)) $r = [$r];

    while (count($l) && count($r))
    {
        $_l = json_encode(array_shift($l));
        $_r = json_encode(array_shift($r));
        if ($result = packet_compare($_l, $_r)) return $result;
    }
    return count($l) - count($r);

}

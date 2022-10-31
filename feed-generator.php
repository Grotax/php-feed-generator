#! php
<?php

use FeedIo\FeedIo;
use FeedIo\Feed;

require_once __DIR__ . '/vendor/autoload.php';



function generateFeed(int $amount, int $start){

    $feedIo = new FeedIo;
    $feed = new Feed;

    # offset
    $amount += $start;

    $feed->setTitle('Test Feed');

    for ($i=$start; $i < $amount; $i++) { 
        $item = $feed->newItem();
        $item->setTitle('Item ' . $i);
        $item->setLastModified(new \DateTime());
        $item->setLink('https://feed-io.net/item/' . $i);
        $item->setContent("Hope you like the code you are reading");
        $item->setSummary('my summary');
        $feed->add($item);
    }



    return $feedIo->toAtom($feed);
}

function createFeedFile($amount, $start, $file){
    $myfile = fopen($file, "w");
    $feed = generateFeed($amount, $start);
    fwrite($myfile, $feed);
}

$arguments = getopt("a:s:f:h::");

if (isset($arguments["h"])){
    $help=<<<EOT
    -a provide the amount of items e.g. -a 100
    -s provide the start number of the first item, should be smaller than -a e.g -s 50, default 0
    -f provide a path to the resulting feed file
    EOT;

    echo "Help for feed-generator.php" . PHP_EOL;
    echo $help . PHP_EOL;
    exit;
}


if (! isset($arguments["a"])){
    echo "-a is required to define the amount of items e.g. -a 100" . PHP_EOL;
    exit;
}

if (! isset($arguments["f"])){
    echo "-f is required; provide a path to the resulting feed file" . PHP_EOL;
    exit;
}

$start = $arguments["s"] ?? 0;

echo "Creating Feed with: " . $arguments["a"] . " items, starting with item nr. " . $start . PHP_EOL;
echo "File name: " . $arguments["f"] . PHP_EOL;
createFeedFile($arguments["a"], $start, $arguments["f"]);
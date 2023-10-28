#! php
<?php

use FeedIo\FeedIo;
use FeedIo\Feed;

require_once __DIR__ . '/vendor/autoload.php';



function generateFeed(int $amount, int $start, bool $oldDate){

    $feedIo = new FeedIo;
    $feed = new Feed;

    # offset
    $amount += $start;

    $feed->setTitle('Test Feed');



    for ($i=$start; $i < $amount; $i++) { 
        $item = $feed->newItem();
        $item->setTitle('Item ' . $i);
        if ($oldDate){
            $item->setLastModified(new \DateTime("-3 days"));
        } else {
            $item->setLastModified(new \DateTime());
        }
        $item->setLink('http://localhost:8090/item/' . $i);
        $item->setPublicId('http://localhost:8090/item/' . $i);
        $item->setContent("Hope you like the code you are reading");
        $item->setSummary('Title Number ' . $i);
        $feed->add($item);
    }



    return $feedIo->toAtom($feed);
}

function createFeedFile($amount, $start, $file, $oldDate){
    $myfile = fopen($file, "w");
    $feed = generateFeed($amount, $start, $oldDate);
    fwrite($myfile, $feed);
}

$arguments = getopt("a:s:f:o:h::");

if (isset($arguments["h"])){
    $help=<<<EOT
    -a provide the amount of items e.g. -a 100
    -s provide the start number of the first item, should be smaller than -a e.g -s 50, default 0
    -f provide a path to the resulting feed file
    -o (optional) use an older date for the items, -o true; -o yes; -o y
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

# prevent warning from php
if (isset($arguments["o"])){
    $oldDate = (bool) $arguments["o"] ?? false;
} else {
    $oldDate = false;
}


echo "Creating Feed with: " . $arguments["a"] . " items, starting with item nr. " . $start . PHP_EOL;
echo "Using an older date: " . var_export($oldDate, true) . PHP_EOL;
echo "File name: " . $arguments["f"] . PHP_EOL;

createFeedFile($arguments["a"], $start, $arguments["f"], $oldDate);
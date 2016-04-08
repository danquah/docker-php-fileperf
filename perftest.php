#!/usr/bin/env php
<?php

if (count($argv) < 2) {
  echo "Syntax ${argv[0]} <directory> <number of files to create> <size in kb>\n";
  exit;
}

$dir = $argv[1];
$num_files = $argv[2];
$file_size = $argv[3];

echo "Creating $num_files files {$file_size}KB each in $dir, total size " . number_format($file_size * $num_files) . "KB \n";

$buffer = str_repeat('x', 1024);
$start = microtime(TRUE);
foreach (range(0, $num_files) as $number) {
  // Create file, truncate if exists.
  $file_path = gen_filepath($dir, $number);
  $fh = fopen($file_path, 'w+');

  if (!$fh) {
    die("Could not create file $file_path\n");
  }

  for ($i = 0; $i < $file_size; $i++) {
    fwrite($fh, $buffer);
  }
}
$duration = round(microtime(TRUE) - $start, 2);
echo "Created $num_files files in $duration ms\n";

// Clear phps file stat cache.
clearstatcache();
$start = time();
foreach (range(0, $num_files) as $number) {
  stat(gen_filepath($dir, $number));
}

$duration = round(microtime(TRUE) - $start, 2);
echo "Got status of $num_files files in $duration ms \n";

foreach (range(0, $num_files) as $number) {
  file_get_contents(gen_filepath($dir, $number));
}

$duration = round(microtime(TRUE) - $start, 2);
echo "Read $num_files files in $duration ms \n";


function gen_filepath($dir, $number) {
  return "$dir/file-$number";
}

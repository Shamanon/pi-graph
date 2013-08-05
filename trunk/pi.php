#!/usr/bin/php
<?php
/** $Id$
 ** $Author$
 ** $Date$
 ** $Revision$ 
 ** $URL$
 *
 * Pi Graph - Written by Joshua Besneatte
 * Completely free, link to my blog if you like it or send me some bitcoins
 * 
 * bitcoin:12WshrtpEf9TXtLbv8gTjeM5DPBcosbZVZ
 *
 *
 *  find a pattern in PI?
 *  let each digit move the cursor, starts at 0,0
 *  evens move x, odds move y
 *  if number is 4 or 5 don't move
 *  0 and 1 = -2; 2,3 = -1; 6,7 = +1; 8,9 = +2 
 *  this is the command line interface version, probably won't work on a webserver
 * 
 * this version accepts command line arguments, eg 
 * ./pi [number of digits to graph] [display output] [start digit]
 * 
 * or you can use zero-reset mode and it will reset z,y to zero at selected intervals
 * ./pi --zero-reset [reset threshold]
 * this is not done yet
 *
 */

if(array_search('--zero-reset',$argv)){
	echo "this still needs to be done\n";
	print_r($argv);
	exit;
}

$size = null;
$file = null;
$display = null;
$delete = null;
$startpoint = 0;

echo "Welcome to pi-plot\n\r";

if(isset($argv[1])) $size = $argv[1];

while(!is_numeric($size)){
	echo "how many digits of pi?\n";
	$size = trim(fgets(STDIN));
}

$file = $size.".txt";

if(isset($argv[2])) $display = $argv[2]; 

if(isset($argv[3])){ 
	$startpoint = $argv[3];
	echo "starting at $startpoint\n";
	$file = $startpoint."-".($startpoint+$size).'.txt';
}

if(file_exists($file)){ 
	
	while($delete !== 'y' && $delete !== 'n'){
		echo "the file $file exists, delete file?\n";
		$delete = trim(fgets(STDIN));
	}
	if($delete == 'y') unlink($file);
	else{
		echo "ok, using existing file\n";
		`xgraph $file &`;
		exit;
	}
}

while($display !== 'y' && $display !== 'n'){
	echo "display output while writing, y/n?\n";
	$display = trim(fgets(STDIN));
}

$x = "0";
$y = "0";

require_once("digits.php");

echo "$size digits of pi loaded\n";
echo "writing x,y coordinates to $file\n";
echo "display output: $display\n";

sleep(1);

for($i=$startpoint;$i<=$size+$startpoint;$i++){
	$v = substr($pi,$i,1);
	if(is_numeric($v)){
		switch($v){
			case 0: 
				$x = $x - 2;
				break;
			case 1:
				$y = $y - 2;
				break;
			case 2:
				$y--;
				break;
			case 3:
				$x--;
				break;
			case 6:
				$x++;
				break;
			case 7: 
				$y++;
				break;
			case 8:
				$x = $x + 2;
				break;
			case 9:
				$y = $y + 2;
				break;
		}
		if($display == 'y') echo "$x $y\n";
		file_put_contents($file,"$x $y\n",FILE_APPEND);
	}
}

`xgraph $file &`;
?>
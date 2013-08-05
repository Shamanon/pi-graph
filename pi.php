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

$size = null;
$file = null;
$display = null;
$delete = null;
$startpoint = 0;
$rezero = false;

echo "Welcome to pi-plot\n";

if(array_search('--zero-reset',$argv)){
	$size = isset($argv[2]) ? $argv[2] : 1000000;
	$argv[3] = 0;
	$rezero = true;
	echo "Zero reset after $size digits\n";
}else if(isset($argv[1])) $size = $argv[1];

while(!is_numeric($size)){
	echo "how many digits of pi?\n";
	$size = trim(fgets(STDIN));
}

if(isset($argv[2])) $display = $argv[2]; 

if(isset($argv[3])){ 
	$startpoint = $argv[3];
	$file = "files/".$startpoint."-".($startpoint+$size).'.txt';
}else $file = "files/".$size.".txt";

if(file_exists($file)){ 
	
	if($rezero) $delete = 'y';
	
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

echo "starting at $startpoint\n";
echo "$size digits of pi out of ".strlen($pi)." digits have been loaded\n";
echo "writing x,y coordinates to $file\n";
echo "display output: $display\n";

sleep(1);

$filestring = $file;

$cap = $size+$startpoint;

a:
echo "new values: startpoint = $startpoint, size = $size, cap = $cap\n\n";
for($i=$startpoint;$i<=$cap;$i++){
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
				$x--;
				break;
			case 3:
				$y--;
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
		if($display == 'y') echo "digit #$i is $v making the coordinates $x $y\n";
		file_put_contents($file,"$x $y\n",FILE_APPEND);
		if($rezero && $i == $size && $startpoint < 10000000){
			break;
		}	
	}
}

if($rezero && $startpoint < 10000000){
	$x = 0;
	$y = 0;
	echo "\n moving to next file\n";
	echo "\ncurrent values: startpoint = $startpoint, size = $size, cap = $cap\n";
	$startpoint = $startpoint + $size;
	$cap = $size + $startpoint;
	$file = "files/".$startpoint."-".($startpoint+$size).'.txt';
	if(file_exists($file)) unlink($file);
	echo "\nstarting at $startpoint\n";
	echo "writing x,y coordinates to $file\n";
	echo "display output: $display\n";
	$filestring = "$filestring $file";
	goto a;
}
`xgraph -ng $filestring &`;
?>

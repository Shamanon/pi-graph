#!/usr/bin/php
<?php
/** $Id$
 ** $Author$
 ** $Date$
 ** $Revision$ 
 ** $URL$
 *
 * Big Pi Graph - Written by Joshua Besneatte
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
 * this version accepts no arguments, it generates a file for every 1 million digits
 * pulled from the digits file, xgraph then graphs each block in a different color
 *
 */

$size = null;
$file = null;
$display = null;
$delete = null;
$startpoint = 0;
$huge = false; // use the huge file ignored by svn, must be manually downloaded
$huge_limit = 100000000;

if(isset($argv[1]) && $argv[1] == "huge") $huge = true;
if(isset($argv[2])) $huge_limit = $argv[2];


echo "\nWelcome to bigpi!\n\nI graph Pi according to the besneatte algorithm, one million plots per file\n";
sleep(1);
echo "\nI am about to plot all points of pi, using the ".(!$huge?"standard":"huge")." size file\nExisting files will be overwritten.\nPress control + c to cancel\n";
if($huge) echo "\nI will stop after $huge_limit digits\n";
echo "\nStarting in: 5";
for($i=5;$i--;$i!=0){
	echo " ".$i;
	sleep(1);
}

echo "\n\n";

$file = !$huge ? "files/bigpie.txt" : "files/hugepie.txt"; 

if(file_exists($file)) unlink($file);

$x = "0";
$y = "0";

$block = 0;
$fnum = 0;
$fnumf = str_pad($fnum, 3, '0', STR_PAD_LEFT);
$count = 0;
$filestring = $file.$fnumf;
$total_count = 0;

$handle = @fopen(!$huge ? "digits" : "huge", "r");

if(file_exists($file.$fnumf)) unlink($file.$fnumf);

echo "Writing to file $file$fnumf\n";
sleep(2);

if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
		echo "\nProcessing block: $block\n";
		$block++;
		trim($buffer);
        $pi = str_split($buffer);
        foreach($pi as $k => $v){
			if(is_numeric($v)){
				$count++;
				echo "$v";
				$total_count++;
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
				file_put_contents($file.$fnumf,"$x $y\n",FILE_APPEND);
				if($count == 1000000){
					$fnum++;
					$fnumf = str_pad($fnum, 3, '0', STR_PAD_LEFT);
					$filestring = "$filestring $file".$fnumf;
					if(file_exists($file.$fnumf)) unlink($file.$fnumf);
					$count=0;
					echo "\n\nWriting to next file $file$fnumf\n\n";
					echo "\n\nProcessed $total_count digits so far!\n\n";
					sleep(2);
				}
				if($total_count == $huge_limit){
					echo "\n\n$total_count digits processed! Launching xgraph\n";
					goto a;
				}	
			}
		}
	}

    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}

a:

`xgraph $filestring &`;
?>

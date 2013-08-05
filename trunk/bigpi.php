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

echo "Welcome to big pi\n an attempt to plot over 1000000 places";

$file = "bigpie.txt";

if(file_exists($file)) unlink($file);

$x = "0";
$y = "0";

$block = 0;
$fnum = 0;
$fnumf = str_pad($fnum, 3, '0', STR_PAD_LEFT);
$count = 0;
$filestring = $file.$fnumf;

$handle = @fopen("digits", "r");

if(file_exists($file.$fnumf)) unlink($file.$fnumf);

if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
		echo "Processing block: $block\n";
		$block++;
		trim($buffer);
        echo $buffer."\n";
        $pi = str_split($buffer);
        foreach($pi as $v){
			if(is_numeric($v)){
				$count++;
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
				file_put_contents($file.$fnumf,"$x $y\n",FILE_APPEND);
				if($count == 1000000){
					$fnum++;
					$fnumf = str_pad($fnum, 3, '0', STR_PAD_LEFT);
					$filestring = "$filestring $file".$fnumf;
					if(file_exists($file.$fnumf)) unlink($file.$fnumf);
					$count=0;
				}	
			}
		}
	}

    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}

`xgraph $filestring &`;
?>

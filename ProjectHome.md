# In Loving Memory of John Edward "Jed" Larimer #
## Hope you found your place in that Pi in the Sky ##

I am always wondering about patterns in Pi, and I got to thinking about how I could make a fractal type drawing of pi. So, I took Pi to 10 million places as a string, minus the dot, and created x,y plot coordinates using the following formula:

start with the x,y coords as 0,0

now, take every digit of Pi, and convert it into an operator. even
numbers operate the X axis, odd numbers Y as follows

0 : x-2<br>
1 : y-2<br>
2 : x-1<br>
3 : y-1<br>

( 4 and 5 are midpoints and do nothing )<br>
<br>
6 : x+1<br>
7 : y+1<br>
8 : x+2<br>
9 : y+2<br>

for example:<br>
<br>
the first digit is 3: <br>
subtract 1 from y and now the x,y coords are 0,-1<br>
<br>
the next digit is 1: <br>
subtract 2 from y and now the x,y coords are 0,-3<br>
<br>
the next digit is 4:<br>
do nothing, and now the x,y coords are 0,-3<br>
<br>
and so on through each digit of pi<br>
<br>
each x,y combo is written to a file<br>
<br>
I then graphed the output via xgraph... and here you go, I got up to 10,000,000 digits of pi<br>
<br>
<a href='http://artofconfusion.org/pi/'>http://artofconfusion.org/pi/</a>

The most workable version uses php5 command line interface, but it can be modified for web use.<br>
<br>
requires php5 and xgraph<br>
<br>
<h3>pi.php</h3>
this script accepts arguments <a href='number.md'>of digits</a> <a href='display.md'>output y/n</a> <a href='start.md'>point</a>
reads pi from digits.php, currently 10 million digits<br>
<br>
<h3>bigpi.php</h3>
this script runs automatically, and overwrites all existing files with current computations.<br>
this creates a new file for every 1 million entries which then displays as a different color in xgraph<br>
reads pi from digits, currently 10 million digits<br>
<br>
or you can download billion million digits from somewhere and use the huge option<br>
put pi data in a file called huge<br>
<br>
once the script has been run, x graph can be pointed directly to the created files. you only need to run the script to rebuild or make new files.<br>
<br>
after you run bigpi, you can run xgraph bigpie.txt
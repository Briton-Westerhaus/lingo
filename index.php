<?php
session_start();
?>
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title> Mediaclambake - Lingo by Briton Westerhaus.</title>
<meta name="description" content="An online version of the Lingo gameshow." />
<meta name="keywords" content="media, entertainment, fun, games" />
<meta name="author" content="Briton Westerhaus" />
<p class="titlebar"></p>
<div class="content">
<form action="index.php" method="post">
<table border="border">
<tr>
	<th colspan = "5">Sent from Server to Player</th>
	<th colspan = "5">Entered by Player</th>
</tr>
<?php
if(!IsSet($_SESSION[server])):
initializegamearray();
View();
else:
$guessarray = posttostandard($_POST);
processguess($guessarray);
endif;
function initializegamearray(){
	$dictionary = file("media/words5.txt");
	$randfinish = rand(1, filesize("media/words5.txt")/7);
	$word = $dictionary[$randfinish];
	$_SESSION[server] =  array(
	array('<font color = "FF0000">' . strtoupper($word{0}) . '<font />',"&nbsp","&nbsp","&nbsp","&nbsp"),
	array("&nbsp","&nbsp","&nbsp","&nbsp","&nbsp"),
	array("&nbsp","&nbsp","&nbsp","&nbsp","&nbsp"),
	array("&nbsp","&nbsp","&nbsp","&nbsp","&nbsp"),
	array("&nbsp","&nbsp","&nbsp","&nbsp","&nbsp"),
	array("&nbsp","&nbsp","&nbsp","&nbsp","&nbsp"));
	$_SESSION[user] = array(
	array('<input type = "text" name = "0" size = "1" maxlength = "1">','<input type = "text" name = "1" size = "1" maxlength = "1">','<input type = "text" name = "2" size = "1" maxlength = "1">','<input type = "text" name = "3" size = "1" maxlength = "1">','<input type = "text" name = "4" size = "1" maxlength = "1">'),
	array("&nbsp","&nbsp","&nbsp","&nbsp","&nbsp"),
	array("&nbsp","&nbsp","&nbsp","&nbsp","&nbsp"),
	array("&nbsp","&nbsp","&nbsp","&nbsp","&nbsp"),
	array("&nbsp","&nbsp","&nbsp","&nbsp","&nbsp"),
	array("&nbsp","&nbsp","&nbsp","&nbsp","&nbsp"));
	$_SESSION[word] = trim($word);
	$_SESSION[guesses] = 0;
	$_SESSION[gameover] = 0;
}
function posttostandard($parray){
	if(sizeof($parray) == 5):
		return $parray;
	else:
	$toreturn = array();
	while($toreturn[] = next($parray)):
	endwhile;
	return $toreturn;
	endif;
}
function processguess($guessarray){
	if(strtolower($_SESSION[word]) == strtolower(implode($guessarray))):
		echo '<h3 align = "center">You won!</h3>';
		$_SESSION[gameover] = 1;
	endif;
	$temp = $_SESSION[guesses];
	$_SESSION[guesses]++;
	$_SESSION[user][$_SESSION[guesses]] = $_SESSION[user][$temp];
	$_SESSION[user][$temp] = $guessarray;
	if($_SESSION[guesses] == 5 && strtolower($_SESSION[word]) != strtolower(implode($guessarray))):
		echo '<h3 align ="center">You lost. </h3><br />';
		echo "The word was $_SESSION[word].";
		$_SESSION[gameover] = 1;
	endif;
	$temp = 0;
	while($temp < 5):
		$thearray[] = $_SESSION[word]{$temp};
		$temp++;
	endwhile;
	serverdisplay($guessarray, $thearray);
}
function serverdisplay($guesses, $wordarray){
	$num = $_SESSION[guesses];
	$temp = 0;
	$temptwo = 0;
	while($temp < 5):
		while($temptwo < 5):
			if($temp == $temptwo && strtolower($wordarray[$temptwo]) == strtolower($guesses[$temp])):
				$_SESSION[server][$num][$temp] = ('<font color = "ff0000">' . strtoupper($guesses[$temp]) . '<font />');
			endif;
			if(strtolower($wordarray[$temptwo]) == strtolower($guesses[$temp]) && $temp != $temptwo && $_SESSION[server][$num][$temp] == "&nbsp" && strtolower($wordarray[$temptwo]) != strtolower($guesses[$temptwo])):
				$_SESSION[server][$num][$temp] = ('<font color = "0000ff">' . strtoupper($guesses[$temp]) . '<font />');
			endif;
			$temptwo++;
		endwhile;
		if($_SESSION[server][$num][$temp] == "&nbsp"):
			$_SESSION[server][$num][$temp] = strtolower($guesses[$temp]);
		endif;
		$temptwo = 0;
		$temp++;
	endwhile;
}
$server = $_SESSION[server];
$user = $_SESSION[user];
echo "<tr>";
	$temparray = current($server);
	foreach($temparray as $temp)
		echo "<td>$temp</td>";
	$temparray = current($user);
	foreach($temparray as $temp)
		echo "<td>$temp</td>";
echo "</tr>";
echo "<tr>";
	$temparray = next($server);
	foreach($temparray as $temp)
		echo "<td>$temp</td>";
	$temparray = next($user);
	foreach($temparray as $temp)
		echo "<td>$temp</td>";
echo "</tr>";
echo "<tr>";
	$temparray = next($server);
	foreach($temparray as $temp)
		echo "<td>$temp</td>";
	$temparray = next($user);
	foreach($temparray as $temp)
		echo "<td>$temp</td>";
echo "</tr>";
echo "<tr>";
	$temparray = next($server);
	foreach($temparray as $temp)
		echo "<td>$temp</td>";
	$temparray = next($user);
	foreach($temparray as $temp)
		echo "<td>$temp</td>";
echo "</tr>";
echo "<tr>";
	$temparray = next($server);
	foreach($temparray as $temp)
		echo "<td>$temp</td>";
	$temparray = next($user);
	foreach($temparray as $temp)
		echo "<td>$temp</td>";
echo "</tr>";
echo "<tr>";
	$temparray = next($server);
	foreach($temparray as $temp)
		echo "<td>$temp</td>";
	$temparray = next($user);
	foreach($temparray as $temp)
		echo "<td>$temp</td>";
echo "</tr>";
echo "</table>";
echo "<br />";
if($_SESSION[gameover] == 0):
	echo '<input type="submit" value="Take a guess!" />';
endif;
echo "</form>";
if($_SESSION[gameover] == 1):
	unset($_SESSION[user]);
	unset($_SESSION[server]);
	unset($_SESSION[word]);
	unset($_SESSION[guesses]);
	echo '<form action="index.php" method="post">';
	echo '<input type="submit" value="Play Again?" />';
	echo '</form>';
endif;
?>
<h3><center>How to play:</center></h3></br>
<p>A <font color = "ff0000">RED UPPER CASE</font> letter means that the letter you guessed is the right letter in the right place.</p>
<p>A <font color = "0000ff">BLUE UPPER CASE</font> letter means that the letter you guessed is in the word, but not at the position you guessed.</p>
<p>A black lower case letter means the letter you guessed is not in the word at all.</p>
<p>You have five guesses to get the word right.  If you don't get the word right after the fifth guess, you lose!</p>
<br />
<a href="comment.php?category=games&content=Lingo">Comment</a>
<br />
</div>
</center>
</div>
</body>
</html>
<?php
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Briton Westerhaus - Lingo</title>
		<meta name="description" content="An online version of the Lingo gameshow." />
		<meta name="keywords" content="media, entertainment, fun, games" />
		<meta name="author" content="Briton Westerhaus" />
		<link rel="stylesheet" type="text/css" href="default.css" />
		<script type="text/javascript" src="default.js"></script>
	</head>
	<body>
			<div class="content">
				<h2>Lingo</h2>
				<button class="help" onclick="showModal()">?</button>
				<br />
				<form action="index.php" method="post" name="gameForm" onsubmit="return validateForm();">
				<table>
					<tr>
						<th colspan = "5">Clues</th>
						<th colspan = "5">Guesses</th>
					</tr>
				<?php
					if (!isset($_SESSION['server'])) {
						initializegamearray();
					} else {
						$guessarray = posttostandard($_POST);
						processguess($guessarray);
					}

					function initializegamearray(){
						$cipher = $cipher = "aes-128-cbc";
						$ivlen = openssl_cipher_iv_length($cipher);

						$file = fopen("words.txt","r");

						$iv = fread($file, $ivlen);

						$wordCount = (filesize("words.txt") - $ivlen) / 22;
					
						$rand = rand(0, $wordCount);
					
						fseek($file, $ivlen + $rand * 22);
						$encrypted = fread($file, 22) . "==";

						$word = openssl_decrypt($encrypted, $cipher, "Briton Westerhaus Lingo", 0, $iv);
						echo $word . "<br />";

						$_SESSION['server'] =  [];
						$_SESSION['server'][] = ['<span class="correct">' . strtoupper($word{0}) . '</span>',"&nbsp;","&nbsp;","&nbsp;","&nbsp;"];
						for ($i = 1; $i < 6; $i++) {
							$temparray = [];
							for ($j = 0; $j < 5; $j++) {
								$temparray[] = '&nbsp;';
							}
							$_SESSION['server'][] = $temparray;
						}

						$_SESSION['user'] = [];
						$temparray = [];
						for ($i = 0; $i < 5; $i++) {
							$temparray[] = '<input type="text" name="' . $i . '" size="1" maxlength="1" id="input' . $i . '" onselect="selectInput(' . $i . ')" onclick="selectInput(' . $i . ')" oninput="inputChanged(this)">';
						}
						$_SESSION['user'][] = $temparray;

						for ($i = 1; $i < 6; $i++) {
							$temparray = [];
							for ($j = 0; $j < 5; $j++) {
								$temparray[] = '&nbsp;';
							}
							$_SESSION['user'][] = $temparray;
						}
						$_SESSION['word'] = $word;
						$_SESSION['guesses'] = 0;
						$_SESSION['gameover'] = 0;
					}

					function posttostandard($parray){
						if (sizeof($parray) == 5) {
							return $parray;
						} else {
							$toreturn = array();
							while ($toreturn[] = next($parray)) {} //This is a weird way to do it, but it's okay for now
							return $toreturn;
						}
					}

					function processguess($guessarray){
						if (strtolower($_SESSION['word']) == strtolower(implode($guessarray))) {
							echo '<h3 align = "center">You won!</h3>';
							$_SESSION['gameover'] = 1;
						}
						$temp = $_SESSION['guesses'];
						$_SESSION['guesses']++;
						$_SESSION['user'][$_SESSION['guesses']] = $_SESSION['user'][$temp];
						$_SESSION['user'][$temp] = $guessarray;
						if ($_SESSION['guesses'] == 5 && strtolower($_SESSION['word']) != strtolower(implode($guessarray))) {
							echo '<h3 align ="center">You lost. </h3><br />';
							echo "The word was $_SESSION[word].";
							$_SESSION['gameover'] = 1;
						}
						$temp = 0;
						for ($i = 0; $i < 5; $i++) {
							$thearray[] = $_SESSION['word'][$i];
						}
						serverdisplay($guessarray, $thearray);
					}

					function serverdisplay($guesses, $wordarray){
						$num = $_SESSION['guesses'];
						$guesses = array_pad($guesses, 5, '&nbsp;');

						for ($i = 0; $i < 5; $i++) {
							for ($j = 0; $j < 5; $j++) {
								if ($i == $j && strtolower($wordarray[$j]) == strtolower($guesses[$i])) {
									$_SESSION['server'][$num][$i] = ('<span class="correct">' . strtoupper($guesses[$i]) . '</span>');
								}
								if (strtolower($wordarray[$j]) == strtolower($guesses[$i]) && $i != $j && $_SESSION['server'][$num][$i] == "&nbsp;" && strtolower($wordarray[$j]) != strtolower($guesses[$j])) {
									$_SESSION['server'][$num][$i] = ('<span class="wrong-place">' . strtoupper($guesses[$i]) . '</span>');
								}
							}
							if ($_SESSION['server'][$num][$i] == "&nbsp;") {
								$_SESSION['server'][$num][$i] = strtolower($guesses[$i]);
							}
						}
					}

					$server = $_SESSION['server'];
					$user = $_SESSION['user'];
					for ($i = 0; $i < 6; $i++):
						echo "<tr>";
						$temparray = $server[$i];
						foreach($temparray as $temp)
							echo "<td>$temp</td>";
						$temparray = $user[$i];
						foreach($temparray as $temp)
							echo "<td>$temp</td>";
						echo "</tr>";
					endfor;
					echo "</table>";
					echo "<br />";
					if($_SESSION['gameover'] == 0):
						echo '<input type="submit" value="Take a guess!" />';
					else:
						unset($_SESSION['user']);
						unset($_SESSION['server']);
						unset($_SESSION['word']);
						unset($_SESSION['guesses']);
						echo '<input type="submit" value="Play Again?" name="playAgain"/>';
					endif;
					echo '</form>';
				?>
			</div>
			<div id="ModalContainer">
				<div class="modal">
					<h3>How to play</h3>
					<p>A <span class="correct">red upper case</span> letter means that the letter you guessed is the right letter in the right place.</p>
					<p>A <span class="wrong-place">blue upper case</span> letter means that the letter you guessed is in the word, but not at the position you guessed.</p>
					<p>A black lower case letter means the letter you guessed is not in the word at all.</p>
					<p>You have five guesses to get the word right.  If you don't get the word right after the fifth guess, you lose!</p>
					<button onclick="hideModal()">&#10004;</button>
				</div>
			</div>
		</div>
	</body>
</html>

<?php
	session_start();
	if (isset($_POST['numLetters']) &&  is_numeric($_POST['numLetters'])) {
		$_SESSION['numLetters'] = $_POST['numLetters'];
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Briton Westerhaus - Lingo</title>
		<meta name="description" content="An online version of the Lingo gameshow." />
		<meta name="keywords" content="media, entertainment, fun, games" />
		<meta name="author" content="Briton Westerhaus" />
		<link rel="stylesheet" type="text/css" href="default.css" />
		<script type="text/javascript">
			const numLetters = <?php echo $_SESSION['numLetters']; ?>;
		</script>
		<script type="text/javascript" src="default.js"></script>
	</head>
	<body>
			<div class="content">
				<h2>Lingo</h2>
				<button class="help" onclick="showModal()">?</button>
				<?php
					function initializegamearray() {
						$cipher = $cipher = "aes-128-cbc";
						$ivlen = openssl_cipher_iv_length($cipher);

						$file = fopen("encrypted" . $_SESSION['numLetters'] . ".txt","r");

						$iv = fread($file, $ivlen);

						$wordCount = (filesize("encrypted" . $_SESSION['numLetters'] . ".txt") - $ivlen) / 22;

						$rand = rand(0, $wordCount);

						fseek($file, $ivlen + $rand * 22);
						$encrypted = fread($file, 22) . "==";

						$word = openssl_decrypt($encrypted, $cipher, "Briton Westerhaus Lingo", 0, $iv);

						$_SESSION['server'] =  [];

						$first_array = ['<span class="correct">' . strtoupper($word{0}) . '</span>'];
						for ($i = 1; $i < $_SESSION['numLetters']; $i++) {
							$first_array[] = "&nbsp;";
						}
						$_SESSION['server'][] = $first_array;

						for ($i = 1; $i < $_SESSION['numLetters'] + 1; $i++) {
							$temparray = [];
							for ($j = 0; $j < $_SESSION['numLetters']; $j++) {
								$temparray[] = '&nbsp;';
							}
							$_SESSION['server'][] = $temparray;
						}

						$_SESSION['user'] = [];
						$temparray = [];
						for ($i = 0; $i < $_SESSION['numLetters']; $i++) {
							$temparray[] = '<input type="text" name="' . $i . '" size="1" maxlength="1" id="input' . $i . '" onselect="selectInput(' . $i . ')" onclick="selectInput(' . $i . ')" oninput="inputChanged(this)">';
						}
						$_SESSION['user'][] = $temparray;

						for ($i = 1; $i < $_SESSION['numLetters'] + 1; $i++) {
							$temparray = [];
							for ($j = 0; $j < $_SESSION['numLetters']; $j++) {
								$temparray[] = '&nbsp;';
							}
							$_SESSION['user'][] = $temparray;
						}
						$_SESSION['word'] = $word;
						$_SESSION['guesses'] = 0;
						$_SESSION['gameover'] = 0;
					}

					function posttostandard($parray){
						if (sizeof($parray) == $_SESSION['numLetters']) {
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
						if ($_SESSION['guesses'] == $_SESSION['numLetters'] && strtolower($_SESSION['word']) != strtolower(implode($guessarray))) {
							echo '<h3 align ="center">You lost. </h3><br />';
							echo "The word was $_SESSION[word].";
							$_SESSION['gameover'] = 1;
						}
						$temp = 0;
						for ($i = 0; $i < $_SESSION['numLetters']; $i++) {
							$thearray[] = $_SESSION['word'][$i];
						}
						serverdisplay($guessarray, $thearray);
					}

					function serverdisplay($guesses, $wordarray){
						$num = $_SESSION['guesses'];
						$guesses = array_pad($guesses, $_SESSION['numLetters'], '&nbsp;');

						for ($i = 0; $i < $_SESSION['numLetters']; $i++) {
							for ($j = 0; $j < $_SESSION['numLetters']; $j++) {
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
					
					if (isset($_POST['changeNumLetters'])) {
						unset($_SESSION['numLetters']);
						unset($_SESSION['user']);
						unset($_SESSION['server']);
						unset($_SESSION['word']);
						unset($_SESSION['guesses']);
					}

					if (!isset($_SESSION['numLetters'])) {
				?>
				<h3>How many letters and guesses?</h3>
				<form action="index.php" method="post" name="gameForm" id="GameForm">
					<input class="numButton" type="submit" name="numLetters" value= "7" />
					<input class="numButton" type="submit" name="numLetters" value= "6" />
					<input class="numButton" type="submit" name="numLetters" value= "5" />
					<input class="numButton" type="submit" name="numLetters" value= "4" />
				</form>
				<?php
					} else {
				?>
				<br />
				<form action="index.php" method="post" name="gameForm" onsubmit="return validateForm();">
				<table>
					<tr>
						<th colspan="<?php echo $_SESSION['numLetters']; ?>">Clues</th>
						<th colspan="<?php echo $_SESSION['numLetters']; ?>">Guesses</th>
					</tr>
				<?php
						if (!isset($_SESSION['server'])) {
							initializegamearray();
						} else {
							$guessarray = posttostandard($_POST);
							processguess($guessarray);
						}

						$server = $_SESSION['server'];
						$user = $_SESSION['user'];
						for ($i = 0; $i < $_SESSION['numLetters'] + 1; $i++) {
							echo "<tr>";
							$temparray = $server[$i];
							foreach ($temparray as $temp) {
								echo "<td>$temp</td>";
							}
							$temparray = $user[$i];
							foreach ($temparray as $temp) {
								echo "<td>$temp</td>";
							}
							echo "</tr>";
						}
						echo "</table>";
						echo "<br />";
						if($_SESSION['gameover'] == 0) {
							echo '<input type="submit" value="Take a guess!" />';
						} else {
							unset($_SESSION['user']);
							unset($_SESSION['server']);
							unset($_SESSION['word']);
							unset($_SESSION['guesses']);
							echo '<input type="submit" value="Play Again?" name="playAgain"/>';
						}
				?>
					</form>
					<br />
					<br />
					<form action="index.php" method="post" style="width: <?php echo 6.9 * $_SESSION['numLetters']; ?>em;">
						<input type="submit" onclick="return confirm('Are you sure? \nThis will quit your current game.');" value="&#8592; Back" name="changeNumLetters" />	
					</form>
				<?php
					}
				?>
			</div>
			<div id="ModalContainer">
				<div class="modal">
					<h3>How to play</h3>
					<p>A <span class="correct">red upper case</span> letter means that the letter you guessed is the right letter in the right place.</p>
					<p>A <span class="wrong-place">blue upper case</span> letter means that the letter you guessed is in the word, but at a different place.</p>
					<p>A black lower case letter means the letter you guessed is not in the word at all.</p>
					<?php 
						if (isset($_SESSION['numLetters'])) {
							switch ($_SESSION['numLetters']) {
								case 4:
									$number_word = "four";
									$ordinal_word = "fourth";
									break;

								case 5:
									$number_word = "five";
									$ordinal_word = "fifth";
									break;

								case 6:
									$number_word = "six";
									$ordinal_word = "sixth";
									break;

								case 7:
									$number_word = "seven";
									$ordinal_word = "seventh";
									break;

								default: // Default to 5 because why not?
									$number_word = "five";
									$ordinal_word = "fifth";
									break;
							}
					?>
					<p>You have <?php echo $number_word; ?> guesses to get the word right.  If you don't get the word right after the <?php echo $ordinal_word; ?> guess, you lose!</p>
					<?php } else { ?>
					<p>You can choose the number of letters in the word you are guessing. You will also have that many chances to guess, before you lose!</p>
					<?php } ?>
					<button onclick="hideModal()">&#10004;</button>
				</div>
			</div>
		</div>
	</body>
</html>

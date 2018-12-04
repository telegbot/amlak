<?php

	include('./config.php');

	foreach (glob("./Base/*.php") as $file) {
		include($file);
	}

	foreach (glob("./Commands/*.php") as $file) {
		include($file);
	}

	$bot = new Bot(TOKEN, BOTID, BOTNAME);

?>
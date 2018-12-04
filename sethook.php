<?php
	include('./config.php');

	$flag = isset($_GET["set"]) ? true : false;

	$url = "https://api.telegram.org/bot" . TOKEN . "/setWebhook" . ($flag ? "?url=" . BOTMASTER : "");

	print_r(file_get_contents($url));
?>
<?php
	function HandleShowMelk($bot, $message) {
		$chat = $message->chat;

		$bot->sendMessage($chat->id, "نمایش ملک تصادفی " . rand(0, 1000));
	}
?>
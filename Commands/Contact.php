<?php
	function HandleContact($bot, $message) {
		$chat = $message->chat;

		$msg = "جهت ارتباط با ما با شماره تماس های زیر تماس حاصل فرمایید.\n";
		$msg .= "09121234567\n";
		$msg .= "09121234567\n\n";
		$msg .= "و همچنین سامانه اطلاعات املاک:\n";
		$msg .= "@amlak\n\n";
		$msg .= "و برای ارتباط با مدیریت:\n";
		$msg .= "@amlak";

		$bot->sendMessage($chat->id, $msg);
	}
?>

<?php
	include('./loader.php');

	$update = $bot->getUpdate();

	$message = $update->message;
	$chat = $message->chat;
	$from = $message->from;

	if ($bot->hasOpenConversation($chat->id, $from->id)) {
		if ($message->text == "انصراف") {
			HandleCancel($bot, $message);
			die();
		}

		$conv = $bot->getConversation($chat->id, $from->id);

		if ($message->text == "بازگشت") {
			HandleBack($bot, $message, $conv->data);
			die();
		}

		if ($conv->command == "submit") {
			HandleSubmitMelkConversation($bot, $message, $conv);
		} else if ($conv->command == "request") {
			HandleRequestMelkConversation($bot, $message, $conv);
		} else {
			$bot->deleteConversation($chat->id, $from->id);
			$bot->sendMessage($chat->id, "دستور نامشخص است!");
			HandleCancel($bot, $message);
		}

		die();
	}

	$parsed = $bot->parseMessage($message->text);

	if ($parsed->bot != $bot->botid) {
		die();
	}

	if ($parsed->command != null) {
		switch ($parsed->command) {
			case "start":
				HandleStart($bot, $message);
				break;
			default:
				$bot->sendMessage($chat->id, "متاسفانه چنین دستوری وجود ندارد!");
		}
	} else {
		switch($message->text) {
			case "نمایش املاک 🏤":
				HandleShowMelk($bot, $message);
				break;
			case "ثبت ملک 🏠":
				HandleSubmitMelk($bot, $message);
				break;
			case "تقاضای ملک 🏡":
				HandleRequestMelk($bot, $message);
				break;
			case "تماس با ما 📮":
				HandleContact($bot, $message);
				break;
			case "پشتیبانی 📞":
				HandleSupport($bot, $message);
				break;
			case "بازگشت به صفحه اصلی":
				HandleMainPage($bot, $message);
				break;
		}
	}
?>

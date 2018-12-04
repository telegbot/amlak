<?php
	function HandleRequestMelk($bot, $message) {
		global $reqkeyboards;
		global $reqmessages;

		$chat = $message->chat;
		$bot->createConversation($chat->id, $message->from->id, "request");

		$key = $reqkeyboards[0];
		$msg = $reqmessages[0];

		$bot->sendMessage($chat->id, $msg, $key);
	}

	function HandleRequestMelkConversation($bot, $message, $conversation) {
		global $reqkeyboards;
		global $reqmessages;

		$chat = $message->chat;

		$text = $message->text;
		$conv = $conversation->data;

		$msg = "";

		switch($conv->step) {
			case 0:
				$conv->title = trim($text);
				break;
			case 1:
				$conv->application = trim($text);
				break;
			case 2:
				if (!is_numeric(trim($text))) {
					$bot->sendMessage($chat->id, "متراژ باید عدد باشد");
					return;
				}

				$conv->area = intval(trim($text));
				break;
			case 3:
				$conv->position = trim($text);
				break;
			case 4:
				$conv->rooms = trim($text);
				break;
			case 5:
				$conv->floors = trim($text);
				break;
			case 6:
				$conv->cooling = trim($text);
				break;
			case 7:
				$conv->heating = trim($text);
				break;
			case 8:
				$conv->elevator = trim($text);
				break;
			case 9:
				$conv->parking = trim($text);

				if ($conv->title == "مشارکت" || $conv->title == "معاوضه") {
					$msg .= "ارزش ملک";
				} else {
					$msg .= "مبلغ " . $conv->title;
				}
				
				break;
			case 10:
				$conv->price = trim($text);
				break;
			case 11:
				$conv->district = trim($text);
				break;
			case 12:
				$conv->name = trim($text);
				break;
			case 13:
				$conv->phone = trim($text);
				break;
			case 14:
				if ($text == "ادامه"){
					$text = "نامشخص";
				}
				$conv->description = trim($text);
				break;
			case 15:
				if ($text == "تایید و ذخیره") {
					if ($bot->requestMelk($conv)) {
						$bot->sendMessage($message->chat->id, "اطلاعات با موفقیت به ثبت رسید");
					} else {
						$bot->sendMessage($message->chat->id, "خطایی در ثبت اطلاعات رخ داده است");
					}

					HandleCancel($bot, $message);
				}
				return;
		}

		$conv->step++;

		$key = $reqkeyboards[$conv->step];
		$msg .= $reqmessages[$conv->step];

		$bot->sendMessage($chat->id, $msg, $key);

		$bot->updateConversation($message->chat->id, $message->from->id, $conv);
	}
?>

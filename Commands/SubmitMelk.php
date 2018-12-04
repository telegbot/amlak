<?php
	function HandleSubmitMelk($bot, $message) {
		global $keyboards;
		global $messages;

		$chat = $message->chat;
		$bot->createConversation($chat->id, $message->from->id, "submit");

		$key = $keyboards[0];
		$msg = $messages[0];

		$bot->sendMessage($chat->id, $msg, $key);
	}

	function HandleSubmitMelkConversation($bot, $message, $conversation) {
		global $keyboards;
		global $messages;

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
				break;
			case 10:
				if (!is_numeric(trim($text))) {
					$bot->sendMessage($chat->id, "سن بنا باید عدد باشد");
					return;
				}

				$conv->age = intval(trim($text));
				break;
			case 11:
				$conv->status = trim($text);

				if ($conv->title == "مشارکت" || $conv->title == "معاوضه") {
					$msg .= "ارزش ملک";
				} else {
					$msg .= "مبلغ " . $conv->title;
				}

				break;
			case 12:
				$conv->price = trim($text);
				break;
			case 13:
				$conv->district = trim($text);
				break;
			case 14:
				$conv->address = trim($text);
				break;
			case 15:
				$conv->owner = trim($text);
				break;
			case 16:
				$conv->phone = trim($text);
				break;
			case 17:
				if ($text == "ادامه"){
					$text = "نامشخص";
				}
				$conv->description = trim($text);
				break;
			case 18:
				$pic = "نامشخص";

				if ($text != "ادامه"){
					$fid = "";
					if (isset($message->document)) {
						$fid = $message->document->file_id;
					} else if (isset($message->photo)) {
						$fid = $message->photo[2]->file_id;
					}

					$file = $bot->getFile($fid);

					if ($file->ok) {
						$pic = $file->result->file_path;
					}
				}

				$conv->picture = $pic;
				break;
			case 19:
				if ($text == "تایید و ذخیره") {
					if ($bot->submitMelk($conv)) {
						$bot->sendMessage($message->chat->id, "اطلاعات با موفقیت به ثبت رسید");
					} else {
						$bot->sendMessage($message->chat->id, "خطایی در ثبت اطلاعات رخ داده است");
					}

					HandleCancel($bot, $message);
				}
				return;
		}

		$conv->step++;

		$key = $keyboards[$conv->step];
		$msg .= $messages[$conv->step];

		$bot->sendMessage($chat->id, $msg, $key);

		$bot->updateConversation($message->chat->id, $message->from->id, $conv);
	}
?>

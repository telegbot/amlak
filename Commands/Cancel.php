<?php
	function HandleCancel($bot, $message) {
		$bot->deleteConversation($message->chat->id, $message->from->id);

		$chat = $message->chat;

		$keyboard = [
			"keyboard" => [
				["نمایش املاک 🏤"],
				["ثبت ملک 🏠", "تقاضای ملک 🏡"],
				["پشتیبانی 📞", "تماس با ما 📮"]
			]
		];

		$bot->sendMessage($chat->id, "لطفا یک گزینه را انتخاب کنید.", $keyboard);
	}
?>

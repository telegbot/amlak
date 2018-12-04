<?php
	function HandleStart($bot, $message) {
		$chat = $message->chat;

		$keyboard = [
			"keyboard" => [
				["نمایش املاک 🏤"],
				["ثبت ملک 🏠", "تقاضای ملک 🏡"],
				["پشتیبانی 📞", "تماس با ما 📮"]
			]
		];

		$bot->sendMessage($chat->id, "به ربات مشاور املاکی خوش امدید", $keyboard);
	}
?>
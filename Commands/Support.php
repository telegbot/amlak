<?php
	function HandleSupport($bot, $message) {
		$chat = $message->chat;

		$msg = "جهت ارتباط با پشتیبانی و گزارش مشکلات و تیم سازنده ربات با آی دی های زیر ارتباط برقرار کنید:";

		$bot->sendMessage($chat->id, $msg);
	}
?>

<?php
	function HandleBack($bot, $message, $conv) {
		global $keyboards;
		global $messages;

		$conv->step--;

		$chat = $message->chat;

		$bot->updateConversation($chat->id, $message->from->id, $conv);

		$k = $keyboards[$conv->step];
		$m = $messages[$conv->step];

		$bot->sendMessage($chat->id, $m, $k);
	}
?>

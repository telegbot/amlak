<?php
	class Bot {
		private $token = "";
		var $botname = "";
		var $botid = "";
		var $db;

		function __construct($token, $id, $name) {
			$this->token = $token;
			$this->botid = $id;
			$this->botname = $name;
			$this->db = new DB();
		}

		function getUpdate() {
			return json_decode(file_get_contents("php://input"), false);
		}

		function getFile($file_id) {
			$url = "https://api.telegram.org/bot" . $this->token . "/getFile?file_id=" . $file_id;

			return json_decode(file_get_contents($url), false);
		}

		function sendMessage($chat_id, $message, $keyboard = null) {
			$url = "https://api.telegram.org/bot" . $this->token . "/sendMessage?chat_id=" . $chat_id . "&text=" . urlencode($message);
			if ($keyboard != null) {
				$url .= "&reply_markup=" . urlencode(json_encode($keyboard));
			}

			file_get_contents($url);
		}
		function answerCallbackQuery($callback_id, $message = "") {
			$url = "https://api.telegram.org/bot" . $this->token . "/answerCallbackQuery?callback_query_id=" . $callback_id;

			if ($message != "") {
				$url .= "&text=" . urlencode($message);
			}

			file_get_contents($url);
		}

		function parseMessage($message) {
			$obj = new stdClass();

			if (preg_match("/\/([^\s\@]+)\@?([^\s\@]+)?[^\s]*\s?(.+)?/", $message, $match)) {
				$obj->command = (isset($match[1]) && !is_null($match[1]) && !empty($match[1]) ? $match[1] : null);
				$obj->bot = (isset($match[2]) && !is_null($match[2]) && !empty($match[2]) ? $match[2] : $this->botid);
				$obj->argument = (isset($match[3]) && !is_null($match[3]) && !empty($match[3]) ? $match[3] : null);
			} else {
				$obj->command = null;
				$obj->bot = $this->botid;
				$obj->argument = $message;
			}

			return $obj;
		}

		function isCallbackQuery($update) {
			return isset($update->callback_query);
		}

		function createConversation($chat_id, $user_id, $command) {
			$res = $this->db->QueryWithParameters("INSERT INTO conversations(user_id, chat_id, command, data, last_try) VALUES (:uid, :cid, :cmd, :dt, :ltry);",
									array(
										array(":uid", $user_id),
										array(":cid", $chat_id),
										array(":cmd", $command),
										array(":dt", "{state: 0}"),
										array(":ltry", date("Y-m-d H:i:s"))
									)
								);
			if ($res) {
				return ($res->rowCount() == 1);
			}
			return false;
		}
		function deleteConversation($chat_id, $user_id) {
			$res = $this->db->QueryWithParameters("DELETE FROM conversations WHERE user_id = :uid AND chat_id = :cid",
									array(
										array(":uid", $user_id),
										array(":cid", $chat_id)
									)
								);
			if ($res) {
				return ($res->rowCount() == 1);
			}
			return false;
		}
		function getConversation($chat_id, $user_id) {
			$res = $this->db->QueryWithParameters("SELECT command, data FROM conversations WHERE user_id = :uid AND chat_id = :cid",
									array(
										array(":uid", $user_id),
										array(":cid", $chat_id)
									)
								);
			if ($res->rowCount() == 1) {
				$results = $res->fetch(PDO::FETCH_ASSOC);

				$obj = new stdClass();
				$obj->command = $results["command"];
				$obj->data = json_decode($results["data"], false);

				return $obj;
			}
		}
		function updateConversation($chat_id, $user_id, $data) {
			$res = $this->db->QueryWithParameters("UPDATE conversations SET data = :dt, last_try = :ltry WHERE user_id = :uid AND chat_id = :cid",
									array(
										array(":uid", $user_id),
										array(":cid", $chat_id),
										array(":ltry", date("Y-m-d H:i:s")),
										array(":dt", json_encode($data))
									)
								);
			if ($res) {
				return ($res->rowCount() == 1);
			}
			return false;
		}
		function hasOpenConversation($chat_id, $user_id) {
			$res = $this->db->QueryWithParameters("SELECT COUNT(*) AS Count FROM conversations WHERE user_id = :uid AND chat_id = :cid",
									array(
										array(":uid", $user_id),
										array(":cid", $chat_id)
									)
								);
			if ($res) {
				$count = $res->fetch(PDO::FETCH_ASSOC)["Count"];
				return ($count > 0);
			}

			return false;
		}

		function submitMelk($data) {
			$res = $this->db->QueryWithParameters("INSERT INTO submited(title, application, area, position, rooms, floors, cooling, heating, elevator, parking, age, status, price, district, address, owner, phone, picture, description) VALUES (:title, :application, :area, :position, :rooms, :floors, :cooling, :heating, :elevator, :parking, :age, :status, :price, :district, :address, :owner, :phone, :picture, :description)",
									array(
										array(":title", $data->title),
										array(":application", $data->application),
										array(":area", $data->area),
										array(":position", $data->position),
										array(":rooms", $data->rooms),
										array(":floors", $data->floors),
										array(":cooling", $data->cooling),
										array(":heating", $data->heating),
										array(":elevator", $data->elevator),
										array(":parking", $data->parking),
										array(":age", $data->age),
										array(":status", $data->status),
										array(":price", $data->price),
										array(":district", $data->district),
										array(":address", $data->address),
										array(":owner", $data->owner),
										array(":phone", $data->phone),
										array(":picture", $data->picture),
										array(":description", $data->description)
									)
								);
			if ($res) {
				return ($res->rowCount() == 1);
			}
			return false;
		}
		function requestMelk($data) {
			$res = $this->db->QueryWithParameters("INSERT INTO requested(title, application, area, position, rooms, floors, cooling, heating, elevator, parking, price, district, name, phone, description) VALUES (:title, :application, :area, :position, :rooms, :floors, :cooling, :heating, :elevator, :parking, :price, :district, :name, :phone, :description)",
									array(
										array(":title", $data->title),
										array(":application", $data->application),
										array(":area", $data->area),
										array(":position", $data->position),
										array(":rooms", $data->rooms),
										array(":floors", $data->floors),
										array(":cooling", $data->cooling),
										array(":heating", $data->heating),
										array(":elevator", $data->elevator),
										array(":parking", $data->parking),
										array(":price", $data->price),
										array(":district", $data->district),
										array(":name", $data->name),
										array(":phone", $data->phone),
										array(":description", $data->description)
									)
								);
			if ($res) {
				return ($res->rowCount() == 1);
			}
			return false;
		}
	}
?>

<?php
	class DB {
		const HOST = "localhost";
		const NAME = "omrani";
		const USER = "omrani_user";
		const PASS = "omranibot!@#123";

		private $pdo;
		private $stmt;

		function __construct() {
			$this->pdo = new PDO("mysql:host=" . self::HOST . ";dbname=" . self::NAME, self::USER, self::PASS);
			$this->pdo->exec("SET NAMES UTF8");
		}

		private function ExecuteQuery($query, $params) {
			$this->stmt = $this->pdo->prepare($query);
			foreach ($params as $param) {
				if (count($param) == 2) {
					$this->stmt->bindParam($param[0], $param[1]);
				} else {
					$this->stmt->bindParam($param[0], $param[1], $param[2]);
				}
			}
			return $this->stmt->execute();
		}

		function Query($query) {
			if ($this->ExecuteQuery($query)) {
				return $this->stmt;
			}
			return false;
		}
		function QueryWithParameters($query, $params) {
			if ($this->ExecuteQuery($query, $params)) {
				return $this->stmt;
			}
			return false;
		}
		function getStatement() {
			return $this->stmt;
		}
	}
?>

<?php

require_once 'Config.php';
class InvoiceModel
{
	protected $host;
	protected $user;
	protected $pass;
	protected $db;
	protected $condb;
	// set database config for mysql
	function __construct()
	{
		$objconfig = new Config();
		$this->host = $objconfig->host;
		$this->user = $objconfig->user;
		$this->pass =  $objconfig->pass;
		$this->db = $objconfig->db;
	}
	// open mysql data base
	public function open_db()
	{
		$this->condb = new mysqli($this->host, $this->user, $this->pass, $this->db);
		if ($this->condb->connect_error) {
			die("Erron in connection: " . $this->condb->connect_error);
		}
	}
	public function closeDb()
	{
	}

	// close database
	public function close_db()
	{
		$this->condb->close();
	}

	// insert record
	public function insertRecord($obj)
	{
		try {
			$this->open_db();
			$query = $this->condb->prepare("INSERT INTO invoices (category,name) VALUES (?, ?)");
			$query->bind_param("ss", $obj->category, $obj->name);
			$query->execute();
			$res = $query->get_result();
			$last_id = $this->condb->insert_id;
			$query->close();
			$this->close_db();
			return $last_id;
		} catch (Exception $e) {
			$this->close_db();
			throw $e;
		}
	}
	//update record
	public function updateRecord($obj)
	{
		try {
			$this->open_db();
			$query = $this->condb->prepare("UPDATE invoices SET category=?,name=? WHERE id=?");
			$query->bind_param("ssi", $obj->category, $obj->name, $obj->id);
			$query->execute();
			$res = $query->get_result();
			$query->close();
			$this->close_db();
			return true;
		} catch (Exception $e) {
			$this->close_db();
			throw $e;
		}
	}
	// delete record
	public function deleteRecord($id)
	{
		try {
			$this->open_db();
			$query = $this->condb->prepare("DELETE FROM invoices WHERE id=?");
			$query->bind_param("i", $id);
			$query->execute();
			$res = $query->get_result();
			$query->close();
			$this->close_db();
			return true;
		} catch (Exception $e) {
			$this->closeDb();
			throw $e;
		}
	}
	// select record     
	public function selectRecord($id)
	{
		try {
			$this->open_db();
			if ($id > 0) {
				$query = $this->condb->prepare("SELECT * FROM invoices WHERE id=?");
				$query->bind_param("i", $id);
			} else {
				$query = $this->condb->prepare("SELECT * FROM invoices");
			}

			$query->execute();
			$res = $query->get_result();
			$query->close();
			$this->close_db();
			return $res;
		} catch (Exception $e) {
			$this->close_db();
			throw $e;
		}
	}
}

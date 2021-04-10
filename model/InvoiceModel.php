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
			$result = $this->condb->query("INSERT INTO invoices (category,name) VALUES (?, ?)");
			$last_id = $this->condb->insert_id;
			$this->close_db();
			if($result) {
			    return $last_id;
			}
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
			$result = $this->condb->query("UPDATE invoices SET category=?,name=? WHERE id=?");
			$this->close_db();
			if($result) {
			    return true;
			}
			return false;
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
			$result = $this->condb->query("DELETE FROM invoices WHERE id=$id");
			$this->close_db();
			if($result) {
			    return true;
			}
			return false;
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
				$result = $this->condb->query("SELECT * FROM invoices WHERE id=$id");
			} else {
				$result = $this->condb->query("SELECT * FROM invoices");
			}
			$this->close_db();
			return $result;
		} catch (Exception $e) {
			$this->close_db();
			throw $e;
		}
	}
}

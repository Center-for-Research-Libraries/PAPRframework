<?php

	/**
	*
	*	 TITLE: MySQLi Query & Session Handler
	*	AUTHOR: Jabari J. Hunt
	*	E-MAIL: jabari@jabari.net
	*	   PHP: 5
	**/

	class dbMySQLi
	{
		//////////////////////////////////////////////
		// ********** CONNECTION VARIABLES **********
		//////////////////////////////////////////////

			// DATABASE CONNECTION DETAILS

				private $host     = '192.168.1.142';				// SERVER (usually 'localhost')
				private $database = 'repositoryDecisionFramework';	// DATABASE INSTANCE (name)
				private $user     = 'jhunt';						// USERNAME
				private $password = 'Ja8r1_H0nt';					// PASSWORD

			// SESSION NAME & TIME TO EXPIRE

				private $session_name = 'REPOSITORYDECISIONFRAMEWORK';	// SESSION NAME
				private $expires      = '30';	                        // TIME BEFORE SESSION EXPIRES (minutes)

		/*******************************************************************************
		*****   DO NOT EDIT BELOW THIS BOX UNLESS YOU KNOW WHAT YOU ARE DOING!!!   *****
		*******************************************************************************/

		//////////////////////////////////////////////
		// CLASS VARIABLES -> DB OBJECT | INSERT ID | SESSIONS (ON/OFF)
		//////////////////////////////////////////////

			protected $db, $insert_id, $use_sessions;
			private $prepared;

		//////////////////////////////////////////////
		// CONSTRUCTOR | DESTRUCTOR
		//////////////////////////////////////////////

			public function __construct($use_sessions = FALSE)
			{
				// CONNECT TO DATABASE | CHECK CONNECTION | SET USE SESSIONS VARIABLE
				// START SESSION IF REQUESTED

					$this->db = @new mysqli($this->host, $this->user, $this->password, $this->database);
					if($this->db->connect_error == TRUE) {die ('<b style="color: #F00;">COULD NOT CONNECT TO THE DATABASE SERVER</b>');}
					$this->use_sessions = $use_sessions;

					if ($this->use_sessions == TRUE)
					{
						// SET SESSION ID | RUN SET SESSION HANDLER METHOD | START SESSION

							if (!empty($this->session_name)) {session_name($this->session_name);}
							session_set_save_handler(array($this, "open"), array($this, "close"), array($this, "read"), array($this, "write"), array($this, "destroy"), array($this, "gc"));
							session_start();
					}
			}

			public function __destruct()
			{
				// CLOSE SESSION | CLOSE DATABASE

					if ($this->use_sessions == TRUE) {session_write_close();}
					$this->db->close();
			}

		//////////////////////////////////////////////
		// SESSION HANDLER METHODS -> OPEN | CLOSE | READ | WRITE | DESTROY | GARBAGE COLLECTION
		//////////////////////////////////////////////

			private function open() {return TRUE; echo 'open';}

			private function close() {return TRUE; echo 'close';}

			private function read($session_id)
			{
				// GET CURRENT TIMESTAMP | QUERY DATABASE FOR SESSION DATA
				// PROCESS SESSION IF ONE WAS RETURNED -> GET SESSION DATA | SET NEW EXPIRATION
				// RETURN SESSION DATA

					$now    = time();
					$result = $this->db->query("SELECT data FROM sessions WHERE id = '{$session_id}' AND expires > '{$now}'");

					if ($result->num_rows <> 1) {$session['data'] = '';}
					else
					{
						$session = $result->fetch_assoc();

						$expires = $now + ($this->expires * 60);
						$this->db->query("UPDATE sessions SET expires = {$expires} WHERE id = '{$session_id}'");
					}

					return $session['data'];
			}

			private function write($session_id, $session_data)
			{
				// GET CURRENT TIMESTAMP |  ESCAPE SESSION DATA | REPLACE/INSERT SESSION DATA IN DATABASE -> RETURN RESULT

					$expires      = time() + ($this->expires * 60);
					$session_data = $this->db->real_escape_string($session_data);
					return $this->db->query("INSERT INTO sessions (id, data, expires) VALUES ('{$session_id}', '{$session_data}', '{$expires}') ON DUPLICATE KEY UPDATE data = '{$session_data}', expires = '{$expires}'");
			}

			private function destroy($session_id)
			{
				// DELETE SESSION BASED ON SESSION ID

					return $this->db->query("DELETE FROM sessions WHERE id = '{$session_id}'");
			}

			private function gc()
			{
				// GET CURRENT TIMESTAMP | DELETE OLD SESSIONS -> RETURN RESULT

					$now = time();
					return $this->db->query("DELETE FROM sessions WHERE expires <= '{$now}'");
			}
	}
?>
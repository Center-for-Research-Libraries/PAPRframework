<?php

	//////////////////////////////////////////////
	// INCLUDE MySQLi CLASS
	// DECLARE MySQLiCustomQueries CLASS
	//////////////////////////////////////////////

		require('dbMySQLi.php');

		class dbMySQLiCustom extends dbMySQLi
		{
			//////////////////////////////////////////////
			// CONSTRUCTOR | DESCRUCTOR -> CALL PARENT CONSTRUCTOR
			//////////////////////////////////////////////

				public function __construct($use_sessions = FALSE) {parent::__construct($use_sessions);}
				public function __destruct() {parent::__destruct();}

			//////////////////////////////////////////////
			// APP SPECIFIC CUSTOM METHODS
			//////////////////////////////////////////////

				// CHECK ARCHIVE BY ID

					public function checkArchive($archive_id)
					{
						$valid  = FALSE;
						$result = $this->db->query("SELECT id FROM archives WHERE id = '{$archive_id}'");
						if ($result->num_rows == 1) {$valid = TRUE;}

						return $valid;
					}

				// GET ARCHIVE BY ID

					public function getArchiveById($archive_id)
					{
						$result = $this->db->query("SELECT id, name FROM archives WHERE id = '{$archive_id}' LIMIT 1");
						return $result->fetch_assoc();
					}

				// GET ALL CATEGORIES

					public function getAllCategories()
					{
						$result = $this->db->query('SELECT id, name FROM categories ORDER BY order_by');
						$array  = NULL;

						while ($row = $result->fetch_assoc())
						{
							$array[] = array('id' => $row['id'], 'name' => $row['name']);
						}

						return $array;
					}

				// GET TOPICS BY CATEGORY

					public function getCategoryTopics($category_id)
					{
						$result = $this->db->query("SELECT id, name FROM category_topics WHERE category_id = '{$category_id}' ORDER BY order_by");
						$array  = NULL;

						while ($row = $result->fetch_assoc())
						{
							$array[] = array('id' => $row['id'], 'name' => $row['name']);
						}

						return $array;
					}

				// GET CHOICES BY TOPIC BY ID

					public function getTopicChoices($topic_id)
					{
						$result = $this->db->query("SELECT id, name, ranking FROM category_topic_choices WHERE category_topic_id = '{$topic_id}' ORDER BY order_by");
						$array  = NULL;

						while ($row = $result->fetch_assoc())
						{
							$array[] = array('id' => $row['id'], 'name' => $row['name']);
						}

						return $array;
					}
		}
?>
<?php

	// CONNECT TO DATABASE

		require('dbMySQLiCustom.php');
		$db = new dbMySQLiCustom(TRUE);

	// GET ARCHIVE ID

		$valid_archive = FALSE;

		if (!empty($_GET['archive']) && is_numeric($_GET['archive'])) {$valid_archive = $db->checkArchive($_GET['archive']);}

		if ($valid_archive) {$archive_id = $_REQUEST['archive'];}
		else {die('<strong>INVALID ARCHIVE!!!</strong>');}

	// MAKE SURE ALL QUESTIONS WERE ANSWERED



	// GET ANSWERS

		if (!empty($_POST))
		{
			foreach ($_POST as $topic_id => $choice_id)
			{
				if (!is_numeric($topic_id) || !is_numeric($choice_id)) {die('<strong>INVALID ANSWERS SUBMITTED!!!</strong>');}
				else
				{
					echo "TOPIC ID: {$topic_id} | CHOICE ID: {$choice_id}<br/>\n";
				}
			}
		}

	// CLOSE DATABASE

		unset($db);

?>
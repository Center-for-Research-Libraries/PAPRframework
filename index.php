<?php

	// CONNECT TO DATABASE

		require('dbMySQLiCustom.php');
		$db = new dbMySQLiCustom(TRUE);

			// MAKE SURE ARCHIVE ID IS VALID

				$valid_archive = FALSE;

				if (!empty($_GET['archive']) && is_numeric($_GET['archive'])) {$valid_archive = $db->checkArchive($_GET['archive']);}

				if ($valid_archive) {$archive_id = $_GET['archive'];}
				else {die('<strong>INVALID ARCHIVE!!!</strong>');}

	// FUNCTION THAT BUILDS & DISPLAYS FORM

		function displayForm($db, $archive_id)
		{
			// GET ARCHIVE DATA | START FORM

				$archive = $db->getArchiveById($archive_id);

				echo "<h1>Archive name: {$archive['name']}</h1>\n"; //AJE
				echo '<form action="submit_answers.php?archive=' . $archive_id . '" method="POST">' . "\n";

			// GET ALL CATEGORIES
			// LOOP THROUGH EACH & DISPLAY

				$categories = $db->getAllCategories();

				if ($categories == NULL) {die('<strong>ERROR RETRIEVING CATEGORIES FROM DATABASE!!!</strong>');}
				else
				{
					foreach($categories as $category)
					{
						// START BLOCKQUOTE
						// DISPLAY CATEGORY NAME

							echo "<blockquote>\n";
							echo "<h2>{$category['name']}</h2>\n";

						// GET ALL CATEGORY TOPICS
						// LOOP THROUG EACH & DISPLAY

							$topics = $db->getCategoryTopics($category['id']);

							if ($topics == NULL) {die('<strong>ERROR RETRIEVING "' . strtoupper($category['name']) . '" TOPICS FROM DATABASE!!!</strong>');}
							else
							{
								foreach($topics as $topic)
								{
									// START BLOCKQUOTE
									// DISPLAY TOPIC NAME

										echo "<blockquote>\n";
										echo "<h4>{$topic['name']}</h4>\n";

									// GET ALL TOPIC CHOICES
									// LOOP THROUG EACH & DISPLAY
									// END BLOCKQUOTE

										$choices = $db->getTopicChoices($topic['id']);

										if ($choices == NULL) {die('<strong>ERROR RETRIEVING TOPIC CHOICES FROM DATABASE!!!</strong>');}
										else
										{
											foreach($choices as $choice)
											{
												// DISPLAY CHOICE

													echo '<input type="radio" id="' . $topic['id'] . '" name="' . $topic['id'] . '" value="' . $choice['id'] . '"> ' . $choice['name'] . "<br/>\n";
											}

											echo "<br/>\n";
										}

										echo "</blockquote>\n";
								}
							}

						// END BLOCKQUOTE

							echo "</blockquote>\n";
					}
				}

			// DISPLAY SUBMIT BUTTON | END FORM

				echo '<div align="center"><input type="submit" value="Submit Answers" /></div>' . "\n";
				echo '</form>';
		}

?>
<html>
  <head>
  	<link rel="stylesheet" type="text/css" href="css/index.css" />
  	<title>PAPR project</title>
  </head>
  <body>
    <?php include("header.php"); ?>
    <div class="pageHeader">&nbsp;</div>
    <div id="page"><?php displayForm($db, $archive_id); ?></div>
    <?php include("footerCRL.html"); ?>
  </body>
</html>
<?php unset($db); ?>
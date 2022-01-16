<?php 
	include("../include.inc");
    // Include the file that contains the variables that point 
    // to a handle of db connections & SQL queries & other added declarations.
    include("$host" . "/blog-oop/model/model.php");

    // Include the file that contains the miscellaneous functions.
    include("$host" . "/blog-oop/misc/functions.php");

    // If url request variable is set; assign value to query otherwise it's null.
    $query = (isset($_REQUEST["query"])) ? $_REQUEST["query"] : "";
	$query = trim(strip_tags($query));

	if ($query != "") {
		// Select posts grouped by month and year
		$sqlQuery = "SELECT post_id, title, summary, DATE_FORMAT(postdate, '%e %b %Y at %H:%i') AS dateattime FROM posts 
			WHERE MATCH(title, summary, post) AGAINST ('$query') LIMIT 50";
		$queryObj = new BlogPost($dbConn, $sqlQuery);
	  	$posts = $queryObj->getAllResults();
	  	$numresults = $queryObj->getNumRowResults();
	  	var_dump($posts);
	} else {
	  	$numresults = 0;
	}


	// format search for HTML display
	$query = stripslashes(htmlentities($query));
?>
	<!-- this is the main part of the page -->
	<div id="maincontent">
		<div id="posts">
		<h2>Search Results</h2>
		<div id="results">
		<?php
			if ($posts && ($numresults > 0)) {
				
				$plural1 = ($numresults==1) ? "is" : "are";
				$plural2 = ($numresults==1) ? "" : "s";
				echo "<p>There $plural1 <em>$numresults</em> post$plural2 matching your search for <cite>$query</cite>.</p>";
				echo "<dl>\n";
				foreach ($posts as $post) {
					$post_id = $post["post_id"];
					$title = $post["title"];
					$summary = $post["summary"];
					echo "<dt><a href='view-post.php?post_id=$post_id'>$title</a></dt>\n";
					echo "<dd>$summary</dd>\n";
				}
				echo "</dl>";
			} elseif($query == "") {
				echo "<p><cite>You didn't type in anything in the search bar.</cite>.</p>";
			}
			else {
				echo "<p>There were no posts matching your search for <cite>$query</cite>.</p>";
			}
		?>
		</div>
	</div>

	<div id="sidebar">
	<?php include("searchform.php"); ?>
	</div>
	<!-- sidebar ends -->

	</div>
	<!-- maincontent ends -->

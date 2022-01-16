<?php
	// Include the file that contains the variables that point 
    // to a handle of db connections & SQL queries declarations.
    include("$host" . "/blog-oop/misc/functions.php");

    include("$host" . "/blog-oop/model/model.php");
?>

<!-- this is the main part of the page -->
<div id="maincontent">
	
		<div id='posts'>
			<?php
				if($posts) {
					foreach($posts as $post) {
							echo "<h2 id='" . $post["post_id"] . "'><a href='./views/view-post.php?post_id=" . 
								$post["post_id"] . "' rel='bookmark'>" . $post["title"] . "</a></h2>\n";
						    echo "<h4>Posted on " . $post["dateattime"] . "</h4>\n";
						    echo "<div class='post'>" . format($post["post"]) . "</div>";
					} 
				}
				else {
					echo "<p>I haven't posted to my blog yet.</p>";
				}
			?> 
		</div>
	

	<div id='sidebar'>
		<div id="about">
			<h3>About this</h3>
			<p>What motivated me to set up this blog-space was my desire to share my thoughts on the books 
				I've read and provide a gist or summary on the thrusts of their contents. And to recommend 
				those titles which I think can contribute to the enrichment of their readers. And to also 
				express my suggestions on areas that I think can be made better.
			</p>
		</div>

		<?php include("searchform.php"); ?>

		<h3 id="viewarchive"><a href='/blog-oop/views/view-archive.php'>View the Archive</a></h3>

		<div id="recent">
			<h3>Recent posts</h3>
			<?php
				if($posts) {
				  echo "<ul>\n";
				  foreach ($posts as $post) {
				  	echo "<li><a href = /blog-oop/views/view-post.php?post_id=" . $post["post_id"] . " rel='bookmark'>" . $post["title"] . "</a></li>\n";
				  }
				  echo "</ul>";
				}
			?>
		</div>
	</div>
	<!-- sidebar ends -->
</div>
<!-- maincontent ends -->


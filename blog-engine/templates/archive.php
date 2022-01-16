<?php
    include("../include.inc");
    
    // Include the file that contains the miscellaneous functions.
    include("$host" . "/blog-oop/misc/functions.php");

    // Include the file that contains the declaration to Database & BlogPost classes.
    include("$host" . "/blog-oop/model/model.php");

    // Get $month and $year from the URL query string .
    // If not set blank otherwise.
    $month = (isset($_REQUEST["month"])) ? $_REQUEST["month"] : "";
    $year = (isset($_REQUEST["year"])) ? $_REQUEST["year"] : "";

    if (preg_match("/^[0-9][0-9][0-9][0-9]$/", $year) AND preg_match("/^[0-9]?[0-9]$/", $month)) { 
        $sqlQuery = "SELECT post_id, title, summary, DATE_FORMAT(postdate, '%e %b %Y at %H:%i') 
            AS dateattime FROM posts WHERE MONTH(postdate) = $month AND YEAR(postdate) = $year";
        $queryObj = new BlogPost($dbConn, $sqlQuery);
        $posts = $queryObj->getAllResults();
        
        if ($posts) {
            $showbymonth = true;
            $text = strtotime("$month/1/$year");
            $thismonth = date("F Y", $text);
        }
    }

    if (!isset($showbymonth)) {
        $showbymonth = false;
        // Select posts grouped by month and year
        $sqlQuery = "SELECT DATE_FORMAT(postdate, '%M %Y') AS monthyear, MONTH(postdate) 
            AS month, YEAR(postdate) AS year, count(*) AS count FROM posts  GROUP BY monthyear ORDER BY year, month";
        $queryObj = new BlogPost($dbConn, $sqlQuery);
        $posts = $queryObj->getAllResults();
    }

?>

<!-- this is the main part of the page -->
<div id="maincontent">
    <div id="posts">
    <h2 class="archivesequence"><?php if(isset($thismonth)) echo $thismonth; ?> Archive</h2>
    <?php
        switch ($showbymonth) {
            case true:
            if($posts) {
                echo "<dl>\n";
                foreach ($posts as $post) {
                    echo "<dt><a href='./view-post.php?post_id=" . $post["post_id"] . "' rel='bookmark'>"
                        . $post["title"] . "</a></dt>\n";
                    echo "<dd>" . $post["summary"] . "</dd>\n";
                  }
              echo "</dl>";
            }
            break;
              
            case false:
            $previousyear = "";
            if($posts) {
                foreach ($posts as $post) {
                    if ($year != $previousyear) {
                        if ($previousyear != "") {
                            echo "</ul>\n";
                        }
                        echo "<h3>$year</h3>";
                        echo "<ul>\n";
                        $previousyear = $post["year"];
                    }
                    $plural = ($post["count"] == 1 ) ? "" : "s";
                    echo "<li><a href='view-archive.php?year=" . $post["year"] . 
                    "&amp;month=" . $post["month"] . "'>" . $post["monthyear"] . 
                    "</a> (" . $post["count"] . " post" . $plural . ")</li>\n";
                } //while ($posts = );
                echo "</ul>";
            }
            break;
        }
    ?>
    </div>

    <div id="sidebar">
        <?php include("searchform.php"); ?>
    </div>
<!-- sidebar ends -->

</div>
<!-- maincontent ends -->
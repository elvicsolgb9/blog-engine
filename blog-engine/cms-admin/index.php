<?php

    include("../include.inc");
    // Include the file that contains the variables that point 
    // to a handle of db connections and a BlogPost class that
    // will issue the SQL queries.
    include("$host" . "/blog-oop/classes/blogposts.php");
    include("$host" . "/blog-oop/model/model.php");

    // Include the file that contains the miscellaneous functions.
    include("$host" . "/blog-oop/misc/functions.php");

    // If delete has a valid post_id
    $delete = (isset($_REQUEST["delete"]))?$_REQUEST["delete"]:""; 
    if (preg_match("/^[0-9]+$/", $delete)) {
        $queryObj = new BlogPost($dbConn, $sqlQueryAdd);
        $result = $queryObj->getQueryResults();
        if (!$result) {
            $message = "<p class='messageupdatefailed'>Failed to delete post $delete. MySQL said " . 
                $dbConn->mysqli_error() . "</p>";
        } else {
            $message = "<p class='messageupdated'>Post $delete deleted.";
            $message .= "<br />" . makerssfeed() . "</p>";
        }
    }


    // Select all posts in db 
    $sqlQuery = "SELECT post_id, title, DATE_FORMAT(postdate, '%e %b %Y at %H:%i') AS dateattime FROM posts ORDER BY postdate DESC";
    $queryObj = new BlogPost($dbConn, $sqlQuery);
    $result = $queryObj->getQueryResults();
    $myposts = $queryObj->getAllResults();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<title>All blog posts - Blog CMS</title>
<style type="text/css"> @import url(cms.css); </style>
</head>
<body>
    <?php include("nav.inc") ?>
    <h1>All blog posts</h1>
    <?php
        if (isset($message)) { echo "<p class='messageupdated'>".$message."</p>"; }
        if($myposts) {
            echo "<ol>\n";
            foreach ($myposts as $post) {
                $post_id = $post["post_id"];
                $title = $post["title"];
                $dateattime = $post["dateattime"];
                echo "<li value='$post_id'>";
                echo "<a href='addpost.php?post_id=$post_id'>$title</a> posted $dateattime";
                echo " [<a href='".$_SERVER["PHP_SELF"]."?delete=$post_id' onclick='return confirm(\"Are you sure?\")'>delete</a>]";
                echo "</li>\n";
            } echo "</ol>";
        } else {
            echo "<p>There are no blog posts in the database.</p>";
        }
    ?>
</body>
</html>

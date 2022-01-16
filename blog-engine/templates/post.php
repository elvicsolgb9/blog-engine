<?php
    include("../include.inc");
    
    // Include the file that contains the variables that point 
    // to a handle of db connections and a BlogPost class that
    // will issue the SQL queries.
    include("$host" . "/blog-oop/classes/blogposts.php");
    include("$host" . "/blog-oop/model/model.php");

    // Include the file that contains the miscellaneous functions.
    include("$host" . "/blog-oop/misc/functions.php");

    // Get post_id from query string
    $post_id = (isset($_REQUEST["post_id"])) ? $_REQUEST["post_id"] : ""; 

    // If post_id is a number get 'post' from database
    if (preg_match("/^[0-9]+$/", $post_id)) {
        // Concatenate additional string query to what's already declared in 'model.php.php'
        // for a specific query that limits a result value. 
        // A convulated work around to overcome limitation in http-var value transfer.
        $sqlQuery .= " WHERE post_id=$post_id LIMIT 1";
        $queryObj = new BlogPost($dbConn, $sqlQuery);
        $posts = $queryObj->getAllResults();
    }

    // If comment has been submitted and post exists then add comment to database
    if (isset($_POST["postcomment"]) != "") {
        // Add slashes to the submitted form-data to be dealt with by MySQL.
        $posttitle = addslashes(trim(strip_tags($_POST["posttitle"])));
        $name = addslashes(trim(strip_tags($_POST["name"])));
        $email = addslashes(trim(strip_tags($_POST["email"])));
        $website = addslashes(trim(strip_tags($_POST["website"])));
        $comment = addslashes(trim(strip_tags($_POST["comment"])));
        
        // SQL query to insert comment to the comments database. Then store in $result2.
        // If comment has been submitted and post exists then add comment to database
        $sqlQueryComment = "INSERT INTO comments(post_id,name,email,website,comment) 
            VALUES ('$post_id', '$name', '$email', '$website', '$comment')";
        $queryObj = new BlogPost($dbConn, $sqlQueryComment);
        $result = $queryObj->getQueryResults();
        
        if (!$result) {
            $message = "Failed to insert comment.";
        } 
        else {
            $message = "Comment added."; 
            $comment_id = $result->insert_id;
            
            // Send yourself an email when a comment is successfully added
            $emailsubject = "Comment added to: ".$posttitle;
            $emailbody = "Comment on '" . $posttitle."'"."\r\n" ." http://www.your-domain-name.com/post.php?post_id=" . $post_id . "#c" . $comment_id . "\r\n\r\n" . $comment . "\r\n\r\n" . $name ." (" . $website . ")\r\n\r\n";
            $emailbody = stripslashes($emailbody);
            $emailheader = "From: ".$name." <".$email.">\r\n"."Reply-To: ".$email;
            mail("you@your-domain-name.com", $emailsubject, $emailbody, $emailheader);
      
            // direct to post page to eliminate repeat posts
            header("Location: ../views/view-post.php?post_id=$post_id&message=$message");
        }
    }
        
    if ($posts) {
        // SQL query to pull out the the comments stored in the comments db.
        $sqlQueryComments = "SELECT comment_id, name, website, comment FROM comments WHERE post_id=$post_id";        
        $objComments = new BlogPost($dbConn, $sqlQueryComments);
        $mycomments = $objComments->getAllResults();
    }
?>


<!-- this is the main part of the page -->
<div id="maincontent">
    <div id="posts">
        <?php
            if($posts) {
                foreach ($posts as $post) {
                    echo "<h2>" .  $post["title"] . "</h2>\n";
                    echo "<h4>Posted on:" . $post["dateattime"] . "</h4>\n";
                    echo "<div class='post'>\n" . format($post["post"]) . "\n</div>";
                }
            } 
            else {
                echo "<p>There is no post matching a post_id of $post_id.</p>";
            }
        ?>

        <div id="comments">
        <h2>Comments</h2>
        <?php
            if($mycomments) {
                echo "<dl>";
                foreach ($mycomments as $comment) {
                    if ($comment["website"] != "") {
                        echo "<dt><a href='" . $comment["comment_id"] . "'> " . $comment["name"] . "</a> wrote:</dt>\n";
                    } 
                    else {
                        echo "<dt>". $comment["name"] . " wrote:</dt>\n";
                    }
                    echo "<dd>" . format($comment["comment"]) . "</dd>\n";
                } 
                echo "</dl>";
            } 
            else {
                echo "<p>There are no comments yet.</p>";
            }
        ?>
        </div>   <!-- comments ends -->
    </div>
    <!-- posts ends -->

    <div id="sidebar">

    <?php include("searchform.php"); ?>

    <h3 id="viewarchive"><a href='/blog-oop/views/view-archive.php'>View the Archive</a></h3>
    
    <form action="<?='../templates/post.php' ?>" method="post" id="addcomment">
        <input type="hidden" name="post_id" value="<?=$post_id ?>" />
        <input type="hidden" name="posttitle" value="<?=$title ?>" />
        <h3>Add a comment</h3>
        <?php
            if (isset($message)) {
              echo "<p class='message'>" . $_POST["message"]."</p>";
            }
        ?>
        <p>Name: <input name="name" type="text" /></p>
        <p>Email: <input name="email" type="text" /></p>
        <p>Website: <input name="website" type="text" /></p>
        <p>Comment: <textarea name="comment" cols="25" rows="15"></textarea></p>
        <p><input type="submit" name="postcomment" value="Post comment" /></p>
    </form> 

    </div>
    <!-- sidebar ends -->
</div>
<!-- maincontent ends -->
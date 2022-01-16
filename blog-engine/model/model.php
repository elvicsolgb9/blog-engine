<?php

	$host = $_SERVER['CONTEXT_DOCUMENT_ROOT'];

	// Include file that contains the Database & queryObj
	// classes to be used by the model layer.
	require_once("$host" . "/blog-oop/classes/database.php");
	require_once("$host" . "/blog-oop/classes/blogposts.php");

	//////////////////////////////////////////
	// MySQL-specific DB abstraction layer //
	////////////////////////////////////////

	function connect_select_db()
	{
		// Create a database class to connect to.
		$dbConn = new Database('localhost', 'username', 'password', 'blog');
		return $dbConn;
	}

	function query_db($dbConn, $sqlQuery)
	{
		// Store the SQL query result to a MyResult object 
		// assigned through the Database class's query method.
		$queryResults = $dbConn->query($sqlQuery);
		return $queryResults;
	}

	// Database access layer //

	/////////////////////////////////////////////////////////////////////////
	// Declare variables to be used by the other files which include this. //
	// After a database connection a queryObj object will be created to store
	// an array of posts and to make a new db query.
	////////////////////////////////////////////////////////////////////////
	
	// Open connection to database & select a db first. 
	$dbConn = connect_select_db();

	///////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////
	// Initial query here will use first the standard select query to pull out the data stored in the db
    // The queries will be supplied with concatenting string later to satisfy a WHERE clause condition
    // or will be assigned different query string in the implementing file to perfrom a specific task.
    // Example: $sqlQuery .= " WHERE post_id=$post_id LIMIT 1"
	$sqlQuery = "SELECT post_id, title, summary, post, DATE_FORMAT(postdate, '%e %b %Y at %H:%i') 
       AS dateattime FROM posts";
	
	// Create an instance of queryObj for use to store array of posts.
	// And also for use in selecting a specific or random post.
	$queryObj = new BlogPost($dbConn, $sqlQuery);
    $posts = $queryObj->getAllResults();
    $queryObj->selectRandomPost();	
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////

?>
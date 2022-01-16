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
		$dbConn = new Database('localhost', 'oldandnewshelves', 'Victoriou$Mercy77', 'blog');
		return $dbConn;
	}

	// Database access layer //
	
	// Open connection to the DB server & select a db first. 
	// 	This will create a DB handle to be used  by the other files 
	//	that will require to query and get results from a DB. 
	$dbConn = connect_select_db();

	///////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////
	// Initial query here will use first the standard select query to pull out the stored data in the db.
    // The queries will be supplied with concatenting string later to satisfy a WHERE clause condition
    // or will be assigned different query string in the implementing file to perfrom a specific task.
    // Example: $sqlQuery .= " WHERE post_id=$post_id LIMIT 1"
	$sqlQuery = "SELECT post_id, title, summary, post, DATE_FORMAT(postdate, '%e %b %Y at %H:%i') 
       AS dateattime FROM posts";
	
	// Create an instance of BlogPost object for use to store array.
	// And also for use in issuing a query task to the DB.
	$queryObj = new BlogPost($dbConn, $sqlQuery);
    $posts = $queryObj->getAllResults();
    
    $queryObj->selectRandomPost();	
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////

?>
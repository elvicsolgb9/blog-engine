<?php
	class BlogPost
	{
		protected $queryResults;
		protected $numRows;
		protected $queryObj;

		// BlogPost constructor will be given a db handle
		// 	so it can issue a query to the opened db connection.
		function __construct($db, $query)
		{
			$this->queryObj = array();
			$this->queryResults = $db->query($query);
		}

		function getAllResults()
		{
			while ($row =  ($this->queryResults)->fetch_array()) {
				$this->queryObj[] = $row;
			}

			return $this->queryObj;
		}

		function selectRandomPost()
		{
			mysqli_data_seek(($this->queryResults)->get_result(), 0);
		}

		function getNumRowResults()
		{	 
			$this->numRows = ($this->queryResults)->get_numRows();
			return $this->numRows;
		}

		function getQueryResults()
		{
			return $this->queryResults;
		}
	}
?>
<?php	
	class Database 
	{
		protected $host;
		protected $user;
		protected $pwd;
		protected $dbName;
		protected $dbLink;
		protected $result;
		protected $resultObj;

		// Database class constructor
		function __construct($host, $user, $pwd, $dbName){
		    $this->host = $host;
		    $this->user = $user;
		    $this->pwd = $pwd;
		    $this->dbName = $dbName;
			$this->connect();
		}

		// Connect to the MySQL Server and Select the database 
		// ('will happen automatically at instantiation')
		public function connect() 
		{
			try {
				$this->dbLink = @mysqli_connect($this->host, $this->user, $this->pwd, $this->dbName);
				if (!$this->dbLink) {
					throw new Exception ("Couldn't connect $this->user to $this->dbName");
				}
			}
			catch (Exception $e) {
				echo 'Error from MySQL Query Command:  ' . $e->getMessage();
				exit();
			}
			
			return $this->dbLink;
		}

		// Execute an SQL query
		public function query($query) 
		{
			try {
				$this->result = mysqli_query($this->dbLink, $query, MYSQLI_STORE_RESULT);
				if (!$this->result) {
					throw new Exception ('Error from MySQL Query Command:  ' . mysqli_Error($this->dbLink));
				}
			}
			catch (Exception $e) {
				echo 'Error from MySQL Query Command:  ' . $e->getMessage();
				exit();
			}

			// store result in new object to emulate mysqli OO interface
			return $this->resultObj = new MyResult($this->result);
			
		}

		// Close MySQL Connection
		public function close()
		{
	    	mysqli_close($this->dbLink);
	    }	
	}

	class MyResult 
	{
		protected $theResult;
		protected $num_rows;

		function __construct($r) 
		{
			if (is_bool($r)) {
				$this->num_rows = 0;
			}
			else {
				$this->theResult = $r;
				// get total number of records found
				$this->num_rows = mysqli_num_rows($r);
			}
		}

		// fetch associative array of result (works on one row at a time) 
		function fetch_assoc() 
		{
			$newRow = mysqli_fetch_assoc($this->theResult);
			return $newRow;
		}

		// this function is same as above, it also fetches
		// array of result (works on one row at a time) 
		function fetch_array() 
		{
			$newRow = mysqli_fetch_array($this->theResult, MYSQLI_ASSOC);
			return $newRow;
		}

		function get_result() 
		{
			return $this->theResult;
		}

		function get_numRows() 
		{
			return $this->num_rows;
		}
	}
?>
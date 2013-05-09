
class MySQL extends DBBase {

	/**
	 * @param $dbhost
	 * @param $dbname
	 * @param $dbuser
	 * @param string $dbpass
	 * @throws Exception
	 */
	public function connect($dbhost, $dbname, $dbuser, $dbpass = '') {
		$this->con = mysql_connect($dbhost, $dbuser, $dbpass);
		mysql_select_db($dbname, $this->con);
		if (!$this->con) {
			throw new Exception("Connect failed: %s", mysql_error());
		}
	}

	/**
	 * @param $query
	 */
	public function execute($query) {
		if($this->query_result = mysql_query($query, $this->con)) {
			$this->result_array = null;
		} else {
			throw new Exception('Query failed: '.mysql_error());
		}
	}

	/**
	 * @return array|void
	 */
	public function getResultAsArray() {
		$return = array();

		if(is_array($this->result_array)) {
			return $this->result_array;
		}

		while($row = mysql_fetch_assoc($this->query_result)) {
			$return[] = $row;
		}

		$this->result_array = $return;

		return $return;
	}
}

interface iDatabase {
	public function connect($dbhost, $dbname, $dbuser, $dbpass = '');
	public function execute($query);
	public function getResultAsArray();
}
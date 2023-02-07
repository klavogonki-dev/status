<?

try {
	$db = new mysqli("localhost", "root", "", "kgru");
} catch (mysqli_sql_exception $e) {
	die("Need mysql on localhost user:root, pass:, db:kgru");
}

?>
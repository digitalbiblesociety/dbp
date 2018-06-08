<?php

$servername = env('DB_HOST');
$username = env('DB_USERNAME');
$password = env('DB_PASSWORD');
$port = env('DB_PORT');
$database=env('DB_DATABASE');

// Create connection
try {
	$conn = new PDO("mysql:host=$servername;dbname=$database;port=$port;charset=utf8", $username, $password);
	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
catch(PDOException $e)
	{
	}

echo "<!DOCTYPE html>\n<html>\n<head>\n<meta charset='utf-8' />\n<title>Bible.is Validation</title>\n</head>\n<body>";

$groupName='FCBH_GENERAL';

$sql = "SELECT bible_filesets.id,bible_filesets.hash_id,bible_filesets.set_type_code,bible_filesets.bucket_id FROM bible_filesets,access_group_filesets,access_groups WHERE access_group_filesets.hash_id=bible_filesets.hash_id AND access_group_filesets.access_group_id=access_groups.id AND access_groups.name='".$groupName."';";
if ($conn->query($sql) == TRUE) {
	$sth = $conn->prepare($sql);
	$sth->execute();
	$result = $sth->fetchAll();
	echo "\n<h3>Filesets in ".htmlspecialchars($groupName)." Group:</h3>";
	echo "\n<table style=\"border:1px solid black; border-collapse: collapse;\">";
	$isOpen=false;
	for ($i = 0; $i < count($result); $i++){
		echo "\n<tr><td style=\"border:1px solid black\">".$result[$i]['id']."</td><td style=\"border:1px solid black\">".$result[$i]['hash_id']."</td><td style=\"border:1px solid black\">".$result[$i]['bucket_id']."</td><td style=\"border:1px solid black\">".$result[$i]['set_type_code']."</td></tr>";
	}
	echo "\n</table>";
} else {
	echo "Error: " . $sql . " " . $conn->error;
}


echo "</body>\n</html>";
?>

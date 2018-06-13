<?php

$servername = $_ENV['host'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$port = $_ENV['DB_PORT'];
$database=$_ENV['DB_DATABASE'];

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

$sql = "SELECT t1.id FROM bibles t1 LEFT JOIN bible_fileset_connections t2 ON t2.bible_id=t1.id WHERE t2.bible_id IS NULL;";
if ($conn->query($sql) == TRUE) {
	$sth = $conn->prepare($sql);
	$sth->execute();
	$result = $sth->fetchAll();
	echo "\n<h3>Bibles without Fileset Connections:</h3>";
	echo "\n<p>Bible IDs in the database, but not being used (please ignore)</p>";
	echo "\n<table>";
	$isOpen=false;
	for ($i = 0; $i < count($result); $i++){
		if($i%10==0){
			if($isOpen){
				echo "</tr>\n<tr>";
			}
			else{
				echo "\n<tr>";
				$isOpen=true;
			}
		}
		echo "<td>".$result[$i]['id']."</td>";
	}
	echo "</tr>\n</table>";
	echo "\nThe total is: ".(string)count($result);
} else {
	echo "Error: " . $sql . " " . $conn->error;
}

$sql2 = "SELECT t1.hash_id,t1.id FROM bible_filesets t1 LEFT JOIN bible_fileset_connections t2 ON t2.hash_id=t1.hash_id WHERE t2.hash_id IS NULL;";
if ($conn->query($sql2) == TRUE) {
	$sth = $conn->prepare($sql2);
	$sth->execute();
	$result = $sth->fetchAll();
	echo "\n<h3>Filesets without Connections:</h3>";
	echo "\n<table>";
	$isOpen=false;
	for ($i = 0; $i < count($result); $i++){
		if($i%5==0){
			if($isOpen){
				echo "</tr>\n<tr>";
			}
			else{
				echo "\n<tr>";
				$isOpen=true;
			}
		}
		echo "<td>".$result[$i]['id']." --> ".$result[$i]['hash_id']."</td>";
	}
	echo "</tr>\n</table>";
	echo "\nThe total is: ".(string)count($result);
} else {
	echo "Error: " . $sql . " " . $conn->error;
}


$sql3 = "SELECT t1.hash_id,t1.id FROM bible_filesets t1 LEFT JOIN bible_files t2 ON t2.hash_id=t1.hash_id WHERE t2.hash_id IS NULL AND t1.set_type_code != 'text_plain';";
if ($conn->query($sql3) == TRUE) {
	$sth = $conn->prepare($sql3);
	$sth->execute();
	$result = $sth->fetchAll();
	echo "\n<h3>Filesets without Files (Ignoring plain text):</h3>";
	echo "\n<table>";
	$isOpen=false;
	for ($i = 0; $i < count($result); $i++){
		if($i%5==0){
			if($isOpen){
				echo "</tr>\n<tr>";
			}
			else{
				echo "\n<tr>";
				$isOpen=true;
			}
		}
		echo "<td>".$result[$i]['hash_id']." -> ".$result[$i]['id']."</td>";
	}
	echo "</tr>\n</table>";
	echo "\nThe total is: ".(string)count($result);
} else {
	echo "Error: " . $sql . " " . $conn->error;
}

$sql4 = "SELECT DISTINCT t1.id FROM bibles t1 LEFT JOIN bible_translations t2 ON t2.bible_id=t1.id LEFT JOIN bible_fileset_connections t3 ON t3.bible_id=t1.id WHERE t2.bible_id IS NULL AND t3.bible_id IS NOT NULL;";
if ($conn->query($sql4) == TRUE) {
	$sth = $conn->prepare($sql4);
	$sth->execute();
	$result = $sth->fetchAll();
	echo "\n<h3>Bibles without Titles (and have filesets):</h3>";
	echo "\n<table>";
	$isOpen=false;
	for ($i = 0; $i < count($result); $i++){
		if($i%5==0){
			if($isOpen){
				echo "</tr>\n<tr>";
			}
			else{
				echo "\n<tr>";
				$isOpen=true;
			}
		}
		echo "<td>".$result[$i]['id']."</td>";
	}
	echo "</tr>\n</table>";
	echo "\nThe total is: ".(string)count($result);
} else {
	echo "Error: " . $sql . " " . $conn->error;
}

$sql5 = "SELECT DISTINCT t1.id FROM bibles t1 LEFT JOIN bible_books t2 ON t2.bible_id=t1.id LEFT JOIN bible_fileset_connections t3 ON t3.bible_id=t1.id WHERE t2.bible_id IS NULL AND t3.bible_id IS NOT NULL;";
if ($conn->query($sql5) == TRUE) {
	$sth = $conn->prepare($sql5);
	$sth->execute();
	$result = $sth->fetchAll();
	echo "\n<h3>Bibles without Books (and have filesets):</h3>";
	echo "\n<table>";
	$isOpen=false;
	for ($i = 0; $i < count($result); $i++){
		if($i%5==0){
			if($isOpen){
				echo "</tr>\n<tr>";
			}
			else{
				echo "\n<tr>";
				$isOpen=true;
			}
		}
		echo "<td>".$result[$i]['id']."</td>";
	}
	echo "</tr>\n</table>";
	echo "\nThe total is: ".(string)count($result);
} else {
	echo "Error: " . $sql . " " . $conn->error;
}

$dbpCount=0;
$dbsCount=0;
$sql5c = "SELECT t1.hash_id,t1.id,t1.bucket_id FROM bible_filesets t1 LEFT JOIN bible_fileset_copyrights t2 ON t2.hash_id=t1.hash_id WHERE t2.hash_id IS NULL;";
if ($conn->query($sql5c) == TRUE) {
	$sth = $conn->prepare($sql5c);
	$sth->execute();
	$result = $sth->fetchAll();
	echo "\n<h3>Filesets without Copyrights:</h3>";
	echo "\n<table>";
	$isOpen=false;
	for ($i = 0; $i < count($result); $i++){
		if($i%5==0){
			if($isOpen){
				echo "</tr>\n<tr>";
			}
			else{
				echo "\n<tr>";
				$isOpen=true;
			}
		}
		echo "<td>".$result[$i]['id']."(".$result[$i]['bucket_id'].") --> ".$result[$i]['hash_id']."</td>";
		if($result[$i]['bucket_id']=='dbp-dev'){
			$dbpCount++;
		} elseif($result[$i]['bucket_id']=='dbs-web'){
			$dbsCount++;
		}
	}
	echo "</tr>\n</table>";
	echo "\nThe total is: ".(string)count($result);
	echo "\n<br/>DBP issues: ".(string)$dbpCount;
	echo "\n<br/>DBS issues: ".(string)$dbsCount;
} else {
	echo "Error: " . $sql . " " . $conn->error;
}

$sql5d = "SELECT t1.hash_id,t1.id,t1.bucket_id FROM bible_filesets t1 LEFT JOIN bible_fileset_copyright_organizations t2 ON t2.hash_id=t1.hash_id WHERE t2.hash_id IS NULL;";
if ($conn->query($sql5d) == TRUE) {
	$sth = $conn->prepare($sql5d);
	$sth->execute();
	$result = $sth->fetchAll();
	echo "\n<h3>Filesets without Organizations:</h3>";
	echo "\n<table>";
	$isOpen=false;
	for ($i = 0; $i < count($result); $i++){
		if($i%5==0){
			if($isOpen){
				echo "</tr>\n<tr>";
			}
			else{
				echo "\n<tr>";
				$isOpen=true;
			}
		}
		echo "<td>".$result[$i]['id']."(".$result[$i]['bucket_id'].") --> ".$result[$i]['hash_id']."</td>";
	}
	echo "</tr>\n</table>";
	echo "\nThe total is: ".(string)count($result);
} else {
	echo "Error: " . $sql . " " . $conn->error;
}

$sql5e = "SELECT DISTINCT bible_books.bible_id, GROUP_CONCAT(DISTINCT bible_books.book_id ORDER BY books.id ASC SEPARATOR ', ') AS missing_names FROM bible_books,books,bible_fileset_connections WHERE bible_books.name LIKE '[%]' AND bible_books.book_id=books.id AND bible_books.bible_id=bible_fileset_connections.bible_id GROUP BY bible_books.bible_id;";
if ($conn->query($sql5e) == TRUE) {
	$sth = $conn->prepare($sql5e);
	$sth->execute();
	$result = $sth->fetchAll();
	echo "\n<h3>Books with Placeholders ([book name]):</h3>";
	echo "\n<table style=\"border:1px solid black; border-collapse: collapse;\">";
	$isOpen=false;
	for ($i = 0; $i < count($result); $i++){
		echo "\n<tr><td style=\"border:1px solid black\">".$result[$i]['bible_id']."</td><td style=\"border:1px solid black\">".$result[$i]['missing_names']."</td></tr>";
	}
	echo "\n</table>";
	echo "\nThe total is: ".(string)count($result);
} else {
	echo "Error: " . $sql . " " . $conn->error;
}

$sql6 = "SELECT DISTINCT bibles.iso FROM bibles,bible_fileset_connections WHERE bibles.id=bible_fileset_connections.bible_id;";
if ($conn->query($sql6) == TRUE) {
	$sth = $conn->prepare($sql6);
	$sth->execute();
	$result = $sth->fetchAll();
	echo "\n<h3>Total Langauges:</h3>";
	echo "\n<p>Technically count of unique ISO-639-1 codes</p>";
	echo "\nThe total is: ".(string)count($result);
} else {
	echo "Error: " . $sql . " " . $conn->error;
}

$filesetCount=0;
$sql7 = "SELECT bible_fileset_connections.bible_id,GROUP_CONCAT(DISTINCT CONCAT(bible_translations.iso,': ',bible_translations.name) SEPARATOR '<br/>') AS bible_titles, GROUP_CONCAT(DISTINCT CONCAT(bible_filesets.id,'``',bible_filesets.bucket_id,'``',bible_filesets.set_type_code,'``',bible_filesets.hash_id,'``',bible_fileset_copyrights.copyright_description) SEPARATOR '|') AS fileset_list, GROUP_CONCAT(DISTINCT CONCAT(bible_fileset_copyright_organizations.organization_id,'~~',bible_filesets.hash_id,'~~',organizations.slug) SEPARATOR '##') AS org_list FROM bible_fileset_connections,bible_filesets,bible_translations,bible_fileset_copyrights,bible_fileset_copyright_organizations,organizations WHERE bible_fileset_connections.hash_id=bible_filesets.hash_id AND bible_fileset_connections.bible_id=bible_translations.bible_id AND bible_fileset_copyrights.hash_id=bible_filesets.hash_id AND bible_fileset_copyright_organizations.hash_id=bible_filesets.hash_id AND bible_fileset_copyright_organizations.organization_id=organizations.id GROUP BY bible_fileset_connections.bible_id;";
if ($conn->query($sql7) == TRUE) {
	$sth = $conn->prepare($sql7);
	$sth->execute();
	$result = $sth->fetchAll();
	echo "\n<h3>Total Bibles:</h3>";
	echo "\n<table style=\"border:1px solid black; border-collapse: collapse;\">";
	echo "\n<tr><td style=\"border:1px solid black\">Bible ID</td><td style=\"border:1px solid black\">Titles</td><td style=\"border:1px solid black\">Hash ID</td><td style=\"border:1px solid black\">Fileset ID</td><td style=\"border:1px solid black\">Type</td><td style=\"border:1px solid black\">bucketID</td><td style=\"border:1px solid black\">Copyright Info</td><td style=\"border:1px solid black\">Organizations</td></tr>";
	$isOpen=false;
	for ($i = 0; $i < count($result); $i++){
		$filesets = explode('|',$result[$i]['fileset_list']);
		$orgsets = explode('##',$result[$i]['org_list']);
		$orgDic=array();
		for ($k=0;$k<count($orgsets);$k++){
			$orgParts=explode('~~',$orgsets[$k]);
			if (!array_key_exists($orgParts[1],$orgDic)){
				$orgDic[$orgParts[1]]=array($orgParts[0].":".$orgParts[2]);
			} else {
				array_push($orgDic[$orgParts[1]],$orgParts[0].":".$orgParts[2]);
			}
		}
		$spanSize=count($filesets);
		for ($j = 0; $j < count($filesets); $j++){
			$fileset_parts=explode('``',$filesets[$j]);
			$filesetCount++;
			if($j==0 && count($fileset_parts)>3){
				echo "\n<tr style=\"border:1px solid black\"><td style=\"border:1px solid black\" rowspan=\"".(string)$spanSize."\">".$result[$i]['bible_id']."</td><td style=\"border:1px solid black\" rowspan=\"".(string)$spanSize."\">".$result[$i]['bible_titles']."</td><td style=\"border:1px solid black\">".$fileset_parts[3]."</td><td style=\"border:1px solid black\">".$fileset_parts[0]."</td><td style=\"border:1px solid black\">".$fileset_parts[2]."</td><td style=\"border:1px solid black\">".$fileset_parts[1]."</td><td style=\"border:1px solid black\">".$fileset_parts[4]."</td><td style=\"border:1px solid black\">".implode("<br/>",$orgDic[$fileset_parts[3]])."</td></tr>";
			} elseif(count($fileset_parts)>3) {
				echo "\n<tr><td style=\"border:1px solid black\">".$fileset_parts[3]."</td><td style=\"border:1px solid black\">".$fileset_parts[0]."</td><td style=\"border:1px solid black\">".$fileset_parts[2]."</td><td style=\"border:1px solid black\">".$fileset_parts[1]."</td><td style=\"border:1px solid black\">".$fileset_parts[4]."</td><td style=\"border:1px solid black\">".implode("<br/>",$orgDic[$fileset_parts[3]])."</td></tr>";
			}
		}
	}
	echo "\n</table>";
	echo "\nThe total is: ".(string)count($result);
	echo "\n<p>The fileset count is: ".(string)$filesetCount."</p>";
} else {
	echo "Error: " . $sql . " " . $conn->error;
}

echo "</body>\n</html>";

?>

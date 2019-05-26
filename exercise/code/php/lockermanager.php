<?php
$opt = $_REQUEST["opt"];
$id = $_REQUEST["id"];
if($opt =="e"){
	echo "enable".$id;
	enablelocker($id);
}
else if($opt =="d"){
	echo "disable".$id;
	disablelocker($id);
}
function disablelocker($id){
	require_once("connectdatabase.php");
	$sql = "UPDATE locker SET status=0 WHERE id=".$id;
	if ($conn->query($sql) === TRUE) {
		echo "Record updated successfully";
	} else {
		echo "Error updating record: " . $conn->error;
	}
	$conn->close();
}
function enablelocker($id){
	require_once("connectdatabase.php");
	$sql = "UPDATE locker SET status=1 WHERE id=".$id;
	if ($conn->query($sql) === TRUE) {
		echo "Record updated successfully";
	} else {
		echo "Error updating record: " . $conn->error;
	}
	$conn->close();
}
?>

<?php
	sleep(1);
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<script src="js/js.php"></script>
</head>
<body>
<table>
	<tr>
		<td colspan="3" class="headlocker">LOCKER</td>
		<td rowspan="7" class="controller" id="controller"><p>Click locker to use!</p></td>
	<tr>
	<tr class="typelocker">
		<td class="s">S</td>
		<td class="m">M</td>
		<td class="l">L</td>
	</tr>
<?php
	require_once('php/connectdatabase.php');
	$sql = "SELECT * FROM locker";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	$i = 0;
    while($row = $result->fetch_assoc()) {
		if($row["status"]==0){
			echo "<td class=\"disablelocker\" onclick=\"disablemessage();\" id=\"".$row["number"]."\">".$row["number"]."</td>";
		}
		else if($row["status"]==1){
			$data = "onclick=\"useLocker(".$row["ID"].",".$row["type"].",".$row["number"].")\" id=\"".$row["number"]."\">".$row["number"]."</td>";
			if($i==0){
			echo "<tr><td class=\"s\" ".$data;
		}
        else if($i==1){
			echo "<td class=\"m\" ".$data;
		}
		else if($i==2){
			echo "<td class=\"l\" ".$data."</tr>";
		}
		}
		if($i==2){
			$i=0;
		}
		else{
			$i++;
		}
    }
	} else {
    echo "0 results";
	}
$conn->close();
?>
</table>
<br>
<div style="text-align:center;display:block;">
<div>Develop by Jirayut Porka</div>
</div>
</body>
</html>
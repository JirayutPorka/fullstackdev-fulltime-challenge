<?php
	require_once("../php/connectdatabase.php");
	$sql = "SELECT * FROM lockertype";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	$i = 0;
    while($row = $result->fetch_assoc()) {
		echo "var ".$row["size"]." = [\"".$row["size"]."\",".$row["firstcharge"].",".$row["secondcharge"]."];";
		echo "\n";
    }
	} else {
    echo "0 results";
	}
$conn->close();
?>
var usingLocker = "";
window.onbeforeunload=function(event){enableLocker(usingLocker);};
function useLocker(id,type,number){
	enableLocker(usingLocker);
	disableLocker(id);
	usingLocker = id;
	showDetail(type,number);
}
var currentchargeindex = 1;
setTimeout(useSecondCharge, 3600000);
function useSecondCharge() {
  currentchargeindex = 2;
  document.getElementById("controller").innerHTML= "60 min! new price update!";
}
var finalcharge;
function showDetail(type,number){
	var firstcharge="";
	var secondcharge="";
	var size="";
	var currentcharge="";
	if(type==4){
		size = S[0];
		firstcharge = S[1];
		secondcharge = S[2];
		currentcharge = S[currentchargeindex];
	}
	if(type==5){
		size = M[0];
		firstcharge = M[1];
		secondcharge = M[2];
		currentcharge = M[currentchargeindex];
	}
	if(type==6){
		size = L[0];
		firstcharge = L[1];
		secondcharge = L[2];
		currentcharge = L[currentchargeindex];
	}
	finalcharge = currentcharge;
	document.getElementById("controller").innerHTML= "<p>Locker number "+number+"!</p><p class='controllercontent'>First 60 min : <span id='firstcharge'>"+firstcharge+"</span> THB</p><p class='controllercontent'>After 60 min <span id='secondcharge'>"+secondcharge+"</span> THB</p><p class='controllercontent' style='font-weight:bold;'>Insert Money</p><table><tbody><tr><td class='coin' onclick='addmoney(1);'>1</td><td class='coin' onclick='addmoney(2);'>2</td><td class='coin' onclick='addmoney(5);'>5</td><td class='coin' onclick='addmoney(10);'>10</td><td class='coin' style='background-color:transparent;'></td></tr><tr><td class='bil' onclick='addmoney(20);'>20</td><td class='bil' onclick='addmoney(50);'>50</td><td class='bil' onclick='addmoney(100);'>100</td><td class='bil' onclick='addmoney(500);'>500</td><td class='bil' onclick='addmoney(1000);'>1000</td></tr></tbody></table><p class='controllercontent'>Current money : <span id='currentmoney'>0</span> THB</p><p class='controllercontent'>Current price : <span id='currentprice'>"+currentcharge+"</span> THB</p><div class='btn' onclick='resett();'>RESET</div>&nbsp;&nbsp;&nbsp;<div class='btn' onclick='submitt();'>SUBMIT</div>";
}
function disableLocker(id){
	sentRequest(id,"d");
}
function enableLocker(id){
	if(usingLocker != ""){sentRequest(id,"e")};
}
function sentRequest(id,opt){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      console.log(this.responseText);
    }
	};
	xhttp.open("POST", "php/lockermanager.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("id="+id+"&opt="+opt);
}
var currentmoney = 0;
function addmoney(money){
	currentmoney += money;
	document.getElementById("currentmoney").innerHTML = currentmoney;
}
function resett(){
	currentmoney = 0;
	document.getElementById("currentmoney").innerHTML = currentmoney;
}
function submitt(){
	var message;
	var MoneyBack;
	if(currentmoney >= finalcharge){
		message="GET ITEM!<br>and this is your change:<br>";
		MoneyBack = getMoneyBack(currentmoney-finalcharge);
		message+="<p class='result'>Total change : "+(currentmoney-finalcharge)+" THB</p>";
	}
	else{
		message="CAN NOT GET ITEM!<br>the system give money back:<br>";
		MoneyBack = getMoneyBack(currentmoney);
		message+="<p class='result'>Total change : "+currentmoney+" THB</p>";
	}
	var i;
	for(i=0;i<MoneyBack.length;i++){
		message+="<p class='result'>"+MoneyBack[i]+"</p>";
	}
	message+="<p class='result'>Total price : "+finalcharge+" THB</p>";
	message+="<p class='result'>Total pay : "+currentmoney+" THB</p>";
	resett();
	enableLocker(usingLocker);
	document.getElementById("controller").innerHTML= message;
}
function getMoneyBack(money){
	var staticMoney=[1000,500,100,50,20,10,5,2,1];
	var backMoney = [];
	var currentMoney = money;
	var i;
	for(i=0;i<staticMoney.length;i++){
    	var currentStatic = staticMoney[i];
		if(currentMoney >= currentStatic){
            var tempStack = 0;
			while(tempStack < currentMoney && tempStack+currentStatic<=currentMoney){
            	tempStack+=currentStatic;
			}
			backMoney.push(currentStatic+"X"+(tempStack/currentStatic));
            currentMoney-=tempStack; 
		}
	}
	return backMoney;
}
function disablemessage(){
	document.getElementById("controller").innerHTML= "This Locker is being used! try other Locker!";
}
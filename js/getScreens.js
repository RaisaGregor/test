function getScreen(url, params){
  const request = new XMLHttpRequest();
  request.open("POST", url, true);
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.addEventListener("readystatechange", () => {

    if(request.readyState === 4 && request.status === 200) {
		document.getElementById("screens").innerHTML = request.responseText;
    }
  });
  request.send(params);	
}

function getFirstScreen(){
  getScreen("tariffs_screen1.php","");
}

function getSecondScreen(element){
  const params = "element=" + element;
  getScreen("tariffs_screen2.php", params);
  }

function getThirdScreen(tariff, pay_period){
    const params = "tariff=" + tariff + "&pay_period=" + pay_period;
    getScreen("tariffs_screen3.php", params);
}

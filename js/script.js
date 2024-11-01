window.onload = function () {
	var sendSubscribe = document.getElementById("sendSubscribe");
	var sendUnSubscribe = document.getElementById("sendUnSubscribe");
		if (sendSubscribe) {
			sendSubscribe.onclick = function() {
			sprawdzFormularz("emailsubscribe", "&subscribe=");
		}
	} else {
			sendUnSubscribe.onclick = function() {
			sprawdzFormularz("emailUnsubscribe", "&unsubscribe=");
		}
	}
}

function sprawdzFormularz(id, variable) {
	var zadanie = "";
	zadanie = new XMLHttpRequest();
	var poleEmail = document.getElementById(id).value;
	var zawartosc = poleEmail;
	var nonce = document.getElementById("_wpnonce").value;

	zadanie.open("POST", ajaxurl, true);
	zadanie.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	zadanie.responseType = "";
	zadanie.onload = function(event){ 
		var wynik = document.querySelector(".alert");
		wynik.innerHTML = zadanie.responseText;
	};
	zadanie.onerror = function() {
  		console.log('There was an error!');
	};
	zadanie.send('action=save_document' + variable + '' + zawartosc + '&_wpnonce=' + nonce);
}
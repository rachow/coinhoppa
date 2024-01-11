/**
 *  @author: $rachow
 *  @copyright: Coinhoppa
 *
 *  Bugzy is the in-house bug shipment
 *      This file must be loaded first before any other !
 *
 */

let Bugzy = {
	baseURL: process.env.MIX_APP_URL,
	uri: "/events?"+(Math.random() * 1000000000000000000),
	release: process.env.MIX_APP_VERSION,
	referer: document.referrer,
	marketing: {},
	errorBag: {}
};

window.onerror = () => {
	let str = msg.toLowerCase();
	let substr = "script error";

	Bugzy.errorBag.msg = msg;
	Bugzy.errorBag.url = url;
	Bugzy.errorBag.line = line;
	Bugzy.errorBag.col = col;
	Bugzy.errorBag.error = error;

	// Noz: fetching marketing tags.

	Bugzy.marketing.source = '';
	Bugzy.marketing.utm_source = '';
	Bugzy.marketing.utm_medium = '';
	Bugzy.marketing.utm_campaign = '';
	Bugzy.marketing.utm_content = '';
	Bugzy.marketing.utm_term = '';

	const timings = window.performance.timing;
	const loc = window.location.toString();
	const query_str = loc.split("?");

	// Noz: collect all params.
	if (query_str.length > 1) {
		let pairs = query_str[1].split("&");
		for(let i in pairs){
			let keyval = pairs[i].split("=");
			if(keyval[0] == 'source'){
				Bugzy.marketing.source = keyval[1];
			}else if(keyval[0] == 'utm_source'){
				Bugzy.marketing.utm_source = keyval[1];
			}else if(keyval[0] == 'utm_medium'){
				Bugzy.marketing.utm_medium = keyval[1];
			}else if(keyval[0] == 'utm_campaign'){
				Bugzy.marketing.utm_campaign = keyval[1];
			}else if(keyval[0] == 'utm_content'){
				Bugzy.marketing.utm_content = keyval[1];
			}else if(keyval[0] == 'utm_term'){
				Bugzy.marketing.utm_term = keyval[1];
			}
		}
	}

	if (str.indexOf(substr) > -1) {
		// cross origin error with remote script.
	}

	let wn = window.navigator;

	let platform = wn.platform.toString().toLowerCase();
	let ua = wn.userAgent.toLowerCase();
	let os = "Unknown OS";
	let timestamp = Math.floor(Date.now() / 1000);

 	if (wn.userAgent.indexOf("Win") != -1) os = "Windows";
  	if (wn.userAgent.indexOf("Mac") != -1) os = "Macintosh";
  	if (wn.userAgent.indexOf("Linux") != -1) os = "Linux";
  	if (wn.userAgent.indexOf("Android") != -1) os = "Android";
  	if (wn.userAgent.indexOf("like Mac") != -1) os = "iOS";

	Bugzy.platform = platform;
	Bugzy.agent = ua;
	Bugzy.OS = os;
	Bugzy.timestamp = timestamp;
	Bugzy.timings = timings;

    /*
	 * We need promise based and fast Fetch API.
	 * See: https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API
	*/

	let xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = () => {
		if(this.readyState == 4 && this.status == 200)
		{
			// inform or sleep on it.
			console.log('info:' + window.bug + ' bug was reported.');
			console.log('data:' + JSON.stringify(Bugzy, null, "\t"));
		}
	};

	xhttp.open("POST", Bugzy.baseURL + Bugzy.uri, true);
	xhttp.setRequestHeader('Content-Type', 'application/json');
	xhttp.setRequestHeader('Accept', 'application/json');
	xhttp.send(JSON.stringify(Bugzy));

	// we can attempt to send to Google Analytics really fast!
	// if (error) {
	//    let message = error.stack
	//    ga('send', 'event', 'window.error', message, wn.userAgent);
	// }

	return false;

};


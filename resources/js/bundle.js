/**
 * @author: $rachow
 * @copyright: Coinhoppa
 */

const welcome = () => {
    console.log(`
 $$$$$$\            $$\           $$\                                               
$$  __$$\           \__|          $$ |                                              
$$ /  \__| $$$$$$\  $$\ $$$$$$$\  $$$$$$$\   $$$$$$\   $$$$$$\   $$$$$$\   $$$$$$\  
$$ |      $$  __$$\ $$ |$$  __$$\ $$  __$$\ $$  __$$\ $$  __$$\ $$  __$$\  \____$$\ 
$$ |      $$ /  $$ |$$ |$$ |  $$ |$$ |  $$ |$$ /  $$ |$$ /  $$ |$$ /  $$ | $$$$$$$ |
$$ |  $$\ $$ |  $$ |$$ |$$ |  $$ |$$ |  $$ |$$ |  $$ |$$ |  $$ |$$ |  $$ |$$  __$$ |
\$$$$$$  |\$$$$$$  |$$ |$$ |  $$ |$$ |  $$ |\$$$$$$  |$$$$$$$  |$$$$$$$  |\$$$$$$$ |
 \______/  \______/ \__|\__|  \__|\__|  \__| \______/ $$  ____/ $$  ____/  \_______|
                                                      $$ |      $$ |                
                                                      $$ |      $$ |                
                                                      \__|      \__|              
                    Welcome to Coinhoppa.
                    Get ready to take off!
    `);
};

welcome();

// logging to console
window.log = (d, type) => {	
	if (d.constructor == Array || d.constructor == Object) {
		d = JSON.stringify(d);
	}
	if (type == undefined) {
		type = 'info';
	}
	console.log(type + ': ' + d);
};

// localstorage API
window.getStoreObj = () => {
	let store = null;
	if ('localStorage' in window && window['localStorage'] !== null) {
		store = window.localStorage;
		return store;
	}
	log('device localStorage not supported.', 'info');
	return null;
};

// return disk space in MB
window.getStoreSize = () => {
	let total = 0;
	for (let x in localStorage) {
		// twice the space as value stored in utf-16.
		let amount = (localStorage[x].length * 2) / 1024 / 1024;
		if (!isNaN(amount) && localStorage.hasOwnProperty(x)) {
			total += amount;
		}
	}
	return total.toFixed(2);
};

// grab from the localStorage.
window.getStore = (key) => {
	let store = getStoreObj();
	if (store !== null) {
		return store.getItem(key);
	}
	return false;
};

// clear everything in localStorage.
window.clearStore = () => {
	let store = getStoreObj();
	if (store !== null) {
		store.clear();
	}
};

// get store key from localStorage.
window.getStoreKey = (indx) => {
	let store = getStoreObj();
	if (store !== null) {
		return store.key(indx);
	}
	return false;
};

// add to localstorage
window.addStore = (key, item) => {
	let store = getStoreObj();
	if (store !== null) {
		store.setItem(key, item);
		return true;
	}
	return false;
};

// removing from localStorage helper.
window.removeStore = (item) => {
	let store = getStoreObj();
	if (store !== null) {
		store.removeItem(item);
		return true;
	}
	return false;
};

// grab formatted debug data
window.dd = (obj) => {
	let d = obj;
	if (typeof obj == "object" || typeof obj == "array") {
		d = JSON.stringify(obj, null, '\t');
    }
	return '<small style="word-break: break-all">' + d + '<small>';
};

// delay a callable
window.delayCallable = (callable, delay) => {
	delay = (delay == undefined) ? 3000 : delay;
	setTimeout(callable, delay);
};

// grab the DOM class/id
window.ele = (el) => {
	if (!/^\./.test(el)) {
		el = el.replace('#','');
		el = '#'+el;
	}
	return el;
};

// generate random char, append to URI for cache bursting.
window.generateRandomID = (len) => {
	let result = '';
	let rChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	let charLn = rChars.length;
	if (len == "" || len == undefined) {
		len = 32;
    }

	for (let i=0; i<len; i++) {
		result += rChars.charAt(Math.floor(Math.random() * charLn));
	}
	return result;
}

// download csv using base64 data attribute
window.downloadCSVExport = (data, name) => {
	  //window.location.href = "data:text/csv;base64,"+data;
	  const source = "data:text/csv;base64,"+data;
	  const link = document.createElement("a");
	  const file = (name != undefined) ? name + ".csv" : generateRandomID() + ".csv";
	  link.href = source;
	  link.download = file;
	  link.click();
	  link.remove();
};

// download using b64 data
window.downloadExcelExport = (data, name) => {
	// force open new tab
	// window.location.href = "data:application/vnd.ms-excel;base64,"+data;
	const source = "data:application/vnd.ms-excel;base64,"+data;
	const link = document.createElement("a");
	const file = (name != undefined) ? name + ".xlsx" : generateRandomID() + ".xlsx";
	link.href = source;
	link.download = file;
	link.click();
	link.remove();
};

// download PDF data passed as b64
window.downloadPDFExport = (data, name) => {
	const source = "data:application/pdf;base64,"+data;
	const link = document.createElement("a");
	const file = (name != undefined) ? name + ".pdf" : generateRandomID() + ".pdf";
	link.href = source;
	link.download = file;
	link.click();
	link.remove();
};

// rest form including wysiwyg editor.
window.resetFormData = (fm) => {
	$(fm).find("input,select,textarea").val('').end()
	.find("input[type=checkbox],input[type=radio]").prop("checked", "").end();

	$(fm).find("input,select,textarea").each(function(indx) {
		let idx = $(this).attr('id');
		let cls = $(this).attr('class');
		if (idx != undefined) {
			if (/textarea_editor/.test(cls)) {
				$(ele(idx)).data("wysihtml5").editor.clear();
			} else {
			    $(ele(idx)).val('');
			}
		}
	});

	let frm = $(fm).find('form').attr('id');
	let validate = $(ele(frm)).validate();
	validate.resetForm();
};

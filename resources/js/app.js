/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Bugsnag = require('@bugsnag/js');
window.baseURL = process.env.MIX_APP_URL;

// API configurations.
window.basicAuth = 'Basic ' + btoa( process.env.MIX_APP_BASIC_USERNAME 
 + ':' + process.env.MIX_APP_BASIC_PASSWORD);
window.apiHeaders = {
   headers: { 'Authorization': window.basicAuth }
};

/**
 * Icon set for console logging.
*/
window.mad = String.fromCodePoint(0x1F621);
window.scream = String.fromCodePoint(0x1F631);
window.wave = String.fromCodePoint(0x1F44B);
window.bug = String.fromCodePoint(0x1F41E);
window.flame = String.fromCodePoint(0x1F525);
window.wink = String.fromCodePoint(0x1F609);
window.robot = String.fromCodePoint(0x1F916);
window.points = String.fromCodePoint(0x1F4AF);
window.spark = String.fromCodePoint(0x1F4A5);
window.dizzy = String.fromCodePoint(0x1F4AB);
window.bomb = String.fromCodePoint(0x1F4A3);
window.rocket = String.fromCodePoint(0x1F680);

// load bug notifier
Bugsnag.start({
	apiKey: process.env.MIX_BUGSNAG_API_KEY,
	appVersion: process.env.MIX_APP_VERSION //  app release version
});

/**
 * restrict iframe - other options?
 * @2022
*/
if (window.top != window.self) {
	top.location.href = document.location.href;
}

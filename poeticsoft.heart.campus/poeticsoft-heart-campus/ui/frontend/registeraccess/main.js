/******/ (() => { // webpackBootstrap
/*!************************************************!*\
  !*** ./src/ui/frontend/registeraccess/main.js ***!
  \************************************************/
var PHC_ACESS_DATA = poeticsoft_heart_campus_register_access_data.split('||');
var PHC_POST_ID = PHC_ACESS_DATA[0];
var PHC_USER_EMAIL = PHC_ACESS_DATA[1];
var PHC_USER_IP = PHC_ACESS_DATA[2];
var PHC_LOG_URL = '/wp-json/poeticsoft/heart/campus/v1/access';
var PHC_HEARTBEAT_INTERVAL = 30000;
function trikaLog(action) {
  fetch(PHC_LOG_URL, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      correo: PHC_USER_EMAIL,
      post_id: PHC_POST_ID,
      fecha_hora: new Date().toISOString(),
      accion: action
    }),
    keepalive: true
  })["catch"](function () {});
}
trikaLog('entrada');
var heartbeat = setInterval(function () {
  return trikaLog('heartbeat');
}, PHC_HEARTBEAT_INTERVAL);
document.addEventListener('visibilitychange', function () {
  if (document.visibilityState === 'hidden') {
    clearInterval(heartbeat);
    heartbeat = null;
  } else {
    if (!heartbeat) {
      heartbeat = setInterval(function () {
        return trikaLog('heartbeat');
      }, PHC_HEARTBEAT_INTERVAL);
    }
  }
});
window.addEventListener('pagehide', function () {
  clearInterval(heartbeat);
  var data = JSON.stringify({
    correo: PHC_USER_EMAIL,
    post_id: PHC_POST_ID,
    fecha_hora: new Date().toISOString(),
    accion: 'salida'
  });
  var blob = new Blob([data], {
    type: 'application/json'
  });
  navigator.sendBeacon(PHC_LOG_URL, blob);
});
/******/ })()
;
//# sourceMappingURL=main.js.map
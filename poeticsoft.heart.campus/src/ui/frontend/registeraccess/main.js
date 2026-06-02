const PHC_ACESS_DATA = poeticsoft_heart_campus_register_access_data.split('||')

const PHC_POST_ID = PHC_ACESS_DATA[0]
const PHC_USER_EMAIL = PHC_ACESS_DATA[1]
const PHC_USER_IP = PHC_ACESS_DATA[2]
const PHC_LOG_URL = '/wp-json/poeticsoft/heart/campus/access'
const PHC_HEARTBEAT_INTERVAL = 30000

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
    }
  )
  .catch(() => {})
}

trikaLog('entrada')

let heartbeat = setInterval(
  () => trikaLog('heartbeat'), 
  PHC_HEARTBEAT_INTERVAL
)

document.addEventListener(
  'visibilitychange', 
  () => {

    if (document.visibilityState === 'hidden') {

      clearInterval(heartbeat) 
      heartbeat = null

    } else {

      if (!heartbeat) {
        
        heartbeat = setInterval(
          () => trikaLog('heartbeat'), 
          PHC_HEARTBEAT_INTERVAL
        )
      }
    }
  }
)

window.addEventListener('pagehide', () => {

  clearInterval(heartbeat)

  const data = JSON.stringify({
    correo: PHC_USER_EMAIL,
    post_id: PHC_POST_ID,
    fecha_hora: new Date().toISOString(),
    accion: 'salida'
  });

  const blob = new Blob([data], { type: 'application/json' });

  navigator.sendBeacon(
    PHC_LOG_URL,
    blob
  )
})
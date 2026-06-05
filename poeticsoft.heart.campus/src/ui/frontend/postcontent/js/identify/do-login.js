import identify from './do-identify'

export default ($, $mytools) => {  

  const $login = $mytools.find('.Login')

  $login.on(
    'click',
    function() {

      $('body').append(`
        <div class="poeticsoft-heart-campus-login-overlay">
          <div class="poeticsoft-heart-campus-login">
            <div class="Forms Identify"></div>
            <div class="Close"></div>
          </div>
        </div>
      `)
      
      const $loginWrapper = $('body .poeticsoft-heart-campus-login-overlay')
      const $wrapper = $loginWrapper.find('.poeticsoft-heart-campus-login')
      const $close = $wrapper.find('.Close')

      identify($, $wrapper)

      $close.on(
        'click',
        function() {

          $loginWrapper.remove()
        }
      )

      return false
    }
  )
}
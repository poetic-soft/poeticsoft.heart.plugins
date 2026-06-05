import login from './js/identify/do-login'
import identify from './js/identify/do-identify'
import cantAccess from './js/cantaccess/do-cantaccess'

import './main.scss'

(function($) {

  const waitIdentifyOrigin = setInterval(() => {

    if(poeticsoft_heart_campus_access_by) {

      clearInterval(waitIdentifyOrigin)
  
      const $postContent = $('.wp-block-poeticsoft-heart-campus-postcontent')
      
      const $formsIdentify = $postContent.find('.Forms.Identify')  
      const $formsCantAccess = $postContent.find('.Forms.CantAccess')

      if($formsIdentify.length) {

        identify($, $postContent)
      }  

      if($formsCantAccess.length) {

        cantAccess($, $postContent)
      }
      
      const $mytools = $('.wp-block-poeticsoft-mytools')

      if($mytools.length) {

        login($, $mytools)
      }
    }
  }, 100)

})(jQuery)

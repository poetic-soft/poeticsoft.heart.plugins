import login from './js/identify/do-login'
import identify from './js/identify/do-identify'
import shouldPay from './js/shouldpay/do-shouldpay'
import useTemporalCode from './js/identify/do-usetemporalcode'

import './main.scss'

(function($) {

  const waitIdentifyOrigin = setInterval(() => {

    if(poeticsoft_heart_campus_access_by) {

      clearInterval(waitIdentifyOrigin)
  
      const $postContent = $('.wp-block-poeticsoft_content_payment_postcontent')
      
      const $formsUseTemporalCode = $postContent.find('.Forms.UseTemporalCode')  
      const $formsIdentify = $postContent.find('.Forms.Identify')  
      const $formsShouldPay = $postContent.find('.Forms.ShouldPay')

      if($formsIdentify.length) {

        identify($, $postContent)
      }  

      if($formsShouldPay.length) {

        shouldPay($, $postContent)
      }

      if($formsUseTemporalCode.length) {

        useTemporalCode($, $postContent)
      }
      
      const $mytools = $('.wp-block-poeticsoft-mytools')

      if($mytools.length) {

        login($, $mytools)
      }
    }
  }, 100)

})(jQuery)

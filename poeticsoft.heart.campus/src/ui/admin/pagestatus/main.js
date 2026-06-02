import './main.scss'
import {
  editpagestatus,
  normalpagesstatus
} from './js/pagestatus'
import statusform from './js/statusform'

(function($) {

  const $body = $('body')
  let $pagesstatus
  let formclass

  const waitpageslist = setInterval(() => {

    if(poeticsoft_heart_campus_admin_pageslist) {

      clearInterval(waitpageslist)
      
      if($body.hasClass('block-editor-page')) {

        formclass = 'EditPage'
        $pagesstatus = editpagestatus($)
      }

      if($body.hasClass('edit-php')) {

        formclass = 'PagesList'
        $pagesstatus = normalpagesstatus($)
      }
      
      if(
        $pagesstatus
        &&
        $pagesstatus.length
      ) {
        
        statusform($, $pagesstatus, formclass)
      }
    }

  }, 100)

})(jQuery)


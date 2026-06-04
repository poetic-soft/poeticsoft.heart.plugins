import './main.scss'
import {
  editPageStatus,
  normalPagesStatus
} from './js/pagestatus'
import statusForm from './js/statusform'

(function($) {

  const $body = $('body')
  let $pagesStatus
  let formClass

  const waitPageslist = setInterval(() => {

    if(poeticsoft_heart_campus_admin_pageslist) {

      clearInterval(waitPageslist)
      
      if($body.hasClass('block-editor-page')) {

        formClass = 'EditPage'
        $pagesStatus = editPageStatus($)
      }

      if($body.hasClass('edit-php')) {

        formClass = 'PagesList'
        $pagesStatus = normalPagesStatus($)
      }
      
      if(
        $pagesStatus
        &&
        $pagesStatus.length
      ) {
        
        statusForm($, $pagesStatus, formClass)
      }
    }

  }, 100)

})(jQuery)


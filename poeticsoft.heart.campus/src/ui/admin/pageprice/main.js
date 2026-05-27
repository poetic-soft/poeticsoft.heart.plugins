import './main.scss'
import {
  editpageprice,
  normalpagesprices
} from './js/pageprice'
import priceform from './js/priceform'

(function($) {

  const $body = $('body')
  let $pagesprices
  let formclass

  const waitpageslist = setInterval(() => {

    if(poeticsoft_heart_campus_admin_pageslist) {

      clearInterval(waitpageslist)
      
      if($body.hasClass('block-editor-page')) {

        formclass = 'EditPage'
        $pagesprices = editpageprice($)
      }

      if($body.hasClass('edit-php')) {

        formclass = 'PagesList'
        $pagesprices = normalpagesprices($)
      }
      
      if(
        $pagesprices 
        &&
        $pagesprices.length
      ) {
        
        priceform($, $pagesprices, formclass)
      }
    }

  }, 100)

})(jQuery)


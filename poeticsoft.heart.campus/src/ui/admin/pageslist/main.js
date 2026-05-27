import './main.scss'
import pagelist from './js/pagelist'
import quickedit from './js/quickedit'

(function($) {

  const waitpages = setInterval(() => {    

    if(poeticsoft_heart_campus_admin_pageslist) {

      clearInterval(waitpages)

      const $body = $('body')
      if($body.hasClass('edit-php')) {

        pagelist($)
        quickedit($)

        $('body').addClass('PHCVisible')
      }
    }
  }, 100)  

})(jQuery)




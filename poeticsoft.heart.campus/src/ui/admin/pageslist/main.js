import './main.scss'
import pagelist from './js/pagelist'
import quickedit from './js/quickedit'

(function($) {

  const $body = $('body')

  const waitpages = setInterval(() => {    

    if(poeticsoft_heart_campus_admin_pageslist) {

      clearInterval(waitpages)

      if($body.hasClass('edit-php')) {

        window.poeticsoft_heart_campus_admin_pageslist_refresh = () => {

          pagelist($)
        }

        window.poeticsoft_heart_campus_admin_pageslist_refresh();
        
        $('body').addClass('PHCVisible')

        quickedit($)
      }
    }
  }, 100)  

})(jQuery)




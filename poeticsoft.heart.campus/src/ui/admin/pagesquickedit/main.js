import './main.scss';
import quickedit from './js/quickedit';

(function ($) {
    const $body = $('body');

    const waitpages = setInterval(() => {
        if (poeticsoft_heart_campus_admin_pageslist) {
            clearInterval(waitpages);

            if ($body.hasClass('edit-php')) {
                quickedit($);
            }
        }
    }, 100);
})(jQuery);

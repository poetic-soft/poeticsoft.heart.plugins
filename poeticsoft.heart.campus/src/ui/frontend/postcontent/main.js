import login from './js/identify/do-login';
import identify from './js/identify/do-identify';
import ask from './js/identify/do-ask';
import cantAccess from './js/cantaccess/do-cantaccess';

import './main.scss';

(function ($) {
    const $postContent = $('.wp-block-poeticsoft-heart-campus-postcontent');

    const $mytools = $('.wp-block-poeticsoft-heart-campus-mytools');
    if ($mytools.length) {
        login($, $mytools);

        const $formsIdentify = $postContent.find('.Forms.Identify');
        if ($formsIdentify.length) {
            ask($, $postContent);
        }
    } else {
        const $formsIdentify = $postContent.find('.Forms.Identify');
        if ($formsIdentify.length) {
            identify($, $postContent);
        }
    }

    const $formsCantAccess = $postContent.find('.Forms.CantAccess');
    if ($formsCantAccess.length) {
        cantAccess($, $postContent);
    }
})(jQuery);

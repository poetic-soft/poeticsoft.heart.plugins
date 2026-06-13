import forms from './forms';
import message from '../common/message';
import { apiFetch } from '../common/utils';

export default ($, $wrapper, email, location) => {
    const $forms = $wrapper.find('.Forms.Identify');
    $forms.find('.Form').remove();

    $forms.html(
        forms({
            form: 'confirmLink'
        })
    );

    const $linkConfirm = $forms.find('.Form.ConfirmLink');
    const $identifyResendLink = $linkConfirm.find('a.ResendLink');

    $identifyResendLink.on('click', function () {
        message($, $wrapper, 'Reenviando...', 'Warn');

        apiFetch({
            url: 'identify',
            body: {
                email: email,
                url: location
            }
        })
            .then((data) => {
                if (data.success) {
                    message($, $wrapper, 'Se ha reenviado el link.', 'Info');
                }
            })
            .catch((error) => {
                console.log(error);

                message($, $wrapper, 'Error de servidor, intentalo de nuevo, por favor.', 'Error');
            });

        return false;
    });
};

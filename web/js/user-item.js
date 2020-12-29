/**
 * Created by Zohid on 17.12.2020.
 */

(function($, yii, api){

    function init() {
        var $container = $('#chat-user-index');

        if (!$container.length) {
            return;
        }

        const roles = $container.data('roles');

        $container
            .off('click.changeRole')
            .on('click.changeRole', '.user-item .user-item__action-button', function(e) {

                var $inputButton = $(this);
                var $el = $inputButton.closest('.user-item');

                if ($el.hasClass('isHandled')) {
                    return;
                }

                var $elRole = $el.find('.user-item__role');
                var $inputRole = $el.find('.user-item__action-role:first-child');
                var userId = $el.data('id');

                $el.addClass('isHandled');
                $inputButton.attr('disabled', true);
                $inputRole.attr('disabled', true);

                api.user.updateRole(userId, $inputRole.val())
                    .done(function(user) {
                        $elRole.html(roles[user.role]);
                        $el.addClass('success');
                        setTimeout(function(){
                            $el.removeClass('success');
                        }, 1500);
                    })
                    .fail(function() {
                        $el.addClass('fail');
                        setTimeout(function(){
                            $el.removeClass('fail');
                        }, 1500);
                    })
                    .always(function() {
                        $el.removeClass('isHandled');
                        $inputButton.attr('disabled', false);
                        $inputRole.attr('disabled', false);
                    });
            });
    }

    $(function(){
        init();
    });

}(jQuery, window.yii, window.api));
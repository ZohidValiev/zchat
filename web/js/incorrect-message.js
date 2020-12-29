/**
 * Created by Zohid on 17.12.2020.
 */

(function($, yii, api){

    function init() {
        var $container = $('#chat-incorrect-message-index');

        if (!$container.length) {
            return;
        }

        $container
            .off('click.doCorrect')
            .on('click.doCorrect', '.msg-item a.msg-item__action', function(e) {
                e.stopPropagation();
                e.preventDefault();

                var $action = $(this);
                var $el = $action.closest('.msg-item');

                if ($el.data('isHandled')) {
                    return;
                }

                var msgId = $el.data('id');

                yii.confirm(
                    'Сделать сообщение корректным?',
                    function() {
                        $el
                            .data('isHandled', true)
                            .addClass('isHandled');

                        api.message.doCorrect(msgId)
                            .done(function(message) {
                                $el.addClass('isCorrect');
                                $action.remove();
                                alert('Сообщение уже корректно. Обновите страницу');
                            })
                            .fail(function() {
                                $el.data('isHandled', false);
                                alert('Произошла ошибка:');
                            })
                            .always(function() {
                                $el.removeClass('isHandled');
                            });
                    });
            });
    }

    $(function(){
        init();
    });

}(jQuery, window.yii, window.api));
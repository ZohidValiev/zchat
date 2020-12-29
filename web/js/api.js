/**
 * Created by Zohid on 16.12.2020.
 */

window.api = (function($){

    var pub = {
        message: {
            loadAll: function() {
                return $.ajax({
                    url: '/message/load-all',
                    method: 'get',
                    dataType: 'json'
                });
            },
            loadIncoming: function(lastId) {
                lastId = lastId || 0;

                return $.ajax({
                    url: '/message/load-incoming/' + lastId,
                    method: 'get',
                    dataType: 'json'
                });
            },
            loadIncorrectIds: function() {
                return $.ajax({
                    url: '/message/load-incorrect-ids',
                    method: 'get',
                    dataType: 'json'
                });
            },
            create: function(content) {
                return $.ajax({
                    url: '/message/create',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        content: content
                    }
                });
            },
            doIncorrect: function(id) {
                return $.ajax({
                    url: '/message/' + id + '/do-incorrect',
                    method: 'post',
                    dataType: 'json'
                });
            },
            doCorrect: function(id) {
                return $.ajax({
                    url: '/message/' + id + '/do-correct',
                    method: 'post',
                    dataType: 'json'
                });
            }
        },
        user: {
            updateRole: function(userId, role) {
                return $.ajax({
                    url: '/user/' + userId + '/update-role',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        role: role
                    }
                });
            }
        }
    };

    return pub;
}(jQuery));
/**
 * Created by Zohid on 16.12.2020.
 */

window.api = (function($, userToken){

    function beforeSend(xhr) {
        xhr.setRequestHeader('Authorization', 'Bearer ' + userToken.getAccessToken());
        return true;
    }

    var pub = {
        message: {
            loadAll: function() {
                return $.ajax({
                    url: '/api/msg/message',
                    method: 'get',
                    dataType: 'json',
                    beforeSend: beforeSend
                });
            },
            loadIncoming: function(lastId) {
                lastId = lastId || 0;

                return $.ajax({
                    url: '/api/msg/message/incoming/' + lastId,
                    method: 'get',
                    dataType: 'json',
                    beforeSend: beforeSend
                });
            },
            loadIncorrectIds: function() {
                return $.ajax({
                    url: '/api/msg/message/incorrect-ids',
                    method: 'get',
                    dataType: 'json',
                    beforeSend: beforeSend
                });
            },
            create: function(content) {
                return $.ajax({
                    url: '/api/msg/message',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        content: content
                    },
                    beforeSend: beforeSend
                });
            },
            doIncorrect: function(id) {
                return $.ajax({
                    url: '/api/msg/message/' + id + '/incorrect',
                    //method: 'put',
                    method: 'post',
                    dataType: 'json',
                    beforeSend: beforeSend
                });
            },
            doCorrect: function(id) {
                return $.ajax({
                    url: '/api/msg/message/' + id + '/correct',
                    //method: 'put',
                    method: 'post',
                    dataType: 'json',
                    beforeSend: beforeSend
                });
            }
        },
        user: {
            updateRole: function(userId, role) {
                return $.ajax({
                    url: '/api/msg/user/' + userId + '/role',
                    //method: 'put',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        role: role
                    },
                    beforeSend: beforeSend
                });
            }
        }
    };

    return pub;
}(jQuery, window.userToken));
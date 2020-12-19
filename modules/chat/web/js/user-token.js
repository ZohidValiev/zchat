/**
 * Created by Zohid on 14.12.2020.
 */

window.userToken = (function($) {

    function getItem(key) {
        return window.sessionStorage.getItem('user-token.' + key);
    }

    function setItem(key, value) {
        window.sessionStorage.setItem('user-token.' + key, value);
    }

    function removeItem(key) {
        window.sessionStorage.removeItem('user-token.' + key);
    }

    function init(user) {
        setItem('id', user.id);
        setItem('username', user.username);
        setItem('role', user.role);
        setItem('access-token', user.accessToken);
    }

    function destroy() {
        removeItem('id');
        removeItem('username');
        removeItem('role');
        removeItem('access-token');
    }

    $(function() {
        $(document).on('click', 'button.logout', function() {
            destroy();
        });
    });

    var pub = {
        init: function(user) {
            if (this._initialized) {
                return;
            }

            init(user);

            this._initialized = true;
        },
        isAdmin: function() {
            return this.getRole() == 3;
        },
        getId: function() {
            return parseInt(getItem('id'));
        },
        getUsername: function() {
            return getItem('username');
        },
        getRole: function() {
            return parseInt(getItem('role'));
        },
        getAccessToken: function() {
            return getItem('access-token');
        }
    };

    return pub;
}(window.jQuery));
/**
 * Created by Zohid on 14.12.2020.
 */

(function($, api, userToken, yii){

    const chat = {
        _initialized: false,
        init: function() {
            if (this._initialized) {
                return;
            }

            var $el = $('#chat');

            if (!$el.length) {
                return;
            }

            this._initialized = true;
            this._lastMsgId = undefined;
            this.$el = $('#chat');
            this.$content = this.$el.find('.chat__content');
            this.$msgBox = this.$el.find('.chat__msg-box');
            this.$input = this.$el.find('#chat-msg');
            this.msgTmpl = $('#msg-template').html();

            this.$el.on('click', '.chat-msg.success .chat-msg__action', function(e) {
                e.stopPropagation();

                var msg = $(this).closest('.chat-msg').data('model');

                if (msg.isHandled() || !msg.isCorrect()) {
                    return;
                }

                function ok() {
                    msg.setIsHandled(true);
                    api.message.doIncorrect(msg.getId())
                        .done(function(message) {
                            if (message != null) {
                                msg
                                    .setData(message)
                                    .setAsIncorrect();
                            }
                        })
                        .fail(function(xhr) {
                            console.log(xhr);
                            if (xhr.status == 403) {
                                var response = JSON.parse(xhr.responseText);
                                alert(response.message);
                            }
                        })
                        .always(function(){
                            msg.setIsHandled(false);
                        });
                }

                yii.confirm('Сделать сообщение не корректным?', ok);
            });

            this.$el.on('click', '.chat__msg-send', function(e) {
                e.stopPropagation();

                if (chat.getValue().trim() === '') {
                    return;
                }

                var msg = chat.createMessage(Message.createRawData(chat.getValue()))
                    .setAsWaiting()
                    .appendTo(chat);

                chat
                    .scrollToEnd()
                    .setValue('');

                // Create message
                api.message.create(msg.getContent())
                    .done(function(data){
                        msg
                            .refresh(data)
                            .setAsSuccess();
                        
                        chat.scrollToEnd();
                    })
                    .fail(function(){
                        msg.setAsFail();
                    });
            });

            function loadIncoming() {
                chat._loadIncoming()
                    .always(function() {
                        if (!chat.isScrolled()) {
                            chat.scrollToEnd();
                        }
                    })
                    .always(function() {
                        setTimeout(loadIncoming, 3000);
                    });
            }

            function loadIncorrectIds() {
                chat._loadIncorrectIds()
                    .always(function() {
                        setTimeout(loadIncorrectIds, 5000);
                    });
            }

            setTimeout(function() {
                chat._loadAll()
                    .always(function() {
                        chat.$input.attr('disabled', false);
                    })
                    .always(function() {
                        setTimeout(loadIncoming, 3000);
                    })
                    .always(function() {
                        setTimeout(loadIncorrectIds, 5000);
                    });
            }, 1000);
        },
        getLastMsgId: function () {
            return this._lastMsgId;
        },
        setLastMsgId: function (lastMsgId) {
            this._lastMsgId = lastMsgId;
            return this;
        },
        isScrolled: function() {
            return this.$content.scrollTop() < this._scrollTop;
        },
        scrollToEnd: function() {
            this.$content.animate({
                scrollTop: this.$msgBox.height()
            }, {
                duration: 1,
                complete: function() {
                    chat._scrollTop = chat.$content.scrollTop();
                }
            });

            return this;
        },
        _loadAll: function() {
            chat.setAsLoading();

            return api.message.loadAll()
                .done(function(messages){
                    messages.forEach(function(message) {
                        var msg = chat.createMessage(message);
                        msg
                            .setAsSuccess()
                            .appendTo(chat);

                        chat.setLastMsgId(msg.getId());
                    });

                    if (messages.length > 0) {
                        chat.scrollToEnd();
                    }
                })
                .always(function(){
                    chat.setAsLoaded();
                });
        },
        _loadIncoming: function() {
            chat.setAsLoading();

            return api.message.loadIncoming(chat.getLastMsgId())
                .done(function(messages){
                    messages.forEach(function(message) {
                        var msg = chat.createMessage(message);
                        msg
                            .setAsSuccess()
                            .appendTo(chat)
                            .animate();

                        chat.setLastMsgId(msg.getId());
                    });
                })
                .always(function(){
                    chat.setAsLoaded();
                });
        },
        _loadIncorrectIds: function() {
            return api.message.loadIncorrectIds()
                .done(function(ids) {
                    var $children;

                    if (ids.length > 0) {
                        $children = chat.$msgBox.children();
                        $children.each(function() {
                            var msg = $(this).data('model');

                            if (ids.includes(parseInt(msg.getId()))) {
                                msg.isCorrect() && msg.setAsIncorrect();
                            } else {
                                !msg.isCorrect() && msg.setAsCorrect();
                            }
                        });
                    } else {
                        $children = chat.$msgBox.children('.incorrect');
                        $children.each(function() {
                            var msg = $(this).data('model');
                            msg.setAsCorrect();
                        });
                    }
                });
        },
        getValue: function() {
            return this.$input.val();
        },
        setValue: function(value) {
            this.$input.val(value);
            return this;
        },
        setAsLoading: function() {
            this.$el.addClass('loading');
            return this;
        },
        setAsLoaded: function() {
            this.$el.removeClass('loading');
            return this;
        },
        createMessage: function(msg) {
            return new Message(this.msgTmpl, msg);
        },
        append: function($message) {
            this.$msgBox.append($message);
            return this;
        }
    };

    function Message(el, data) {
        this.$el = $(el);
        this._isHandled = false;

        this.refresh(data);

        this.$el.data('model', this);
    }

    Message.createRawData = function(content) {
        return {
            id: null,
            content: content,
            isCorrect: true,
            role: userToken.getRole(),
            username: userToken.getUsername(),
            createdAt: undefined
        };
    };

    Message.prototype = {
        refresh: function(data) {
            this.setData(data);

            if (data.marker) {
                this.setHtmlContent(data.content);
            } else {
                this.setContent(data.content);
            }
            this.setTitle(data.username);

            if (data.id) {
                this.setAttrId(data.id);
            }

            if (data.createdAt) {
                this.setTime(data.createdAt);
            }

            if (this.ownerHasAdminRole()) {
                this.setAsMarked();
            }

            if (!this.isCorrect()) {
                this.setAsIncorrect();
            }

            return this;
        },
        animate: function() {
            var self = this;
            this.$el
                .css({opacity: 0})
                .animate({opacity: 1}, 1500);
        },
        isCorrect: function() {
            return this.data.isCorrect == 1;
        },
        ownerHasAdminRole: function() {
            return  this.data.userRole == 3;
        },
        setData: function(data) {
            this.data = data;
            return this;
        },
        getId: function() {
            return this.data.id;
        },
        setAttrId: function(id) {
            this.$el
                .attr('id', 'msg-' + id);

            return this;
        },
        getAttrId: function() {
            return this.$el.attr('id');
        },
        setTitle: function(title) {
            this.$el
                .find('.chat-msg__title')
                .html(title);

            return this;
        },
        setHtmlContent: function(content) {
            this.$el
                .find('.chat-msg__content')
                .html(content);

            return this;
        },
        setContent: function(content) {
            this.$el
                .find('.chat-msg__content')
                .text(content);

            return this;
        },
        getContent: function() {
            return this.data.content;
        },
        setTime: function(time) {
            this.$el
                .find('.chat-msg__time')
                .html(time);

            return this;
        },
        isHandled: function() {
            return this._isHandled;
        },
        setIsHandled: function(isHandled) {
            this._isHandled = isHandled;

            if (isHandled) {
                this.$el.addClass('isHandled');
            } else {
                this.$el.removeClass('isHandled');
            }

            return this;
        },
        _removeClasses: function() {
            this.$el
                .removeClass('success waiting fail');
        },
        _getActionEl: function() {
            return this.$el.find('.chat-msg__action');
        },
        _removeActionEl: function() {
            var $actionEl = this._getActionEl();

            $actionEl.length && $actionEl.remove();

            return this;
        },
        setAsSuccess: function() {
            this._removeClasses();
            this.$el.addClass('success');
            return this;
        },
        setAsWaiting: function() {
            this._removeClasses();
            this.$el.addClass('waiting');
            return this;
        },
        setAsFail: function() {
            this._removeClasses();
            this.$el.addClass('fail');
            this._removeActionEl();
            return this;
        },
        setAsMarked: function() {
            this.$el.addClass('marked');
            return this;
        },
        setAsIncorrect: function() {
            this.$el.addClass('incorrect');

            if (this.data.isCorrect == 1) {
                this.data.isCorrect = 0;
            }

            return this;
        },
        setAsCorrect: function() {
            this.$el.removeClass('incorrect');

            if (this.data.isCorrect == 0) {
                this.data.isCorrect = 1;
            }

            return this;
        },
        appendTo: function(chat) {
            chat.append(this.$el);
            return this;
        }
    };

    $(function() {
        chat.init();
    });

}(jQuery, window.api, window.userToken, window.yii));
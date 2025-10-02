fpcm.modules.pollspub = {

    vars: {},

    init: function () {
        fpcm.modules.pollspub._initVote();
        fpcm.modules.pollspub._initButtons();
    },

    _initVote: function () {

        jQuery('button.fpcm-polls-poll-submit').unbind('click');
        jQuery('button.fpcm-polls-poll-reset').unbind('click');

        jQuery('button.fpcm-polls-poll-submit').click(function () {

            var data = {
                rids: [],
                pid: 0,
                fn: 'vote'
            };

            data.pid = jQuery(this).data('pollid');
            if (!data.pid) {
                return false;
            }


            jQuery('input.fpcm-polls-poll' + data.pid + '-option:checked').each(function (key, obj) {
                data.rids.push(parseInt(obj.value));
            });

            if (!data.rids.length) {
                return false;
            }

            fpcm.modules.pollspub._displayLoader(data.pid);
            fpcm.modules.pollspub._execAjax({
                method: 'post',
                action: 'ajaxpublic',
                data: data
            });

            return false;
        });

        jQuery('button.fpcm-polls-poll-reset').click(function () {

            var pid = jQuery(this).data('pollid');
            if (!pid) {
                return false;
            }

            jQuery('input.fpcm-polls-poll' + pid + '-option:checked').prop('checked', false);
            return false;
        });

    },

    _initButtons: function () {

        jQuery('button.fpcm-polls-poll-result').unbind('click');
        jQuery('button.fpcm-polls-poll-form').unbind('click');

        jQuery('button.fpcm-polls-poll-result').click(function () {

            var data = {
                pid: 0,
                fn: 'result'
            };

            data.pid = jQuery(this).data('pollid');
            if (!data.pid) {
                return false;
            }

            fpcm.modules.pollspub._displayLoader(data.pid);
            fpcm.modules.pollspub._execAjax({
                method: 'post',
                action: 'ajaxpublic',
                okCode: 300,
                data: data,
                onDone: function () {
                    fpcm.modules.pollspub._initButtons();
                }
            });

            return false;
        });

        jQuery('button.fpcm-polls-poll-form').click(function () {

            var data = {
                pid: 0,
                fn: 'pollForm'
            };

            data.pid = jQuery(this).data('pollid');
            if (!data.pid) {
                return false;
            }

            fpcm.modules.pollspub._displayLoader(data.pid);
            fpcm.modules.pollspub._execAjax({
                method: 'post',
                action: 'ajaxpublic',
                okCode: 400,
                data: data,
                onDone: function () {
                    fpcm.modules.pollspub._initButtons();
                    fpcm.modules.pollspub._initVote();
                }
            });

            return false;
        });

    },

    _displayLoader: function (pid) {
        jQuery('#fpcm-poll-poll' + pid).html('<div style="position:relative:left:0;right:0;top:0;bottom:0;text-align:center;"><img src="' + fpcm.modules.pollspub.vars.spinner + '"></div>');
    },

    _displayMsg: function (msgData) {

        if (!fpcm.pub) {
            alert(msgData.msg);
            return true;
        }

        fpcm.pub.addMessage({
            id: msgData.msgId,
            txt: msgData.msg,
            type: msgData.code < 0 ? 'error' : 'notice'
        });
    },

    _execAjax: function (_params) {
        
        if (!fpcm.pub) {
            alert('Missing FanPress CM base module "fpcm.pub". Check you you have loaded fanpress/js/fpcm.min.js or fanpress/js/fpcm.js!');
            return true;
        }

        _params.ajaxActionPath = fpcm.modules.pollspub.vars.actionPath;

        _params.onCode = {
            500: function () {
                alert('Während der Anfrage ist ein Fehler aufgetreten!');
            },
            404: function () {
                alert('Das Zeil der Anfrage wurde nicht gefunden!');
            }            
        };
        
        _params.execDone = function (result) {

            if (!result instanceof Object && result.search('FATAL ERROR:') === 3) {
                alert('Während der Anfrage ist ein Fehler aufgetreten!');
                console.error('ERROR MESSAGE: ' + errorThrown + '\n\n STATUS MESSAGE: ' + textStatus);
                return false;
            }

            if (result.code !== _params.okCode) {
                result.msgId = _params.data.fn + 'pid' + _params.data.pid + (new Date()).getTime();
                fpcm.modules.pollspub._displayMsg(result);
            }

            if (result.html !== undefined) {
                jQuery('#fpcm-poll-poll' + _params.data.pid).html(result.html);
            }

            if (!_params.onDone) {
                return false;
            }

            _params.onDone(result);
        }; 

        fpcm.pub.doAjax(_params);
    }

};
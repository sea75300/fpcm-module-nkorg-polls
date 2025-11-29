fpcm.modules.pollspub = {

    vars: {},

    init: function () {
        fpcm.modules.pollspub._initVote();
        fpcm.modules.pollspub._initButtons();
    },

    _initVote: function () {

        fpcm.system.bindClick(
            'button.fpcm-polls-poll-submit',
            (_ev) => {
                var _data = {
                    rids: [],
                    pid: 0,
                    fn: 'vote'
                };

                _data.pid = fpcm.modules.pollspub._getPidByElement(_ev.currentTarget);
                if (!_data.pid) {
                    return false;
                }

                let _selects = fpcm.modules.pollspub._getSelects(_data.pid);
                if (!_selects) {
                    return false;
                }

                for (var _i = 0; _i < _selects.length; _i++) {
                    _data.rids.push(parseInt(_selects[_i].value));
                }

                if (!_data.rids.length) {
                    return false;
                }

                fpcm.modules.pollspub._displayLoader(_data.pid);
                fpcm.modules.pollspub._execAjax({
                    method: 'post',
                    action: 'ajaxpublic',
                    data: _data
                });

                return false;
            }
        );

        fpcm.system.bindClick(
            'button.fpcm-polls-poll-reset',
            (_ev) => {

                var pid = fpcm.modules.pollspub._getPidByElement(_ev.currentTarget);
                if (!pid) {
                    return false;
                }

                let _selects = fpcm.modules.pollspub._getSelects(_data.pid);
                for (var _i = 0; _i < _selects.length; _i++) {
                    _selects[_i].checked = false;
                }

                return false;
            }
        );

    },

    _initButtons: function () {

        fpcm.system.bindClick(
            'button.fpcm-polls-poll-result',
            (_ev) => {
                var data = {
                    pid: 0,
                    fn: 'result'
                };

                data.pid = fpcm.modules.pollspub._getPidByElement(_ev.currentTarget);
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
            }
        );

        fpcm.system.bindClick(
            'button.fpcm-polls-poll-form',
            (_ev) => {

                var data = {
                    pid: 0,
                    fn: 'pollForm'
                };

                data.pid = fpcm.modules.pollspub._getPidByElement(_ev.currentTarget);
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
            }
        );

    },

    _getSelects: function(_pid) {

        let _selects = document.querySelectorAll('input.fpcm-polls-poll' + _pid + '-option:checked');
        if (!_selects) {
            return [];
        }

        return _selects;
    },

    _getPidByElement: function(_el) {

        if (_el.dataset.pollid === undefined) {
            return 0;
        }

        return _el.dataset.pollid;
    },

    _assignToPidArea: function(_pid, _code) {
        document.getElementById(`fpcm-poll-poll${_pid}`).innerHTML =_code;
    },

    _displayLoader: function (_pid) {
        fpcm.modules.pollspub._assignToPidArea(
            _pid,
            '<div style="position:relative:left:0;right:0;top:0;bottom:0;text-align:center;"><img src="' + fpcm.modules.pollspub.vars.spinner + '"></div>'
        );
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
                fpcm.modules.pollspub._assignToPidArea(
                    _params.data.pid,
                    result.html
                );
            }

            if (!_params.onDone) {
                return false;
            }

            _params.onDone(result);
        };

        fpcm.pub.doAjax(_params);
    }

};
if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.polls = {

    replyOptionsStart: 0,
    replyOptionsIdSlug: 'fpcm-nkorgpolls-reply-',

    init: function () {
        fpcm.ui_tabs.render('#polls');
        fpcm.polls._initPollsList();
        fpcm.polls._initPollForm();
    },
    
    initAfter: function() {

        if (fpcm.dataview !== undefined) {
            fpcm.dataview.render('nkorgpolls');
        }

        fpcm.polls._drawChart();
    },

    _initPollsList: function() {

        if (fpcm.vars.jsvars.isPollsList === undefined) {
            return false;
        }
        
        fpcm.ui.selectmenu('#filterStatus', {
            change: function () {
                jQuery('#fpcm-ui-form').submit();
            }
        });

    },

    _initPollForm: function() {

        if (fpcm.vars.jsvars.replyOptionsStart === undefined) {
            return false;
        }

        fpcm.polls.replyOptionsStart = fpcm.vars.jsvars.replyOptionsStart;         

        jQuery('#btnAddReplyOption').unbind('click');
        jQuery('#btnAddReplyOption').click(function () {

            fpcm.polls.replyOptionsStart++;
            jQuery('div.fpcm-ui-nkorgpolls-replyline').last().clone().attr('id', fpcm.polls.replyOptionsIdSlug + fpcm.polls.replyOptionsStart).appendTo('#fpcm-tab-form2-pane');

            var id = '#' + fpcm.polls.replyOptionsIdSlug + fpcm.polls.replyOptionsStart;
            jQuery(id).find('label span').text(fpcm.ui.translate('MODULE_NKORGPOLLS_GUI_POLL_REPLY_TXT').replace('{{id}}', fpcm.polls.replyOptionsStart));
            
            var inputEL = jQuery(id).find('input');
            inputEL.val('');
            inputEL.attr('id', 'polldatareplies' + fpcm.polls.replyOptionsStart);

            jQuery(id).find('input[type=number]').attr('id', 'polldatareplies' + fpcm.polls.replyOptionsStart).val(0);
            jQuery(id).find('input[type=hidden]').attr('id', 'polldataids' + fpcm.polls.replyOptionsStart);
            jQuery(id).find('button.fpcm-ui-nkorgpolls-removereply').attr('data-idx', fpcm.polls.replyOptionsStart);
            jQuery('.fpcm-ui-nkorgpolls-removereply').unbind('click');
            fpcm.polls._initDeleteButtonAction();
            return false;
        });

        fpcm.polls._initDeleteButtonAction();
        return true;
    },

    _initDeleteButtonAction: function () {

        jQuery('.fpcm-ui-nkorgpolls-removereply').click(function () {
            
            var btnIdx = jQuery(this).data('idx');
            if (jQuery('.fpcm-ui-nkorgpolls-removereply').length < 2) {
                return false;
            }
            
            jQuery('#' + fpcm.polls.replyOptionsIdSlug + btnIdx).remove();
            fpcm.polls.replyOptionsStart--;
            
            var replyItems = jQuery('div.fpcm-ui-nkorgpolls-replyline');
            jQuery.each(replyItems, function (idx, obj) {

                idx = (idx + 1);

                jQuery(obj).attr('id', fpcm.polls.replyOptionsIdSlug + idx);
                jQuery(obj).find('label span').text(fpcm.ui.translate('MODULE_NKORGPOLLS_GUI_POLL_REPLY_TXT').replace('{{id}}', idx));
                jQuery(obj).find('input[type=text]').attr('id', 'polldatareplies' + idx);
                jQuery(obj).find('input[type=hidden]').attr('id', 'polldataids' + idx);
                jQuery(obj).find('button.fpcm-ui-nkorgpolls-removereply').attr('data-idx', idx);
                
            });

            return false;
        });
    },

    _drawChart: function () {

        if (fpcm.vars.jsvars.pollChartData === undefined ||
            fpcm.vars.jsvars.replyOptionsStart === undefined ||
            !fpcm.vars.jsvars.voteSum) {
            return false;
        }

        fpcm.ui_chart.draw(fpcm.vars.jsvars.pollChartData);
    }

};

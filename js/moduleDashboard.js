if (fpcm === undefined) {
    var fpcm = {};
}

fpcm.dashboard.onDone.pollsDashboard = {

    execAfter: function () {

        if (fpcm.vars.jsvars.pollChartData === undefined) {
            return false;
        }

        fpcm.ui_chart.draw(fpcm.vars.jsvars.pollChartData);
        return true;
    }

};
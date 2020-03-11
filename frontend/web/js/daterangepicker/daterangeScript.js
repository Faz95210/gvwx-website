$(function () {

    moment.locale("fr");
    var start = moment().subtract(29, 'days');
    var end = moment();

    function getUrlVars() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
            vars[key] = value;
        });
        return vars;
    }

    function cb(start, end) {
        var vars = getUrlVars();
        var url = location.href;
        var queryString = url.substring(0, url.indexOf('?'));

        vars['filter'] = 'period';
        vars['from'] = start.format('DD-MMMM-YYYY');
        vars['to'] = end.format('DD-MMMM-YYYY');

        var locationUrl = queryString + "?";
        for (var key in vars) {
            locationUrl += key + "=" + vars[key] + "&";
        }
        window.location = locationUrl;
        //window.location = location.href + "&filter=period&start=" + start.format('DD-MMMM-YYYY') + "&end=" + end.format('DD-MMMM-YYYY');        
        //$('#reportrange span').html(start.format('DD-MMMM-YYYY') + ' - ' + end.format('DD-MMMM-YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        locale: {
            "applyLabel": "Valider",
            "cancelLabel": "Annuler",
            "fromLabel": "De",
            "toLabel": "à",
            "customRangeLabel": "Personnaliser",
            "weekLabel": "W",
            "daysOfWeek": [
                "Dim",
                "Lun",
                "Mar",
                "Mer",
                "Jeu",
                "Ven",
                "Sam"
            ],
            "monthNames": [
                "Janvier",
                "Février",
                "Mars",
                "Avril",
                "Mai",
                "Juin",
                "Juillet",
                "Aout",
                "Septembre",
                "Octobre",
                "Novembre",
                "Decembre"
            ],
            "firstDay": 1
        },
        ranges: {
            "Aujourd'hui": [moment(), moment()],
            'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Dernier 7 Jours': [moment().subtract(6, 'days'), moment()],
            'Dernier 30 Jours': [moment().subtract(29, 'days'), moment()],
            'Ce mois-ci': [moment().startOf('month'), moment().endOf('month')],
            'Le mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    //cb(start, end);

});
$(document).ready(function () {
    var eventFired = function (type) {
        var n = $('#example')[0];
        n.scrollTop = n.scrollHeight;
    }

    $('#example')
        .on('order.dt', function () {
            eventFired('Order');
        })
        .on('search.dt', function () {
            eventFired('Search');
        })
        .on('page.dt', function () {
            eventFired('Page');
        })
        .DataTable();

});
function initSweetAlertsDelete(title, text, confirmText, cancelText, errorTitle, errorText, itemTable, itemsId) {

    !function ($) {
        "use strict";

        function onSuccess(data) {
            if (data['result'] == 1) {
                location.reload(true);
            } else {
                Swal.fire(errorTitle, errorText, 'error');
            }
        }

        function doDelete(id) {
            const params = {
                'id': id,
                'table': itemTable
            };
            $.post('index.php?r=site/removefromdb',
                params,
                onSuccess,
                'json'
            );
        }

        var SweetAlert = function () {
        };
        // SweetAlert.prototype.init = function () {
        //Warning Message
        $('[id^=' + itemsId + ']').click(function () {
            let id = this.id.replace(itemsId, "");
            Swal.fire({
                title: title,
                text: text,
                confirmButtonText: confirmText,
                cancelButtonText: cancelText,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#02a499",
                cancelButtonColor: "#ec4561",
            }).then(function (result) {
                if (result.value) {
                    doDelete(id);
                }
            });
        });
        $.SweetAlert = new SweetAlert, $.SweetAlert.Constructor = SweetAlert
    }(window.jQuery);

}
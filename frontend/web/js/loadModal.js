/*
$(function(){
  $(document).on('click', '.showModalButton', function(){

    if ($('#modal').hasClass('in')) {
            document.getElementById('modalContent').innerHTML = 
                $('#myModalContent').html();
        document.getElementById('modalTitle').innerHTML = '<h1>' + $('#myModalTitle').html() + '</h1>';
        $('#modal').modal('show');
    } else {
        document.getElementById('modalContent').innerHTML = 
                $('#myModalContent').html();
        document.getElementById('modalTitle').innerHTML = '<h1>' + $('#myModalTitle').html() + '</h1>';
        $('#modal').modal('show');
    }
});});
*/

function loadUrlModal(title, url) {
    $('#modalContent').load(url);
    document.getElementById('modalTitle').innerHTML = '<h1>' + title + '</h1>';
    $('#modal').modal('show');
}

function loadModalWithIframe(title, url) {
    document.getElementById('modalContent').innerHTML = '<iframe width="100%" height="500" src="' + url + '"></iframe>';
    document.getElementById('modalTitle').innerHTML = '<h1>' + title + '</h1>';
    $('#modal').modal('show');
}

function loadModalWithText(title, text) {
    document.getElementById('modalContent').innerHTML = text;
    document.getElementById('modalTitle').innerHTML = '<h1>' + title + '</h1>';
    $('#modal').modal('show');
}

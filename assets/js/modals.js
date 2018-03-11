var $modal = $('#ajax-modal');
$('body').on('click', '.detail_modal',  function (ev) {
	ev.preventDefault();
	var url = $(this).attr('data-url');
    // create the backdrop and wait for next modal to be triggered
    $.fn.modalmanager.defaults.resize = true;
    $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner =
        '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
        '<div class="progress progress-striped active">'+
		'<div class="bar" style="width: 100%;"></div>'+
		'</div>'+
        '</div>';
    $('body').modalmanager('loading');
    setTimeout(function () {
        $modal.load(url, function () {
            $modal.modal();
        });
    }, 500);
});

var $modal_popup = $('#ajax-modal-popup');
$('body').on('click', '.modal_popup',  function (ev) {
	ev.preventDefault();
	var url = $(this).attr('data-url');
    // create the backdrop and wait for next modal to be triggered
    $.fn.modalmanager.defaults.resize = true;
    $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner =
        '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
        '<div class="progress progress-striped active">'+
		'<div class="bar" style="width: 100%;"></div>'+
		'</div>'+
        '</div>';
    $('body').modalmanager('loading');
    setTimeout(function () {
        $modal_popup.load(url, function () {
            $modal_popup.modal();
        });
    }, 500);
});
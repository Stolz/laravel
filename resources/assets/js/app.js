$(document).ready(function () {
    $('body').bootstrapMaterialDesign();
});

// Default AJAX settings
$.ajaxSetup({
    headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    }
});

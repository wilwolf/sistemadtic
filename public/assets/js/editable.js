$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
        }
    });

    $('.xedit').editable({
        url: '{{url("inscripciones/update")}}',
        title: 'Update',
        success: function (response, newValue) {
            console.log('Updated', response)
        }
    });

})
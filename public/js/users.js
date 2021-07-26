jQuery(document).ready(function ($) {
    'use strict';

    const statusIdUrl = $('[id$=form_statusId]').data('autocomplete-url');

    $('[id$=form_statusId]').select2({
        theme: 'bootstrap4',
        minimumInputLength: 0,
        allowClear: true,
        placeholder: '',
        ajax: {
            url: statusIdUrl,
            method: 'POST',
            dataType: 'json',
            delay: 300,
            cache: true,
            headers: {
                'X-XSRF-Token': getCookie('XSRF-Token')
            },
            data: function (params) {
                return {
                    term: params.term,
                    page: params.page
                };
            }
        },
    });
});

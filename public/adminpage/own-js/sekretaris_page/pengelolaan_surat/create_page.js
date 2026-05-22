$(document).ready(function () {
    "use strict";

    $('#jenis-surat').select2({
        placeholder: '-- Pilih Jenis Surat --',
        minimumResultsForSearch: Infinity,
        allowClear: true,
        width: '100%',
    });

    $('#tujuan-surat').select2({
        placeholder: '-- Pilih Tujuan Surat --',
        minimumResultsForSearch: Infinity,
        allowClear: true,
        width: '100%',
    });

    CKEDITOR.replace('isi-surat', {
        height: 250,
        resize_enabled: true,
        toolbar: [
            { name: 'document', items: ['Source'] },
            { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat'] },
            { name: 'lists', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
            { name: 'align', items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'insert', items: ['Table', 'HorizontalRule'] },
            '/',
            { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
            { name: 'tools', items: ['Maximize'] },
        ]
    });
});

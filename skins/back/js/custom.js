/* DataTable */
$(document).ready(function(){
    $('table.dataTableSingle').each(function () {
        $( this ).DataTable({
            responsive: true,
            language: {url: '//cdn.datatables.net/plug-ins/1.10.22/i18n/Italian.json'},
        });
    });

    $('table.dataTableMultiple').each(function () {
        $( this ).DataTable({
            responsive: true,
            language: {url: '//cdn.datatables.net/plug-ins/1.10.22/i18n/Italian.json'},
        });
    });
});


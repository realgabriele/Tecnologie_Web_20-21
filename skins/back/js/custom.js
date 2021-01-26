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

    $('table.dataTableEdit').each(function () {
        $( this ).DataTable({
            responsive: true,
            language: {url: '//cdn.datatables.net/plug-ins/1.10.22/i18n/Italian.json'},
        });
    });
});

/* Edit ajax */
function ajax_edit_request(pars){
    var r = true;
    $.post('ajax_admin_edit.php', pars, function(response){
    })
        .fail(function(error) {
            alert(JSON.stringify(error));
            r = false;
        })
    return r;
}

/**
 *
 * @param element : this clicked element
 * @param pars: associative array
 */
function set_many2many(element, pars){
    var ajax_pars = {
        type: "many2many",
        action: "create",
    };
    if(ajax_edit_request({...ajax_pars, ...pars})) {
        $(element).find("i").removeClass("edit-crossed").addClass("edit-checked");
        $(element).attr("onclick", "unset_many2many(this,"+JSON.stringify(pars)+")");
    }
}

function unset_many2many(element, pars){
    var ajax_pars = {
        type: "many2many",
        action: "delete",
    };
    if(ajax_edit_request({...ajax_pars, ...pars})) {
        $(element).find("i").removeClass("edit-checked").addClass("edit-crossed");
        $(element).attr("onclick", "set_many2many(this,"+JSON.stringify(pars)+")");
    }
}
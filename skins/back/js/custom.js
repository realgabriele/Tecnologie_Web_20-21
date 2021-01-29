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

/* Chart */
$(document).ready(function () {
    $.get('ajax_admin_graph.php', function(response){
        var my_labels = JSON.parse( response.labels );
        var my_values = JSON.parse( response.values );

        'use strict'

        var ticksStyle = {
            fontColor: '#495057',
            fontStyle: 'bold'
        }

        var mode      = 'index'
        var intersect = true

        var $visitorsChart = $('#visitors-chart')
        var visitorsChart  = new Chart($visitorsChart, {
            data   : {
                labels  : my_labels,
                datasets: [{
                    type                : 'line',
                    data                :  my_values,
                    backgroundColor     : 'transparent',
                    borderColor         : '#007bff',
                    pointBorderColor    : '#007bff',
                    pointBackgroundColor: '#007bff',
                    fill                : false
                    // pointHoverBackgroundColor: '#007bff',
                    // pointHoverBorderColor    : '#007bff'
                }]
            },
            options: {
                maintainAspectRatio: false,
                tooltips           : {
                    mode     : mode,
                    intersect: intersect
                },
                hover              : {
                    mode     : mode,
                    intersect: intersect
                },
                legend             : {
                    display: false
                },
                scales             : {
                    yAxes: [{
                        // display: false,
                        gridLines: {
                            display      : true,
                            lineWidth    : '4px',
                            color        : 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks    : $.extend({
                            beginAtZero : true,

                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display  : true,
                        gridLines: {
                            display: false
                        },
                        ticks    : ticksStyle
                    }]
                }
            }
        })

    })
        .fail(function(error) {
            alert(JSON.stringify(error));
        })

});

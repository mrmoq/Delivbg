
if(datatableListUrl && datatableColumns) {
    $(document).ready(function() {
        let table = $('#table').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            info: true,
            searching: true,
            ordering: true,
            ajax: {
                "url": datatableListUrl,
                "type": "POST",
                data: function(d) {
                    d._token = csrfToken
                }
            },
            columns: datatableColumns,
            columnDefs: [{
                className: 'text-center',
                targets: datatableTarget
            }, ],
            language: {
                "sProcessing":   sProcessing,
                "sZeroRecords":  sZeroRecords,
                "sSearch":       sSearch,
                "searchPlaceholder":  oPaginate_sSearch,
                "sLengthMenu":    sLengthMenu,
                "sInfo": sInfo,
                "oPaginate": {
                    "sFirst":    sFirst,
                    "sPrevious": sPrevious,
                    "sNext":     oPaginate_sNext,
                    "sLast":    oPaginate_sLast,
                }
            }
        })
    });
}


if(datatableListUrl && datatableColumns) {
    $(document).ready(function() {
        let table = $('#button-table').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            bLengthChange : false,
            info: true,
            searching: true,
            ordering: true,
            ajax: {
                "url": datatableListUrl,
                "type": "POST",
                data: function(d) {
                    d._token = csrfToken
                }
            },
            columns: datatableColumns,
            // dom: 'Bflrtip',
            buttons: [
                // {
                //     text: 'csv',
                //     extend: 'csvHtml5',
                // },
                {
                    text: 'excel',
                    extend: 'excelHtml5',
                },
                {
                    text: 'pdf',
                    extend: 'pdfHtml5',
                },
                {
                    text: 'print',
                    extend: 'print',
                },  
            ],
            columnDefs: [{
                className: 'text-center',
                targets: datatableTarget
            }, ],
            language: {
                "sProcessing":   sProcessing,
                "sZeroRecords":  sZeroRecords,
                "sSearch":       sSearch,
                "searchPlaceholder":  oPaginate_sSearch,
                "sLengthMenu":    sLengthMenu,
                "sInfo": sInfo,
                "oPaginate": {
                    "sFirst":    sFirst,
                    "sPrevious": sPrevious,
                    "sNext":     oPaginate_sNext,
                    "sLast":    oPaginate_sLast,
                }
            }
        })
    });
}


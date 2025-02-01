jQuery(document).ready(function($) {
    'use strict';

    // if ($("table.first").length) {

    //     $(document).ready(function() {
    //         $('table.first').DataTable();
    //     });
    // }

    console.log($.fn.dataTable.Buttons);

    /* Calender jQuery **/

    if ($("table.second").length) {
        $(document).ready(function() {
            var table = $('table.second').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "http://yellofc.com/point/data/activities",
                    type: 'GET',
                    data: function(d) {
                        // Ensure all filter fields are included and have values
                        d.service = $('#filter-service').val() || '';  // Default empty string if not selected
                        d.agency = $('#filter-agency').val() || '';
                        d.start_date = $('#filter-start-date').val() || '';
                        d.end_date = $('#filter-end-date').val() || '';
                        // Uncomment these if you want to use these filters too
                        // d.type = $('#filter-type').val() || '';
                        // d.status = $('#filter-status').val() || '';
                        // Ensure that we are not repeating columns or other default parameters
                        // Pass only necessary pagination and sorting params
                        d.start = d.start;  // Offset
                        d.length = d.length;  // Records per page
                        d.order_column = d.order[0].column;  // Column being sorted
                        d.order_dir = d.order[0].dir;  // Sort direction
                    }
                },
                columns: [
                    { "data": "id" },
                    { "data": "phone" },
                    { "data": "reference" },
                    { "data": "service" },
                    { "data": "type" },
                    { "data": "status" },
                    { "data": "amount" },
                    { "data": "date" }
                ],
                dom: 'Blfrtip', 
                paging: true,
                lengthMenu: [
                    [10, 25, 50, 100, -1], // Page size options (-1 means "All")
                    [10, 25, 50, 100, "All"] // Display labels
                ],
                pageLength: 10, // Default number of records per page
                lengthChange: true,
                buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
            });
        
            // Ensure that the filter inputs trigger the table reload
            $('.filter-input').on('change', function() {
                table.ajax.reload();  // Reload data with the updated filters
            });

            // Automatically refresh the table every 30 seconds
            setInterval(function() {
                table.ajax.reload(null, false); // Keep the current page when refreshing
            }, 30000); // 30 seconds

            table.buttons().container()
                .appendTo('#example_wrapper .col-md-6:eq(0)');
        
        })    // Optional: Initialize any additional filtering UI elements like date pickers or dropdowns
        
    }


    // if ($("#example2").length) {

    //     $(document).ready(function() {
    //         $(document).ready(function() {
    //             var groupColumn = 2;
    //             var table = $('#example2').DataTable({
    //                 "columnDefs": [
    //                     { "visible": false, "targets": groupColumn }
    //                 ],
    //                 "order": [
    //                     [groupColumn, 'asc']
    //                 ],
    //                 "displayLength": 25,
    //                 "drawCallback": function(settings) {
    //                     var api = this.api();
    //                     var rows = api.rows({ page: 'current' }).nodes();
    //                     var last = null;

    //                     api.column(groupColumn, { page: 'current' }).data().each(function(group, i) {
    //                         if (last !== group) {
    //                             $(rows).eq(i).before(
    //                                 '<tr class="group"><td colspan="5">' + group + '</td></tr>'
    //                             );

    //                             last = group;
    //                         }
    //                     });
    //                 }
    //             });

    //             // Order by the grouping
    //             $('#example2 tbody').on('click', 'tr.group', function() {
    //                 var currentOrder = table.order()[0];
    //                 if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
    //                     table.order([groupColumn, 'desc']).draw();
    //                 } else {
    //                     table.order([groupColumn, 'asc']).draw();
    //                 }
    //             });
    //         });
    //     });
    // }

    // if ($("#example3").length) {

    //     $('#example3').DataTable({
    //         select: {
    //             style: 'multi'
    //         }
    //     });

    // }
    // if ($("#example4").length) {

    //     $(document).ready(function() {
    //         var table = $('#example4').DataTable({
    //             fixedHeader: true
    //         });
    //     });
    // }

});
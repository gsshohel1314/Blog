$(function () {
    $('.js-basic-example').DataTable({
        responsive: true,
    });

    //Exportable table
    $('.js-exportable').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        ordering: false,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
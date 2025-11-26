<script>
    $(document).ready(function () {
        let table = $("#datatable").DataTable({
            "autoWidth": false,
            "pageLength": 50,
        });
        table.on( 'order.dt search.dt', function () {
            table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = (i + 1) + ".";
            } );
        }).draw();
    });
</script>

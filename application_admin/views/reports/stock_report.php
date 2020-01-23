<link rel="stylesheet" type="text/css" href="<?php echo CSS_IMAGES_JS_BASE_URL;?>datatable/datatable_all_css.css">
<script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>datatable/datatables.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Stock Report</h4></div>
    </div>
    <div class="page-content-wrapper ">
    <section class="seller-dashboard-dtl-wrap">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    
                </div>
                <div class="col-md-8 col-sm-8">
                    <div class="seller-pro-filter">

                    </div>
                </div>
                <div class="col-md-12">
                    <table id="stock_report" class="display table table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>SKU ID</th>
                            <th>Product Name</th>
                            <th>Brand</th>
                            <th>Purchase Price</th>
                            <th>Available Qqantity</th>
                            <th>Marchent ID</th>
                            <th>Marchent Name</th>
                            </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Product ID</th>
                            <th>SKU ID</th>
                            <th>Product Name</th>
                            <th>Brand</th>
                            <th>Purchase Price</th>
                            <th>Available Qqantity</th>
                            <th>Marchent ID</th>
                            <th>Marchent Name</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
    </div>
</div>

<script>

    $( document ).ready(function() {

        $('#stock_report tfoot th').each(function () {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
        });
        
        var dataTable = $('#stock_report').DataTable({
            "processing": true,
            "ajax": "<?php echo base_url('reports/get_stock_report_data'); ?>",
            "pageLength": 10,
            "scrollX": true,
            "dom": 'lBfrtip',
            "buttons": [
                {
                    extend: 'collection',
                    text: 'Export',
                    buttons: [
                        'copy',
                        'excel',
                        'csv',
                        'pdf',
                        'print'
                    ]
                }
            ]
        });
        $("#stock_report_filter").css("display","none");

        dataTable.columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                if ( that.search() !== this.value ) {
                    that
                        .search( this.value )
                        .draw();
                }
            } );
        } );

        


    });
</script>
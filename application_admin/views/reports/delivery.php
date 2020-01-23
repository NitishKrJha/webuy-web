<link rel="stylesheet" type="text/css" href="<?php echo CSS_IMAGES_JS_BASE_URL;?>datatable/datatable_all_css.css">
<script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>datatable/datatables.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Sales Report</h4></div>
    </div>
    <div class="page-content-wrapper ">
    <section class="seller-dashboard-dtl-wrap">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <div class="seller-pro-filter">
                       <p>
                           From Order Date: <input id="min" class="form-control" type="text" >
                       </p>
                       <p>
                           To Order Date: <input id="max" class="form-control" type="text">
                       </p>
                       <p>
                           Delivery Status: 
                           <select id="delivery_status" class="form-control">
                            <option value="">Select</option>
                            <?php foreach ($orderStatus as $order) { ?>
                                <option value="<?php echo $order['status']?>"><?php echo $order['status']?></option>
                            <?php } ?>
                           </select>
                       </p>
                    </div>
                </div>
                <div class="col-md-8 col-sm-8">
                    <div class="seller-pro-filter">

                    </div>
                </div>
                <div class="col-md-12">
                    <table id="sales_report" class="display table table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>OrderID</th>
                            <th>OrderDt</th>
                            <th> Delivery Status</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Address</th>
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Seller</th>

                        </tr>
                        </thead>
                        
                    </table>
                </div>
            </div>
        </div>
    </section>
    </div>
</div>

<script>

    $( document ).ready(function() {

        $('#sales_report tfoot th').each(function () {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
        });
        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var min = $('#min').datepicker("getDate");
                var max = $('#max').datepicker("getDate");
                var startDate = new Date(data[1]);
                if (min == null && max == null) { return true; }
                if (min == null && startDate <= max) { return true;}
                if(max == null && startDate >= min) {return true;}
                if (startDate <= max && startDate >= min) { return true; }
                return false;
            }
        );
        var dataTable = $('#sales_report').DataTable({
            "processing": true,
            "ajax": "<?php echo base_url('reports/get_delivery_reports'); ?>",
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
        $("#sales_report_filter").css("display","none");

        $('#delivery_status').on('change keyup', function(){

                dataTable
                .column(2)
                .search(this.value)
                .draw();

              });

        $("#min").datepicker({ onSelect: function () { dataTable.draw(); }, changeMonth: true, changeYear: true });
        $("#max").datepicker({ onSelect: function () { dataTable.draw(); }, changeMonth: true, changeYear: true });

        $('#min, #max').change(function () {
            dataTable.draw();
        });


    });
</script>
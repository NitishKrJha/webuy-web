<link rel="stylesheet" type="text/css" href="<?php echo CSS_IMAGES_JS_BASE_URL;?>datatable/datatable_all_css.css">
<script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>datatable/datatables.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Customer Wise Report</h4></div>
    </div>
    <div class="page-content-wrapper ">
        <section class="seller-dashboard-dtl-wrap">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4 col-sm-4">
                        <div class="seller-pro-filter">
                            <p>
                                Customer ID: <input id="customer_id" class="form-control" type="text" >
                            </p>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <div class="seller-pro-filter">

                        </div>
                    </div>
                    <div class="col-md-12">
                        <table id="customer_report" class="display table table-bordered" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>OrderID</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <!-- <th>Order Type</th> -->
                                <th>Purchase Amount</th>
                                <!-- <th>Reward Points</th> -->
                                <th>Purchase Date and Time</th>
                                <!-- <th>Delivery Status</th> -->
                                <th>Payment Mode</th>
                                <th>Payment Status</th>
                                <!-- <th>Order Status</th> -->
                                <th>Net Cost</th>
                                <th>Discount</th>
                                <th>Shipping Cost</th>
                                <!-- <th>Tax</th> -->
                                <!-- <th>Total Payable</th> -->
                                <!-- <th>Seller/ Merchant id and Name</th> -->

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

        var dataTable = $('#customer_report').DataTable({
            "processing": true,
            "ajax": "<?php echo base_url('reports/get_customer_wise_report'); ?>",
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
        $("#customer_report_filter").css("display","none");
        

            $('#customer_id').on('change keyup', function(){

                dataTable
                .column(1)
                .search(this.value)
                .draw();

              });
                });
</script>
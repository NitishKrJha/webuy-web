<link rel="stylesheet" type="text/css" href="<?php echo CSS_IMAGES_JS_BASE_URL;?>datatable/datatable_all_css.css">
<script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>datatable/datatables.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Merchants</h4></div>
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
                           Merchants Name: 
                           <select id="merchants_name" class="form-control">
                            <option value="">Select Merchant</option>
                            <?php foreach ($merchantsData as $merchant) { ?>
                                <option value="<?php echo $merchant['merchants_id']?>"><?php echo $merchant['first_name'].' '.$merchant['last_name']?></option>
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
                            <th>Product ID</th>
                            <th>Merchant ID</th>
                            <th> Status Change Dates</th>
                            <th>Name</th>
                            <th>Price</th>

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
            "ajax": "<?php echo base_url('reports/get_merchants_reports'); ?>",
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

        $('#merchants_name').on('change keyup', function(){

                dataTable
                .column(1)
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
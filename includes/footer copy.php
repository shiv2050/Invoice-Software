

<footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <?php echo date('Y')?> © OM Software.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-right d-none d-sm-block">
                                Design & Develop by Shivendra
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- Overlay-->
    <div class="menu-overlay"></div>


    <!-- jQuery  -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/metismenu.min.js"></script>
    <script src="assets/js/waves.js"></script>
    <script src="assets/js/simplebar.min.js"></script>

    <!-- Morris Js-->
    <script src="assets/plugins/morris-js/morris.min.js"></script>
    <!-- Raphael Js-->
    <script src="assets/plugins/raphael/raphael.min.js"></script>

    <!-- Morris Custom Js-->
    <script src="assets/pages/dashboard-demo.js"></script>
    <!-- third party js -->
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.bootstrap4.js"></script>
    <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="assets/plugins/datatables/responsive.bootstrap4.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
    <script src="assets/plugins/datatables/buttons.html5.min.js"></script>
    <script src="assets/plugins/datatables/buttons.flash.min.js"></script>
    <script src="assets/plugins/datatables/buttons.print.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.keyTable.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.select.min.js"></script>
    <script src="assets/plugins/datatables/pdfmake.min.js"></script>
    <script src="assets/plugins/datatables/vfs_fonts.js"></script>
    <!-- third party js ends -->

    <!-- Datatables init -->
    <script src="assets/pages/datatables-demo.js"></script>
    <!-- App js -->
    <script src="assets/js/theme.js"></script>
    <script>
        $(document).ready(function() {
            $('#basic-datatable').DataTable();
        });

        function printTable() {
            var printContents = document.querySelector('.card-body').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
    <!--  -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to toggle the tax rate field
    function toggleTaxRate() {
        const taxableSelect = document.querySelector('select[name="taxable"]');
        const taxRateField = document.getElementById('tax-rate-field');
        if (taxableSelect.value === 'yes') {
            taxRateField.style.display = 'block';
        } else {
            taxRateField.style.display = 'none';
        }
    }

    // Initial toggle based on the default value
    toggleTaxRate();

    // Add event listener to the taxable select element
    document.querySelector('select[name="taxable"]').addEventListener('change', toggleTaxRate);

    // Function to add another product field
    function addProductField() {
        const productSection = document.getElementById('product-section');
        const newProductDiv = document.createElement('div');
        newProductDiv.classList.add('product-item');
        newProductDiv.innerHTML = `
            <div class="form-group">
                <label for="product_ids">Select Product</label>
                <select name="product_ids[]" class="form-control product-select" required>
                    <option value="">Choose Product</option>
                    <?php foreach ($products_array as $product): ?>
                    <option value="<?php echo $product['product_id']; ?>">
                        <?php echo $product['product_name']; ?> - ₹<?php echo $product['price']; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantities">Quantity</label>
                <input type="number" name="quantities[]" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <label>Is Taxable?</label>
                <select name="taxable" class="form-control" onchange="toggleTaxRate()" required>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>
            <div id="tax-rate-field" class="form-group" style="display: none;">
                <label for="tax_rate">Select Tax Rate</label>
                <select name="tax_rate" class="form-control">
                    <option value="0">0%</option>
                    <option value="18">18%</option>
                    <option value="28">28%</option>
                </select>
            </div>
        `;
        productSection.appendChild(newProductDiv);
    }

    // Add event listener to the Add Another Product button
    document.getElementById('add-product').addEventListener('click', addProductField);
});
</script>

</body>



</html>
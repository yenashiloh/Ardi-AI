<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<!-- Custom JS -->
<script src="../../assets/js/admin/main.js"></script>

<!-- Sweet Alert  -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    //Data table
    $(document).ready(function() {
        $('#data-table').DataTable({
            responsive: true,
            // Add some basic styling
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "pageLength": 5
        });
        
        // Toggle sidebar
        $("#sidebarToggle").on("click", function() {
            $("#sidebar").toggleClass("collapsed");
            $("#mainContent").toggleClass("expanded");
        });
    });

    //Tooltip
    document.addEventListener("DOMContentLoaded", function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
 //Data table
 $(document).ready(function() {
    $('#data-table').DataTable({
        responsive: true,
        // Add some basic styling
        "lengthMenu": [[10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        "pageLength": 10
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
<div id="loadingIndicator" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
    <div class="text-white">Loading...</div>
</div>

<script>
    function showLoading() {
        document.getElementById("loadingIndicator").classList.remove("hidden");
    }

    // Fungsi untuk menutup loading
    function hideLoading() {
        document.getElementById("loadingIndicator").classList.add("hidden");
    }
</script>
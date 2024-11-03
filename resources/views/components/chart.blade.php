<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script> <!-- Sertakan CanvasJS di sini -->
<style>
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 10px;
    }

    .pagination button {
        margin: 0 5px;
        padding: 5px 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background: #f0f0f0;
        cursor: pointer;
    }

    .pagination button.active {
        background: #007BFF;
        color: white;
    }
</style>

<div>
    <!-- Chart Popup -->
    <div id="chartPopup" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-10 rounded shadow-lg relative w-1/2 flex flex-col max-h-[80vh] overflow-hidden">
            <button onclick="closeChartPopup()" class="absolute top-2 right-2 text-gray-700">✕</button>

            <div class="flex items-end mb-4">
                <!-- Filter Section -->
                <div class="mr-4 flex flex-col gap-2">
                    <label for="startDate">Start Date:</label>
                    <input type="datetime-local" id="startDate" class="border border-gray-300 rounded p-2">
                </div>
                <div class="mr-4 flex flex-col gap-2">
                    <label for="endDate">End Date:</label>
                    <input type="datetime-local" id="endDate" class="border border-gray-300 rounded p-2">
                </div>
                <button onclick="filterData()"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">Apply
                    Filter</button>
            </div>

            <!-- Data Table with Pagination -->
            <div class="overflow-auto max-h-60 mb-4">
                <table class="min-w-full border-collapse border border-gray-300 mt-4">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">Date</th>
                            <th class="border border-gray-300 px-4 py-2">Value</th>
                        </tr>
                    </thead>
                    <tbody id="dataTableBody"></tbody>
                </table>
            </div>

            <div class="pagination mb-4" id="pagination"></div> <!-- Pagination Container -->

            <!-- Chart Container -->
            <div id="chartContainer" style="height: 370px; width: 100%;"></div>
        </div>
    </div>
</div>

<script>
    let currentSensorId = 1; // Default sensor ID
    let currentData = []; // To store current data for table
    let currentPage = 1; // To track the current page
    const rowsPerPage = 10; // Set max rows per page

    // Function to show the chart popup
    function showChartPopup() {
        document.getElementById("chartPopup").classList.remove("hidden");
    }

    // Function to hide the chart popup
    function closeChartPopup() {
        document.getElementById("chartPopup").classList.add("hidden");
    }

    // Function to render the chart with provided dataPoints
    function renderChart(dataPoints, sensorName) {
        const chartContainer = document.getElementById("chartContainer");
        if (!chartContainer) {
            console.error("Chart Container with id 'chartContainer' was not found");
            return;
        }

        const chart = new CanvasJS.Chart(chartContainer, {
            theme: "light2",
            animationEnabled: true,
            zoomEnabled: true,
            title: {
                text: sensorName // Judul diambil dari sensorName
            },
            axisX: {
                title: "", // Kosongkan judul untuk menghilangkan teks "Timestamp"
                valueFormatString: "YYYY-MM-DD HH:mm:ss", // Format label sumbu X
                labelAngle: -45 // Mengatur sudut label
            },
            axisY: {
                title: "Value"
            },
            data: [{
                type: "line",
                dataPoints: dataPoints
            }]
        });
        chart.render();
    }

    // Function to process the API response into chart data points
    function processApiResponse(apiResponse) {
        if (!Array.isArray(apiResponse)) {
            console.error("API response is not valid or empty:", apiResponse);
            return [];
        }
        return apiResponse.map(item => ({
            x: new Date(item.addTime), // Convert addTime to a Date object
            y: parseFloat(item.val) // Convert val to a numeric value
        }));
    }

    // Function to load data from API and render chart
    document.addEventListener('chart-data-loaded', function(event) {
        const dataPoints = processApiResponse(event.detail.dataList);
        const sensorName = event.detail.sensorName; // Ambil nama sensor dari detail
        renderChart(dataPoints, sensorName);
        showChartPopup(); // Show the popup after rendering the chart

        // Populate the table and pagination
        populateTable(event.detail.dataList);
    });

    function getRealtime(id) {
        currentSensorId = id; // Simpan ID sensor yang dikirim
        document.getElementById("startDate").value = ""; // Reset start date input
        document.getElementById("endDate").value = ""; // Reset end date input
        const url = `{{ secure_url('sensors') }}/${id}/realtime`;

        console.log("Fetching data from:", url); // Log URL untuk memeriksa
        fetch(url, {
                method: "GET",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok " + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                console.log("Data received:", data); // Log data yang diterima
                const sensorData = {
                    dataList: data.data.dataList,
                    sensorName: data.data.sensorName // Ambil nama sensor dari respons
                };
                currentData = sensorData.dataList; // Simpan data untuk tabel
                document.dispatchEvent(new CustomEvent('chart-data-loaded', {
                    detail: sensorData
                }));
            })
            .catch(error => {
                console.error("Error fetching data:", error); // Log error jika terjadi kesalahan
            });
    }

    function filterData() {
        const startDate = document.getElementById("startDate").value;
        const endDate = document.getElementById("endDate").value;

        // Periksa apakah ada filter yang diberikan
        if (!startDate && !endDate) {
            console.log("No filters applied, fetching latest data.");
            getRealtime(currentSensorId); // Jika tidak ada filter, ambil data terbaru
            return;
        }

        // Jika filter ada, bisa menggunakan logika lain di sini
        // (misalnya menggunakan fetch dengan parameter untuk startDate dan endDate)
        console.log("Applying filter with startDate:", startDate, "endDate:", endDate);

        // Jika ingin mengirim data filter, Anda bisa melakukannya dengan fetch
        const filterUrl = `{{ url('sensors') }}/${currentSensorId}/realtime?startDate=${startDate}&endDate=${endDate}`;
        fetch(filterUrl, {
                method: "GET",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(response => response.json())
            .then(data => {
                const sensorData = {
                    dataList: data.data.dataList,
                    sensorName: data.data.sensorName // Ambil nama sensor dari respons
                };
                currentData = sensorData.dataList; // Simpan data untuk tabel
                document.dispatchEvent(new CustomEvent('chart-data-loaded', {
                    detail: sensorData
                }));
            })
            .catch(error => {
                console.error("Error fetching filtered data:", error);
            });
    }

    function populateTable(dataList) {
        // Sort the data by addTime in ascending order
        const sortedData = dataList.sort((a, b) => new Date(a.addTime) - new Date(b.addTime));

        const tableBody = document.getElementById("dataTableBody");
        tableBody.innerHTML = ""; // Clear previous data

        const totalRows = sortedData.length; // Update totalRows to the length of sorted data
        const totalPages = Math.ceil(totalRows / rowsPerPage);

        // Paginate data
        const startRow = (currentPage - 1) * rowsPerPage;
        const endRow = Math.min(startRow + rowsPerPage, totalRows);
        for (let i = startRow; i < endRow; i++) {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td class="border border-gray-300 px-4 py-2">${new Date(sortedData[i].addTime).toLocaleString()}</td>
                <td class="border border-gray-300 px-4 py-2">${sortedData[i].val}</td>
            `;
            tableBody.appendChild(row);
        }

        // Render pagination
        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        const pagination = document.getElementById("pagination");
        pagination.innerHTML = ""; // Clear previous pagination

        const maxPagesToShow = 5; // Maximum number of pagination buttons to display

        // Calculate start and end page numbers for pagination display
        let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
        let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

        // Adjust the start page if there are fewer than 5 total pages
        if (endPage - startPage < maxPagesToShow - 1) {
            startPage = Math.max(1, endPage - maxPagesToShow + 1);
        }

        // Create pagination buttons
        for (let page = startPage; page <= endPage; page++) {
            const button = document.createElement("button");
            button.innerText = page;
            button.className = page === currentPage ? "active" : "";
            button.onclick = function() {
                currentPage = page; // Update current page
                populateTable(currentData); // Populate table with current data
            };
            pagination.appendChild(button);
        }

        // Previous and Next buttons
        if (currentPage > 1) {
            const prevButton = document.createElement("button");
            prevButton.innerText = "Previous";
            prevButton.onclick = function() {
                if (currentPage > 1) {
                    currentPage--;
                    populateTable(currentData);
                }
            };
            pagination.prepend(prevButton);
        }

        if (currentPage < totalPages) {
            const nextButton = document.createElement("button");
            nextButton.innerText = "Next";
            nextButton.onclick = function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    populateTable(currentData);
                }
            };
            pagination.appendChild(nextButton);
        }
    }
</script>

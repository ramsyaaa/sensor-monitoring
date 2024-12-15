<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Create New Report</h2>
    </div>
    <div>
        <p>{{ $device_name }}</p>
        <p>{{ $point_code }}</p>
    </div>

    <div class="overflow-x-auto bg-white px-6 py-8 rounded-lg shadow-lg">
        <form id="reportForm">
            @csrf
            <div class="mb-4">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input 
                    type="date" 
                    id="start_date" 
                    name="start_date" 
                    class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"
                >
                <span class="text-red-600 text-sm mt-1" id="start_date_error"></span>
            </div>
            <div class="mb-6">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input 
                    type="date" 
                    id="end_date" 
                    name="end_date" 
                    class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"
                    min=""
                >
                <span class="text-red-600 text-sm mt-1" id="end_date_error"></span>
            </div>
            <div class="flex justify-end">
                <button 
                    type="button"
                    onclick="submitData(this, {{ $id }})"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md shadow"
                >
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

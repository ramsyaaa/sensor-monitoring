<div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-white">Data Sensor Device</h2>
    </div>

    <div class="overflow-x-auto bg-white px-6 pb-10 rounded-lg shadow-lg">
        <div class="py-5 font-bold text-[20px]">
            Device ID:{{ $data['device']['id'] }} <br>
            Device Name: {{$data['device']['deviceName']}}<br>
            Device Number: {{$data['device']['deviceNo']}}<br>
            {{-- <strong>Address:</strong> {{$data['device']['address']}}<br>
            <strong>Point Code:</strong> {{$data['device']['point_code']}}<br>
            <strong>Location Info:</strong> {{$data['device']['location_information']}}<br>
            <strong>Note:</strong> {{$data['device']['note']}}<br>
            <strong>Surrounding Waters:</strong> {{$data['device']['surrounding_waters']}}<br>
            <strong>Electrical Panel:</strong> {{$data['device']['electrical_panel']}}<br> --}}
        </div>
        <table class="min-w-full border border-gray-300 rounded-lg shadow-md">
            <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <tr class="text-[12px]">
                    <th class="py-3 px-6 text-left">No</th>
                    <th class="py-3 px-6 text-center">Sensor</th>
                    <th class="py-3 px-6 text-left">Value</th>
                    <th class="py-3 px-6 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm font-light">
                @foreach ($sensors as $index => $sensor)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">{{ $loop->iteration }}</td>
                    <td class="py-3 px-6 text-left flex gap-4 items-start">
                        <div>
                            <img class="max-w-[40px]" src="{{ checkUrlIcon($sensor['sensorName']) }}" alt="">
                        </div>
                        <div>
                            <span class="font-bold text-[16px]">{{ $sensor['sensorName'] }}</span>
                            <br>
                            <span class="text-[12px]">ID:{{ $sensor['id'] }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-left">
                        <span class=" text-[20px]">{{ $sensor['value'] }} {{ $sensor['unit'] }}</span>
                        <br>
                        <span>Updated {{ $sensor['updateDate'] }}</span>
                    </td>
                    <td class="py-3 px-6">
                        <div class="flex gap-2 justify-center">
                            <a href="javascript:void(0)" onclick="getRealtime({{ $sensor['id'] }})" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-200">RT / History Curv</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="flex items-center justify-between mb-4">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-white">Edit User Password</h2>
</div>
<div class="overflow-x-auto bg-white rounded-lg px-6 py-10 shadow-lg">
    <div>
        <div class="space-y-4">
            <form action="{{ '/users/' . $id . '/update' }}" method="POST">
                @csrf
                <div class="flex flex-col md:flex-row gap-4 items-center">
                    <label for="username" class="font-semibold w-40">Username</label>
                    <div class="w-full flex flex-col gap-2">
                        <input id="username" type="text" name="username" value="{{ old('username', $username) }}" class="w-full flex-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                        <div class="text-red-500 text-[12px]">
                            @error('username')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 items-center">
                    <label for="password" class="font-semibold w-40">Password</label>
                    <div class="w-full flex flex-col gap-2">
                        <input id="password" type="password" name="password" class="w-full flex-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>

                <div class="flex justify-center mt-4">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-6 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Save Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
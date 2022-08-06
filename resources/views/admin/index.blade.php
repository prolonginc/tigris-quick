<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Pending Users</h1>
                <p class="mt-2 text-sm text-gray-700">A list of all the users that are pending and awaiting approval</p>
            </div>
        </div>
        <div class="-mx-4 mt-8 overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:-mx-6 md:mx-0 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                    <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">Business Name</th>
                    <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">Phone</th>
                    <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">Email</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Approve</span>
                    </th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                @foreach($pendingUsers as $user)
                    <tr>
                        <td class="w-full max-w-0 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none sm:pl-6">
                            {{$user->name}}
                        </td>
                        <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell"> {{$user->business_name}}</td>
                        <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell"> {{$user->phone}}</td>
                        <td class="px-3 py-4 text-sm text-gray-500"> {{$user->email}}</td>
                        <td class="py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <a href="{{route('admin.approve', $user->id)}}" class="text-indigo-600 hover:text-indigo-900">Approve<span class="sr-only">, Lindsay Walton</span></a>
                        </td>
                    </tr>
                @endforeach
                <!-- More people... -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="h-20"></div>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Approved Users</h1>
                <p class="mt-2 text-sm text-gray-700">A list of all the users that are Active</p>
            </div>
        </div>
        <div class="-mx-4 mt-8 overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:-mx-6 md:mx-0 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                    <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">Business Name</th>
                    <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">Phone</th>
                    <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">Email</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Remove</span>
                    </th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                @foreach($pendingUsers as $user)
                    <tr>
                        <td class="w-full max-w-0 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none sm:pl-6">
                            {{$user->name}}
                        </td>
                        <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell"> {{$user->business_name}}</td>
                        <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell"> {{$user->phone}}</td>
                        <td class="px-3 py-4 text-sm text-gray-500"> {{$user->email}}</td>
                        <td class="py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <a href="{{route('admin.destroy', $user->id)}}" class="text-indigo-600 hover:text-indigo-900">Approve<span class="sr-only">, Lindsay Walton</span></a>
                        </td>
                    </tr>
                @endforeach
                <!-- More people... -->
                </tbody>
            </table>
        </div>
    </div>


</x-app-layout>

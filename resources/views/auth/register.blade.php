<x-guest-layout>
    <x-auth-card>
        <div class="min-h-full flex">
            <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
                <div class="mx-auto w-full max-w-sm lg:w-96">
                    <div>
                        <img class="h-12 w-auto" src="{{asset('/images/logo.svg')}}" alt="Workflow">
                        <div class="h-20"></div>
                        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Request an Account</h2>
                    </div>

                    <div class="mt-8">
                        <div>

                        </div>

                        <div class="mt-6">
                            <x-auth-validation-errors class="mb-4" :errors="$errors" />

                            <form method="POST" class="space-y-6" action="{{ route('register') }}">
                                @csrf
                                <div>
                                    <div class="mt-1">
                                        <input id="full_name" placeholder="Full Name" name="name" type="text" autocomplete="name" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <div>
                                    <div class="mt-1">
                                        <input id="business_name"  placeholder="Business Name" name="business_name" type="text" autocomplete="name" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                                <div>
                                    <div class="mt-1">
                                        <input id="phone_number"  placeholder="Phone Number" name="phone_number" type="text" autocomplete="phone_number" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <div>
                                    <div class="mt-1">
                                        <input id="email" placeholder="Email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <div class="mt-1">
                                        <input id="password"  placeholder="Password" name="password" type="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <div class="mt-1">
                                        <input id="password"  placeholder="Password Confirmation" name="password_confirmation" type="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-sky-400 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden lg:block relative w-0 flex-1">
                <img class="absolute inset-0 h-full w-full object-cover" src="{{asset('/images/login.png')}}" alt="">
            </div>
        </div>
    </x-auth-card>
</x-guest-layout>

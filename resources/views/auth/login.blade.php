<x-guest-layout>
    <x-auth-card>
        <div class="min-h-full flex">
            <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
                <div class="mx-auto w-full max-w-sm lg:w-96">
                    <div>
                        <img class="h-12 w-auto" src="{{asset('/images/logo.svg')}}" alt="Workflow">
                        <div class="h-40"></div>
                        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Sign in to your account</h2>
                    </div>

                    <div class="mt-8">
                        <div>

                        </div>

                        <div class="mt-6">
                            <x-auth-validation-errors class="mb-4" :errors="$errors" />

                            <form method="POST" class="space-y-6" action="{{ route('login') }}">
                                @csrf
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700"> Email address </label>
                                    <div class="mt-1">
                                        <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <label for="password" class="block text-sm font-medium text-gray-700"> Password </label>
                                    <div class="mt-1">
                                        <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <input id="remember-me" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="remember-me" class="ml-2 block text-sm text-gray-900"> Remember me </label>
                                    </div>

                                    <div class="text-sm">
                                        <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500"> Forgot your password? </a>
                                    </div>
                                </div>

                                <div>
                                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-sky-400 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Sign in</button>
                                    <div class="h-10"></div>
                                    <a href="/register">
                                    <button type="button" class="w-full flex justify-center px-2 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Request an Account </button>
                                    </a>
                                </div>
                            </form>
                            <div class="h-10"></div>
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

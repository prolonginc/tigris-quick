<x-guest-layout>
    <x-auth-card>
        <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                <div class="w-full max-w-md">
                    <div class="text-center">
                        <a href="/"><img class="h-12 w-auto mx-auto" src="{{asset('/images/logo.svg')}}" alt="Workflow"></a>
                        <div class="h-10"></div>
                        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Reset your password</h2>
                    </div>

                    <div class="mt-8">
                        <div class="mt-6">
                            <x-auth-validation-errors class="mb-4" :errors="$errors" />

                            <form method="POST" class="space-y-6" action="{{ route('password.update') }}">
                                @csrf

                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700"> Email address </label>
                                    <div class="mt-1">
                                        <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email', $request->email) }}" required autofocus class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700"> New Password </label>
                                    <div class="mt-1">
                                        <input id="password" name="password" type="password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700"> Confirm Password </label>
                                    <div class="mt-1">
                                        <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <div>
                                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-sky-400 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Reset Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
    </x-auth-card>
</x-guest-layout>

<x-guest-layout>
    <x-auth-card>
        <div class="min-h-full flex">
            <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
                <div class="mx-auto w-full max-w-sm lg:w-96">
                    <div>
                        <img class="h-12 w-auto" src="{{asset('/images/logo.svg')}}" alt="Workflow">
                        <div class="h-40"></div>
                    </div>
                    <img class="h-12 w-auto" src="{{asset('/images/checkmark.svg')}}" alt="Workflow">

                    <h3 class="mt-6 text-3xl font-bold text-gray-900">Your Request Has Been Sent</h3>
                    <div class="mt-8">
                    <p class="text-sm centered text-gray-400 "> We'll review your information and, if approved, you'll be able to log into your account within 24 hours.</p>
                    </div>
                        <div class="mt-8">
                        <div class="mt-6">
                            <h3> </h3>
                            <div class="h-40"></div>
                            <div class="h-40"></div>
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

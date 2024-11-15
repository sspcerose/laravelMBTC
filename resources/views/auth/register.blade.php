@extends("layout.userLayout") 

<body class="bg-slate-800 flex flex-col lg:flex-row">

    <!-- Left Section -->
    <section class="bg-gray-50 rounded-br-5xl rounded-bl-5xl md:flex-[3] lg:rounded-tr-5xl lg:rounded-bl-none">

        <div class="p-1 font-bold text-gray-400 text-3xl pb-20">
            <h1>MBTC</h1>
        </div>

        <div class='px-5 lg:px-10 xl:px-10'>
            <div class="font-bold text-gray-950 text-4xl pb-6 md:text-5xl xl:text-6xl">
                <h1><span class='text-orange-300'>Create</span> your</h1>
                <h1>Account Now</h1>
            </div>
            <div class="text-1xl text-gray-950 pr-14 pb-44 md:text-2xl xl:text-3xl">
                <p>Plan your adventure with ease. Sign up today!</p>
            </div>
        </div>
    </section>

    <!-- Right Section -->
    <section class="bg-slate-800 md:flex-[4] md:h-screen">
        <div class="px-8 pt-20 lg:pt-36 md:px-36 lg:px-36 xl:px-48">

            <div class="font-bolder mb-5">
                <h2 class="text-3xl text-gray-50 font-bold md:text-3xl">Welcome!</h2>
                <h2 class="text-1xl text-gray-400 pb-4 md:pb-4 lg:pb-1">Sign Up to MBTC</h2>
            </div>

            <div class="text-white">
        Please note: fields marked with an asterisk (<span class="text-red-500">*</span>) are required
        </div>

            <div class="">

                <!-- Sign Up Form -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="flex flex-col justify-center">

                        <!-- First Name and Last Name -->
                        <div class="lg:flex lg:flex-row justify-center md:flex-grow lg:space-x-3"> 
                            <!-- First Name -->
                            <div class="w-full mb-2"> <!-- Reduced margin -->
                                <label for="name" class="inline-flex items-center">
                                    <span class="font-medium text-white">{{ __('First Name') }}</span>
                                    <span class="text-red-500 ml-1">*</span> <!-- Red asterisk -->
                                </label>
                                <x-text-input id="name" class="bg-gray-100 rounded-md px-3 py-2 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="First Name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Last Name -->
                            <div class="w-full mb-2"> <!-- Reduced margin -->
                                <label for="last_name" class="inline-flex items-center">
                                    <span class="font-medium text-white">{{ __('Last Name') }}</span>
                                    <span class="text-red-500 ml-1">*</span> <!-- Red asterisk -->
                                </label>
                                <x-text-input id="last_name" class="bg-gray-100 rounded-md px-3 py-2 w-full" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last_name" placeholder="Last Name" />
                                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Mobile Number -->
                        <div class="mb-2"> <!-- Reduced margin -->
                            <label for="mobile_num" class="inline-flex items-center">
                                <span class="font-medium text-white">{{ __('Mobile Number') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk -->
                            </label>
                            <x-text-input 
                                id="mobile_num" 
                                class="bg-neutral-100 rounded-md px-3 py-2 w-full" 
                                type="text" 
                                name="mobile_num" 
                                :value="old('mobile_num')" 
                                required 
                                autofocus 
                                autocomplete="mobile_num" 
                                placeholder="Phone Number e.g. 09xxxxxxxxx" 
                                pattern="09\d{9}" 
                                title="Mobile number must start with 09 and be 11 digits long"
                            />
                            <x-input-error :messages="$errors->get('mobile_num')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mb-2"> <!-- Reduced margin -->
                            <label for="email" class="inline-flex items-center">
                                <span class="font-medium text-white">{{ __('Email') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk -->
                            </label>
                            <x-text-input 
                                id="email" 
                                class="bg-gray-100 rounded-md px-3 py-2 w-full" 
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                required 
                                autocomplete="username" 
                                placeholder="Email" 
                            />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mb-2"> <!-- Reduced margin -->
                            <label for="password" class="inline-flex items-center">
                                <span class="font-medium text-white">{{ __('Password') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk -->
                            </label>
                            <x-text-input 
                                id="password" 
                                class="bg-gray-100 rounded-md px-3 py-2 w-full" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="new-password" 
                                placeholder="Password" 
                            />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4"> <!-- Keep or reduce this margin based on preference -->
                            <label for="password_confirmation" class="inline-flex items-center">
                                <span class="font-medium text-white">{{ __('Confirm Password') }}</span>
                                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk -->
                            </label>
                            <x-text-input 
                                id="password_confirmation" 
                                class="bg-gray-100 rounded-md px-3 py-2 w-full" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password" 
                                placeholder="Confirm Password" 
                            />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center">
                            <a class="underline text-sm text-white hover:text-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                                {{ __('Already registered?') }}
                            </a>
                        </div>

                        <!-- Sign Up Button -->
                        <div class="flex justify-center pb-40 lg:pb-0">
                            <button class="px-20 py-2 bg-orange-300 text-gray-50 font-semibold rounded-lg hover:bg-orange-400 focus:outline-none focus:ring-2 focus:orange-400" type="submit">
                                Sign Up
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </section>

</body>

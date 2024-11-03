@extends("layout.userLayout")

<body class="bg-slate-800 flex flex-col lg:flex-row">

    <!-- Left Section -->
    <section class="bg-gray-50 rounded-br-5xl rounded-bl-5xl md:flex-[3] lg:rounded-tr-5xl lg:rounded-bl-none">

    <div class="">

        <div class="p-5 font-bold text-gray-400 text-3xl pb-36">
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
                <h2 class="text-1xl text-gray-400 pb-8 md:pb-4 lg:pb-5">Sign Up to MBTC</h2>
            </div>

            <div class="">

            <!-- Sign Up Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="flex flex-col justify-center">

                    <!-- First Name and Last Name -->
                    <div class="lg:flex lg:flex-row justify-center md:flex-grow lg:space-x-3">
                        <!-- <div class="w-full"> -->
                            <!-- <x-input-label for="name" :value="__('First Name')" /> -->
                            <x-text-input id="name" class="mb-4 bg-gray-100 rounded-md px-3 py-2 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="First Name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        <!-- </div> -->

                        <!-- <div class="w-full"> -->
                            <!-- <x-input-label for="last_name" :value="__('Last Name')" /> -->
                            <x-text-input id="last_name" class="mb-4 bg-gray-100 rounded-md px-3 py-2 w-full" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last_name" placeholder="Last Name" />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        <!-- </div> -->
                    </div>

                    <!-- Mobile Number -->
                    <!-- <div> -->
                        <!-- <x-input-label for="mobile_num" :value="__('Mobile Number')" /> -->
                        <x-text-input id="mobile_num" class="mb-4 bg-gray-100 rounded-md px-3 py-2 w-full" type="number" name="mobile_num" :value="old('mobile_num')" required autofocus autocomplete="mobile_num" placeholder="Mobile Number" />
                        <x-input-error :messages="$errors->get('mobile_num')" class="mt-2" />
                    <!-- </div> -->

                    <!-- Email -->
                    <!-- <div class="mt-4"> -->
                        <!-- <x-input-label for="email" :value="__('Email')" /> -->
                        <x-text-input id="email" class="mb-4 bg-gray-100 rounded-md px-3 py-2 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    <!-- </div> -->

                    <!-- Password and Confirm Password -->
                    <!-- <div class="mt-4"> -->
                        <!-- <x-input-label for="password" :value="__('Password')" /> -->
                        <x-text-input id="password" class="mb-4 bg-gray-100 rounded-md px-3 py-2 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    <!-- </div> -->

                    <!-- <div class="mt-4"> -->
                        <!-- <x-input-label for="password_confirmation" :value="__('Confirm Password')" /> -->
                        <x-text-input id="password_confirmation" class="mb-6 bg-gray-100 rounded-md px-3 py-2 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    <!-- </div> -->

                </div>

                <!-- Sign Up Button -->
                <div class="flex justify-center pb-40 lg:pb-0">
                    <button class="px-20 py-2 bg-orange-300 text-gray-50 font-semibold rounded-lg hover:bg-orange-400 focus:outline-none focus:ring-2 focus:orange-400" type="submit">
                        Sign Up
                    </button>
                </div>


                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>
                </div>
            </form>

        </div>
    </section>

</body>

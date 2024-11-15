@extends('layout.layout')

@include('layouts.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<body class="font-inter">
    <div class="lg:pl-20 lg:pr-10 flex flex-col items-center">
        <div class="pt-24 lg:pt-28 lg:pr-6 items-center">
            <h1 class="text-black p-8 pl-4 text-center font-extrabold text-3xl">Create New Member</h1>
        </div>
    
    <div class="items-center mx-auto p-3 ">

    <form method="POST" action="{{ route('admin.member.auth.register') }}" class="max-w-md">
        @csrf

        <!-- Name -->
        <div class="flex flex-col justify-center">
        <div class="mb-4">
        Please note: fields marked with an asterisk (<span class="text-red-500">*</span>) are required
        </div>
            <div class="lg:flex lg:flex-row justify-center md:flex-grow lg:space-x-3">
                <div class="mb-4">
                        <label for="name" class="inline-flex items-center">
                        <span class="font-medium text-gray-700">{{ __('First Name') }}</span>
                        <span class="text-red-500 ml-1">*</span> <!-- Red asterisk with margin -->
                    </label> <!-- Red asterisk -->
                    <x-text-input id="pass" class="bg-neutral-100 rounded-md px-3 py-2 w-full" type="hidden" value="new" />
                    <x-text-input id="name" class="bg-neutral-100 rounded-md px-3 py-2 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="e.g. Juan"/>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Last Name -->
                <div class="mb-4">
                    <label for="last_name" class="inline-flex items-center">
                        <span class="font-medium text-gray-700">{{ __('Last Name') }}</span>
                        <span class="text-red-500 ml-1">*</span> <!-- Red asterisk with margin -->
                    </label>
                    <x-text-input id="last_name" class="bg-neutral-100 rounded-md px-3 py-2 w-full" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last_name" placeholder="e.g. Dela Cruz" />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>
            </div>
        </div>


        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="inline-flex items-center">
                <span class="font-medium text-gray-700">{{ __('Email') }}</span>
                <span class="text-red-500 ml-1">*</span> <!-- Red asterisk with margin -->
            </label>
            <x-text-input 
                id="email" 
                class="mt-1 bg-neutral-100 rounded-md px-3 py-2 w-full" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autocomplete="username" 
                placeholder="e.g. example@gmail.com" 
            />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>


            <!-- Mobile Number -->
            <div class="mb-4">
                <label for="mobile_num" class="inline-flex items-center">
                    <span class="font-medium text-gray-700">{{ __('Phone Number') }}</span>
                    <span class="text-red-500 ml-1">*</span> <!-- Red asterisk with margin -->
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
                    placeholder="e.g. 09xxxxxxxxx" 
                    pattern="09\d{9}" 
                    title="Mobile number must start with 09 and be 11 digits long"
                />
                <x-input-error :messages="$errors->get('mobile_num')" class="mt-2" />
            </div>

            <!-- TIN -->
            <div class="mb-4">
                <label for="mobile_num" class="inline-flex items-center">
                        <span class="font-medium text-gray-700">{{ __('TIN') }}</span>
                        <span class="text-red-500 ml-1">*</span> <!-- Red asterisk with margin -->
                    </label>
                <x-text-input 
                    id="tin" 
                    class="bg-neutral-100 rounded-md px-3 py-2 w-full" 
                    type="text" 
                    name="tin" 
                    :value="old('tin')" 
                    required 
                    autofocus 
                    autocomplete="tin" 
                    placeholder="e.g. XXX-XX-XXXX" 
                    pattern="^\d{3}-\d{2}-\d{4}$" 
                    title="Format: 123-45-6789"
                />
                <x-input-error :messages="$errors->get('tin')" class="mt-2" />
            </div>



        <!-- Date Joined -->
                <div class="mb-4">
                <label for="date_joined" class="inline-flex items-center">
                        <span class="font-medium text-gray-700">{{ __('Date of Registration') }}</span>
                        <span class="text-red-500 ml-1">*</span> <!-- Red asterisk with margin -->
                    </label>
                    <x-text-input 
                        id="date_joined" 
                        class="bg-neutral-100 rounded-md px-3 py-2 w-full" 
                        type="date" 
                        name="date_joined" 
                        :value="old('date_joined')" 
                        required 
                        autofocus 
                        autocomplete="date_joined" 
                        placeholder="Date of Registration" 
                    />
                    <x-input-error :messages="$errors->get('date_joined')" class="mt-2" />
                </div>

                <!-- Type -->
                <div class="mb-6">
                <label for="type" class="inline-flex items-center">
                        <span class="font-medium text-gray-700">{{ __('Member Type') }}</span>
                        <span class="text-red-500 ml-1">*</span> <!-- Red asterisk with margin -->
                    </label>
                    <select id="type" class="bg-neutral-100 rounded-md px-3 py-3 w-full" name="type" :value="old('type')" required autofocus autocomplete="type">
                        <option class="" disabled selected>Select Member Type</option>
                        <option value="Driver">Driver</option>
                        <option value="Owner">Owner</option>
                    </select>
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </div>


        <!-- Member Status  -->
        <input type="hidden" name="member_status" value="active" :value="old('active')">
        <x-input-error :messages="$errors->get('active')" class="mt-2" />
        <!-- Password -->
            <div class="mb-4">
                <label for="password" class="inline-flex items-center">
                    <span class="font-medium text-gray-700">{{ __('Password') }}</span>
                    <span class="text-red-500 ml-1">*</span> <!-- Red asterisk -->
                </label>
                <x-text-input id="password" class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" placeholder="Password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

        <!-- Confirm Password -->
            <div class="mb-6">
                <label for="password_confirmation" class="inline-flex items-center">
                    <span class="font-medium text-gray-700">{{ __('Confirm Password') }}</span>
                    <span class="text-red-500 ml-1">*</span> <!-- Red asterisk -->
                </label>
                <x-text-input id="password_confirmation" class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>


<div class="flex justify-between pb-40 lg:pb-0 space-x-2">
                    <button
                        class="w-full px-20 py-2 bg-red-600 text-gray-50 font-semibold rounded-lg hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-red-400"
                        type="button" onclick="window.history.back();">
                        Cancel
                    </button>


            <!-- <x-primary-button class="w-full px-20 py-2 bg-green-400 text-gray-50 font-semibold rounded-lg hover:bg-green-300 focus:outline-none focus:ring-2 focus:green-400">
                Create
            </x-primary-button> -->

            <button
                class="w-full px-20 py-2 bg-green-400 text-gray-50 font-semibold rounded-lg hover:bg-green-300 focus:outline-none focus:ring-2 focus:green-400"
                    type="submit">
                    Create
            </button>
        </div>
    </form>
    </div>



</div>

</body>


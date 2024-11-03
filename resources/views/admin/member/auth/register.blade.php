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

            <div class="lg:flex lg:flex-row justify-center md:flex-grow lg:space-x-3">
            <!-- <x-input-label for="name" :value="__('First Name')" /> -->
            <x-text-input id="name" class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="First Name"/>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />

        <!-- Last Name  -->
            <!-- <x-input-label for="last_name" :value="__('Last Name')" /> -->
            <x-text-input id="last_name" class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last_name" placeholder="Last Name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

         <!-- Email Address -->
            <!-- <x-input-label for="email" :value="__('Email')" /> -->
            <x-text-input id="email" class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Email"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

            <!-- Mobile Number  -->
            <!-- <x-input-label for="mobile_num" :value="__('Mobile Number')" /> -->
            <x-text-input id="mobile_num" class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" type="number" name="mobile_num" :value="old('mobile_num')" required autofocus autocomplete="mobile_num" placeholder="Phone Number" />
            <x-input-error :messages="$errors->get('mobile_num')" class="mt-2" />

        <!-- TIN -->
            <!-- <x-input-label for="tin" :value="__('TIN')" /> -->
            <x-text-input id="tin" class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" type="text" name="tin" :value="old('tin')" required autofocus autocomplete="tin" placeholder="TIN"/>
            <x-input-error :messages="$errors->get('tin')" class="mt-2" />


        <!-- Date Joined  -->
            <x-input-label for="date_joined" :value="__('Date of Registration')" />
            <x-text-input id="date_joined" class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" type="date" name="date_joined" :value="old('date_joined')" required autofocus autocomplete="date_joined" placeholder="Date of Registration" />
            <x-input-error :messages="$errors->get('date_joined')" class="mt-2" />
 
        <!-- Type  -->
            <!-- <x-input-label for="type" :value="__('Type')" /> -->
            <select id="type" class="mb-6 bg-neutral-100 rounded-md px-3 py-3 w-full" name="type" :value="old('type')" required autofocus autocomplete="type">
                <option class="" disabled selected>Select Member Type</option>
                <option value="Driver">Driver</option>
                <option value="Owner">Owner</option>
            </select>
            <x-input-error :messages="$errors->get('type')" class="mt-2" />

        <!-- Member Status  -->
        <input type="hidden" name="member_status" value="active" :value="old('active')">
        <x-input-error :messages="$errors->get('active')" class="mt-2" />
         

        <!-- Password -->
            <!-- <x-input-label for="password" :value="__('Password')" /> -->
            <x-text-input id="password" class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="Password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />

        <!-- Confirm Password -->
            <!-- <x-input-label for="password_confirmation" :value="__('Confirm Password')" /> -->

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


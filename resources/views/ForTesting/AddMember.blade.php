@extends('layout.layout')

@include('ForTesting.layout.adminNav')

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

            <form action="" class="max-w-md">

                <div class="flex flex-col justify-center">

                    <div class="lg:flex lg:flex-row justify-center md:flex-grow lg:space-x-3">
                        <input class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" type="text" placeholder="First Name">
                        <input class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" type="text" placeholder="Last Name">
                    </div>

                    <input class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" type="text" placeholder="Email">
                    <input class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" type="text" placeholder="Mobile Number">
                    <input class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" type="text" placeholder="TIN">

                    <input class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" type="text" placeholder="Password">
                    <input class="mb-4 bg-neutral-100 rounded-md px-3 py-2 w-full" type="text" placeholder="Confirm Password">

                    <select class="mb-6 bg-neutral-100 rounded-md px-3 py-3 w-full">
                        <option class="" disabled selected>Select Member Type</option>
                        <option value="Owner">Owner</option>
                        <option value="Driver">Driver</option>
                        <option value="Owner/Driver">Owner/Driver</option>
                    </select>

                </div>

                <div class="flex justify-between pb-40 lg:pb-0 space-x-2">
                    <button
                        class="w-full px-20 py-2 bg-red-600 text-gray-50 font-semibold rounded-lg hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-red-400"
                        type="button" onclick="window.history.back();">
                        Cancel
                    </button>

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
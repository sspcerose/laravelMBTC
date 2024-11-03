@extends('layout.layout')

@include('ForTesting.layout.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
    <div class="flex items-center justify-center min-h-screen">
        <div class="pt-24 md:pt-0 max-w-md w-full mx-auto">
            <h1 class="text-black p-8 text-center font-extrabold text-3xl">Add Tariff</h1>
            <div class="items-center">
                <form action="" class="mx-10">
                    <div class="flex flex-col justify-center">
                        <input class="mb-6 bg-neutral-100 rounded-md px-3 py-2 w-full" type="text" placeholder="Destination">
                        <input class="mb-6 bg-neutral-100 rounded-md px-3 py-2 w-full" type="text" placeholder="Rate">
                        <input class="mb-8 bg-neutral-100 rounded-md px-3 py-2 w-full" type="text" placeholder="Succeeding Rate">
                    </div>
                    <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 pb-40 lg:pb-0">
                        <button class="w-full md:w-1/2 py-2 bg-red-600 text-gray-50 font-semibold rounded-lg hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400" type="button" onclick="window.history.back();">
                            Cancel
                        </button>
                        <button class="w-full md:w-1/2 py-2 bg-green-400 text-gray-50 font-semibold rounded-lg hover:bg-green-300 focus:outline-none focus:ring-2 focus:ring-green-400" type="submit">
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
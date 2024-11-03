@extends('layout.layout')

@include('layouts.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body class="font-inter">
    <div class="flex items-center justify-center min-h-screen">
        <div class="pt-24 md:pt-0 max-w-md w-full mx-auto">
            <h1 class="text-black p-8 text-center font-extrabold text-3xl">Update Tariff</h1>
            <div class="items-center">

                <!-- Success Message Container -->
                <div id="successMessage" class="hidden successMessageAlert mt-3 relative flex w-5/6 mx-auto p-3 text-sm text-white bg-green-500 rounded-md">
                    <svg class="w-6 h-6 text-white-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <span class="ml-2">Successfully Updated the Tariff!</span>
                </div>

                <!-- Form -->
                <form id="tariffForm" method="POST" action="{{ url('admin/tariff/updatetariff/' . $viewtariffs->id) }}" class="mx-10">
                    @csrf
                    <div class="flex flex-col justify-center">
                        <label for="destination">Destination:</label>
                        <input type="text" id="destination" name="destination" class="mb-6 bg-neutral-100 rounded-md px-3 py-2 w-full" required value="{{ $viewtariffs->destination }}">

                        <label for="rate">Rate:</label>
                        <input type="number" id="rate" name="rate" class="mb-6 bg-neutral-100 rounded-md px-3 py-2 w-full" required value="{{ $viewtariffs->rate }}">

                        <label for="succeeding">Succeeding:</label>
                        <input type="number" id="succeeding" name="succeeding" class="mb-6 bg-neutral-100 rounded-md px-3 py-2 w-full" required value="{{ $viewtariffs->succeeding }}">
                    </div>

                    <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 pb-40 lg:pb-0">
                        <button class="w-full md:w-1/2 py-2 bg-red-600 text-gray-50 font-semibold rounded-lg hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400" type="button" onclick="window.history.back();">Cancel</button>

                        <button id="submitButton" class="w-full md:w-1/2 py-2 bg-yellow-400 text-gray-50 font-semibold rounded-lg hover:bg-yellow-300 focus:outline-none focus:ring-2 focus:ring-yellow-400">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('submitButton').addEventListener('click', function(event) {
            event.preventDefault(); 

            var successMessage = document.getElementById('successMessage');
            successMessage.classList.remove('hidden');

            setTimeout(function() {
                document.getElementById('tariffForm').submit();
            }, 1000);
        });
    </script>
</body>

</html>


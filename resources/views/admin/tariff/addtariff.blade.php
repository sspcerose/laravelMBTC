@extends('layout.layout')
@include('layouts.adminNav')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.datatables.net/v/dt/dt-2.1.8/b-3.1.2/r-3.0.3/datatables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<body class="font-inter">
    <div class="flex items-center justify-center min-h-screen">
        <div class="pt-24 md:pt-0 max-w-md w-full mx-auto">
            <h1 class="text-black p-8 text-center font-extrabold text-3xl">Add Tariff</h1>
            <div class="items-center">

            <!-- Success Message Container -->
            

            <form id="tariffForm" action="{{ url('admin/tariff/addtariff') }}" method="POST" class="mx-10 tariffAddForm">
                @csrf
                <div class="flex flex-col justify-center">
                    <x-input-error :messages="$errors->get('destination')" class="" /> 
                    <input type="text" id="destination" name="destination" class="mb-6 bg-neutral-100 rounded-md px-3 py-2 w-full" required placeholder="Destination" value="{{old('destination')}}">
                    <input type="number" id="rate" name="rate" class="mb-6 bg-neutral-100 rounded-md px-3 py-2 w-full" required placeholder="Rate" value="{{old('rate')}}">
                    <input type="number" id="succeeding" name="succeeding" class="mb-6 bg-neutral-100 rounded-md px-3 py-2 w-full" required placeholder="Succeeding Rate" value="{{old('succeeding')}}">
                </div>
                
                <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 pb-40 lg:pb-0">
                    <button class="w-full md:w-1/2 py-2 bg-red-600 text-gray-50 font-semibold rounded-lg hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400" type="button" onclick="location.href='{{ route('admin.tariff.tariff') }}'">
                        Cancel
                    </button>
                    <input type="hidden" id="status" value="active" name="status">
                    <button id="submitButton" class="w-full md:w-1/2 py-2 bg-green-400 text-gray-50 font-semibold rounded-lg hover:bg-green-300 focus:outline-none focus:ring-2 focus:ring-green-400 triggerAddTariff" type="button">
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
         document.addEventListener('click', function (e) {
    // Handle "Assign Driver" button click
    if (e.target.classList.contains('triggerAddTariff')) {
        const form = e.target.closest('.tariffAddForm'); // Get the form to submit
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to add this tariff.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Add it!'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.closest('.tariffAddForm').submit();
                Swal.fire({
                    title: "Added!",
                    text: "The tariff has been added.",
                    icon: "success"
                });
            }
        });
    }
});
    </script>
</body>

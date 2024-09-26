<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 mb-6">
                    <!-- {{ __("You're logged in!") }} -->
                    <a href="{{ route('admin.booking.booking') }}" class="bg-red-500 text-white py-2 px-4 rounded ml-4 mb-4">Booking</a>
                    <a href="{{ route('admin.schedule.schedule') }}" class="bg-red-500 text-white py-2 px-4 rounded ml-4 mb-4">Schedule</a>
                    <a href="{{ route('admin.member.member') }}" class="bg-red-500 text-white py-2 px-4 rounded ml-4 mb-4">Member</a>
                    <a href="{{ route('admin.monthlydues.monthlydues')}}" class="bg-red-500 text-white py-2 px-4 rounded ml-4 mb-4">Monthly Dues</a>
                    <a href="{{ route('admin.vehicle.vehicle') }}" class="bg-red-500 text-white py-2 px-4 rounded ml-4 mb-4">Vehicle</a>
                    <a href="{{ route('admin.tariff.tariff') }}" class="bg-red-500 text-white py-2 px-4 rounded ml-4 mb-4">Tariff</a>
                </div>
                

            </div>
        </div>
    </div>

   
</x-app-layout>

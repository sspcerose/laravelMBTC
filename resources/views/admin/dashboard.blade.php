@extends('layout.layout')

@include('layouts.adminNav')

<body class="font-inter">

    <div class="lg:pl-20 md:pr-5">

        <h1 class="font-semibold pt-28 px-4 text-4xl">Hello, <span>Admin</span>!</h1>
        <h1 class="text-black p-4 font-extrabold text-4xl">Welcome</h1>

        <div class="bg-neutral-200 p-4 lg:p-8 rounded-3xl m-4">

            <!-- Bento Box Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 lg:gap-8 mb-8">
                <!-- Total Bookings Card -->
                <div class="bg-blue-500 text-white  p-5 rounded-2xl  lg:h-52 shadow-md">
                    <h2 class="text-xl font-bold mb-2">Total Bookings</h2>
                    <p class="text-xl lg:text-5xl font-semibold">{{ $totalBookings }}</p> <!-- Placeholder value -->
                </div>
                <!-- Registered Users Card -->
                <div class="bg-green-500 text-white p-5 rounded-2xl lg:h-52 shadow-md">
                    <h2 class="text-xl font-bold mb-2">Registered Users</h2>
                    <p class="text-xl lg:text-5xl font-semibold">{{ $totalUsers }}</p> <!-- Placeholder value -->
                </div>
                <div class="bg-red-500 text-white p-5 rounded-2xl lg:h-52 shadow-md">
                    <h2 class="text-xl font-bold mb-2">Total Members</h2>
                    <p class="text-xl lg:text-5xl font-semibold">{{ $totalMembers }}</p> <!-- Placeholder value -->
                </div>
            </div>
        </div>
    </div>

</body>
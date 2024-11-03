<x-app-layout>
    <body class="font-inter">
        <section class="lg:pt-32 lg:px-32 xl:px-52 relative">
            <div class="bg-landing h-[95vh] md:h-[100vh] flex items-center justify-center bg-cover bg-center lg:hidden"></div>
            <div class="hidden lg:block rounded-3xl overflow-hidden h-[60vh]">
                <img src="{{ asset('img/hero-bg-1.jpg') }}" alt="Description of image" class="w-full h-full object-cover" />
            </div>

            <div id="bookingCard"
                class="relative translate-y-[-20%] mb-10 inset-x-0 top-[90%] lg:top-[85%] left-1/2 transform -translate-x-1/2 w-full max-w-2xl xl:max-w-3xl rounded-3xl bg-gray-50 pt-16 md:px-16">
                
            @if(!Auth::check())
            
                @if($activetariffs->isEmpty())
                    <div class="md:flex md:flex-row justify-center md:flex-grow md:space-x-3 md:pb-5">
                        <p class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full">I am sorry, no available destination right now</p>
                    </div>
                @else
                    <div class="flex flex-col justify-center mx-10">
                        <h1 class="text-black font-extrabold text-center text-4xl mb-8">Book Now</h1>
                        <form id="firstPageForm" enctype="multipart/form-data">
                            @csrf
                            <input hidden type="text" id="customer_id" name="customer_id" value="{{ Auth::user()->id }}" required>
                            
                            <label class="font-bold pb-2">Location</label>
                            <div class="md:flex md:flex-row justify-center md:flex-grow md:space-x-3">
                                <input class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full" type="text" id="location" name="location" placeholder="From" required>
                                <select name="id" id="id" class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full">
                                    @foreach($activetariffs as $activetariff)
                                        <option value="{{ $activetariff->id }}">{{ $activetariff->destination }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <label class="font-bold pb-2">Date</label>
                            <div class="md:flex md:flex-row justify-center md:flex-grow md:space-x-3 md:pb-5">
                                <input class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full" type="date" id="start_date" name="start_date" min="{{ date('Y-m-d') }}" required>
                                <input class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full" type="date" id="end_date" name="end_date" min="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="md:flex justify-center md:flex-grow md:space-x-3 pb-4 md:pb-8">
                                <div class="mb-4 bg-gray-200 rounded-md w-full flex items-center space-x-3 py-1 pl-3 px-1 justify-between">
                                    <label class="font-medium">Passenger</label>
                                    <div class="flex items-center md:space-x-1">
                                        <button type="button" id="decrement-button" class="font-bold w-8 h-8 rounded-md hover:bg-gray-300 text-1xl">−</button>
                                        <input type="text" id="counter-input" class="text-black bg-gray-50 text-sm rounded-md max-w-[2.5rem] h-7 text-center" placeholder="0" value="0" required id="passenger" name="passenger"/>
                                        <button type="button" id="increment-button" class="font-bold w-8 h-8 rounded-md hover:bg-gray-300 text-1xl">+</button>
                                    </div>
                                </div>
                                <input class="mb-4 font-bold text-black bg-gray-200 rounded-md px-3 py-2 w-full" type="text" placeholder="Total:" id="price" name="price">
                            </div>

                            <div class="flex justify-center md:flex-row md:w-full md:justify-end pb-4 md:pb-16 md:px-0">
                                <a href="{{ route('login') }}" id="bookButton" class="px-20 py-2 bg-orange-300 text-white font-semibold rounded-lg hover:bg-orange-500 focus:outline-none focus:ring-1 focus:orange-400" type="button">Book</a>
                            </div>
                        </form>
                    </div>
                @endif
                @endif
            </div>
        </section>
    </body>
</x-app-layout>

<x-app-layout>

    <body class="font-inter">
        <section class="lg:pt-32 lg:px-32 xl:px-52 relative">
            <div class="bg-landing h-[95vh] md:h-[100vh] flex items-center justify-center bg-cover bg-center lg:hidden"></div>
            <div class="hidden lg:block rounded-3xl overflow-hidden h-[60vh]">
                <img src="{{ asset('img/hero-bg-1.jpg') }}" alt="Description of image" class="w-full h-full object-cover" />
            </div>

            <div id="bookingCard" class="relative translate-y-[-20%] mb-10 inset-x-0 top-[90%] lg:top-[85%] left-1/2 transform -translate-x-1/2 w-full max-w-2xl xl:max-w-3xl rounded-3xl bg-gray-50 pt-16 md:px-16">
                @if($activetariffs->isEmpty())
                <div class="md:flex md:flex-row justify-center md:flex-grow  md:space-x-3 md:pb-5">
                    <p class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full">I am sorry, no available destination right now</p>
                </div>
                @else
                    <div class="flex flex-col justify-center mx-10">
                        <h1 class="text-black font-extrabold text-center text-4xl mb-8">Book Now</h1>
                        @if(!Auth::check())
                            <form id="firstPageForm" enctype="multipart/form-data">
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
                                    <a href="{{ route('login') }}" class="px-20 py-2 bg-orange-300 text-white font-semibold rounded-lg hover:bg-orange-500 focus:outline-none focus:ring-1 focus:orange-400" type="button">Login to Book</a>
                                </div>
                            </form>
                        @else
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
                                    <!-- <input class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full" type="time" required> -->
                                </div>

                                <div class="md:flex justify-center md:flex-grow md:space-x-3 pb-4 md:pb-8">
                                    <div class="mb-4 bg-gray-200 rounded-md w-full flex items-center space-x-3 py-1 pl-3 px-1 justify-between">
                                        <label class="font-medium">Passenger</label>
                                        <div class="flex items-center md:space-x-1">
                                            <button type="button" id="decrement-button" class="font-bold w-8 h-8 rounded-md hover:bg-gray-300 text-1xl">−</button>
                                            <input type="text" id="counter-input" class="text-black bg-gray-50 text-sm rounded-md max-w-[2.5rem] h-7 text-center" placeholder="1" value="1" required id="passenger" name="passenger"/>
                                            <button type="button" id="increment-button" class="font-bold w-8 h-8 rounded-md hover:bg-gray-300 text-1xl">+</button>
                                        </div>
                                    </div>
                                    <input class="mb-4 font-bold text-black bg-gray-200 rounded-md px-3 py-2 w-full" type="text" placeholder="Total:" id="price" name="price">
                                </div>
                                
                                <div class="flex justify-center md:flex-row md:w-full md:justify-end pb-4 md:pb-16 md:px-0">
                                    <button id="bookButton" class="px-20 py-2 bg-orange-300 text-white font-semibold rounded-lg hover:bg-orange-500 focus:outline-none focus:ring-1 focus:orange-400" type="submit">Book</button>
                                </div>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </section>
    </body>

    <script>
        const decrementButton = document.getElementById('decrement-button');
        const incrementButton = document.getElementById('increment-button');
        const counterInput = document.getElementById('counter-input');
        let counterValue = 1;

        function updateCounter() {
            counterInput.value = counterValue;
        }

        decrementButton.addEventListener('click', function() {
            if (counterValue > 1) {
                counterValue--;
                updateCounter();
            }
        });

        incrementButton.addEventListener('click', function() {
            if (counterValue < 20) { 
                counterValue++;
                updateCounter();
            }
        });

        $(document).ready(function() {
            var firstPageData = {}; 

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#bookButton').click(function() {
                //first page data
                firstPageData['customer_id'] = $('#customer_id').val();
                firstPageData['location'] = $('#location').val();
                firstPageData['id'] = $('#id').val();
                firstPageData['start_date'] = $('#start_date').val();
                firstPageData['end_date'] = $('#end_date').val();
                firstPageData['passenger'] = $('#counter-input').val();
                firstPageData['price'] = $('#price').val();

                // second page
                $('#bookingCard').html(`
                    <div class="flex flex-col justify-center px-8 md:px-4 ">
                        <i id="goBackButton" class="fas fa-arrow-left text-2xl text-slate-950 hover:text-slate-600 pb-8 md:pb-4"
                            style="cursor: pointer;">Where is the back icon</i>

                        <h1 class="text-black font-extrabold text-center text-sm pb-2">Please complete payment or down payment to confirm booking reservation</h1>
                        <p class="text-center mb-8 text-xs">A down payment of 50% is needed to confirm booking</p>

                        <div class="flex justify-center space-x-3 items-center">
                            <h1 class="text-black font-extrabold text-center text-4xl">Total:</h1>
                            <p class="text-black font-extrabold text-center text-4xl" id="price" name="price">${firstPageData.price}</p>
                        </div>

                        <div class="flex justify-center mb-8 items-center">
                            <h2 class="text-black font-bold text-center text-md">Down payment: </h2>
                            <p class="text-black font-bold text-center text-md">₱1,000{{-- ${{ number_format($downPaymentAmount, 2) }} --}}</p>
                        </div>

                        <div class="flex flex-col justify-center lg:flex-row items-center lg:space-y-0 pb-4">
                            <div class="flex flex-col">
                                <div class="flex flex-row justify-center space-x-5 mb-4">
                                    <button onclick="toggleModal('gcashModal')">
                                        <img src="{{ asset('img/gcash.png') }}" alt="Gcash" class="h-12 w-12 rounded-full bg-blue-200" />
                                    </button>
                                    <!-- Maya Button -->
                                    <button onclick="toggleModal('mayaModal')">
                                        <img src="{{ asset('img/maya.jpg') }}" alt="Maya" class="h-12 w-12 rounded-full" />
                                    </button>
                                </div>
                                <h3 class="text-xs text-center">Select and scan the QR code to send payment</h3>
                            </div>

                            <!-- Gcash Modal -->
                                <div id="gcashModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
                                    <div class="bg-white p-6 rounded-lg w-80">
                                        <h2 class="text-center text-lg mb-4">Gcash Payment</h2>
                                        <img src="{{ asset('img/gcash.png') }}" alt="Gcash QR Code" class="w-full h-auto mb-4" />
                                        <button class="bg-blue-500 text-white px-4 py-2 rounded" onclick="toggleModal('gcashModal')">Close</button>
                                    </div>
                                </div>

                                <!-- Maya Modal -->
                                <div id="mayaModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
                                    <div class="bg-white p-6 rounded-lg w-80">
                                        <h2 class="text-center text-lg mb-4">Maya Payment</h2>
                                        <img src="{{ asset('img/maya.jpg') }}" alt="Maya QR Code" class="w-full h-auto mb-4" />
                                        <button class="bg-blue-500 text-white px-4 py-2 rounded" onclick="toggleModal('mayaModal')">Close</button>
                                    </div>
                                </div>

                            <hr class=" my-10 h-0.5 bg-neutral-300 rounded-full border-1  w-full lg:hidden">

                            <div class="hidden lg:flex lg:flex-1 lg:justify-center lg:items-center mx-12">
                                <div class="h-[150px] min-h-[1em] w-0.5 bg-neutral-300 mx-4"></div>
                            </div>

                            <div class="flex flex-col items-center">
                                <form id="secondPageForm" enctype="multipart/form-data"">
                                    <input type="file" id="receipt" class="w-full text-gray-400 font-medium text-sm bg-gray-200 file:cursor-pointer file:border-0 file:py-2 file:px-2 file:mr-2 file:bg-gray-300 file:hover:bg-gray-400 file:text-black rounded-md mb-4" />
                                </form>
                                <h1 class="text-center text-xs pb-10">Upload Payment Receipt</h1>
                            </div>
                        </div>

                        <div class="flex justify-center md:flex-row md:w-full md:justify-end pb-4 md:pb-16 md:px-0">
                            <button id="submitButton" class="w-full max-w-xs lg:w-auto px-10 py-3 bg-green-700 text-white font-semibold rounded-xl hover:bg-green-500" type="button">Confirm</button>
                        </div>
                    </div>
                `);

                $('#goBackButton').click(function() {
                    location.reload(); 
                });

                
                // Handle the second form submission
                $('#submitButton').click(function() {
                    var formData = new FormData();
                    formData.append('customer_id', firstPageData.customer_id);
                    formData.append('location', firstPageData.location);
                    formData.append('id', firstPageData.id);
                    formData.append('start_date', firstPageData.start_date);
                    formData.append('end_date', firstPageData.end_date);
                    formData.append('passenger', firstPageData.passenger);
                    formData.append('price', firstPageData.price);
                    formData.append('receipt', $('#receipt')[0].files[0]);

                    $.ajax({
                        url: '{{ url("bookingform") }}',
                        method: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#bookingCard').prepend(`
                                <div role="alert" class="mt-3 relative flex w-full p-3 text-sm text-white bg-green-500 rounded-md">
                                    Booking Success! 
                                    <button class="flex items-center justify-center transition-all w-8 h-8 rounded-md text-white hover:bg-green/10 active:bg-green/10 absolute top-1.5 right-1.5" type="button" onclick="location.reload();">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

            `);

            $('#submitButton').prop('disabled', true).text('Confirmed');

            // $('html, body').animate({ scrollTop: 0 }, 'slow');
        },
                        error: function(error) {
                            alert('Error occurred during booking confirmation.');
                        }
                    });
                });
            });
        });

        // Modal
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }


        $(document).ready(function() {
        function calculatePrice() {
            var id = $('#id').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            if (id && start_date && end_date) {
                $.ajax({
                    url: '{{ url("/calculate-price") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        start_date: start_date,
                        end_date: end_date,
                    },
                    success: function(response) {
                        $('#price').val('₱' + response.price);
                    },
    error: function(xhr, status, error) {
        console.log('Error: ', xhr.responseText); 
    }
                });
            }
        }

        $('#id, #start_date, #end_date').change(function() {
            calculatePrice();
        });
    });


    </script>
</x-app-layout>

<x-app-layout>

    <body class="font-inter">
        <section class="lg:pt-32 lg:px-32 xl:px-52 relative">
            <div class="bg-landing h-[95vh] md:h-[100vh] flex items-center justify-center bg-cover bg-center lg:hidden"></div>
            <div class="hidden lg:block rounded-3xl overflow-hidden h-[60vh]">
                <img src="{{ asset('img/system/hero-bg-1.jpg') }}" alt="Description of image" class="w-full h-full object-cover" />
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
                                    <!-- <input class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full" type="text" id="location" name="location" placeholder="From" required> -->
                                    <select name="location" id="location" class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full">
                                            <option value="A. Pascual">A. Pascual</option>
                                            <option value="Abar 1st">Abar 1st</option>
                                            <option value="Abar 2nd">Abar 2nd</option>
                                            <option value="Bagong Sikat">Bagong Sikat</option>
                                            <option value="Caanawan">Caanawan</option>
                                            <option value="Calaocan">Calaocan</option>
                                            <option value="Camanacsacan">Camanacsacan</option>
                                            <option value="Canuto Ramos">Canuto Ramos</option>
                                            <option value="Crisanto Sanchez">Crisanto Sanchez</option>
                                            <option value="Culaylay">Culaylay</option>
                                            <option value="Dizol">Dizol</option>
                                            <option value="F.E. Marcos">F.E. Marcos</option>
                                            <option value="Kaliwanagan">Kaliwanagan</option>
                                            <option value="Kita-KIta">Kita-KIta</option>
                                            <option value="Malasin">Malasin</option>
                                            <option value="Manicla">Manicla</option>
                                            <option value="Palestina">Palestina</option>
                                            <option value="Parang Mangga">Parang Mangga</option>
                                            <option value="Pinili">Pinili</option>
                                            <option value="Polaris">Polaris</option>
                                            <option value="Rafael Rueda, Sr.">Rafael Rueda, Sr.</option>
                                            <option value="Raymundo Eugenio">Raymundo Eugenio</option>
                                            <option value="San Agustin">San Agustin</option>
                                            <option value="San Juan">San Juan</option>
                                            <option value="San Mauricio">San Mauricio</option>
                                            <option value="Santo Niño 1st">Santo Niño 1st</option>
                                            <option value="Santo Niño 2nd">Santo Niño 2nd</option>
                                            <option value="Santo Tomas">Santo Tomas</option>
                                            <option value="Sibut">Sibut</option>
                                            <option value="Sinipit Bubon">Sinipit Bubon</option>
                                            <option value="Tabulac">Tabulac</option>
                                            <option value="Tayabo">Tayabo</option>
                                            <option value="Tondod">Tondod</option>
                                            <option value="Tulat">Tulat</option>
                                            <option value="Villa Joson">Villa Joson</option>
                                            <option value="Villa Marina">Villa Marina</option>
                                    </select>

                                    <select name="id" id="id" class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full">
                                        @foreach($activetariffs as $activetariff)
                                            <option value="{{ $activetariff->id }}">{{ $activetariff->destination }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <label class="font-bold pb-2">Date</label>
                                <div class="md:flex md:flex-row justify-center md:flex-grow md:space-x-3 md:pb-5">
                                    <input type="text" id="time" name="time" class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full" placeholder="pick-up time">
                                    <input class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full" type="date" id="start_date" name="start_date" min="{{ \Carbon\Carbon::now()->addDays(3)->format('Y-m-d') }}" required>
                                    <input class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full" type="date" id="end_date" name="end_date" min="{{ \Carbon\Carbon::now()->addDays(3)->format('Y-m-d') }}" required>
                                </div>

                                <div class="md:flex justify-center md:flex-grow md:space-x-3 pb-4 md:pb-8">
                                    <div class="mb-4 bg-gray-200 rounded-md w-full flex items-center space-x-3 py-1 pl-3 px-1 justify-between">
                                    <label class="font-medium w-1/3">Passenger</label> 
                                        <!-- <div class="flex items-center md:space-x-1"> -->
                                            <!-- <button type="button" id="decrement-button" class="font-bold w-8 h-8 rounded-md hover:bg-gray-300 text-1xl">−</button>
                                            <input type="text" id="counter-input" class="text-black bg-gray-50 text-sm rounded-md max-w-[2.5rem] h-7 text-center" placeholder="1" value="1" required id="passenger" name="passenger"/>
                                            <button type="button" id="increment-button" class="font-bold w-8 h-8 rounded-md hover:bg-gray-300 text-1xl">+</button> -->
                                            <select name="passenger" id="passenger" class="bg-gray-200 rounded-md px-3 py-1 w-2/3">
                                                @for ($i = 1; $i <= 15; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                        </select>
                                    </div>
                                    <input class="mb-4 font-bold text-black bg-gray-200 rounded-md px-3 py-2 w-full" type="text" placeholder="Total Rate:" id="price" name="price">
                                </div>
                                
                                <div class="flex justify-center md:flex-row md:w-full md:justify-end pb-4 md:pb-16 md:px-0">
                                    <a href="{{ route('login') }}" class="px-20 py-2 bg-orange-300 text-white font-semibold rounded-lg hover:bg-orange-500 focus:outline-none focus:ring-1 focus:orange-400" type="button">Login to Book</a>
                                </div>
                            </form>
                        @else
                            <form id="firstPageForm" enctype="multipart/form-data">
                                @csrf
                                <input hidden type="text" id="customer_id" name="customer_id" value="{{ Auth::user()->id }}" required>

                                <div class="md:flex md:flex-row justify-center md:flex-grow md:space-x-3">
                                <div class="md:flex md:flex-row justify-center md:flex-grow md:space-x-3 md:pb-5">
                                <div class="w-full">
                                <label for="start_date" class="font-bold pb-2">Pick-up Location</label>
                                
                                    
                                    <!-- <input class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full" type="text" id="location" name="location" placeholder="From" required> -->
                                    <select name="location" id="location" class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full">
                                            <option value="A. Pascual, San Jose City">A. Pascual, San Jose City</option>
                                            <option value="Abar 1st, San Jose City">Abar 1st, San Jose City</option>
                                            <option value="Abar 2nd, San Jose City">Abar 2nd, San Jose City</option>
                                            <option value="Bagong Sikat, San Jose City">Bagong Sikat, San Jose City</option>
                                            <option value="Caanawan, San Jose City">Caanawan, San Jose City</option>
                                            <option value="Calaocan, San Jose City">Calaocan, San Jose City</option>
                                            <option value="Camanacsacan, San Jose City">Camanacsacan, San Jose City</option>
                                            <option value="Canuto Ramos, San Jose City">Canuto Ramos, San Jose City</option>
                                            <option value="Crisanto Sanchez, San Jose City">Crisanto Sanchez, San Jose City</option>
                                            <option value="Culaylay, San Jose City">Culaylay, San Jose City</option>
                                            <option value="Dizol, San Jose City">Dizol, San Jose City</option>
                                            <option value="F.E. Marcos, San Jose City">F.E. Marcos, San Jose City</option>
                                            <option value="Kaliwanagan, San Jose City">Kaliwanagan, San Jose City</option>
                                            <option value="Kita-KIta, San Jose City">Kita-KIta, San Jose City</option>
                                            <option value="Malasin, San Jose City">Malasin, San Jose City</option>
                                            <option value="Manicla, San Jose City">Manicla, San Jose City</option>
                                            <option value="Palestina, San Jose City">Palestina, San Jose City</option>
                                            <option value="Parang Mangga, San Jose City">Parang Mangga, San Jose City</option>
                                            <option value="Pinili, San Jose City">Pinili, San Jose City</option>
                                            <option value="Polaris, San Jose City">Polaris, San Jose City</option>
                                            <option value="Rafael Rueda, Sr., San Jose City">Rafael Rueda, Sr., San Jose City</option>
                                            <option value="Raymundo Eugenio, San Jose City">Raymundo Eugenio, San Jose City</option>
                                            <option value="San Agustin, San Jose City">San Agustin, San Jose City</option>
                                            <option value="San Juan, San Jose City">San Juan, San Jose City</option>
                                            <option value="San Mauricio, San Jose City">San Mauricio, San Jose City</option>
                                            <option value="Santo Niño 1st, San Jose City">Santo Niño 1st, San Jose City</option>
                                            <option value="Santo Niño 2nd, San Jose City">Santo Niño 2nd, San Jose City</option>
                                            <option value="Santo Tomas, San Jose City">Santo Tomas, San Jose City</option>
                                            <option value="Sibut, San Jose City">Sibut, San Jose City</option>
                                            <option value="Sinipit Bubon, San Jose City">Sinipit Bubon, San Jose City</option>
                                            <option value="Tabulac, San Jose City">Tabulac, San Jose City</option>
                                            <option value="Tayabo, San Jose City">Tayabo, San Jose City</option>
                                            <option value="Tondod, San Jose City">Tondod, San Jose City</option>
                                            <option value="Tulat, San Jose City">Tulat, San Jose City</option>
                                            <option value="Villa Joson, San Jose City">Villa Joson, San Jose City</option>
                                            <option value="Villa Marina, San Jose City">Villa Marina, San Jose City</option>
                                    </select>
                                    </div>

                                    <div class="w-full">
                                        <label for="end_date" class="font-bold pb-2">Destination</label>
                                    <select name="id" id="id" class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full">
                                        @foreach($activetariffs as $activetariff)
                                            <option value="{{ $activetariff->id }}">{{ $activetariff->destination }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                </div>
                                </div>

                                <!-- <label class="font-bold pb-2">Date and Time</label> -->
                                <div class="md:flex md:flex-row justify-center md:flex-grow md:space-x-3 md:pb-5">
                                    
                                    

                                    <div class="w-full">
                                        <label for="start_date" class="font-bold pb-2">Start Date</label>
                                        <input class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full" type="date" id="start_date" name="start_date" min="{{ \Carbon\Carbon::now()->addDays(3)->format('Y-m-d') }}" required>
                                    </div>
                                    
                                    <div class="w-full">
                                        <label for="end_date" class="font-bold pb-2">End Date</label>
                                        <input class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full" type="date" id="end_date" name="end_date" min="{{ \Carbon\Carbon::now()->addDays(3)->format('Y-m-d') }}" required>
                                    </div>

                                </div>

                                <!-- <div class="md:flex md:flex-grow md:space-x-3 pb-1 md:pb-2"> -->
                                    <!-- <div class="mb-4 bg-gray-200 rounded-md w-full flex items-center space-x-3 py-1 px-3 lg:w-96"> -->
                                    <div class="md:flex md:flex-row justify-center md:flex-grow md:space-x-3 md:pb-5">
                                    <div class="w-full">
                                    <label for="start_date" class="font-bold pb-2">Pick-up time</label>
                                        <input type="text" id="time" name="time" class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full" placeholder="time" required>
                                    </div>

                                        <div class="w-full">
                                        <label class="font-bold pb-2">No. of Passengers</label> 
                                        <!-- <div class="flex items-center md:space-x-1"> -->
                                            <!-- <button type="button" id="decrement-button" class="font-bold w-8 h-8 rounded-md hover:bg-gray-300 text-1xl">−</button>
                                            <input type="text" id="counter-input" class="text-black bg-gray-50 text-sm rounded-md max-w-[2.5rem] h-7 text-center" placeholder="1" value="1" required id="passenger" name="passenger"/>
                                            <button type="button" id="increment-button" class="font-bold w-8 h-8 rounded-md hover:bg-gray-300 text-1xl">+</button> -->
                                            <select name="passenger" id="passenger" class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full">
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                        </select>
                                        </div>
                                    </div>
                                <!-- </div> -->

                                <!-- <div class="md:flex md:flex-row justify-center md:flex-grow md:space-x-3 md:pb-5">
                                <input class="mb-4 font-bold text-black bg-gray-200 rounded-md px-3 py-2 w-full hidden" type="text" placeholder="Total Rate:" id="rat" name="rat">
                                <input class="mb-4 font-bold text-black bg-gray-200 rounded-md px-3 py-2 w-full hidden" type="text" placeholder="Total Rate:" id="succ" name="succ">
                                <input class="mb-4 font-bold text-black bg-gray-200 rounded-md px-3 py-2 w-full" type="text" placeholder="Total Rate:" id="price" name="price">
                                </div> -->
                                <div class="md:flex md:flex-col md:items-end md:space-y-2 md:py-5 text-right">
                                    <p class="font-bold hidden" id="topay">PAYMENT DETAILS</p>
                                    <p class="text-gray-700 font-bold hidden" id="ratt">Rate Subtotal: <span id="rat" class="font-bold text-black hidden">₱0</span></p>
                                    <p class="text-gray-700 font-bold hidden" id="succee">Succeeding Rate Subtotal: <span id="succ" class="font-bold text-black hidden">₱0</span></p>
                                    <p class="text-green-700 font-bold hidden" id="inclusion">Inclusions: Vehicle, a Driver, and Gas</p>
                                    <input class="mb-4 font-bold text-black bg-gray-200 rounded-md px-3 py-2 w-64" type="text" placeholder="Total Payment" id="price" name="price">
                                    <p class="text-gray-700 font-bold hidden" id="minimum">Minimum down payment starts at: <span id="minAmount" class="font-bold text-black">₱1000.00</span></p>
                                </div>
                                
                                <div class="flex justify-center md:flex-row md:w-full md:justify-end pb-4 md:pb-16 md:px-0">
                                    <button id="bookButton" class="px-20 py-2 bg-orange-300 text-white font-semibold rounded-lg hover:bg-orange-500 focus:outline-none focus:ring-1 focus:orange-400" type="submit">Next</button>
                                </div>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </section>
    </body>

    <script>
        // const decrementButton = document.getElementById('decrement-button');
        // const incrementButton = document.getElementById('increment-button');
        // const counterInput = document.getElementById('counter-input');
        // let counterValue = 1;

        // function updateCounter() {
        //     counterInput.value = counterValue;
        // }

        // decrementButton.addEventListener('click', function() {
        //     if (counterValue > 1) {
        //         counterValue--;
        //         updateCounter();
        //     }
        // });

        // incrementButton.addEventListener('click', function() {
        //     if (counterValue < 20) { 
        //         counterValue++;
        //         updateCounter();
        //     }
        // });
        $(document).ready(function() {
            var firstPageData = {}; 

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // $('#bookButton').click(function() {
            //     //first page data
            //     firstPageData['customer_id'] = $('#customer_id').val();
            //     firstPageData['location'] = $('#location').val();
            //     firstPageData['id'] = $('#id').val();
            //     firstPageData['time'] = $('#time').val();
            //     firstPageData['start_date'] = $('#start_date').val();
            //     firstPageData['end_date'] = $('#end_date').val();
            //     // firstPageData['passenger'] = $('#counter-input').val();
            //     firstPageData['passenger'] = $('#passenger').val();
            //     firstPageData['price'] = $('#price').val();
            $('#bookButton').click(function(event) {
        event.preventDefault();

        $('#errorMessages').remove();

        var errors = [];
        
        if ($('#location').val() === "") {
            errors.push("Location is required.");
        }
        if ($('#id').val() === "") {
            errors.push("Please select a destination.");
        }
        if ($('#time').val() === "") {
            errors.push("Pick-up time is required.");
        }
        if ($('#start_date').val() === "") {
            errors.push("Start date is required.");
        }
        if ($('#end_date').val() === "") {
            errors.push("End date is required.");
        }
        if ($('#passenger').val() === "") {
            errors.push("Passenger count is required.");
        }

        if (errors.length > 0) {
            var errorHtml = '<div id="errorMessages" class="bg-red-300 text-red-800 p-4 rounded-md mb-4"><ul>';
            errors.forEach(function(error) {
                errorHtml += '<li>' + error + '</li>';
            });
            errorHtml += '</ul></div>';
            $('#bookingCard').prepend(errorHtml);
            return; 
        }

        firstPageData['customer_id'] = $('#customer_id').val();
        firstPageData['location'] = $('#location').val();
        firstPageData['id'] = $('#id').val();
        firstPageData['time'] = $('#time').val();
        firstPageData['start_date'] = $('#start_date').val();
        firstPageData['end_date'] = $('#end_date').val();
        firstPageData['passenger'] = $('#passenger').val();
        firstPageData['price'] = $('#price').val();
        firstPageData['rat'] = $('#rat').text();
        firstPageData['succ'] = $('#succ').text();

        // <p class="text-center mb-8 text-xs">A mimimum of ₱1,000 down payment is needed to confirm booking</p>
        // <h1 class="text-black font-extrabold text-center text-sm pb-2">Please complete payment or down payment to confirm booking reservation</h1>

        // <div class="flex justify-center space-x-3 items-center">
        //                     <p class="text-black font-extrabold text-center text-4xl" id="price" name="price">${firstPageData.price}</p>
        //                 </div>
                // second page
                $('#bookingCard').html(`
                    <div class="flex flex-col justify-center px-8 md:px-4 ">
                        <i id="goBackButton" class="fas fa-arrow-left text-2xl text-slate-950 hover:text-slate-600 pb-8 md:pb-4"
                            style="cursor: pointer;">Back</i>
                        <hr class="border-1 border-gray-800">
                        <div class="md:flex md:flex-col md:items-end md:space-y-2 md:py-5 text-right">
                        <p class="text-gray-700 font-bold" id="topay">PAYMENT DETAILS</p>
                        <p class="text-gray-700" id="ratt">Rate Subtotal: <span id="rat" class="font-bold text-black">${firstPageData.rat}</span></p>
                        <p class="text-gray-700" id="succee">Succeeding rate Subtotal: <span id="succ" class="font-bold text-black">${firstPageData.succ}</span></p>
                        <p class="text-green-700 font-bold" id="inclusion">Inclusions: Vehicle, a Driver, and Gas</p>
                        <input class="mb-4 font-bold text-black bg-gray-200 rounded-md px-3 py-2 w-64" type="text" placeholder="Total Rate:" id="price" name="price" value="${firstPageData.price}">
                        <p class="text-gray-700 font-bold  id="minimum">Minimum down payment starts at: <span id="minAmount" class="font-bold text-black">₱1000.00</span></p>
                    </div>
                        
                        <hr class="border-1 border-gray-800">
                        <div class="flex justify-center mb-8 items-center">
                            <h2 class="text-red-600 font-bold text-md">Please be advised that if you wish to cancel your reservation, your payment is NON-REFUNDABLE</h2>
                            <p class="text-black font-bold text-center text-md"></p>
                        <hr>
                        </div>
                        

                        <div class="flex flex-col justify-center lg:flex-row items-center lg:space-y-0 pb-4">
                            <div class="flex flex-col">
                                <div class="flex flex-row justify-center space-x-5 mb-4">
                                    <button onclick="toggleModal('gcashModal')">
                                        <img src="{{ asset('img/system/gcash.png') }}" alt="Gcash" class="h-12 w-12 rounded-full bg-blue-200" />
                                    </button>
                                    <!-- Maya Button -->
                                    <button onclick="toggleModal('mayaModal')">
                                        <img src="{{ asset('img/system/maya.jpg') }}" alt="Maya" class="h-12 w-12 rounded-full" />
                                    </button>
                                </div>
                                <h3 class="text-xs text-center">Select and scan the QR code to send payment</h3>
                            </div>

                            <!-- Gcash Modal -->
                                <div id="gcashModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
                                    <div class="bg-white p-6 rounded-lg w-80">
                                        <h2 class="text-center text-lg mb-4">Gcash Payment</h2>
                                        <img src="{{ asset('img/system/gcash.jpg') }}" alt="Gcash QR Code" class="w-full h-auto mb-4" />
                                        <button class="bg-blue-500 text-white px-4 py-2 rounded" onclick="toggleModal('gcashModal')">Close</button>
                                    </div>
                                </div>

                                <!-- Maya Modal -->
                                <div id="mayaModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
                                    <div class="bg-white p-6 rounded-lg w-80">
                                        <h2 class="text-center text-lg mb-4">Maya Payment</h2>
                                        <img src="{{ asset('img/system/maya1.jpg') }}" alt="Maya QR Code" class="w-full h-auto mb-4" />
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
                

            
                $('#submitButton').click(function() {
                    event.preventDefault();

                    $('#errorMessages').remove(); 

                    var errors = []; 

                    if ($('#receipt')[0].files.length === 0) {
                        errors.push("Receipt upload is required.");
                    }

        if (errors.length > 0) {
            var errorHtml = '<div id="errorMessages" class="bg-red-300 text-red-800 p-4 rounded-md mb-4"><ul>';
            errors.forEach(function(error) {
                errorHtml += '<li>' + error + '</li>';
            });
            errorHtml += '</ul></div>';
            $('#bookingCard').prepend(errorHtml);
            return; 
        }

                    var formData = new FormData();
                    formData.append('customer_id', firstPageData.customer_id);
                    formData.append('location', firstPageData.location);
                    formData.append('id', firstPageData.id);
                    formData.append('time', firstPageData.time);
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
                                    error: function(xhr, status, error) {
                                alert('Error occurred during booking confirmation:\n\n' +
                                    'Status: ' + status + '\n' +
                                    'Error: ' + error + '\n' +
                                    'Response: ' + xhr.responseText);
                            
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
                        $('#price').val('TOTAL PAYMENT: ₱' + response.price + '.00'); // Set total price
                        $('#succ').text('₱' + response.succeeding + '.00'); // Set succeeding rate
                        $('#rat ').text('₱' + response.rate);
                        $('#rat').removeClass('hidden');
                        $('#succ').removeClass('hidden');
                        $('#topay').removeClass('hidden');
                        $('#inclusion').removeClass('hidden');
                        $('#ratt').removeClass('hidden');
                        $('#succee').removeClass('hidden');
                        $('#minimum').removeClass('hidden');
                    
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


let isChangingDate = false; 
flatpickr("#time", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "h:i K",
    minuteIncrement: 30, 
    clickOpens: true,
    // allowInput: true, 

    onChange: function(selectedDates, dateStr, instance) {
        if (isChangingDate) return; 
        var date = selectedDates[0];
        if (date) {
            var minutes = date.getMinutes();
            var roundedMinutes = Math.round(minutes / 30) * 30;
            date.setMinutes(roundedMinutes);

            isChangingDate = true; 
            instance.setDate(date, true);
            isChangingDate = false;
        }
    }
});


    </script>
</x-app-layout>

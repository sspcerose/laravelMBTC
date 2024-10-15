@extends('layout.layout')

@include('ForTesting.layout.userNav')

<body class="font-inter">

    <section class="lg:pt-32 lg:px-32 xl:px-52 relative">

        <div class="bg-landing h-[95vh] md:h-[100vh] flex items-center justify-center bg-cover bg-center lg:hidden">
        </div>

        <div class="hidden lg:block rounded-3xl overflow-hidden h-[60vh]">
            <img src="{{ asset('img/hero-bg-1.jpg') }}" alt="Description of image" class="w-full h-full object-cover" />
        </div>

        <div id="bookingCard"
            class="relative translate-y-[-20%] mb-10 inset-x-0 top-[90%] lg:top-[85%] left-1/2 transform -translate-x-1/2 w-full max-w-2xl xl:max-w-3xl rounded-3xl bg-gray-50 pt-16 md:px-16 md:shadow-[0_2.8px_2.2px_rgba(0,_0,_0,_0.034),_0_6.7px_5.3px_rgba(0,_0,_0,_0.048),_0_12.5px_10px_rgba(0,_0,_0,_0.06),_0_22.3px_17.9px_rgba(0,_0,_0,_0.072),_0_41.8px_33.4px_rgba(0,_0,_0,_0.086),_0_100px_80px_rgba(0,_0,_0,_0.12)]">
            <form id="bookingForm" action="">
                <div class="flex flex-col justify-center mx-10">
                    <h1 class="text-black font-extrabold text-center text-4xl mb-8">Book Now</h1>
                    <label class="font-bold pb-2">Location</label>
                    <div class="md:flex md:flex-row justify-center md:flex-grow  md:space-x-3">
                        <input class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full" type="text" placeholder="From">
                        <input class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full" type="text" placeholder="To">
                    </div>

                    <label class="font-bold pb-2">Date</label>
                    <div class="md:flex md:flex-row justify-center md:flex-grow  md:space-x-3 md:pb-5">
                        <input class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full" type="date" placeholder="Depart">
                        <input class="mb-4 bg-gray-200 rounded-md px-3 py-2 w-full" type="date" placeholder="Return">
                    </div>

                    <div class="md:flex justify-center md:flex-grow md:space-x-3 pb-4 md:pb-8">
                        <div
                            class="mb-4 bg-gray-200 rounded-md w-full flex items-center space-x-3 py-1 pl-3 px-1 justify-between">
                            <label class="font-medium">Passenger</label>
                            <div class="flex items-center md:space-x-1">
                                <button type="button" id="decrement-button"
                                    class="font-bold w-8 h-8 rounded-md hover:bg-gray-300 text-1xl">−</button>
                                <input type="text" id="counter-input"
                                    class="text-black bg-gray-50 text-sm rounded-md max-w-[2.5rem] h-7 text-center"
                                    placeholder="0" value="0" required />
                                <button type="button" id="increment-button"
                                    class="font-bold w-8 h-8 rounded-md hover:bg-gray-300 text-1xl">+</button>
                            </div>
                        </div>
                        <input class="mb-4 font-bold text-black bg-gray-200 rounded-md px-3 py-2 w-full" type="text"
                            placeholder="Total:">
                    </div>
                </div>

                <div class="flex justify-center md:flex-row md:w-full md:justify-end pb-4 md:pb-16 md:px-0">
                    <button id="bookButton"
                        class="px-20 py-2 bg-orange-300 text-white font-semibold rounded-lg hover:bg-orange-500 focus:outline-none focus:ring-1 focus:orange-400"
                        type="button">Book</button>
                </div>
            </form>
        </div>
    </section>

</body>

<script>
    const decrementButton = document.getElementById('decrement-button');
    const incrementButton = document.getElementById('increment-button');
    const counterInput = document.getElementById('counter-input');
    let counterValue = 0;

    function updateCounter() {
        counterInput.value = counterValue;
    }

    decrementButton.addEventListener('click', function() {
        if (counterValue > 0) {
            counterValue--;
            updateCounter();
        }
    });

    incrementButton.addEventListener('click', function() {
        counterValue++;
        updateCounter();
    });

    const bookingCard = document.getElementById('bookingCard');
    const originalForm = bookingCard.innerHTML; // Store the original booking form HTML

    function initializeBookingButton() {
        const bookButton = document.getElementById('bookButton');
        if (bookButton) {
            bookButton.addEventListener('click', function() {
                bookingCard.innerHTML = `
                    <div class="flex flex-col justify-center px-8 md:px-4 ">

                <i id="goBackButton" class="fas fa-arrow-left text-2xl text-slate-950 hover:text-slate-600 pb-8 md:pb-4"
                    style="cursor: pointer;"></i>

                <h1 class="text-black font-extrabold text-center text-sm pb-2">Please complete payment or down payment
                    to confirm booking reservation</h1>

                <p class="text-center mb-8 text-xs">A down payment of 50% is needed to confirm booking</p>


                <div class="flex justify-center space-x-3 items-center">
                    <h1 class="text-black font-extrabold text-center text-4xl">Total:</h1>
                    <p class="text-black font-extrabold text-center text-4xl"> ₱4,000{{-- ${{ number_format($downPaymentAmount, 2) }} --}}</p>

                </div>

                <div class="flex justify-center mb-8 items-center">
                    <h2 class="text-black font-bold text-center text-md">Down payment: </h2>
                    <p class="text-black font-bold text-center text-md">₱2,000 {{-- ${{ number_format($downPaymentAmount, 2) }} --}}</p>
                </div>

                <div class="flex flex-col justify-center lg:flex-row items-center lg:space-y-0 pb-4">

                    <div class="flex flex-col">
                        <div class="flex flex-row justify-center space-x-5 mb-4">
                            <button>
                                <img src="{{ asset('img/gcash.png') }}" alt="Gcash"
                                    class="h-12 w-12 rounded-full bg-blue-200" />
                            </button>

                            <button>
                                <img src="{{ asset('img/maya.jpg') }}" alt="Maya"
                                    class="h-12 w-12 rounded-full" />
                            </button>
                        </div>

                        <h3 class="text-xs text-center">Select and scan the QR code to send payment</h3>
                    </div>

                    <hr class=" my-10 h-0.5 bg-neutral-300 rounded-full border-1  w-full lg:hidden">

                    <div class="hidden lg:flex lg:flex-1 lg:justify-center lg:items-center mx-12">
                        <div class="h-[150px] min-h-[1em] w-0.5 bg-neutral-300 mx-4"></div>
                    </div>

                    <div class="flex flex-col items-center">
                        <form action="">
                            <input type="file"
                                class="w-full text-gray-400 font-medium text-sm bg-gray-200 file:cursor-pointer file:border-0 file:py-2 file:px-2 file:mr-2 file:bg-gray-300 file:hover:bg-gray-400 file:text-black rounded-md mb-4" />
                        </form>
                        <h1 class="text-center text-xs pb-10">Upload Payment Receipt</h1>
                    </div>

                </div>

                <div class="flex justify-center items-center">
                    <form action="" method="POST" class="mb-10 w-full flex justify-center lg:justify-end">
                        <button
                            class="w-full max-w-xs lg:w-auto px-10 py-3 bg-green-700 text-white font-semibold rounded-xl hover:bg-green-500">
                            Confirm
                        </button>
                    </form>
                </div>
            </div>
                `;

                // Reinitialize the goBackButton event listener
                const goBackButton = document.getElementById('goBackButton');
                goBackButton.addEventListener('click', function() {
                    bookingCard.innerHTML = originalForm; // Restore the original booking form content
                    initializeBookingButton(); // Reinitialize the booking button
                });
            });
        }
    }

    // Initialize the booking button when the page loads
    initializeBookingButton();
</script>
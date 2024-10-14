@extends("layout.userLayout")

<body class="bg-slate-800 flex flex-col lg:flex-row">

    <section
        class="bg-gray-50 rounded-br-5xl rounded-bl-5xl md:flex-[3] lg:rounded-tr-5xl lg:rounded-bl-none">

        <div class="">

            <div class="p-5 font-bold text-gray-400 text-3xl pb-36">
                <h1>MBTC</h1>
            </div>

            <div class='px-5 lg:px-10 xl:px-10'>

                <div class="font-bold text-gray-950 text-4xl pb-6  md:text-5xl xl:text-6xl">
                    <h1><span class='text-orange-300'>Create</span> your</h1>
                    <h1 class="">Account Now</h1>
                </div>

                <div class="text-1xl text-gray-950 pr-14 pb-44 md:text-2xl xl:text-3xl">
                    <p>Plan your adventure with ease. Sign up today!</p>
                </div>

            </div>

        </div>

    </section>

    <section class="bg-slate-800 md:flex-[4] md:h-screen">

        <div class="px-8 pt-20 lg:pt-36 md:px-36 lg:px-36 xl:px-48">

            <div class="font-bolder mb-5">
                <h2 class="text-3xl text-gray-50 font-bold md:text-3xl">Welcome!</h2>
                <h2 class="text-1xl text-gray-400 pb-8 md:pb-4 lg:pb-5">Sign Up to MBTC</h2>
            </div>

            <div class="">

                <form action="">

                    <div class="flex flex-col justify-center">

                        <div class="lg:flex lg:flex-row justify-center md:flex-grow lg:space-x-3">
                            <input class="mb-4 bg-gray-100 rounded-md px-3 py-2 w-full" type="text" placeholder="First Name">
                            <input class="mb-4 bg-gray-100 rounded-md px-3 py-2 w-full" type="text" placeholder="Last Name">
                        </div>

                        <input class="mb-4 bg-gray-100 rounded-md px-3 py-2 w-full" type="text" placeholder="Email">
                        <input class="mb-4 bg-gray-100 rounded-md px-3 py-2 w-full" type="text" placeholder="Mobile Number">
                        <input class="mb-4 bg-gray-100 rounded-md px-3 py-2 w-full" type="text" placeholder="Password">
                        <input class="mb-6 bg-gray-100 rounded-md px-3 py-2 w-full" type="text" placeholder="Confirm Password">

                    </div>

                    <div class="flex justify-center pb-40 lg:pb-0">
                        <button
                            class=" px-20 py-2 bg-orange-300 text-gray-50 font-semibold rounded-lg hover:bg-orange-400 focus:outline-none focus:ring-2 focus:orange-400"
                            type="submit">
                            Sign Up
                        </button>
                    </div>



                </form>

            </div>


        </div>





    </section>

</body>
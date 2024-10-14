@extends("layout.userLayout")

<body class="bg-orange-300 flex flex-col lg:flex-row font-inter">

    <section
        class="bg-orange-300 rounded-br-5xl rounded-bl-5xl md:flex-[4] lg:rounded-br-5xl lg:rounded-tr-5xl lg:rounded-bl-none ">

        <div class="p-5">

            <div class="font-bold text-white text-3xl pb-40 xl:pb-52">
                <h1>MBTC</h1>
            </div>

            <div class='px-0 sm:px-5 lg:px-10'>

                <div class="font-extrabold text-white text-4xl pb-3 md:text-6xl xl:text-7xl">
                    <h1><span class='text-slate-900'>Book</span> Your</h1>
                    <h1 class="">Escape Now</h1>
                </div>

                <div class="text-1xl text-gray-800 font-semibold pr-14 pb-20 md:text-1xl lg:pr-44">
                    <p class="md:pr-40 lg:pr-0">Book online with ease, and enjoy flexible options to fit your schedule.
                    </p>
                </div>

            </div>

        </div>

    </section>

    <section
        class="bg-white md:flex-[3] md:h-screen rounded-tr-5xl rounded-tl-5xl lg:rounded-tr-none lg:rounded-bl-5xl">

        <div class="px-8 pt-20 lg:pt-52 xl:pt-60 md:px-52 lg:px-16 xl:px-32">

            <div class="font-bolder mb-10">
                <h2 class="text-3xl text-gray-900 font-extrabold lg:text-4xl">Welcome Back,</h2>
                <h2 class="text-3xl text-gray-900 font-extrabold lg:text-4xl">Admin!</h2>
            </div>

            <div>

                <form action="">

                    <div class="flex flex-col justify-center">
                        <input class="mb-4  bg-gray-100 rounded-md px-3 py-2 w-full" type="text"
                            placeholder="Email or Username">
                        <input class="mb-6  bg-gray-100 rounded-md px-3 py-2 w-full" type="text "
                            placeholder="Password">
                    </div>

                    <div class="flex justify-center pb-40 lg:pb-0">
                        <button
                        class=" px-20  py-2 bg-orange-300 text-white font-semibold rounded-lg hover:bg-orange-500 focus:outline-none focus:ring-2 focus:orange-400"
                        type="submit">
                            Sign In
                        </button>
                    </div>



                </form>

            </div>


        </div>





    </section>

</body>

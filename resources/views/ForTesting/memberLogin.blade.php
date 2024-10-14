@extends("layout.userLayout")


<body class=" flex flex-col lg:flex-row">

    <section class="bg-white md:flex-[3] lg:rounded-bl-none md:h-screen ">

        <div class="p-5">

            <div class="font-bold text-gray-400 text-2xl pb-40 xl:pb-52">
                <h1 class="">MBTC</h1>
            </div>

            <div class='px-0 sm:px-5 lg:px-10'>

                <div class="font-bold text-gray-900 text-5xl pb-6 md:text-6xl xl:text-7xl">
                    <h1><span class='text-orange-300'>Book</span> Your</h1>
                    <h1 class="">Escape Now</h1>
                </div>

                <div class="text-1xl text-gray-400 pr-14 pb-20 md:text-1xl xl:text-2xl lg:pr-48">
                    <p class="md:pr-40 lg:pr-0">Book online with ease, and enjoy flexible options to fit your schedule.</p>
                </div>

            </div>
            <div>

            </div>

        </div>

    </section>

    <section class="bg-orange-300 md:flex-[2] md:h-screen rounded-tl-5xl rounded-tr-5xl lg:rounded-bl-5xl lg:rounded-tr-none">

        <div class="px-8 pt-20 lg:pt-40 xl:pt-44 md:px-52 lg:px-16 xl:px-32 align-middle">

            <div class="font-bolder mb-5 ">
                <h2 class="text-3xl text-gray-900 font-bold lg:text-3xl">Welcome Back!</h2>
                <h2 class="text-lg text-gray-800 pb-16 md:pb-10 lg:pb-5">Sign in to MBTC</h2>
            </div>

            <div>

                <form action="">

                    <div class="flex flex-col justify-center">
                        <input class="mb-4 bg-gray-100 rounded-md px-3 py-2 w-full" type="text"
                            placeholder="Email or Username">
                        <input class="mb-6 bg-gray-100 rounded-md px-3 py-2 w-full" type="text " placeholder="Password">
                    </div>

                    <div class="flex justify-center pb-40 lg:pb-0">
                        <button
                            class=" px-20 py-2 bg-slate-900 text-white font-semibold rounded-lg hover:bg-slate-700 "
                            type="submit">
                            Sign In
                        </button>
                    </div>



                </form>

            </div>


        </div>





    </section>

</body>
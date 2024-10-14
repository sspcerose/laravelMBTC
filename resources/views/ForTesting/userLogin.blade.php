@extends("layout.userLayout")

<body class="flex flex-col lg:flex-row">

    <section class="bg-slate-800 md:flex-[4] rounded-br-5xl rounded-bl-5xl  lg:rounded-br-5xl lg:rounded-tr-5xl lg:rounded-bl-none md:h-screen">

        <div class="p-5">

            <div class="font-bold text-white text-3xl pb-40 xl:pb-48">
                <h1 class="">MBTC</h1>
            </div>

            <div class='px-0 sm:px-5 lg:px-10'>

                <div class="font-bold text-white text-5xl pb-6 md:text-6xl xl:text-7xl">
                    <h1><span class='text-orange-300'>Book</span> Your</h1>
                    <h1 class="">Escape Now</h1>
                </div>

                <div class="text-1xl text-gray-400 pr-14 pb-32 lg:text-2xl lg:pr-48">
                    <p class="md:pr-40 lg:pr-0">Book online with ease, and enjoy flexible options to fit your schedule.</p>
                </div>

            </div>

        </div>

    </section>

    <section class="bg-white md:flex-[3] md:h-screen ">

        <div class="px-8 pt-14 lg:pt-52 xl:pt-60 md:px-52 lg:px-16 xl:px-32">

            
           

            <div class="font-bolder mb-5">
                <h2 class="text-3xl text-gray-900 font-bold lg:text-4xl">Welcome Back!</h2>
                <h2 class="text-1xl text-gray-400 pb-16 md:pb-10 lg:pb-5">Sign in to MBTC</h2>
            </div>

            <div>

                <form action="">

                    <div class="flex flex-col justify-center">
                        <input class="mb-4 mx-2 bg-gray-100 rounded-md px-3 py-2" type="text"
                            placeholder="Email or Username">
                        <input class="mb-6 mx-2 bg-gray-100 rounded-md px-3 py-2" type="text " placeholder="Password">
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
@extends('layout.layout')

@include('ForTesting.layout.userNav')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body>
    <div class="max-w-3xl mx-auto p-2 pb-40">
        <div class="pt-24 flex justify-between items-center">
            <h1 class="text-black py-6  text-center md:text-left font-semibold text-3xl lg:text-5xl">Welcome, <span class="font-extrabold">Joseph</span></h1>
        </div>

        <div class="bg-neutral-300 rounded-2xl p-3">
            <form action="" class="flex flex-col lg:flex-row justify-between bg-neutral-50 rounded-xl px-4 py-16 lg:p-10 md:px-20">


                <div class="relative flex justify-center items-center pb-10 md:px-10">
                    <label for="file-input" class="flex flex-col items-center justify-center bg-neutral-300 rounded-full p-2 cursor-pointer hover:bg-neutral-400">
                        <input id="file-input" type="file" class="hidden" accept="image/*" onchange="displayImage(event)" />
                        <i id="user-icon" class="fas fa-user-circle text-9xl p-1"></i>
                        <img id="profile-image" src="" alt="User Image" class="rounded-full hidden w-32 h-32 object-cover absolute" />
                    </label>
                </div>

                <div class="flex flex-col">
                    <label for="name" class="mb-1 mx-2 text-sm font-medium text-gray-700">Name</label>
                    <input id="name" class="mb-4 mx-2 bg-neutral-200 rounded-lg px-3 py-2" type="text" placeholder="Joseph">

                    <label for="email" class="mb-1 mx-2 text-sm font-medium text-gray-700">Email</label>
                    <input id="email" class="mb-4 mx-2 bg-neutral-200 rounded-lg px-3 py-2" type="email" placeholder="Joseph@gmail.com">

                    <label for="phone" class="mb-1 mx-2 text-sm font-medium text-gray-700">Mobile</label>
                    <input id="phone" class="mb-4 mx-2 bg-neutral-200 rounded-lg px-3 py-2" type="tel" placeholder="0918372847">

                    <label for="password" class="mb-1 mx-2 text-sm font-medium text-gray-700">Password</label>
                    <input id="password" class="mb-6 mx-2 bg-neutral-200 rounded-lg px-3 py-2" type="password" placeholder="passwordisPass">

                    <div class="flex justify-end">
                        <button type="submit" class="w-1/2 right-0 mb-4 mx-2 bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-500 focus:outline-none">
                            Save
                        </button>
                    </div>
                </div>


            </form>
        </div>
    </div>
</body>

<script>
    function displayImage(event) {
        const file = event.target.files[0]; // Get the uploaded file
        const image = document.getElementById('profile-image'); // Image element to display the photo
        const userIcon = document.getElementById('user-icon'); // User icon element

        if (file) {
            const reader = new FileReader(); // Create a FileReader to read the file
            reader.onload = function(e) {
                image.src = e.target.result; // Set the image source to the uploaded file
                image.classList.remove('hidden'); // Show the image
                userIcon.classList.add('hidden'); // Hide the user icon
            }
            reader.readAsDataURL(file); // Read the file as a data URL
        }
    }
</script>
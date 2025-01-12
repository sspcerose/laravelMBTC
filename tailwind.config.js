import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            borderRadius: {
                "4xl": "2rem", // You can specify a value larger than 3xl here
                "5xl": "3rem", // Another example of a larger radius
                "6xl": "6rem",
            },

            shadow: {
                "4xl": "2rem", // You can specify a value larger than 3xl here
                "5xl": "3rem", // Another example of a larger radius
                "6xl": "6rem",
                "8xl": "8rem",
                "12xl": "12rem",
                "18xl": "18rem",
            },
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};

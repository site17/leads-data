/** @type {import('tailwindcss').Config} */
const defaultTheme = require("tailwindcss/defaultTheme");
export default {
    purge: false, // Отключите purge временно
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: "class",
    theme: {
        extend: {
            tableLayout: ["hover", "focus"],
            fontFamily: {
                sans: ["Arial", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                "primary-1": "#4097FF",
                "primary-2": "#1A56DB",
                "primary-3": "#BBDAFF",
                "dark-1": "#18181B",
                "color-gray": "#F9FAFB",
                "gray-2": "#F1F1F1",
                "gray-3": "#F2F2F2",
            },
            boxShadow: {
                primary: "0px 25px 50px 0px #FFFFFF1A",
            },
            backgroundColor: {
                "pink-one": "#ff49db",
                "latest-product": "#ECFEF4",
                "primary-btn": "rgba(41, 41, 48, 0.2)",
                "primary-3": "#BBDAFF",
            },
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("flowbite/plugin"),
        // [forms, typography, require("tw-elements/dist/plugin.cjs")],
        // require("tw-elements/dist/plugin.cjs"),
    ],
};

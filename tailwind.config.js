/** @type {import('tailwindcss').Config} */
module.exports = {
  // Tailwind escanea estos archivos para generar solo las clases utilizadas
  content: ["./public/**/*.{html,js}"],
  theme: {
    extend: {
      // Paleta de marca personalizada: se usa como bg-brand-600, text-brand-700, etc.
      colors: {
        brand: {
          50: "#f6f7ee",
          100: "#e9ecd6",
          500: "#6b7c37",
          600: "#556329",
          700: "#45511f",
          900: "#2b3314",
        },
      },
      fontFamily: {
        sans: ["Inter", "ui-sans-serif", "system-ui", "Segoe UI", "Roboto", "sans-serif"],
        serif: ["Lora", "Georgia", "serif"],
      },
    },
  },
  plugins: [],
};

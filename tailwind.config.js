/** @type {import('tailwindcss').Config} */
module.exports = {
  // Tailwind escanea estos archivos para generar solo las clases utilizadas
  content: ["./public/**/*.{html,js}"],
  theme: {
    extend: {
      // Paleta de marca personalizada: se usa como bg-brand-600, text-brand-700, etc.
      colors: {
        brand: {
          50: "#f0fdf4",
          100: "#dcfce7",
          500: "#22c55e",
          600: "#16a34a",
          700: "#15803d",
          900: "#14532d",
        },
      },
      fontFamily: {
        sans: ["Inter", "ui-sans-serif", "system-ui", "Segoe UI", "Roboto", "sans-serif"],
      },
    },
  },
  plugins: [],
};

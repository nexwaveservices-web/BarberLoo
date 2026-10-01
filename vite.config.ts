import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import path from 'path';
import { fileURLToPath } from 'url';
import { defineConfig } from 'vite';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export default defineConfig(() => {
  return {
    plugins: [react(), tailwindcss()],
    resolve: {
      alias: {
        '@': path.resolve(__dirname, '.'),
      },
    },
    build: {
      rollupOptions: {
        input: {
          main: path.resolve(__dirname, 'index.html'),
          login: path.resolve(__dirname, 'login.html'),
          signup: path.resolve(__dirname, 'signup.html'),
          booking: path.resolve(__dirname, 'booking.html'),
          myBookings: path.resolve(__dirname, 'my-bookings.html'),
          customerDiscover: path.resolve(__dirname, 'customer/discover.html'),
          customerShopDetails: path.resolve(__dirname, 'customer/shop-details.html'),
          customerQueue: path.resolve(__dirname, 'customer/queue.html'),
          customerProfile: path.resolve(__dirname, 'customer/profile.html'),
          barberDashboard: path.resolve(__dirname, 'barber/dashboard.html'),
          barberAnalytics: path.resolve(__dirname, 'barber/analytics.html'),
          barberShop: path.resolve(__dirname, 'barber/shop.html'),
          barberQueue: path.resolve(__dirname, 'barber/queue.html'),
          barberServices: path.resolve(__dirname, 'barber/services.html'),
          barberAppointments: path.resolve(__dirname, 'barber/appointments.html'),
          adminDashboard: path.resolve(__dirname, 'admin/dashboard.html')
        }
      }
    },
    server: {
      port: 3000,
      host: '0.0.0.0',
      // HMR is disabled in AI Studio via DISABLE_HMR env var.
      hmr: process.env.DISABLE_HMR !== 'true',
      watch: process.env.DISABLE_HMR === 'true' ? null : {},
    },
  };
});


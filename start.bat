@echo off
title DASHBOARD CENTER TOOLS NET - Development Server
color 0A
echo.
echo ====================================
echo  Starting Laravel Development Server
echo ====================================
echo.
echo Server akan berjalan di:
echo - Backend:  http://localhost:8000
echo - Frontend: http://localhost:5173 (Vite)
echo.
echo Tekan CTRL+C untuk menghentikan server
echo ====================================
echo.

start "Laravel Server" cmd /k "php artisan serve"
start "Vite Dev Server" cmd /k "npm run dev"

echo.
echo [INFO] Server berjalan di window terpisah
echo [INFO] Tutup window ini untuk menutup semua server
pause

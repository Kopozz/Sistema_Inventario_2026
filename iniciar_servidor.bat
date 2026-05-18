@echo off
chcp 65001 > nul
title Servidor de Desarrollo - Sistema de Repuestos
echo ===================================================================
echo       INICIANDO SERVIDOR DE DESARROLLO - SISTEMA DE REPUESTOS
echo ===================================================================
echo.

:: Detectar ejecutable de PHP de XAMPP o del sistema
set "PHP_BIN=php"
if exist "C:\xampp\php\php.exe" (
    set "PHP_BIN=C:\xampp\php\php.exe"
)

echo [INFO] Usando ejecutable de PHP: %PHP_BIN%
echo [OK] Servidor iniciado correctamente.
echo.
echo === INSTRUCCIONES DE ACCESO ===
echo.
echo 👉 Abre tu navegador e ingresa a: http://localhost:8000
echo.
echo ===================================================================
echo Presione Ctrl+C en esta ventana para detener el servidor.
echo ===================================================================
echo.

:: Ir al directorio de la app y arrancar el servidor embebido
cd /d "%~dp0miapp"
"%PHP_BIN%" -S localhost:8000 -t public public/index.php
pause

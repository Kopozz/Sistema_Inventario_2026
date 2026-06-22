-- ============================================================
-- MIGRACIÓN: Agregar columna 'imagen' a tabla repuestos
-- Sistema de Repuestos de Vehículos
-- Fecha: 2026-06-22
-- 
-- INSTRUCCIONES:
-- 1. Ingresa al panel de AlwaysData (https://admin.alwaysdata.com)
-- 2. Ve a "Bases de datos" > "MySQL" > "phpMyAdmin"
-- 3. Selecciona tu base de datos
-- 4. Ve a la pestaña "SQL" y pega este script
-- 5. Haz clic en "Ejecutar"
-- ============================================================

-- Verificar si la columna ya existe antes de agregarla (evita errores)
SET @col_exists = (
    SELECT COUNT(*) 
    FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'repuestos' 
    AND COLUMN_NAME = 'imagen'
);

-- Solo agregar si no existe
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE repuestos ADD COLUMN imagen MEDIUMTEXT DEFAULT NULL AFTER activo',
    'SELECT "La columna imagen ya existe, no se realizaron cambios" AS mensaje'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Confirmar resultado
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT,
    ORDINAL_POSITION
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME = 'repuestos'
ORDER BY ORDINAL_POSITION;

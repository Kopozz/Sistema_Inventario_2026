# Pruebas de Concurrencia

Este documento describe escenarios manuales y un ejemplo de uso del script CLI de stress para validar el locking pesimista.

## 1. Preparación
1. Asegúrate de tener un repuesto con `stock_actual = 10` (ej: ID = 1).
2. Ten dos navegadores / ventanas (A y B) autenticados con usuarios válidos.

## 2. Escenarios Manuales
### 2.1 Ventas simultáneas
- A prepara venta con repuesto 1 cantidad 8 (no envía todavía).
- B prepara venta con repuesto 1 cantidad 6.
- B envía primero → debería completar (stock pasa a 4).
- A envía después → error de "Concurrencia" o "Stock insuficiente".

### 2.2 Salida vs Venta
- A prepara salida manual de 7.
- B prepara venta de 6.
- Ejecutar B primero (stock a 4) → A falla.

### 2.3 Ajuste y Venta
- Ajuste a 2 preparado en A.
- Venta de 3 preparada en B.
- Procesar venta primero (stock 7) → luego ajuste deja stock 2.

### 2.4 Anulación y Nueva Venta
- Realizar venta de 5 (stock de 10 → 5).
- Intentar venta de 6 → falla.
- Anular primera venta (stock vuelve a 10).
- Reintentar venta de 6 → pasa.

### 2.5 Ajuste sin cambio
- Ajuste a valor igual al stock actual → genera movimiento sin modificar stock.

## 3. Script CLI de Stress (simple)
Archivo: `scripts/stress_venta.php` (creado al ejecutar esta guía si aún no existe)

Invocación repetida (simular conflicto secuencial):
```
php scripts/stress_venta.php --repuesto=1 --intentos=5 --cantidad=3
```
El script intenta crear una venta de `cantidad` para el repuesto indicado varias veces y reporta éxito o fallo.

## 4. Interpretación de Resultados
- Mensajes con prefijo `Concurrencia:` indican que el bloqueo protegió la integridad.
- Fallos por stock insuficiente tras la primera operación son esperados.

## 5. Próximas Mejores Prácticas
- Añadir logging estructurado de conflictos.
- Incorporar test automatizado (PHPUnit) con simulación de transacciones secuenciales.
- Considerar estrategia optimista si el número de colisiones comienza a ser alto.

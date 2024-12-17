<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use verifactuPHP\Models\Desglose;

class DesgloseTest extends TestCase
{
    public function testDesgloseInitialization(): void
    {
        try {
            // Datos de entrada simulados
            $data = [
                'impuesto' => 1,
                'claveRegimen' => 1,
                'calificacionOperacion' => 1,
                'operacionExenta' => 0,
                'tipoImpositivo' => 21.0,
                'baseImponibleOImporteNoSujeto' => 100.0,
                'baseImponibleACoste' => 100.0,
                'cuotaRepercutida' => 21.0,
                'tipoRecargoEquivalencia' => 0.0,
                'cuotaRecargoEquivalencia' => 0.0
            ];

            $desglose = new Desglose($data);

            // Verificaciones de los valores iniciales
            $this->assertSame(1, $desglose->getImpuesto(), "Error: El impuesto no coincide");
            $this->assertSame(1, $desglose->getClaveRegimen(), "Error: La clave de régimen no coincide");
            $this->assertSame(1, $desglose->getCalificacionOperacion(), "Error: La calificación de operación no coincide");
            $this->assertSame(0, $desglose->getOperacionExenta(), "Error: La operación exenta no coincide");
            $this->assertSame(21.0, $desglose->getTipoImpositivo(), "Error: El tipo impositivo no coincide");
            $this->assertSame(100.0, $desglose->getBaseImponibleOImporteNoSujeto(), "Error: La base imponible no coincide");
            $this->assertSame(100.0, $desglose->getBaseImponibleACoste(), "Error: La base imponible a coste no coincide");
            $this->assertSame(21.0, $desglose->getCuotaRepercutida(), "Error: La cuota repercutida no coincide");
            $this->assertSame(0.0, $desglose->getTipoRecargoEquivalencia(), "Error: El tipo de recargo de equivalencia no coincide");
            $this->assertSame(0.0, $desglose->getCuotaRecargoEquivalencia(), "Error: La cuota de recargo de equivalencia no coincide");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function testSettersAndGetters(): void
    {
        try {
            // Datos de entrada para la prueba de setters y getters
            $data = [
                'impuesto' => 1,
                'claveRegimen' => 1,
                'calificacionOperacion' => 1,
                'operacionExenta' => 0,
                'tipoImpositivo' => 21.0,
                'baseImponibleOImporteNoSujeto' => 1000.0,
                'baseImponibleACoste' => 1000.0,
                'cuotaRepercutida' => 210.0,
                'tipoRecargoEquivalencia' => 0.0,
                'cuotaRecargoEquivalencia' => 0.0
            ];

            $desglose = new Desglose($data);

            // Actualización de valores mediante setters
            $desglose->setImpuesto(2);
            $desglose->setClaveRegimen(2);
            $desglose->setCalificacionOperacion(2);
            $desglose->setOperacionExenta(1);
            $desglose->setTipoImpositivo(10.0);
            $desglose->setBaseImponibleOImporteNoSujeto(2000.0);
            $desglose->setBaseImponibleACoste(2000.0);
            $desglose->setCuotaRepercutida(200.0);
            $desglose->setTipoRecargoEquivalencia(5.2);
            $desglose->setCuotaRecargoEquivalencia(104.0);

            // Verificaciones de los valores actualizados
            $this->assertSame(2, $desglose->getImpuesto(), "Error: El impuesto no se actualizó correctamente");
            $this->assertSame(2, $desglose->getClaveRegimen(), "Error: La clave de régimen no se actualizó correctamente");
            $this->assertSame(2, $desglose->getCalificacionOperacion(), "Error: La calificación de operación no se actualizó correctamente");
            $this->assertSame(1, $desglose->getOperacionExenta(), "Error: La operación exenta no se actualizó correctamente");
            $this->assertSame(10.0, $desglose->getTipoImpositivo(), "Error: El tipo impositivo no se actualizó correctamente");
            $this->assertSame(2000.0, $desglose->getBaseImponibleOImporteNoSujeto(), "Error: La base imponible no se actualizó correctamente");
            $this->assertSame(2000.0, $desglose->getBaseImponibleACoste(), "Error: La base imponible a coste no se actualizó correctamente");
            $this->assertSame(200.0, $desglose->getCuotaRepercutida(), "Error: La cuota repercutida no se actualizó correctamente");
            $this->assertSame(5.2, $desglose->getTipoRecargoEquivalencia(), "Error: El tipo de recargo de equivalencia no se actualizó correctamente");
            $this->assertSame(104.0, $desglose->getCuotaRecargoEquivalencia(), "Error: La cuota de recargo de equivalencia no se actualizó correctamente");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function testGetArrayData(): void
    {
        try {
            // Datos de entrada para la prueba de obtención de datos en array
            $data = [
                'impuesto' => 1,
                'claveRegimen' => 1,
                'calificacionOperacion' => 1,
                'operacionExenta' => 1,
                'tipoImpositivo' => 21.0,
                'baseImponibleOImporteNoSujeto' => 100.0,
                'baseImponibleACoste' => 100.0,
                'cuotaRepercutida' => 21.0,
                'tipoRecargoEquivalencia' => 1.0,
                'cuotaRecargoEquivalencia' => 1.0
            ];
            $desglose = new Desglose($data);

            // Obtención de datos en formato array
            $arrayData = $desglose->getArrayData();

            // Verificaciones de los datos en el array
            $this->assertSame(1, $arrayData['Impuesto'], "Error: Impuesto incorrecto en el array");
            $this->assertSame(1, $arrayData['ClaveRegimen'], "Error: Clave de régimen incorrecta en el array");
            $this->assertSame(1, $arrayData['CalificacionOperacion'], "Error: Calificación de operación incorrecta en el array");
            $this->assertSame(1, $arrayData['OperacionExenta'], "Error: Operación exenta incorrecta en el array");
            $this->assertSame(21.0, $arrayData['TipoImpositivo'], "Error: Tipo impositivo incorrecto en el array");
            $this->assertSame(100.0, $arrayData['BaseImponibleOImporteNoSujeto'], "Error: Base imponible incorrecta en el array");
            $this->assertSame(100.0, $arrayData['BaseImponibleACoste'], "Error: Base imponible a coste incorrecta en el array");
            $this->assertSame(21.0, $arrayData['CuotaRepercutida'], "Error: Cuota repercutida incorrecta en el array");
            $this->assertSame(1.0, $arrayData['TipoRecargoEquivalencia'], "Error: Tipo de recargo de equivalencia incorrecto en el array");
            $this->assertSame(1.0, $arrayData['CuotaRecargoEquivalencia'], "Error: Cuota de recargo de equivalencia incorrecta en el array");
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function testEmpty(): void
    {
        try {
            // Creación de un desglose vacío
            $desglose = Desglose::empty();

            // Verificaciones de que los valores están vacíos
            $this->assertSame(0, $desglose->getImpuesto(), "Error: El impuesto no está vacío");
            $this->assertSame(0, $desglose->getClaveRegimen(), "Error: La clave de régimen no está vacía");
            $this->assertSame(0, $desglose->getCalificacionOperacion(), "Error: La calificación de operación no está vacía");
            $this->assertSame(0, $desglose->getOperacionExenta(), "Error: La operación exenta no está vacía");
            $this->assertSame(0.0, $desglose->getTipoImpositivo(), "Error: El tipo impositivo no está vacío");
            $this->assertSame(0.0, $desglose->getBaseImponibleOImporteNoSujeto(), "Error: La base imponible no está vacía");
            $this->assertSame(0.0, $desglose->getBaseImponibleACoste(), "Error: La base imponible a coste no está vacía");
            $this->assertSame(0.0, $desglose->getCuotaRepercutida(), "Error: La cuota repercutida no está vacía");
            $this->assertSame(0.0, $desglose->getTipoRecargoEquivalencia(), "Error: El tipo de recargo de equivalencia no está vacío");
            $this->assertSame(0.0, $desglose->getCuotaRecargoEquivalencia(), "Error: La cuota de recargo de equivalencia no está vacía");

            // Verificación de que el array de datos está vacío
            $arrayData = $desglose->getArrayData();
            $this->assertEmpty($arrayData, "Error: El array de datos no está vacío");
        } catch (Exception $e) {
            throw $e;
        }
    }
}
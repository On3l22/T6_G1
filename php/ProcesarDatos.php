<?php
class ProcesarDatos
{
    // Datos del paciente y la cita
    public $edad;
    public $genero;
    public $primera_cita;
    public $tipo_servicio;
    public $fecha;

    // Subtotal de exámenes, total bruto y descuentos
    private $subtotal_examenes;
    public $totalbrt;
    public $descjubilado;
    public $descuento_octubre;

    /**
     * Constructor: inicializa los datos, calcula subtotal de exámenes,
     * total bruto y descuentos.
     */
    public function __construct($edad, $genero, $primera_cita, $tipo_servicio, $examenes_seleccionados, $fecha)
    {
        $this->edad = $edad;
        $this->genero = $genero; 
        $this->primera_cita = $primera_cita;
        $this->tipo_servicio = $tipo_servicio;
        $this->fecha = $fecha;

        // Sumar precios de todos los exámenes seleccionados
        $this->subtotal_examenes = 0;
        foreach ($examenes_seleccionados as $key => $value) {
            $this->subtotal_examenes += (float) $value;
        }

        // Calcular el total bruto, descuento de octubre y de jubilado
        $this->totalbrt = $this->CalcularTotalBruto();
        $this->descuento_octubre = $this->CalcularDescuentoOctubre();
        $this->descjubilado = $this->CalcularDescuentoJubilado();
    }

    /**
     * Calcula el total bruto sumando exámenes y el costo de la cita médica.
     */
    public function CalcularTotalBruto()
    {
        $total = $this->subtotal_examenes;

        if ($this->tipo_servicio === "General") {
            $total += ($this->primera_cita === "Sí") ? 20 : 15;
        } elseif ($this->tipo_servicio === "Especializado") {
            $total += ($this->primera_cita === "Sí") ? 40 : 30;
        }

        return $total;
    }

    /**
     * Calcula el descuento de octubre (10% sobre el total de exámenes).
     * Solo se aplica si el mes es octubre.
     */
    public function CalcularDescuentoOctubre()
    {
        $fecha_obj = new DateTime($this->fecha);
        $mes = $fecha_obj->format('m');
        
        if ($mes === "10" && $this->subtotal_examenes > 0) {
            return $this->subtotal_examenes * 0.10;
        }
        
        return 0;
    }

    /**
     * Calcula el descuento por jubilado (10% sobre el total bruto
     * menos el descuento de octubre).
     */
    public function CalcularDescuentoJubilado()
    {
        if (($this->genero === "masculino" && $this->edad >= 60) ||
            ($this->genero === "femenino" && $this->edad >= 57)) {
            
            // Aplicamos el descuento sobre el total bruto ajustado por octubre
            $subtotalConOctubre = $this->totalbrt - $this->descuento_octubre;
            return $subtotalConOctubre * 0.10;
        }

        return 0;
    }

    /**
     * Devuelve el precio final, aplicando ambos descuentos.
     */
    public function CalcularPrecio()
    {
        return $this->totalbrt - $this->descuento_octubre - $this->descjubilado;
    }
}
?>

<?php
function insertarNuevoPaciente($datos)
{

    include(__DIR__ . '/../conexion/configurar.php');

    try {
        $sql = "INSERT INTO pacientes 
            (cedula, nombre, apellido, edad, sexo, provincia, distrito, ciudad, direccion, telefono_movil, telefono_casa)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssisssssss",
            $datos['cedula'],
            $datos['nombre'],
            $datos['apellido'],
            $datos['edad'],
            $datos['sexo'],
            $datos['provincia'],
            $datos['distrito'],
            $datos['ciudad'],
            $datos['calle'],
            $datos['celular'],
            $datos['telefono']
        );
        $stmt->execute();
        $stmt->close();
    } catch (Exception $e) {
        throw new Exception("Error al insertar paciente: " . $e->getMessage());
    } finally {
        if ($conn) {
            $conn->close();
        }
    }
}

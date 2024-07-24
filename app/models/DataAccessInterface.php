<?php
namespace models;

interface DataAccessInterface{
    public function obtenerTodos();
    public function obtenerPorId($id);
    public function created($datos);
    public function actualizar($datos);
    public function eliminar($datos);
}
?>

<?php
namespace helpers;

class Helper{
    // Método para verificar Encriptar contraseña
    public static function EncriptarPws($pws){
        return  hash('sha256', $pws);
    }
    public static function verificarContraseña($password, $hashedPassword) {
        $hashedInputPassword = self::EncriptarPws($password);
        return hash_equals($hashedInputPassword, $hashedPassword);
    }

    //Método para eliminar contraseña del array
    public static function excludePassword($data) {
        foreach ($data as &$row) {
            unset($row['pws']);
        }
        return $data;
    }
}

?>
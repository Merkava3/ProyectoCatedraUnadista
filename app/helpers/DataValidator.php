<?php

class DataValidator {
      // Validar apellido, nombre, genero como string alfabéticos
      public static function validateString($string) {
        return is_string($string) && preg_match("/^[a-zA-Z]+(\s[a-zA-Z]+)*$/", $string);
    }

    // Validar identificacion como solo números y String
    public static function validateNumericString($dato) {
        // Verificar si el string contiene solo números
    if (!preg_match('/^[0-9]+$/', $dato)) {
        return false;
        }    
    // Verificar si el string contiene caracteres especiales
    if (preg_match('/[^0-9]/', $dato)) {
        return false;
    }
    return true;
    }

    // Validar solo números
    public static function validateOnlyNumbers($number) {
        return is_numeric($number);
    }

    public static function validateNumbedigit10($numero) {
        // Verifica que el número tenga exactamente 10 dígitos y que el tercer dígito sea 3
       
    if (preg_match('/^573\d{9}$/', $numero)) {            
        return true; // El número es válido
    } else {
        
        return false; // El número no es válido
    }
    
    }

    // Validar imagen (tipo y tamaño)    
    public static function validateImage($file) {
        $maxSize = 10 * 1024 * 1024; // 10 MB
        $allowedTypes = ['image/jpeg', 'image/png'];
        
        if ($file['size'] > $maxSize) {
            return 'El archivo supera el tamaño máximo permitido de 10MB';
        }
        
        if (!in_array($file['type'], $allowedTypes)) {
            return 'El archivo debe ser de tipo JPEG o PNG';
        }
        
        return true;
    }


    // Validar contraseña con los requisitos específicos
    //self::verificarSesion();
        //https://code.tutsplus.com/es/how-to-build-a-simple-rest-api-in-php--cms-37000t
    public static function validatePassword($password) {
        // 8 caracteres, al menos 1 mayúscula, 1 minúscula, 1 carácter especial, 1 número
        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/';
        return is_string($password) && preg_match($pattern, $password);
    }

    // Validar correo electrónico con dominios específicos
    public static function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $allowedDomains = ['hotmail.com', 'gmail.com', 'outlook.com'];
        $domain = substr(strrchr($email, "@"), 1);
        return in_array($domain, $allowedDomains);
    }

    // Validar todos los datos
    public static function validateData($data) {
        $errors = [];
        if (isset($data['nombre']) && !self::validateString($data['nombre'])) {
            $errors['nombre'] = 'Nombre no válido';
        }
        if (isset($data['apellido']) && !self::validateString($data['apellido'])) {
            $errors['apellido'] = 'Apellido no válido';
        }
        if (isset($data['genero']) && !self::validateString($data['genero'])) {
            $errors['genero'] = 'Género no válido';
        }        
        if (isset($data['indentificacion']) && !self::validateNumericString($data['indentificacion'])) {
            $errors['indentificacion'] = 'Identificación no válida';
        }

        if (isset($data['correo']) && !self::validateEmail($data['correo'])) {
            $errors['correo'] = 'Correo no válido';
        }     
        
        if (isset($data['pws']) && !self::validatePassword($data['pws'])) {
            $errors['pws'] = 'la password debe ser maximo 8 caracteres una Mayuscula un Numero y caracter especial';
        }

    
        /* 
         if (isset($data['imagen'])) {
            $imageValidationResult = self::validateImage($data['imagen']);
            if ($imageValidationResult !== true) {
                $errors['imagen'] = $imageValidationResult;
            }
        }       
        */
        

        return $errors;
    }

    public static function ValidateSms($data){
        $errors = [];
        if(isset($data['number']) && !self::validateNumbedigit10($data['number'])){
            $errors['number'] = 'numero invalido';
        }
        if(isset($data['send']) && !self::validateString($data['send'])){
            $errors['send'] = 'Solo letras';

        }
        return $errors;
        
    }

}

?>

<?php
// Incluir configuraciones
require_once __DIR__ . '/../config/config.php'; // Asegúrate de que esta ruta sea correcta

function base_url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

class BaseController {
    public function loadModel($model) {
        require_once __DIR__ . '/../models/' . $model . '.php';
        return new $model();
    }

    public function loadView($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}
?>

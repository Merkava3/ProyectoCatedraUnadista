<?php
namespace models;

require_once 'QueryInnerjoin.php';
require_once 'DatabaseHandler.php';
require_once 'QueryBuilder.php';

use helpers\Helper;

class DynamicQuery extends DatabaseHandler {
    private $table;

    public function __construct($table) {
        parent::__construct();
        $this->table = $table;
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM {$this->table}";
        $result = mysqli_query($this->conexion, $query);
        
        if ($result) {
            $data = $this->fetchResults($result);
            $data = Helper::excludePassword($data);
            $response = ['success' => true, 'data' => $data];
        } else {
            $response = ['success' => false, 'error' => mysqli_error($this->conexion)];
        }
        $this->cerrarConexion();
        return $response;
    }

    public function get(){
        $query = "SELECT * FROM {$this->table}";
        $result = mysqli_query($this->conexion, $query);
        if ($result) {
            $data = $this->fetchResults($result);           
            $response = ['success' => true, 'data' => $data];
        } else {
            $response = ['success' => false, 'error' => mysqli_error($this->conexion)];
        }
        $this->cerrarConexion();
        return $response;


    }

    public function obtenerPorId($datos) {
        $column = implode(", ", array_keys($datos));
        $params = array_values($datos);
        $types = 's';
        $query = "SELECT * FROM {$this->table} WHERE {$column} = ?";
    
        list($success, $stmtOrError) = $this->prepareAndExecute($query, $types, $params);
    
        if ($success) {
            $result = $stmtOrError->get_result();
            $data = mysqli_fetch_assoc($result);
            if ($data) {
                $data =Helper::excludePassword([$data]);
                $result = ['success' => true, 'data' => $data[0]];
            } else {
                $result = ['success' => false, 'message' => 'Este registro no existe'];
            }
        } else {
            $result = ['success' => false, 'message' => 'Error en la consulta: ' . $stmtOrError];
        }
        $this->cerrarConexion();
        return $result;
    }
    

    public function created($datos) {
        // Encriptar la contraseña antes de guardar
        if (isset($datos['pws'])) {
            $datos['pws'] = Helper::EncriptarPws($datos['pws']);
        }
        return $this->insert($datos);
    }

    public function actualizar($datos) {
        // Encontrar la clave del identificador de forma dinámica
        $sql = "";           
        list($sql, $values) = QueryBuilder::QueryUpdate($this->table, $datos);        
        // Ejecutar la consulta
        $tipos = str_repeat('s', count($values) - 1) . 'i';    
        list($success, $stmtOrError) = $this->prepareAndExecute($sql, $tipos, $values);    
        if ($success) {
            return ['success' => true, 'message' => 'Actualización exitosa'];
        } else {
            return ['success' => false, 'message' => 'Error al actualizar: ' . $stmtOrError];
        }
        $this->cerrarConexion();
    }
    
    

    public function eliminar($datos) {
        $sql = "";        
        $types = 's'; // Cambiar 's' por el tipo correcto según el tipo de dato de la columna
        $params = array_values($datos); 
        $sql = QueryBuilder::QueryDelete($this->table, $datos);   
        list($success, $stmtOrError) = $this->prepareAndExecute($sql, $types, $params);    
        if ($success) {
            return ['success' => true, 'message' => 'Eliminación exitosa'];
        } else {
            return ['success' => false, 'message' => 'Error al eliminar: ' . $stmtOrError];
        }
        $this->cerrarConexion();
    }

    public function login($data) {      
        list($sql, $params) = QueryBuilder::GetLogin($this->table, $data);           
        list($success, $stmtOrError) = $this->prepareAndExecute($sql, 's', [$params[0]]);
        
        if ($success) {
            // Obtener el resultado de la consulta
            $result = $stmtOrError->get_result();
            $user = mysqli_fetch_assoc($result);
            
            if ($user) {
                // Verificar la contraseña
                if (Helper::verificarContraseña($data['pws'], $user['pws'])) {
                    // Excluir la contraseña de los datos del usuario
                    $user = Helper::excludePassword([$user]);
    
                    // Iniciar la sesión y almacenar los datos del usuario
                    session_start();
                    $_SESSION['user'] = $user[0];
    
                    // Insertar registro en la tabla sesion
                    $insertQuery = "INSERT INTO sesion (fecha_sesion, estado, sesion_usuario) VALUES (NOW(), TRUE, ?)";
                    $insertParams = [$user[0]['id_usuario']];
                    list($insertSuccess, $insertStmtOrError) = $this->prepareAndExecute($insertQuery, 'i', $insertParams);
    
                    if (!$insertSuccess) {
                        return ['success' => false, 'message' => 'Error al registrar la sesión: ' . $insertStmtOrError];
                    }
    
                    // Determinar la página de redirección según el tipo de usuario
                    if ($user[0]['tipo_usuario'] === 'Tutor') {
                        return ['success' => true, 'data' => $user[0], 'pages' => 'dashboard_tutor.html'];
                    } else {
                        return ['success' => true, 'data' => $user[0], 'pages' => 'dashboard_estudiante.html'];
                    }
                } else {
                    return ['success' => false, 'message' => 'Credenciales incorrectas'];
                }
            } else {
                return ['success' => false, 'message' => 'Usuario no encontrado'];
            }
        } else {
            return ['success' => false, 'message' => 'Error en la consulta: ' . $stmtOrError];
        }
    
        // Cerrar la conexión
        $this->cerrarConexion();
    }
    

    

    public function actualizarEstadoSesion($userId) {
        $query = "UPDATE sesion SET estado = FALSE WHERE sesion_usuario = ? AND estado = TRUE";
        $params = [$userId];
        return $this->prepareAndExecute($query, 'i', $params);
    }  
    

    private function insert($datos) {       
        $tipos = str_repeat('s', count($datos));        
        $sql = QueryBuilder::QueryInsert($this->table, $datos);
        try {
            list($success, $stmtOrError) = $this->prepareAndExecute($sql, $tipos, array_values($datos));           
            if ($success) {
                return ['success' => true, 'id' => mysqli_stmt_insert_id($stmtOrError)];
            } else {
                if (strpos($stmtOrError, "Duplicate entry") !== false) {
                    return ['success' => false, 'message' => 'Este registro ya se encuentra registrado', 'data' => null];
                } else {
                    return ['success' => false, 'message' => $stmtOrError];
                }
            }
        } catch (\mysqli_sql_exception $e) {     
           
        return ['success' => false, 'message' => 'Error al insertar: ' . $e->getMessage()];
            
        }
        $this->cerrarConexion();
    }

    private function executeQuery($query, $types = '', $params = []) {
        list($success, $stmtOrError) = $this->prepareAndExecute($query, $types, $params);
        if ($success) {
            if (strpos(trim(strtoupper($query)), 'SELECT') === 0) {
                $result = $stmtOrError->get_result();
                return ['success' => true, 'data' => $this->fetchResults($result)];
            } else {
                return ['success' => true, 'message' => 'Consulta ejecutada correctamente'];
            }
        } else {
            return ['success' => false, 'message' => 'Error en la consulta: ' . $stmtOrError];
        }
        $this->cerrarConexion();
    }

    public function executeQuerysAll ($consulta){       
    return $this->executeQuery($consulta); 
    }

    

    
    private function fetchResults($result) {
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
}
?>

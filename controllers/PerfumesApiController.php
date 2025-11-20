<?php
require_once __DIR__ . '/../models/PerfumesModel.php';

class PerfumesApiController {
    private PerfumeModel $model;

    public function __construct() {
        $this->model = new PerfumeModel();
    }

    // Trae todos los perfumes (opcionalmente filtrados)
    public function getAll($request, $response) { 
         
        $filter = isset($request->query->filter) ? $request->query->filter : null;
        $order  = isset($request->query->order) ? $request->query->order : null;

        $allowedFields = ['sexo', 'duracion', 'precio', 'codigo', 'id_laboratorio'];
        $filters = [];
        $orders = [];
var_dump("controller llega 1");
        
        if ($filter) {
            $conditions = preg_split('/[;,]+/', $filter);

            foreach ($conditions as $condition) {
                if (!str_contains($condition, '=')) {
                    return $response->json(['error' => 'Formato de filtro inválido. Use campo=valor'], 400);
                }

                [$field, $value] = explode('=', $condition, 2);
                $field = trim($field);
                $value = trim($value);

                if (!in_array($field, $allowedFields)) {
                    return $response->json([
                        'error' => "El campo '$field' no se puede usar como filtro",
                        'permitidos' => $allowedFields
                    ], 400);
                }

                if ($value === '') {
                    return $response->json(['error' => 'El valor del filtro no puede estar vacío'], 400);
                }

                $filters[$field] = $value;
            }
        }
var_dump("controller llega 2");
        
    if ($order) {
        $orderParts = preg_split('/[;,]+/', $order);

        foreach ($orderParts as $part) {
            if (!str_contains($part, ':')) {
                return $response->json(['error' => 'Formato de orden inválido. Use campo:asc|desc'], 400);
            }

            [$field, $dir] = explode(':', $part, 2);

            $field = trim($field);
            $dir   = strtolower(trim($dir));

            
            if (!in_array($field, $allowedFields)) {
                return $response->json([
                    'error' => "El campo '$field' no se puede usar para ordenar",
                    'permitidos' => $allowedFields
                ], 400);
            }

            
            if ($dir !== 'asc' && $dir !== 'desc') {
                return $response->json([
                    'error' => "Dirección de orden inválida en '$part'. Use asc o desc."
                ], 400);
            }

            $orders[$field] = $dir;
        }
         
       
    }
     $perfumes = $this->model->getAll($filters, $orders);
        return $response->json($perfumes, 200);
}


    // Trae un perfume por su ID
    public function get($request, $response) {
        $id = $request->params->id ?? null;

        if (!$id) {
            return $response->json(['error' => 'Falta el parámetro ID'], 400);
        }

        $perfume = $this->model->getById($id);

        if ($perfume) {
            $response->json($perfume, 200);
        } else {
            $response->json(['error' => 'Perfume no encontrado'], 404);
        }
    }

    // Crea un nuevo perfume
    public function create($request, $response) {
        $data = $request->body;

        if (!is_numeric($data->id_laboratorio) || !$this->model->existeLaboratorio($data->id_laboratorio)){
            return $response->json(['error' => 'Se espera un laboraorio existente'], 400);
        }
        if (!is_numeric($data->sexo) || $data->sexo < 0 || $data->sexo > 2){
            return $response->json(['error' => 'Se espera un numero entre 0 y 2 en sexo'], 400);
        }
        if (!is_numeric($data->duracion) || $data->duracion < 1){
            return $response->json(['error' => 'Se espera un numero mayor a 1 en la duracion '], 400);
        }
        if (!is_numeric($data->precio) || $data->precio < 0){
            return $response->json(['error' => 'Se espera un numero mayor a 0 en el precio '], 400);
        }

        try {
            $id = $this->model->insert($data);
            return $response->json([
                'id' => $id,
                'message' => 'Perfume creado correctamente'
            ], 201);
        } catch (Exception $e) {
            return $response->json([
                'error' => 'Error al crear el perfume: ' . $e->getMessage()
            ], 500);
        }
    }


    // Actualiza un perfume existente
    public function update($request, $response) {
        $id = $request->params->id ?? null;
        $data = $request->body;

        if (!$id) {
            return $response->json(['error' => 'Falta el parámetro ID'], 400);
        }

        if (empty((array)$data)) {
            return $response->json(['error' => 'El cuerpo de la petición está vacío'], 400);
        }
        if (!is_numeric($data->id_laboratorio) || !$this->model->existeLaboratorio($data->id_laboratorio)){
            return $response->json(['error' => 'Se espera un laboraorio existente'], 400);
        }
        if (!is_numeric($data->sexo) || $data->sexo < 0 || $data->sexo > 2){
            return $response->json(['error' => 'Se espera un numero entre 0 y 2 en sexo'], 400);
        }
        if (!is_numeric($data->duracion) || $data->duracion < 1){
            return $response->json(['error' => 'Se espera un numero mayor a 1 en la duracion '], 400);
        }
        if (!is_numeric($data->precio) || $data->precio < 0){
            return $response->json(['error' => 'Se espera un numero mayor a 0 en el precio '], 400);
        }
      
        try {
            $rows = $this->model->update($id, $data);
            if ($rows > 0) {
                $response->json(['message' => 'Perfume actualizado correctamente'], 200);
            } else {
                $response->json(['error' => 'Perfume no encontrado o sin cambios'], 404);
            }
        } catch (Exception $e) {
            $response->json(['error' => 'Error al actualizar: ' . $e->getMessage()], 500);
        }
    }

    // Elimina un perfume
    public function delete($request, $response) {
        $id = $request->params->id ?? null;

        if (!$id) {
            return $response->json(['error' => 'Falta el parámetro ID'], 400);
        }

        try {
            $rows = $this->model->delete($id);
            if ($rows > 0) {
                $response->json(['message' => 'Perfume eliminado correctamente'], 200);
            } else {
                $response->json(['error' => 'Perfume no encontrado'], 404);
            }
        } catch (Exception $e) {
            $response->json(['error' => 'Error al eliminar: ' . $e->getMessage()], 500);
        }
    }

    // Ruta por defecto si no se encuentra la solicitada
    public function notFound($request, $response) {
        $response->json(['error' => 'Ruta no encontrada'], 404);
    }
}

<?php
require_once __DIR__ . '/Model.php';

class PerfumeModel extends Model {

    // Trae todos los perfumes con su laboratorio
    public function getAll($filters = [], $order = []) {
    $sql = "SELECT p.*, l.nombre AS laboratorio_nombre
            FROM perfumes p
            LEFT JOIN laboratorios l ON l.id = p.id_laboratorio";
    $params = [];

    if (!empty($filters)) {
        $where = [];
        foreach ($filters as $field => $value) {
            $where[] = "p.$field = ?";
            $params[] = $value;
        }
        $sql .= " WHERE " . implode(' AND ', $where);
    }

    if (!empty($order)) {
        $orderParts = [];
        foreach ($order as $col => $dir) {
            $orderParts[] = "$col " . strtoupper($dir);
        }
        $sql .= " ORDER BY " . implode(', ', $orderParts);
    }

    $query = $this->db->prepare($sql);
    $query->execute($params);
    return $query->fetchAll();
}
    
    // Trae un perfume por su ID
    public function getById($id) {
        $sql = "SELECT p.*, l.nombre AS laboratorio_nombre
                FROM perfumes p
                LEFT JOIN laboratorios l ON l.id = p.id_laboratorio
                WHERE p.id = ?";
        $query = $this->db->prepare($sql);
        $query->execute([$id]);
        return $query->fetch();
    }

    // Agrega un nuevo perfume a la base de datos
    public function insert($perfume) {
        $sql = "INSERT INTO perfumes (id_laboratorio, precio, codigo, duracion, aroma, sexo)
                VALUES (?, ?, ?, ?, ?, ?)";
        $query = $this->db->prepare($sql);
        $query->execute([
            $perfume->id_laboratorio,
            $perfume->precio,
            $perfume->codigo ?? null,
            $perfume->duracion,
            $perfume->aroma ?? null,
            $perfume->sexo
        ]);
        return $this->db->lastInsertId(); 
    }

    // Modifica un perfume existente
    public function update($id, $perfume) {
        $sql = "UPDATE perfumes 
                SET id_laboratorio = ?, precio = ?, codigo = ?, duracion = ?, aroma = ?, sexo = ?
                WHERE id = ?";
        $query = $this->db->prepare($sql);
        $query->execute([
            $perfume->id_laboratorio,
            $perfume->precio,
            $perfume->codigo ?? null,
            $perfume->duracion,
            $perfume->aroma ?? null,
            $perfume->sexo,
            $id
        ]);
        return $query->rowCount(); 
    }
    // chequea si existe un laboratorio
    public function existeLaboratorio($id) {
        $sql = "SELECT id FROM laboratorios WHERE id = ?";
        $query = $this->db->prepare($sql);
        $query->execute([$id]);

        return $query->fetch() !== false; 
    }

    // Elimina un perfume por ID
    public function delete($id) {
        $sql = "DELETE FROM perfumes WHERE id = ?";
        $query = $this->db->prepare($sql);
        $query->execute([$id]);
        return $query->rowCount(); 
    }
    
}

# Todo REST PHP – Perfumes (TPE Parte 3)

API REST sencilla para gestionar perfumes: creación, consulta, actualización y eliminación.

---

## Endpoints

### **GET `/perfumes`**
Obtiene la lista completa de perfumes.

Parámetros opcionales:
- **`filter`** → permite filtrar por columna.  
  **Formato:** `filter=columna=valor;columna2=valor2`
- **`order`** → permite ordenar por una columna.  
  **Formato:** `order=columna=ASC|DESC`

---

### **GET `/perfumes/{id}`**
Devuelve los datos del perfume con el ID indicado.

---

### **DELETE `/perfumes/{id}`**
Elimina el perfume correspondiente al ID.

---

### **POST `/perfumes`**
Crea un nuevo perfume.  
El cuerpo debe enviarse en **JSON** siguiendo el DTO de notas.

---

### **PUT `/perfumes/{id}`**
Actualiza el perfume con el ID indicado.  
El cuerpo también debe enviarse en **JSON** usando el mismo DTO.

---

## 📝 Ejemplo de Payload (POST / PUT)

```json
{
  "id_laboratorio": 1,
  "precio": 123.45,
  "codigo": "ABC123",
  "duracion": 60,
  "aroma": "Floral",
  "sexo": 0
}

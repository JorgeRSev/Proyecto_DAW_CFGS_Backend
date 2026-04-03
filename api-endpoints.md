# API Endpoints Documentation

Este documento describe los endpoints disponibles en el backend del proyecto "pelupatas".

# Base URL: http://localhost/pelupatas/backend/src/api

## Endpoints

### 1. `/api/usuarios`
- **Método:** GET
- **Descripción:** Obtiene la lista de todos los usuarios registrados.
- **Respuesta:** Array de objetos usuario.

### 2. `/api/usuarios/:id`
- **Método:** GET
- **Descripción:** Obtiene la información de un usuario específico por su ID.
- **Parámetros:** 
    - `id` (string): Identificador único del usuario.
- **Respuesta:** Objeto usuario.

### 3. `/api/usuarios`
- **Método:** POST
- **Descripción:** Crea un nuevo usuario.
- **Body:** 
    - `name` (string): Nombre del usuario.
    - `email` (string): Correo electrónico.
    - Otros campos relevantes.
- **Respuesta:** Objeto usuario creado.

### 4. `/api/usuarios/:id`
- **Método:** PUT
- **Descripción:** Actualiza la información de un usuario existente.
- **Parámetros:** 
    - `id` (string): Identificador único del usuario.
- **Body:** Campos a actualizar.
- **Respuesta:** Objeto usuario actualizado.

### 5. `/api/usuarios/:id`
- **Método:** DELETE
- **Descripción:** Elimina un usuario por su ID.
- **Parámetros:** 
    - `id` (string): Identificador único del usuario.
- **Respuesta:** Mensaje de éxito o error.

### 6. `/api/mascotas`
- **Método:** GET
- **Descripción:** Obtiene la lista de todas las mascotas registradas.
- **Respuesta:** Array de objetos mascota.

### 7. `/api/mascotas/:id`
- **Método:** GET
- **Descripción:** Obtiene la información de una mascota específica por su ID.
- **Parámetros:** 
    - `id` (string): Identificador único de la mascota.
- **Respuesta:** 
    - {
  "id": 9,
  "nombre": "Thor",
  "raza": "border collie",
  "edad": 5,
  "observaciones": "Pastor, fiel",
  "id_dueno": 2,
  "activa": 1
}

### 8. `/api/mascotas`
- **Método:** POST
- **Descripción:** Crea una nueva mascota.
- **Body:** 
    - `name` (string): Nombre de la mascota.
    - `type` (string): Tipo de mascota.
    - Otros campos relevantes.
- **Respuesta:** Objeto mascota creada.

### 9. `/api/mascotas/:id`
- **Método:** PUT
- **Descripción:** Actualiza la información de una mascota existente.
- **Parámetros:** 
    - `id` (string): Identificador único de la mascota.
- **Body:** Campos a actualizar.
- **Respuesta:** Objeto mascota actualizado.

### 10. `/api/mascotas/:id`
- **Método:** DELETE
- **Descripción:** Elimina una mascota por su ID.
- **Parámetros:** 
    - `id` (string): Identificador único de la mascota.
- **Respuesta:** Mensaje de éxito o error.

### 11. `/api/citas`
- **Método:** GET
- **Descripción:** Obtiene la lista de todas las citas agendadas.
- **Respuesta:** Array de objetos cita.

### 12. `/api/citas/:id`
- **Método:** GET
- **Descripción:** Obtiene la información de una cita específica por su ID.
- **Parámetros:** 
    - `id` (string): Identificador único de la cita.
- **Respuesta:** Objeto cita.

### 13. `/api/citas`
- **Método:** POST
- **Descripción:** Crea una nueva cita.
- **Body:** 
    - `userId` (string): ID del usuario.
    - `petId` (string): ID de la mascota.
    - `date` (string): Fecha de la cita.
    - Otros campos relevantes.
- **Respuesta:** Objeto cita creada.

### 14. `/api/citas/:id`
- **Método:** PUT
- **Descripción:** Actualiza la información de una cita existente.
- **Parámetros:** 
    - `id` (string): Identificador único de la cita.
- **Body:** Campos a actualizar.
- **Respuesta:** Objeto cita actualizado.

### 15. `/api/citas/:id`
- **Método:** DELETE
- **Descripción:** Elimina una cita por su ID.
- **Parámetros:** 
    - `id` (string): Identificador único de la cita.
- **Respuesta:** Mensaje de éxito o error.

---

## Notas

- Todos los endpoints pueden requerir autenticación.
- Los parámetros y campos pueden variar según la implementación específica.
- Las respuestas incluyen mensajes de error en caso de fallos.

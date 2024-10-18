<?php

namespace App\Http\Controllers;

use App\Jobs\PublishAlumno;
use App\Models\Alumnos;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alumnos = Alumnos::all();
        return response()->json($alumnos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //si el post está programado, crea un job que se ejecute en la fecha:
        $validated = $request->validate([
            'alumno_nombre' => 'required|string|max:50',
            'alumno_apellido' => 'required|string|max:30',
            'alumno_dni' => 'required|integer|unique:alumnos,alumno_dni',
            'alumno_telefono' => 'required|string|max:15',
            'alumno_correo' => 'required|string|email|max:100|unique:alumnos,alumno_correo',
            'scheduled_at' => 'nullable|date',
        ]);

        $alumno = Alumnos::create($validated);
        if ($alumno->scheduled_at) {
            PublishAlumno::dispatch($alumno)->delay($alumno->scheduled_at);
        }
        return response()->json($alumno, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $alumno = Alumnos::find($id);
        if (!$alumno) {
            return response()->json(['message' => 'Alumno no encontrado'], 404);
        }
        return response()->json($alumno);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $alumno = Alumnos::find($id);
        if (!$alumno) {
            return response()->json(['message' => 'Alumno no encontrado'], 404);
        }

        $validated = $request->validate([
            'alumno_nombre' => 'sometimes|required|string|max:50',
            'alumno_apellido' => 'sometimes|required|string|max:30',
            'alumno_dni' => 'sometimes|required|integer|unique:alumnos,alumno_dni,' . $alumno->alumno_id,
            'alumno_telefono' => 'sometimes|required|string|max:15',
            'alumno_correo' => 'sometimes|required|string|email|max:100|unique:alumnos,alumno_correo,' . $alumno->alumno_id,
            'scheduled_at' => 'nullable|date',
        ]);

        $alumno->update($validated);

        return response()->json($alumno);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $alumno = Alumnos::find($id);
        if (!$alumno) {
            return response()->json(['message' => 'Alumno no encontrado'], 404);
        }

        $alumno->delete();
        return response()->json(['message' => 'Alumno eliminado correctamente']);
    }


    /* ***
    Resumen de los métodos:
        show($id): Muestra la información de un alumno específico. Si el alumno no se encuentra, devuelve un mensaje de error 404.

        update(Request $request, $id): Actualiza la información de un alumno existente. Utiliza la validación condicional (con sometimes) para que solo los campos proporcionados sean requeridos.

        destroy($id): Elimina un alumno específico. Si el alumno no se encuentra, devuelve un mensaje de error 404. Si se elimina correctamente, devuelve un mensaje de confirmación.
    ***
    */
}

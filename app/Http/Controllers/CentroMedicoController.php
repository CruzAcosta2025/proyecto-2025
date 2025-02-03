<?php

namespace App\Http\Controllers;

use App\Models\CentroMedico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CentroMedicoController extends Controller
{
    // Listar todos los centros médicos
    public function index()
    {
        $centros = CentroMedico::all();
        return view('ViewCentroMedico', compact('centros'));
    }

    // Mostrar un centro médico específico (AJAX)
    public function show($id)
    {
        $centros = CentroMedico::find($id);

        if ($centros) {
            return response()->json(['centros' => $centros]);
        } else {
            return response()->json(['centros' => null], 404); // Retorna 404 si no se encuentra
        }
    }


    // Crear un nuevo centro médico (formulario)
    public function create()
    {
        $centros = CentroMedico::all();
        return view('ViewCentroMedico', compact('centros'));
    }


    // Guardar un nuevo centro médico
    public function store(Request $request)
    {
        try {
            // Verificar que los datos recibidos sean correctos
            Log::debug('Datos recibidos:', $request->all());

            // Validación de datos
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'direccion' => 'required|string|max:255',
                'ruc' => 'required|string|max:11',
                'color_tema' => 'nullable|string|max:7',
                'estado' => 'required|in:activo,inactivo',
            ]);

            // Crear el centro médico
            $centros = CentroMedico::create([
                'nombre' => $validated['nombre'],
                'direccion' => $validated['direccion'],
                'ruc' => $validated['ruc'],
                'color_tema' => $validated['color_tema'] ?? '#ffffff', // Valor por defecto
                'estado' => $validated['estado'],
            ]);

            return response()->json(['success' => true, 'centro' => $centros], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error al crear el centro médico:', ['exception' => $e]);
            return response()->json(['success' => false, 'error' => 'Error del servidor. Inténtalo nuevamente.'], 500);
        }
    }

    // Editar un centro médico
    public function edit($id)
    {
        //$centros = CentroMedico::all();
        $centros = CentroMedico::findOrFail($id);
        return view('ViewCentroMedico', compact('centro'));
    }

    public function update(Request $request, $id)
{
    $centros = CentroMedico::find($id);

    if (!$centros) {
        return response()->json(['error' => 'Centro médico no encontrado'], 404);
    }

    // Validar los datos
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'direccion' => 'required|string|max:255',
        'ruc' => 'required|string|max:11|unique:centro_medicos,ruc,' . $id,
        'color_tema' => 'nullable|string|max:7',
        'estado' => 'required|in:activo,inactivo',
    ]);

    // Actualizar el centro médico
    $centros->update([
        'nombre' => $validated['nombre'],
        'direccion' => $validated['direccion'],
        'ruc' => $validated['ruc'],
        'color_tema' => $validated['color_tema'],
        'estado' => $validated['estado'],
    ]);

    return response()->json(['success' => true, 'centro' => $centros]);
}


    // Eliminar un centro médico
    public function destroy($id)
    {
        $centros = CentroMedico::find($id);

        if (!$centros) {
            return response()->json(['error' => 'Centro médico no encontrado'], 404);
        }

        try {
            $centros->delete();
            return response()->json(['success' => true, 'message' => 'Centro médico eliminado exitosamente']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar el centro médico:', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Error al eliminar el centro médico.'], 500);
        }
    }
}

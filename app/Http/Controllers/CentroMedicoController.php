<?php

namespace App\Http\Controllers;

use App\Models\KanbanBoard;
use App\Models\KanbanColumn;
use App\Models\KanbanTask;
use App\Models\Tarea;
use App\Models\Proyecto;
use App\Models\TareaUsuario;
use App\Models\User;
use Illuminate\Support\Facades\Log;


use Illuminate\Http\Request;

class CentroMedicoController extends Controller
{
    public function create()
    {
        return view('ViewAgregarCentro');
    }
    

}
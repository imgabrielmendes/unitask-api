<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Tarefa;

class TarefaController extends Controller
{
    public function index()
    {
        return response()->json(Tarefa::all());
    }
}


<?php

namespace App\Controllers;
use App\Models\ClienteModel;

class Home extends BaseController
{
    public function index(): string
    {
        $clientesModel = new ClienteModel(); 
        $dados['clientes'] = $clientesModel->findAll(); // Busca todos os clientes

        return view('cliente/clientes', $dados);
    }
}

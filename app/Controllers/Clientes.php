<?php

namespace App\Controllers;
use App\Models\ClienteModel;
use App\Models\ProdutoModel;
use App\Models\PedidoModel;

class Clientes extends BaseController
{
    public function cadastroCliente()
    {
        $clientes = new ClienteModel();

        $dados = [
            'dados' => $this->request->getPost('dados'),
            'razao_social' => $this->request->getPost('razao_social')
        ];


        if ($clientes->insert($dados)) {
            return redirect()->to(base_url('/'))->with('success', 'Cliente cadastrado com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar cliente.');
        }
    }

    public function editarCliente() 
   {
        $id = $this->request->getPost('id');

        if (!$id) {
            return redirect()->to('/')->with('error', 'ID do cliente não fornecido.');
        }

        $clienteModel = new ClienteModel();
        $cliente = $clienteModel->find($id);

        if (!$cliente) {
            return redirect()->to('/')->with('error', 'Cliente não encontrado.');
        }

        // Atualiza os dados do cliente
        $data = [
            'cpf_cnpj' => $this->request->getPost('dados'),
            'razao_social' => $this->request->getPost('razao_social'),
        ];

        $clienteModel->update($id, $data);

        return redirect()->to('/')->with('success', 'Cliente atualizado com sucesso.');
    }

    public function deletarCliente($id = null)
    {
        if ($id === null) {
            return redirect()->to('/')->with('error', 'ID inválido.');
        }

        $clientesModel = new ClienteModel();
        $cliente = $clientesModel->find($id);

        if (!$cliente) {
            return redirect()->to('/')->with('error', 'Cliente não encontrado.');
        }

        // Exclui pedidos do cliente
        $pedidoModel = new PedidoModel();
        $pedidoModel->where('cliente_id', $id)->delete();

        // Exclui o cliente
        $clientesModel->delete($id);

        return redirect()->to('/')->with('success', 'Cliente excluído com sucesso.');
    }

}

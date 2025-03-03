<?php

namespace App\Controllers;
use App\Models\ClienteModel;
use App\Models\ProdutoModel;
use App\Models\PedidoModel;
use App\Models\PedidoProdutoModel;

class Pedidos extends BaseController
{
    public function index()
    {
       $Pedidos = new PedidoModel();
        $Clientes = new ClienteModel();
        $Produtos = new ProdutoModel();
        $PedidoProdutoModel = new PedidoProdutoModel();

        $pedidos = $Pedidos->select('pedidos.id, 
                                    pedidos.cliente_id, 
                                    COALESCE(clientes.razao_social, "Cliente não informado") AS razao_social, 
                                    pedidos.status, 
                                    COALESCE(SUM(pedido_produtos.preco_unitario * pedido_produtos.quantidade), 0) AS total')
            ->join('clientes', 'clientes.id = pedidos.cliente_id', 'left')
            ->join('pedido_produtos', 'pedido_produtos.pedido_id = pedidos.id', 'left')
            ->groupBy('pedidos.id, pedidos.cliente_id, clientes.razao_social, pedidos.status')
            ->findAll();

        // Criar um array associando os produtos a cada pedido
        foreach ($pedidos as &$pedido) {
            $pedido['produtos'] = $PedidoProdutoModel
                ->select('produtos.id, produtos.nome, produtos.preco')
                ->join('produtos', 'produtos.id = pedido_produtos.produto_id', 'left')
                ->where('pedido_produtos.pedido_id', $pedido['id'])
                ->findAll();
        }


        $data = [
            'clientes' => $Clientes->findAll(),
            'produtos' => $Produtos->findAll(),
            'pedidos'  => $pedidos
        ];

        return view('pedido/Pedidos', $data);

    }

    public function cadastroPedido()
    {
        $Pedidos = new PedidoModel();
        $PedidoProdutos = new PedidoProdutoModel(); 
        
        $pedidoData = [
            'cliente_id' => $this->request->getPost('cliente_id'),
            'status' => $this->request->getPost('status'),
        ];
        $pedidoId = $Pedidos->insert($pedidoData);

        $produtos = $this->request->getPost('produto_id'); 
        
        if (!empty($produtos)) {
            foreach ($produtos as $produtoId) {
                $produto = (new ProdutoModel())->find($produtoId); 
                $PedidoProdutos->insert([
                    'pedido_id' => $pedidoId,
                    'produto_id' => $produtoId,
                    'quantidade' => 1, 
                    'preco_unitario' => $produto['preco']
                ]);
            }
        }

        return redirect()->to(base_url('pedidos'))->with('success', 'Pedido cadastrado com sucesso!');
    }

    public function editarPedido($id)
    {
        $Pedidos = new PedidoModel();
        $PedidoProdutos = new PedidoProdutoModel();

        // Atualiza os dados do pedido
        $pedidoData = [
            'cliente_id' => $this->request->getPost('cliente_id'),
            'status' => $this->request->getPost('status')
        ];
        $Pedidos->update($id, $pedidoData);

        // Deletar produtos antigos do pedido
        $PedidoProdutos->where('pedido_id', $id)->delete();

        // Inserir novos produtos no pedido
        $produtos = $this->request->getPost('produto_id');
        if (!empty($produtos)) {
            foreach ($produtos as $produtoId) {
                $produto = (new ProdutoModel())->find($produtoId);
                $PedidoProdutos->insert([
                    'pedido_id' => $id,
                    'produto_id' => $produtoId,
                    'quantidade' => 1, 
                    'preco_unitario' => $produto['preco']
                ]);
            }
        }

        return redirect()->to(base_url('pedidos'))->with('success', 'Pedido atualizado com sucesso!');
    }

    public function deletarPedido($id)
    {
        $Pedidos = new PedidoModel();
        $PedidoProdutos = new PedidoProdutoModel();

        // Remover produtos do pedido
        $PedidoProdutos->where('pedido_id', $id)->delete();

        // Remover o pedido
        $Pedidos->delete($id);

        return redirect()->to(base_url('pedidos'))->with('success', 'Pedido excluído com sucesso!');
    }  
}

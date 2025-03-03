<?php
namespace App\Controllers;
use App\Models\ProdutoModel;
use App\Models\PedidoModel;

class Produtos extends BaseController
{
    public function index()
    {
        $produtosModel = new ProdutoModel(); 
        $produtos = $produtosModel->findAll(); 

        $dados = [
            'produtos' => $produtos
        ]; 

        return view('produto/produtos', $dados);
    }

    public function cadastroProduto()
    {
        $produtos = new ProdutoModel();
        
        $dados = [
            'nome' => $this->request->getPost('nome'),
            'descricao' => $this->request->getPost('descricao'),
            'preco' => $this->request->getPost('preco')
        ];

        if ($produtos->insert($dados)) {
            return redirect()->to(base_url('/produtos'))->with('success', 'Produto cadastrado com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar produto.');
        } 
    }

    public function editarProduto($id = null)
    {
        $model = new ProdutoModel();
        $id = $id ?? $this->request->getPost('id'); 
        
        $produto = $model->find($id);
        if (!$produto) {
            return redirect()->to('/produtos')->with('error', 'Produto não encontrado.');
        }

        $data = [
            'nome' => $this->request->getPost('nome'),
            'descricao' => $this->request->getPost('descricao'),
            'preco' => $this->request->getPost('preco'),
        ];

        $model->update($id, $data);
        return redirect()->to('/produtos')->with('success', 'Produto atualizado com sucesso.');
    }
    
    public function deletarProduto($id)
    {

        $produtosModel = new ProdutoModel();

        $produtos = $produtosModel->find($id);
        if ($produtos) {
            $produtosModel->delete($id);
            return redirect()->to('/produtos')->with('success', 'Produtos excluído com sucesso.');
        } else {
            return redirect()->to('/produtos')->with('error', 'Produtos não encontrado.');
        }

    } 
}
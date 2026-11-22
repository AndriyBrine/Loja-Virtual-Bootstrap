<?php

namespace App\Controllers;

use App\Models\ProdutoModel;

class ProdutosController extends BaseController
{
    private ProdutoModel $produtoModel;

    public function __construct()
    {
        $this->produtoModel = new ProdutoModel();
    }

    public function index()
    {
        $dados['produtos'] = $this->produtoModel->orderBy('id', 'DESC')->findAll();
        return view('produtos/index', $dados);
    }

    public function criar()
    {
        $arquivo = $this->request->getFile('imagem');
        $nomeImagem = null;

        if ($arquivo && $arquivo->getError() !== UPLOAD_ERR_NO_FILE) {
            $regrasArquivo = [
                'imagem' => [
                    'rules'  => 'is_image[imagem]|max_size[imagem,4096]',
                    'errors' => [
                        'is_image' => 'O arquivo enviado deve ser uma imagem válida.',
                        'max_size' => 'A imagem deve ter no máximo 4MB.',
                    ],
                ],
            ];

            if (! $this->validate($regrasArquivo)) {
                return redirect()->back()->withInput()->with('erros', $this->validator->getErrors());
            }

            if ($arquivo->isValid() && ! $arquivo->hasMoved()) {
                $destino = ROOTPATH . 'public/uploads/produtos';
                if (! is_dir($destino)) {
                    mkdir($destino, 0777, true);
                }

                $nomeImagem = $arquivo->getRandomName();
                $arquivo->move($destino, $nomeImagem);
            } else {
                return redirect()->back()->withInput()->with('erro', 'Erro no upload: ' . $arquivo->getErrorString());
            }
        }

        $precoRaw = str_replace(',', '.', (string) $this->request->getPost('preco'));

        $dadosForm = [
            'nome'      => trim((string) $this->request->getPost('nome')),
            'preco'     => $precoRaw,
            'estoque'   => (int) $this->request->getPost('estoque'),
            'descricao' => trim((string) $this->request->getPost('descricao')),
            'imagem'    => $nomeImagem,
        ];

        if ($this->produtoModel->save($dadosForm)) {
            return redirect()->to('/produtos')->with('sucesso', 'Produto adicionado à vitrine!');
        }

        if ($nomeImagem && is_file(ROOTPATH . 'public/uploads/produtos/' . $nomeImagem)) {
            unlink(ROOTPATH . 'public/uploads/produtos/' . $nomeImagem);
        }

        return redirect()->back()->withInput()->with('erros', $this->produtoModel->errors());
    }

    public function vender($id)
    {
        $produto = $this->produtoModel->find($id);

        if (! $produto) {
            return redirect()->to('/produtos')->with('erro', 'Produto não encontrado.');
        }

        $estoqueAtual = (int) $produto['estoque'];

        if ($estoqueAtual <= 0) {
            return redirect()->to('/produtos')->with('erro', 'Produto esgotado no momento.');
        }

        $this->produtoModel->update($id, ['estoque' => $estoqueAtual - 1]);

        return redirect()->to('/produtos')->with('sucesso', 'Compra realizada! Estoque atualizado.');
    }

    public function repor($id)
    {
        $produto = $this->produtoModel->find($id);

        if (! $produto) {
            return redirect()->to('/produtos')->with('erro', 'Produto não encontrado.');
        }

        $novoEstoque = (int) $produto['estoque'] + 1;
        $this->produtoModel->update($id, ['estoque' => $novoEstoque]);

        return redirect()->to('/produtos')->with('sucesso', '+1 unidade adicionada ao estoque!');
    }

    public function deletar($id)
    {
        $produto = $this->produtoModel->find($id);

        if (! $produto) {
            return redirect()->to('/produtos')->with('erro', 'Produto não encontrado.');
        }

        if ($this->produtoModel->delete($id)) {
            // Remove também o arquivo físico da imagem, se existir
            if (! empty($produto['imagem']) && is_file(FCPATH . 'uploads/produtos/' . $produto['imagem'])) {
                unlink(FCPATH . 'uploads/produtos/' . $produto['imagem']);
            }
            return redirect()->to('/produtos')->with('sucesso', 'Produto removido da loja.');
        }

        return redirect()->to('/produtos')->with('erro', 'Erro ao remover o produto.');
    }
}
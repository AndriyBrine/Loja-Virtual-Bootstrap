<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoModel extends Model
{
    protected $table            = 'produtos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nome', 'descricao', 'imagem', 'preco', 'estoque'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'nome'      => 'required|min_length[3]|max_length[255]',
        'preco'     => 'required|decimal|greater_than_equal_to[0.01]',
        'estoque'   => 'required|is_natural',
        'descricao' => 'permit_empty|max_length[1000]',
        'imagem'    => 'permit_empty|max_length[255]',
    ];

    protected $validationMessages = [
        'nome' => [
            'required'   => 'O nome do eletrônico é obrigatório.',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres.',
            'max_length' => 'O nome não pode ultrapassar 255 caracteres.',
        ],
        'preco' => [
            'required'              => 'O preço é obrigatório.',
            'decimal'               => 'Informe um preço válido (ex: 199.90).',
            'greater_than_equal_to' => 'O preço mínimo deve ser R$ 0,01.',
        ],
        'estoque' => [
            'required'   => 'A quantidade em estoque é obrigatória.',
            'is_natural' => 'O estoque deve ser um número inteiro maior ou igual a zero.',
        ],
    ];
}


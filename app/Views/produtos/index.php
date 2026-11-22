<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore - Catálogo de Eletrônicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f5f7;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .product-card {
            border-radius: 12px;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
            border: 1px solid #eceef2;
        }
        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08) !important;
        }
        .product-img-box {
            height: 170px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            background: #ffffff;
        }
        .product-img-box img {
            max-height: 145px;
            max-width: 100%;
            object-fit: contain;
        }
        .product-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2c313a;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.6em;
            line-height: 1.3;
        }
        .price-old {
            font-size: 0.75rem;
            color: #8c939d;
            text-decoration: line-through;
        }
        .price-main {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1a1d23;
            line-height: 1.1;
        }
        .badge-discount {
            background-color: #e6f7ed;
            color: #198754;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .price-sub {
            font-size: 0.74rem;
            color: #6c757d;
        }
        .badge-stock-alert {
            background-color: #fff0f0;
            color: #dc3545;
            border: 1px solid #f8d7da;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
        }
        .btn-icon-top {
            width: 30px;
            height: 30px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #6c757d;
            background: transparent;
            border: none;
        }
        .btn-icon-top:hover {
            background-color: #f1f3f5;
            color: #212529;
        }
    </style>
</head>
<body>

<div class="container my-4" style="max-width: 1280px;">

    <!-- Cabeçalho da Loja -->
    <div class="d-flex flex-wrap justify-content-between align-items-center bg-white p-3 rounded-3 shadow-sm mb-4">
        <div class="d-flex align-items-center gap-2">
            <span class="bg-warning text-dark fw-bold px-2 py-1 rounded">
                <i class="bi bi-lightning-charge-fill"></i>
            </span>
            <div>
                <h1 class="h5 fw-bold mb-0">TechStore Hardware</h1>
                <small class="text-muted"><?= count($produtos) ?> produtos no catálogo</small>
            </div>
        </div>

        <button class="btn btn-warning fw-semibold px-3" type="button" data-bs-toggle="collapse" data-bs-target="#formCadastroCollapse" aria-expanded="<?= session()->getFlashdata('erros') ? 'true' : 'false' ?>">
            <i class="bi bi-plus-lg me-1"></i> Cadastrar Produto
        </button>
    </div>

    <!-- Alertas Flashdata -->
    <?php if (session()->getFlashdata('sucesso')): ?>
        <div class="alert alert-success alert-dismissible fade show py-2 px-3 small shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('sucesso')) ?>
            <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('erro')): ?>
        <div class="alert alert-danger alert-dismissible fade show py-2 px-3 small shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('erro')) ?>
            <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('erros')): ?>
        <div class="alert alert-danger alert-dismissible fade show small shadow-sm" role="alert">
            <ul class="mb-0 ps-3">
                <?php foreach (session()->getFlashdata('erros') as $erro): ?>
                    <li><?= esc($erro) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Formulário Recolhível de Cadastro (com upload de imagem) -->
    <div class="collapse <?= (session()->getFlashdata('erros') || empty($produtos)) ? 'show' : '' ?> mb-4" id="formCadastroCollapse">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Adicionar Eletrônico ao Catálogo</h2>
                <form action="<?= base_url('produtos/criar') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold mb-1">Nome / Modelo *</label>
                            <input type="text" name="nome" class="form-control" placeholder="Ex: Placa de Vídeo RTX 4060 8GB" value="<?= esc(old('nome')) ?>" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold mb-1">Preço (R$) *</label>
                            <input type="text" name="preco" class="form-control" placeholder="1999.90" value="<?= esc(old('preco')) ?>" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold mb-1">Estoque *</label>
                            <input type="number" name="estoque" class="form-control" min="0" value="<?= esc(old('estoque') ?? '5') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold mb-1">Foto do Produto (JPG, PNG, WEBP)</label>
                            <input type="file" name="imagem" class="form-control" accept="image/png, image/jpeg, image/webp">
                        </div>
                        <div class="col-md-9">
                            <input type="text" name="descricao" class="form-control" placeholder="Especificações técnicas curtas (opcional)" value="<?= esc(old('descricao')) ?>">
                        </div>
                        <div class="col-md-3 d-grid">
                            <button type="submit" class="btn btn-dark fw-semibold">
                                <i class="bi bi-check2-circle me-1"></i> Salvar na Vitrine
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Grid de Produtos (Estilo Vitrine E-commerce) -->
    <?php if (!empty($produtos) && is_array($produtos)): ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
            <?php foreach ($produtos as $produto): ?>
                <?php
                    $estoque   = (int) $produto['estoque'];
                    $esgotado  = ($estoque === 0);
                    $preco     = (float) $produto['preco'];
                    $temImagem = !empty($produto['imagem']) && is_file(ROOTPATH . 'public/uploads/produtos/' . $produto['imagem']);
                ?>
                <div class="col">
                    <div class="card h-100 bg-white shadow-sm product-card <?= $esgotado ? 'opacity-75' : '' ?>">
                        <div class="card-body p-3 d-flex flex-column justify-content-between">

                            <div>
                                <!-- Ações Rápidas no Topo -->
                                <div class="d-flex justify-content-end align-items-center gap-1 mb-1">
                                    <form action="<?= base_url('produtos/repor/' . $produto['id']) ?>" method="POST" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn-icon-top" title="Repor +1 unidade no estoque">
                                            <i class="bi bi-plus-circle"></i>
                                        </button>
                                    </form>

                                    <form action="<?= base_url('produtos/deletar/' . $produto['id']) ?>" method="POST" class="d-inline" onsubmit="return confirm('Remover este item do catálogo?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn-icon-top text-danger" title="Excluir produto">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Imagem do Produto -->
                                <div class="product-img-box mb-2">
                                    <?php if ($temImagem): ?>
                                        <img src="/uploads/produtos/<?= esc($produto['imagem']) ?>" alt="<?= esc($produto['nome']) ?>">
                                    <?php else: ?>
                                        <div class="text-center text-muted">
                                            <i class="bi bi-pc-display display-4 d-block opacity-25"></i>
                                            <span style="font-size: 0.7rem;">Sem foto</span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Título do Produto -->
                                <h2 class="product-title mb-1" title="<?= esc($produto['nome']) ?>">
                                    <?= esc($produto['nome']) ?>
                                </h2>

                                <!-- Descrição Real (se houver) -->
                                <?php if (!empty($produto['descricao'])): ?>
                                    <p class="text-muted small mb-2 text-truncate" title="<?= esc($produto['descricao']) ?>">
                                        <?= esc($produto['descricao']) ?>
                                    </p>
                                <?php endif; ?>

                                <!-- Preço Real -->
                                <div class="mb-2">
                                    <div class="price-main my-1">
                                        <?= number_to_currency($preco, 'BRL', 'pt_BR', 2) ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Rodapé: Botão Comprar + Estoque -->
                            <div class="pt-2 border-top d-flex justify-content-between align-items-center gap-2">
                                <form action="<?= base_url('produtos/vender/' . $produto['id']) ?>" method="POST" class="flex-grow-1">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm w-100 fw-semibold <?= $esgotado ? 'btn-secondary' : 'btn-warning' ?>" <?= $esgotado ? 'disabled' : '' ?>>
                                        <i class="bi bi-cart-plus me-1"></i> <?= $esgotado ? 'Esgotado' : 'Comprar' ?>
                                    </button>
                                </form>

                                <?php if (! $esgotado): ?>
                                    <span class="badge-stock-alert text-nowrap">
                                        Restam <?= $estoque ?> Unid.
                                    </span>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-3 shadow-sm text-center p-5 text-muted">
            <i class="bi bi-box-seam display-4 d-block mb-2"></i>
            Nenhum eletrônico cadastrado na vitrine ainda.
        </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
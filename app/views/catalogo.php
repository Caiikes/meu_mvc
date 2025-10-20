<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Catálogo de Produtos</title>
    <link rel="stylesheet" href="CatalogoEstilo.css">
</head>

<body>

    <div class="Cabecalho">
        <h1>Catálogo de Produtos</h1>
        <p>Visualize nossos produtos em 3D</p>
        <div class="InfoCabecalho">
            <span class="TotalProdutos">Total de produtos: <?php echo count($produtos); ?></span>
        </div>
    </div>

    <div class="ConteinerPrincipal">
        <?php if (count($produtos) > 0): ?>
            <div class="GaleriaDeProdutos" id="galeria"></div>
        <?php else: ?>
            <div class="MensagemVazia">
                <p>Nenhum produto cadastrado ainda.</p>
            </div>
        <?php endif; ?>

        <div class="SecaoAdicionarProduto">
            <h2>Adicione um novo produto</h2>
            <button class="BotaoAdicionarProduto" onclick="abrirModalNovoProduto()">+ Novo Produto</button>
        </div>
    </div>

    <?php include __DIR__ . '/modais.php'; ?>

    <script type="importmap">
        {
        "imports": {
            "three": "https://cdn.jsdelivr.net/npm/three@0.165.0/build/three.module.js",
            "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.165.0/examples/jsm/"
        }
    }
    </script>

    <script type="module">
        import {
            GerarGaleria
        } from './CatalogoScript.js';

        const Produtos = <?php echo json_encode($produtos); ?>;

        document.addEventListener("DOMContentLoaded", () => {
            GerarGaleria(Produtos);
        });
    </script>

    <script src="app/views/ModalScript.js"></script>
    <script>
        produtosGlobal = <?php echo json_encode($produtos); ?>;
    </script>
</body>

</html>
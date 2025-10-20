<?php

session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/controllers/ProdutoController.php';

define('IMAGENS_PADRAO', [
    'Teclado Mecânico' => 'imagens/teclado.png',
    'Mouse Gamer' => 'imagens/mouse.png',
    'Monitor 24"' => 'imagens/monitor.png',
]);

function verificarColunaImagens()
{
    try {
        $conn = Database::conectar();
        $stmt = $conn->prepare("SHOW COLUMNS FROM produtos LIKE 'imagem'");
        $stmt->execute();
        $coluna = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$coluna) {
            if (isset($_POST['adicionar_coluna_imagem'])) {
                if ($_POST['adicionar_coluna_imagem'] === 'sim') {
                    $stmtAdd = $conn->prepare("ALTER TABLE produtos ADD COLUMN imagem VARCHAR(255) DEFAULT ''");
                    $stmtAdd->execute();
                    $_SESSION['mensagem_coluna'] = 'Coluna de imagem adicionada com sucesso!';
                    header('Location: index.php');
                    exit;
                } else {
                    $_SESSION['coluna_imagem_negada'] = true;
                }
            } else {
?>
                <html>

                <head>
                    <meta charset="UTF-8">
                </head>

                <body>
                    <script>
                        var resultado = confirm('Para exibir as imagens da página corretamente, é necessário ter a coluna "imagem" no banco de dados.\n\nDeseja adicioná-la agora?');

                        var form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'index.php';

                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'adicionar_coluna_imagem';
                        input.value = resultado ? 'sim' : 'nao';

                        form.appendChild(input);
                        document.body.appendChild(form);
                        form.submit();
                    </script>
                </body>

                </html>
<?php
                exit;
            }
        }

        if (isset($_SESSION['mensagem_coluna'])) {
            echo '<script>alert("' . $_SESSION['mensagem_coluna'] . '");</script>';
            unset($_SESSION['mensagem_coluna']);
        }
    } catch (Exception $e) {
    }
}

function adicionarImagemPadrao($produto)
{
    if (empty($produto['imagem'] ?? null)) {
        $nome = $produto['nome'] ?? '';
        if (isset(IMAGENS_PADRAO[$nome])) {
            $produto['imagem'] = IMAGENS_PADRAO[$nome];
        }
    }
    return $produto;
}

function processarProdutosComImagens($produtos)
{
    return array_map(function ($produto) {
        return adicionarImagemPadrao($produto);
    }, $produtos);
}

verificarColunaImagens();

$controller = new ProdutoController();

$acao = $_GET['acao'] ?? 'index';

switch ($acao) {
    case 'index':
        $controller->index();
        break;

    case 'adicionar':
        $controller->adicionar();
        break;

    case 'editar':
        $controller->editar();
        break;

    case 'excluir':
        $controller->excluir();
        break;

    default:
        header('Location: index.php');
        exit;
}

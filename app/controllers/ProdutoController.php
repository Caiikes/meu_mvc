<?php
require_once __DIR__ . '/../models/Produto.php';

class ProdutoController {

    public function index() {
        $produtos = Produto::listar();
        $produtos = processarProdutosComImagens($produtos);
        require __DIR__ . '/../views/catalogo.php';
    }

    public function listarTodos() {
        return Produto::listar();
    }

    public function buscarPorId($id) {
        return Produto::buscarPorId($id);
    }

    public function adicionar() {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método não permitido');
            }

            $nome = trim($_POST['nome'] ?? '');
            $preco = floatval($_POST['preco'] ?? 0);

            if (empty($nome) || $preco <= 0) {
                throw new Exception('Nome e preço válidos são obrigatórios');
            }

            $caminhoImagem = $this->processarUploadImagem();
            
            if (Produto::adicionar($nome, $preco, $caminhoImagem)) {
                echo json_encode(['sucesso' => true, 'mensagem' => 'Produto adicionado com sucesso']);
            } else {
                throw new Exception('Erro ao adicionar o produto');
            }
        } catch (Exception $e) {
            echo json_encode(['sucesso' => false, 'mensagem' => $e->getMessage()]);
        }
    }

    public function editar() {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método não permitido');
            }

            $id = intval($_POST['id'] ?? 0);
            $nome = trim($_POST['nome'] ?? '');
            $preco = floatval($_POST['preco'] ?? 0);

            if ($id <= 0 || empty($nome) || $preco <= 0) {
                throw new Exception('Dados inválidos');
            }

            $produtoAtual = Produto::buscarPorId($id);
            if (!$produtoAtual) {
                throw new Exception('Produto não encontrado');
            }

            $caminhoImagem = null;
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                if (!empty($produtoAtual['imagem']) && file_exists($produtoAtual['imagem'])) {
                    unlink($produtoAtual['imagem']);
                }
                $caminhoImagem = $this->processarUploadImagem();
            }

            if (Produto::atualizar($id, $nome, $preco, $caminhoImagem)) {
                echo json_encode(['sucesso' => true, 'mensagem' => 'Produto atualizado com sucesso']);
            } else {
                throw new Exception('Erro ao atualizar o produto');
            }
        } catch (Exception $e) {
            echo json_encode(['sucesso' => false, 'mensagem' => $e->getMessage()]);
        }
    }

    public function excluir() {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método não permitido');
            }

            $dados = json_decode(file_get_contents('php://input'), true);
            $id = intval($dados['id'] ?? 0);

            if ($id <= 0) {
                throw new Exception('ID inválido');
            }

            $produto = Produto::buscarPorId($id);
            if ($produto && !empty($produto['imagem']) && file_exists($produto['imagem'])) {
                unlink($produto['imagem']);
            }

            if (Produto::excluir($id)) {
                echo json_encode(['sucesso' => true, 'mensagem' => 'Produto excluído com sucesso']);
            } else {
                throw new Exception('Erro ao excluir o produto');
            }
        } catch (Exception $e) {
            echo json_encode(['sucesso' => false, 'mensagem' => $e->getMessage()]);
        }
    }

    private function processarUploadImagem() {
        if (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
            return '';
        }

        $arquivo = $_FILES['imagem'];
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/jpg'];
        $tamanhoMaximo = 5 * 1024 * 1024; // 5MB

        // Validações
        if (!in_array($arquivo['type'], $tiposPermitidos)) {
            throw new Exception('Tipo de arquivo não permitido. Use PNG, JPG ou JPEG.');
        }

        if ($arquivo['size'] > $tamanhoMaximo) {
            throw new Exception('Arquivo muito grande. Máximo 5MB.');
        }

        // Cria pasta de imagens se não existir
        $pastaImagens = __DIR__ . '/../../imagens';
        if (!is_dir($pastaImagens)) {
            mkdir($pastaImagens, 0755, true);
        }

        // Gera nome único
        $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        $nomeArquivo = md5(uniqid() . time()) . '.' . $extensao;
        $caminhoCompleto = $pastaImagens . '/' . $nomeArquivo;

        // Move arquivo
        if (!move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
            throw new Exception('Erro ao fazer upload da imagem');
        }

        return 'imagens/' . $nomeArquivo;
    }
}
?>

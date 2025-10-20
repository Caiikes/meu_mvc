<?php
require_once __DIR__ . '/../../config/database.php';

class Produto {
    public static function listar() {
        $conn = Database::conectar();
        $stmt = $conn->prepare("SELECT * FROM produtos ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function buscarPorId($id) {
        $conn = Database::conectar();
        $stmt = $conn->prepare("SELECT * FROM produtos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function adicionar($nome, $preco, $imagem = '') {
        $conn = Database::conectar();
        $stmt = $conn->prepare("INSERT INTO produtos (nome, preco, imagem) VALUES (:nome, :preco, :imagem)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':imagem', $imagem);
        return $stmt->execute();
    }

    public static function atualizar($id, $nome, $preco, $imagem = null) {
        $conn = Database::conectar();
        
        if ($imagem === null) {
            $stmt = $conn->prepare("UPDATE produtos SET nome = :nome, preco = :preco WHERE id = :id");
        } else {
            $stmt = $conn->prepare("UPDATE produtos SET nome = :nome, preco = :preco, imagem = :imagem WHERE id = :id");
            $stmt->bindParam(':imagem', $imagem);
        }
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':preco', $preco);
        return $stmt->execute();
    }

    public static function excluir($id) {
        $conn = Database::conectar();
        $stmt = $conn->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>

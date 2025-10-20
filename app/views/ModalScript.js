let produtosGlobal = [];

function abrirModal(idModal) {
    const modal = document.getElementById(idModal);
    if (modal) {
        modal.classList.add('ativo');
        document.body.style.overflow = 'hidden';
    }
}

function fecharModal(idModal) {
    const modal = document.getElementById(idModal);
    if (modal) {
        modal.classList.remove('ativo');
        document.body.style.overflow = 'auto';

        if (idModal === 'modalNovoProduto') {
            document.getElementById('formNovoProduto').reset();
            document.getElementById('previewImagem').classList.remove('ativo');
        } else if (idModal === 'modalEditarProduto') {
            document.getElementById('formEditarProduto').reset();
            document.getElementById('previewImagemEditar').classList.remove('ativo');
        }
    }
}

function abrirModalNovoProduto() {
    abrirModal('modalNovoProduto');
}

function abrirModalEditar(id) {
    const produto = produtosGlobal.find(p => p.id == id);
    if (!produto) return;

    document.getElementById('idProdutoEditar').value = produto.id;
    document.getElementById('nomeProdutoEditar').value = produto.nome;
    document.getElementById('precoProdutoEditar').value = produto.preco;

    const containerImagem = document.getElementById('containerImagemAtual');
    containerImagem.innerHTML = '';

    if (produto.imagem) {
        const img = document.createElement('img');
        img.src = produto.imagem;
        img.className = 'ImagemAtual';
        img.alt = 'Imagem atual';
        containerImagem.appendChild(img);
    }

    document.getElementById('previewImagemEditar').classList.remove('ativo');
    document.getElementById('inputImagemEditar').value = '';

    abrirModal('modalEditarProduto');
}

function abrirModalVisualizar(id) {
    const produto = produtosGlobal.find(p => p.id == id);
    if (!produto) return;

    document.getElementById('tituloVisualizacao').textContent = produto.nome;
    document.getElementById('idVisualizacao').textContent = produto.id;
    document.getElementById('nomeVisualizacao').textContent = produto.nome;
    document.getElementById('precoVisualizacao').textContent =
        'R$ ' + parseFloat(produto.preco).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    document.getElementById('botaoEditarVisualizar').onclick = function () {
        fecharModal('modalVisualizarProduto');
        abrirModalEditar(id);
    };

    abrirModal('modalVisualizarProduto');

    setTimeout(() => {
        const vitrine = document.getElementById('vitrine-visualizar');
        vitrine.innerHTML = '';

        import('../../CatalogoScript.js').then(module => {
            module.CriarVitrine3D('vitrine-visualizar', produto.imagem);
        }).catch(error => {
            console.error('Erro ao carregar CatalogoScript:', error);
        });
    }, 100);
}

function salvarProduto(event, acao) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);

    const url = `index.php?acao=${acao}`;

    fetch(url, {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                alert(data.mensagem);
                location.reload();
            } else {
                alert('Erro: ' + data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao processar o formulário');
        });
}

function excluirProduto(id) {
    if (!confirm('Tem certeza que deseja excluir este produto?')) {
        return;
    }

    fetch('index.php?acao=excluir', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id })
    })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                alert(data.mensagem);
                location.reload();
            } else {
                alert('Erro: ' + data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao processar a exclusão');
        });
}

function configurarUploadImagem(idArea, idInput, idPreview) {
    const areaUpload = document.getElementById(idArea);
    const inputImagem = document.getElementById(idInput);
    const previewImagem = document.getElementById(idPreview);

    if (!areaUpload || !inputImagem || !previewImagem) return;

    areaUpload.addEventListener('dragover', (e) => {
        e.preventDefault();
        areaUpload.classList.add('dragover');
    });

    areaUpload.addEventListener('dragleave', () => {
        areaUpload.classList.remove('dragover');
    });

    areaUpload.addEventListener('drop', (e) => {
        e.preventDefault();
        areaUpload.classList.remove('dragover');

        const arquivos = e.dataTransfer.files;
        if (arquivos.length > 0) {
            inputImagem.files = arquivos;
            mostrarPreview(arquivos[0], previewImagem, inputImagem);
        }
    });

    inputImagem.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            mostrarPreview(e.target.files[0], previewImagem, inputImagem);
        }
    });
}

function mostrarPreview(arquivo, elementoPreview, elementoInput) {
    const tamanhoMaximo = 5 * 1024 * 1024;

    if (arquivo.size > tamanhoMaximo) {
        alert('Arquivo muito grande! Máximo 5MB.');
        elementoInput.value = '';
        elementoPreview.classList.remove('ativo');
        return;
    }

    if (!arquivo.type.startsWith('image/')) {
        alert('Por favor, selecione um arquivo de imagem válido.');
        elementoInput.value = '';
        elementoPreview.classList.remove('ativo');
        return;
    }

    const leitor = new FileReader();
    leitor.onload = (evento) => {
        elementoPreview.src = evento.target.result;
        elementoPreview.classList.add('ativo');
    };
    leitor.readAsDataURL(arquivo);
}

document.addEventListener('DOMContentLoaded', () => {
    configurarUploadImagem('areaUpload', 'inputImagem', 'previewImagem');
    configurarUploadImagem('areaUploadEditar', 'inputImagemEditar', 'previewImagemEditar');
});

window.abrirModalNovoProduto = abrirModalNovoProduto;
window.abrirModalEditar = abrirModalEditar;
window.abrirModalVisualizar = abrirModalVisualizar;
window.excluirProduto = excluirProduto;
window.fecharModal = fecharModal;

<div class="ModalOverlay" id="modalNovoProduto" onclick="fecharModal('modalNovoProduto')">
    <div class="ModalConteudo" onclick="event.stopPropagation()">
        <div class="ModalCabecalho">
            <h2>Adicionar Novo Produto</h2>
            <button class="BotaoFecharModal" onclick="fecharModal('modalNovoProduto')">×</button>
        </div>
        <form id="formNovoProduto" class="FormularioProduto" onsubmit="salvarProduto(event, 'adicionar')">
            <div class="GrupoFormulario">
                <label for="nomeProduto">Nome do Produto:</label>
                <input type="text" id="nomeProduto" name="nome" required placeholder="Ex: Teclado Mecânico">
            </div>
            <div class="GrupoFormulario">
                <label for="precoProduto">Preço (R$):</label>
                <input type="number" id="precoProduto" name="preco" step="0.01" required placeholder="Ex: 150.00">
            </div>
            <div class="GrupoFormulario">
                <label>Imagem do Produto:</label>
                <div class="AreaUploadImagem" id="areaUpload" onclick="document.getElementById('inputImagem').click()">
                    <p class="TextoUpload">
                        <strong>Clique aqui</strong> ou arraste uma imagem<br>
                        <small>PNG, JPG, JPEG (máx. 5MB)</small>
                    </p>
                    <img id="previewImagem" class="PreviewImagem" alt="Preview">
                </div>
                <input type="file" id="inputImagem" name="imagem" accept="image/*" style="display: none;">
            </div>
            <div class="AcoesModal">
                <button type="button" class="BotaoCancelar" onclick="fecharModal('modalNovoProduto')">Cancelar</button>
                <button type="submit" class="BotaoSalvar">Salvar Produto</button>
            </div>
        </form>
    </div>
</div>

<div class="ModalOverlay" id="modalEditarProduto" onclick="fecharModal('modalEditarProduto')">
    <div class="ModalConteudo" onclick="event.stopPropagation()">
        <div class="ModalCabecalho">
            <h2>Editar Produto</h2>
            <button class="BotaoFecharModal" onclick="fecharModal('modalEditarProduto')">×</button>
        </div>
        <form id="formEditarProduto" class="FormularioProduto" onsubmit="salvarProduto(event, 'editar')">
            <input type="hidden" id="idProdutoEditar" name="id">
            
            <div class="GrupoFormulario">
                <label for="nomeProdutoEditar">Nome do Produto:</label>
                <input type="text" id="nomeProdutoEditar" name="nome" required placeholder="Ex: Teclado Mecânico">
            </div>
            <div class="GrupoFormulario">
                <label for="precoProdutoEditar">Preço (R$):</label>
                <input type="number" id="precoProdutoEditar" name="preco" step="0.01" required placeholder="Ex: 150.00">
            </div>
            <div class="GrupoFormulario">
                <label>Imagem do Produto:</label>
                <div id="containerImagemAtual"></div>
                <div class="AreaUploadImagem" id="areaUploadEditar" onclick="document.getElementById('inputImagemEditar').click()">
                    <p class="TextoUpload">
                        <strong>Clique aqui</strong> para alterar a imagem<br>
                        <small>PNG, JPG, JPEG (máx. 5MB)</small>
                    </p>
                    <img id="previewImagemEditar" class="PreviewImagem" alt="Preview">
                </div>
                <input type="file" id="inputImagemEditar" name="imagem" accept="image/*" style="display: none;">
            </div>
            <div class="AcoesModal">
                <button type="button" class="BotaoCancelar" onclick="fecharModal('modalEditarProduto')">Cancelar</button>
                <button type="submit" class="BotaoSalvar">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>

<div class="ModalOverlay" id="modalVisualizarProduto" onclick="fecharModal('modalVisualizarProduto')">
    <div class="ModalConteudo ModalConteudoVisualizar" onclick="event.stopPropagation()">
        <div class="ModalCabecalho">
            <h2 id="tituloVisualizacao">Visualizar Produto</h2>
            <button class="BotaoFecharModal" onclick="fecharModal('modalVisualizarProduto')">×</button>
        </div>
        <div class="ConteudoVisualizar">
            <div class="SecaoVitrine3DVisualizar" id="vitrine-visualizar"></div>

            <div class="DetalhesVisualizacao">
                <div class="LinhaProduto">
                    <span class="LabelProduto">ID:</span>
                    <span class="ValorProduto" id="idVisualizacao">-</span>
                </div>
                <div class="LinhaProduto">
                    <span class="LabelProduto">Nome:</span>
                    <span class="ValorProduto" id="nomeVisualizacao">-</span>
                </div>
                <div class="LinhaProduto">
                    <span class="LabelProduto">Preço:</span>
                    <span class="ValorPrecoProduto" id="precoVisualizacao">-</span>
                </div>
            </div>

            <div class="AcoesModal">
                <button class="BotaoSalvar" id="botaoEditarVisualizar">Editar Produto</button>
                <button class="BotaoCancelar" onclick="fecharModal('modalVisualizarProduto')">Fechar</button>
            </div>
        </div>
    </div>
</div>

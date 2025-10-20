import * as THREE from "three";
import { OrbitControls } from "three/addons/controls/OrbitControls.js";

// FUNÇÃO PARA CRIAR UMA VITRINE 3D
export function CriarVitrine3D(IdConteiner, UrlImagem) {
    const Conteiner = document.getElementById(IdConteiner);
    if (!Conteiner) return;

    // CENA
    const Cena = new THREE.Scene();
    Cena.background = new THREE.Color(0xffffff);
    Cena.fog = new THREE.Fog(0xffffff, 10, 20);

    // CÂMERA
    const Largura = Conteiner.clientWidth;
    const Altura = Conteiner.clientHeight;
    const Camera = new THREE.PerspectiveCamera(75, Largura / Altura, 0.1, 1000);
    Camera.position.set(4, 3.5, 5);

    // RENDERIZADOR
    const Renderizador = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    Renderizador.setSize(Largura, Altura);
    Renderizador.setPixelRatio(window.devicePixelRatio);
    Renderizador.shadowMap.enabled = true;
    Renderizador.shadowMap.type = THREE.PCFShadowShadowMap;
    Conteiner.appendChild(Renderizador.domElement);

    // CONTROLES
    const Controles = new OrbitControls(Camera, Renderizador.domElement);
    Controles.autoRotate = true;
    Controles.autoRotateSpeed = 2;
    Controles.enableDamping = true;
    Controles.dampingFactor = 0.05;

    // LUZES
    const LuzAmbiente = new THREE.AmbientLight(0xffffff, 0.5);
    Cena.add(LuzAmbiente);

    const LuzDirecional = new THREE.DirectionalLight(0xffffff, 1.2);
    LuzDirecional.position.set(5, 5, 5);
    LuzDirecional.castShadow = true;
    LuzDirecional.shadow.mapSize.width = 2048;
    LuzDirecional.shadow.mapSize.height = 2048;
    Cena.add(LuzDirecional);

    const LuzInternaVermelha = new THREE.PointLight(0xff9999, 0.8);
    LuzInternaVermelha.position.set(-1.2, 1.2, 1.2);
    Cena.add(LuzInternaVermelha);

    const LuzInternaAzul = new THREE.PointLight(0x99ccff, 0.8);
    LuzInternaAzul.position.set(1.2, 1.2, -1.2);
    Cena.add(LuzInternaAzul);

    const LuzPreenchimento = new THREE.DirectionalLight(0xccccff, 0.4);
    LuzPreenchimento.position.set(-5, 3, 3);
    Cena.add(LuzPreenchimento);

    // VITRINE
    const GeometriaVitrine = new THREE.BoxGeometry(3, 3.5, 3);
    const MaterialVitrine = new THREE.MeshPhysicalMaterial({
        color: 0xf0f8ff,
        transparent: true,
        opacity: 0.15,
        roughness: 0.05,
        metalness: 0.9,
        transmission: 0.95,
        thickness: 0.8,
        ior: 1.5,
        reflectivity: 1.0,
        side: THREE.DoubleSide
    });
    const Vitrine = new THREE.Mesh(GeometriaVitrine, MaterialVitrine);
    Vitrine.castShadow = true;
    Vitrine.receiveShadow = true;
    Cena.add(Vitrine);

    // BORDAS
    const MaterialBorda = new THREE.MeshStandardMaterial({
        color: 0xcccccc,
        metalness: 1.0,
        roughness: 0.1
    });

    const GeometriaBorda = new THREE.BoxGeometry(3.2, 0.1, 0.1);
    const BordaSuperior = new THREE.Mesh(GeometriaBorda, MaterialBorda);
    BordaSuperior.position.y = 1.75;
    BordaSuperior.castShadow = true;
    Cena.add(BordaSuperior);

    const BordaInferior = new THREE.Mesh(GeometriaBorda, MaterialBorda);
    BordaInferior.position.y = -1.75;
    BordaInferior.castShadow = true;
    Cena.add(BordaInferior);

    // BASE
    const GeometriaBase = new THREE.CylinderGeometry(1.8, 2, 0.3, 32);
    const MaterialBase = new THREE.MeshStandardMaterial({
        color: 0x444444,
        metalness: 0.7,
        roughness: 0.3
    });
    const Base = new THREE.Mesh(GeometriaBase, MaterialBase);
    Base.position.y = -2.1;
    Base.castShadow = true;
    Base.receiveShadow = true;
    Cena.add(Base);

    // CARREGADOR DE TEXTURA
    const CarregadorTextura = new THREE.TextureLoader();
    let Produto;

    CarregadorTextura.load(UrlImagem, function (Textura) {
        const GeometriaProduto = new THREE.PlaneGeometry(2.2, 2.2);
        const MaterialProduto = new THREE.MeshStandardMaterial({
            map: Textura,
            metalness: 0,
            roughness: 0.5,
            side: THREE.DoubleSide
        });
        Produto = new THREE.Mesh(GeometriaProduto, MaterialProduto);
        Produto.castShadow = true;
        Produto.receiveShadow = true;
        Produto.position.y = -0.3;
        Cena.add(Produto);
    }, undefined, function (Erro) {
        console.warn('Imagem não encontrada:', UrlImagem);
        const GeometriaProduto = new THREE.PlaneGeometry(2.2, 2.2);
        const MaterialProduto = new THREE.MeshStandardMaterial({
            color: 0x3366ff,
            metalness: 0,
            roughness: 0.5,
            side: THREE.DoubleSide
        });
        Produto = new THREE.Mesh(GeometriaProduto, MaterialProduto);
        Produto.castShadow = true;
        Produto.receiveShadow = true;
        Produto.position.y = -0.3;
        Cena.add(Produto);
    });

    // PLANO DE SOMBRA
    const GeometriaPlano = new THREE.PlaneGeometry(10, 10);
    const MaterialSombra = new THREE.ShadowMaterial({ opacity: 0.3 });
    const Plano = new THREE.Mesh(GeometriaPlano, MaterialSombra);
    Plano.rotation.x = -Math.PI / 2;
    Plano.position.y = -2.5;
    Plano.receiveShadow = true;
    Cena.add(Plano);

    // ANIMAÇÃO
    function Animar() {
        requestAnimationFrame(Animar);
        Controles.update();
        Renderizador.render(Cena, Camera);
    }
    Animar();

    // RESPONSIVIDADE
    const TratarRedimensionamento = () => {
        const NovaLargura = Conteiner.clientWidth;
        const NovaAltura = Conteiner.clientHeight;
        Camera.aspect = NovaLargura / NovaAltura;
        Camera.updateProjectionMatrix();
        Renderizador.setSize(NovaLargura, NovaAltura);
    };

    window.addEventListener("resize", TratarRedimensionamento);
}

// GERAR CARDS DOS PRODUTOS
export function GerarGaleria(Produtos) {
    const Galeria = document.getElementById("galeria");
    Galeria.innerHTML = "";

    Produtos.forEach(Produto => {
        const Card = document.createElement("div");
        Card.className = "CartaoProduto";
        Card.innerHTML = `
            <div class="ConteinerVitrine3D" id="vitrine-${Produto.id}"></div>
            <div class="InformacoesProduto">
                <div class="IdProduto">ID: ${Produto.id}</div>
                <div class="NomeProduto">${Produto.nome}</div>
                <div class="PrecoProduto">${Produto.preco}</div>
                <div class="AcoesProduto">
                    <button class="Botao BotaoVisualizar" onclick="abrirModalVisualizar(${Produto.id})">Visualizar</button>
                    <div class="AcoesBotoes">
                        <button class="Botao BotaoEditar" onclick="abrirModalEditar(${Produto.id})">Editar</button>
                        <button class="Botao BotaoExcluir" onclick="excluirProduto(${Produto.id})">Excluir</button>
                    </div>
                </div>
            </div>
        `;
        Galeria.appendChild(Card);

        setTimeout(() => {
            CriarVitrine3D(`vitrine-${Produto.id}`, Produto.imagem);
        }, 100);
    });
}

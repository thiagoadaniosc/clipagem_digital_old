<?php FUNCTIONS::getHeader(); ?>
<div class="row col-lg-12 col-xl-11 col-md-12 baseBody" style="overflow:auto;">
    <h1 class="text-left col-lg-12" style="padding: 20px;">Lista de Clipagens</h1>
    
    <form style="padding: 20px;" class="row col-lg-12 col-xl-12 col-md-12 col-sm-12 justify-content-end">
        <div class="col-lg-2 col-xl-2 col-md-12">
            <label class="col-lg-6 float-left" style="padding-top:10px; padding-bottom:10px;" for="show">Exibir: </label>
            <select class="form-control col-lg-5 col-xl-6 float-left" name="show" style="height: 50px; border-radius: 0">
                <?php if (isset($_GET['show']) && $_GET['show'] == 10): ?>
                <option value="10"  selected="selected">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <?php elseif(isset($_GET['show']) && $_GET['show'] == 20): ?>
                <option value="10">10</option>
                <option value="20" selected="selected">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <?php elseif(isset($_GET['show']) && $_GET['show'] == 50): ?>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50" selected="selected">50</option>
                <option value="100">100</option>
                <?php elseif(isset($_GET['show']) && $_GET['show'] == 100): ?>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100" selected="selected">100</option>
                <?php else : ?>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-inline justify-content-end col-xl-10">
            <select class="form-control" name="pesquisar" style="height: 50px; border-radius: 0">
                <option value="todos">Todos</option>
                <option value="titulo">Título</option>
                <option value="data">Data</option>
                <option value="tags">Tags</option>
                <option value="autor">Autor</option>
                <option value="veiculo">Veículo</option>
                <option value="editoria">Editoria</option>
            </select>
            <input class="form-control" name="valor" type="search" placeholder="Pesquisar...">
            <select class="form-control col-lg-2 col-xl-2" name="mes" style="height: 50px; border-radius: 0">
                <option value="">Meses</option>
                <option value="01">Janeiro</option>
                <option value="02">Fevereiro</option>
                <option value="03">Março</option>
                <option value="04">Abril</option>
                <option value="05">Maio</option>
                <option value="06">Junho</option>
                <option value="07">Julho</option>
                <option value="08">Agosto</option>
                <option value="09">Setembro</option>
                <option value="10">Outubro</option>
                <option value="11">Novembro</option>
                <option value="12">Dezembro</option>
            </select>
            <input class="form-control col-lg-3 col-xl-2" type="number" name="ano" placeholder="Ano">
            <button type="submit" class="btn btn-primary form-control" style="padding:14px; border-radius: 0">Pesquisar</button>
            
        </div>
    </form>
    <a href="/pesquisa" class="text-center col-lg-12" style="padding-left: 20px" target="_blank">Baixar Pesquisa</a>
    <?php if(isset($_GET['valor'])): ?>
    <p class="text-center col-lg-12" style="padding: 0; margin-bottom: 0"><b> Pesquisa: </b> <?=$_GET['valor']?>
        <?php if (isset($_GET['mes']) && !empty($_GET['mes'])) : ?>
        <b> Mês: </b> <?=$_GET['mes']?>
        <?php endif; ?>
        <?php if (isset($_GET['ano']) && !empty($_GET['ano'])) : ?>
        <b> Ano: </b> <?=$_GET['ano']?>
        <?php endif; ?>
        
    </p>
    <?php endif; ?>
    <table class="table table-bordered table-hover table-sm col-lg-12">
        <caption><?= FUNCTIONS::totalRegClipagens(); ?> - Clipagens encontradas</caption>
        <thead class="thead-light">
            <th scrope="col">#</th>
            <th scrope="col">Título</th>
            <th scrope="col">Veículo</th>
            <th scrope="col">Editoria</th>
            <th scrope="col">Autor</th>
            <th scrope="col">Data de publicação</th>
            <th scrope="col">Página</th>
            <th scrope="col" class="hidden-md">Tipo (Capa ou Interno)</th>
            <th scrope="col">Tags</th>
            <th scrope="col">Opções</th>
        </thead>
        <tbody>
            <?php 
            $i = 0;
            if (isset($_SESSION['arquivos'])) {
                unset($_SESSION['arquivos']);
            }
            while($dados = $lista->fetch_assoc()): ?>
            <?php if ($dados['tipo'] == 'capa') {
                $tipo = 'Capa';
            } else {
                $tipo = 'Conteúdo Interno';
            }
            $_SESSION['arquivos'][$i] = $dados['nome'];
            $i++;
            ?>
            <tr>
                <th scope="row"><?=$dados['id_clipagem']?></th>
                <td><?=$dados['titulo']?></td>
                <td><?=$dados['veiculo']?></td>
                <td><?=$dados['editoria']?></td>
                <td><?=$dados['autor']?></td>
                <td><?=$dados['data']?></td>
                <td><?=$dados['pagina']?></td>
                <td><?=$tipo?></td>
                <td><?=$dados['tags']?></td>
                <td>
                    <a class="badge badge-primary" href="uploads/<?=$dados['nome']?>" target="_blank">Visualizar</a>
                    <?php if ($_SESSION['admin'] == true):?> 
                    <a class="badge badge-secondary" href="/editar?id=<?=$dados['id_clipagem']?>">Editar</a>
                    <a class="badge badge-danger" onclick="return confirm('Tem certeza que deseja remover este item ? ')" href="/deletar?id=<?=$dados['id_clipagem']?>">Remover</a>
                    <?php endif;?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        
    </table>
    
    <div class="row col-lg-12 justify-content-center">
        <nav aria-label="...">
            <ul class="pagination">
                
            <?php $page = isset($_GET['page']) && !empty($_GET['page']) ? $_GET['page'] : 1; ?>
            <?php $show = isset($_GET['show']) && !empty($_GET['show']) ? $_GET['show'] : 10; ?>

            <?php $totalReg = FUNCTIONS::totalRegClipagens(); ?>
            <?php $lastPage = ceil(FUNCTIONS::totalRegClipagens() / $show);  ?>
            <?php 
            if ($complete_request_uri == '/clipagens' || $complete_request_uri == '/') {
                $pageURI = '/clipagens?page=';
            } else {
                $pageURI = explode("&page", $complete_request_uri);
                $pageURI = explode("?page", $pageURI[0]);
              
                if ($pageURI[0] == '/clipagens') {
                    $pageURI = $pageURI[0].'?page=';
                } else {
                    $pageURI = $pageURI[0].'&page=';
                }
            }
            
            ?>
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="<?=$pageURI . ($page-1)?>" tabindex="-1" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>


                <li class="page-item"><a class="page-link" href="<?=$pageURI . ($page-1)?>"><?= $page -1; ?></a></li>

                <?php endif; ?>
                <li class="page-item active">
                    <a class="page-link" href="<?=$pageURI . $page?>"><?= $page; ?><span class="sr-only">(current)</span></a>
                </li>


                <?php if ($page < $lastPage) : ?>
                <li class="page-item"><a class="page-link" href="<?=$pageURI . ($page+1)?>"><?= $page + 1;?></a></li>

                <li class="page-item">
                    <a class="page-link" href="<?=$pageURI. ($page+1)?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    
</div>
<?php FUNCTIONS::getFooter(); ?>
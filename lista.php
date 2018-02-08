<?php FUNCTIONS::getHeader(); ?>
<div class="row col-lg-12 col-xl-11 col-md-12 baseBody" style="overflow:auto;">
    <h1 class="text-left col-lg-3" style="padding: 20px;">Lista de Clipagens</h1>

    <form style="padding: 20px;" class="col-lg-9 col-md-12 col-sm-12 justify-content-end">
        <div class="form-inline justify-content-end">
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
            <select class="form-control col-lg-2 col-xl-3" name="mes" style="height: 50px; border-radius: 0">
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
    <table class="table table-bordered table-hover">
        <caption><?= $lista->num_rows;?> - Clipagens encontradas</caption>
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
                    <a class="badge badge-secondary" onclick="return false" onmousemove="alert('Função Desativada !')" href="/editar?id=<?=$dados['id_clipagem']?>">Editar</a>
                    <a class="badge badge-danger" onclick="return confirm('Tem certeza que deseja remover este item ? ')" href="/deletar?id=<?=$dados['id_clipagem']?>">Remover</a>
                    <?php endif;?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>

    </table>

</div>
<?php FUNCTIONS::getFooter(); ?>
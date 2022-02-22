
    <?php
    require_once "cabecalho.php";
    ?>
     
    <?php
    //Relatorio
    require_once "conexao.php";
    $query_select="";
    $array_pesquisa_rel=['id','nome','email','data','modalidades','sexo','esporte','salario'];
    $flag=0;
    $id=$nome=$email=$data=$modalidades=$sexo=$esportes=$salario="";
      
if ($_POST['coluna_rel'] == 'id') {
    $query_select = "SELECT * FROM formulario WHERE ".$_POST['coluna_rel']." = ".$_POST['valor_pesquisa_rel'];
} else {
    $query_select = "SELECT * FROM formulario WHERE {$_POST['coluna_rel'] } like '%{$_POST['valor_pesquisa_rel']}%'";
}
   
 $relatorio=$con->query($query_select);

   
    ?>
    <main class="shadow-lg py-3 px-md-5 p-3 mb-5 bg-dark text-white rounded border">
    <form action="#" method="post">
    <div class="form-row">
    <div class="form-group col-md-3 text-right">
    <label for="pesquisa_rel">Pesquisa: </label>
    </div>
    <div class="form-group col-md-3">
    <select class="form-control" name="coluna_rel" id="coluna_rel">
    <option value="">--Selecione---</option>
     <?php foreach ($array_pesquisa_rel as $pesquisa) : ?>
    <option value="<?= $pesquisa ?>"><?= $pesquisa ?></option>
    <?php endforeach ?>
    </select>
    </div>
    <div class="form-group col-md-3">
    <input type="text" name="valor_pesquisa_rel" class="form-control" value="" placeholder="Escreva a consulta aqui...">
    </div>
    <div class="form-group col-md-3">
    <input type="submit" name="pesquisar_rel" value="Pesquisar" class="btn btn-primary text-center">
    </div>
    </form>

    <table class='table table-hover table-striped table-bordered'>
    
    <thead>
    <th>ID</th>
    <th>Nome</th>
    <th>email</th>
    <th>Data de Nascimento</th>
    <th>Modalidades</th>
    <th>Esporte</th>
    <th>Salário</th>
    <th>Sexo</th>
  
      <?php foreach($relatorio as $rel){?>
        <tr>
        <td><?php echo $id=$rel['id'];?></td>
        <td><?php echo $nome=$rel['nome'];?></td>
        <td><?php echo $email=$rel['email'];?></td>
        <td><?php echo date("d/m/Y",strtotime($data=$rel['data_nascimento']));?></td>
        <td><?php echo $modalidades=$rel['modalidades'];?></td>
        <td><?php echo $esporte=$rel['esporte'];?></td>
        <td><?php echo "R$ ".$salario=$rel['salario'];?></td>
        <td><?php echo $sexo=$rel['sexo'];?></td>
        </tr>
  
       
      <?php } ?>
       </table>
</main>
</body>
<?php
require_once "rodape.php";
?>
</html>
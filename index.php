
    <?php
    require_once "cabecalho.php";
    ?>
    <main class="shadow-lg py-3 px-md-5 p-3 mb-5 bg-dark text-white rounded border">
        <?php
        $array_pesquisa = ['id', 'nome', 'e-mail'];
        $flag = 0;
        function sexoSelecionado($sex, string $vlr)
        {

            if (!strcmp($sex, $vlr)) {
                return "checked";
            }
        }
        if (isset($_POST['pesquisar'])) {
            require_once "conexao.php";
            $coluna = $_POST['coluna'];
            $valor_pesquisa = $_POST['valor_pesquisa'];

            if (!strcmp($coluna, 'e-mail'))
                $coluna = str_replace('-', '', $coluna);

            $query_select = "";
            if ($coluna == 'id') {
                $query_select = "SELECT * FROM formulario WHERE $coluna = '$valor_pesquisa' LIMIT 1";
            } else {
                $query_select = "SELECT * FROM formulario WHERE $coluna like '%$valor_pesquisa%' LIMIT 1";
            }

            $resultado_query = $con->query($query_select);
            if ($resultado_query->num_rows > 0) {
                foreach ($resultado_query as $res) {
                    $id = $res['id'];
                    $nome = $res['nome'];
                    $email = $res['email'];
                    $data = $res['data_nascimento'];
                    $modalidades = $res['modalidades'];
                    $esporte = $res['esporte'];
                    $salario = $res['salario'];
                    $sexo = $res['sexo'];
                    $flag = 1;
                }
            } else {
                echo "<div class='alert alert-danger' role='alert'>";
                echo "Nenhum dado foi retornado!";
                echo "</div>";
            }
            $con->close();
        }
        $array_erro = [];
        if (isset($_POST['enviar']) || isset($_POST['alterar'])) {
            echo $_POST['id'];

            $nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_STRING);
            if (empty($nome))
                $array_erro['nome'] = "Campo nome não preenchido";
            $sexo = filter_input(INPUT_POST, "sexo", FILTER_SANITIZE_STRING);
            if (empty($sexo))
                $array_erro['sexo'] = "Campo sexo não permitido";
            $esporte = filter_input(INPUT_POST, "esporte", FILTER_SANITIZE_STRING);
            if (empty($esporte)) //ver esporte
                $array_erro['esporte'] = "Campo esporte inválido";
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
            if (empty($email)) {
                $array_erro['email'] = "Campo e-mail não preenchido";
            } else {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                    $array_erro['email'] = "E-mail inválido";
            }
            //$data = "";
            if (!empty($_POST['dtnascimento'])) {
                //$data = date("d/m/Y", strtotime($_POST['dtnascimento']));
                //2020-04-05  YYYY/mm/dd
                $data = $_POST['dtnascimento'];
            } else {
                $array_erro['dtnascimento'] = "Campo data de nascimento não preenchido";
            }
            $intervalo = ["options" => ["min_range" => 1, "max_range" => 5]];
            $modalidades = $_POST['modalidades'];
            if (empty($modalidades)) {
                $array_erro['modalidades'] = "Campo modalidades não preenchido";
            } else {
                if (!filter_var($modalidades, FILTER_VALIDATE_INT, $intervalo))
                    $array_erro['modalidades'] = "Valor inválido";
            }
            $sexo = $_POST['sexo'];
            if (empty($sexo))
                $array_erro[] = "Campo sexo deve ser selecionado!";

            $salario = $_POST['salario'];
            if (empty($salario)) {
                $array_erro[] = "Campo salário deve ser selecionado!";
            } else
                if(filter_var($salario, FILTER_VALIDATE_FLOAT, "[options=>['decimal'=>',']]")){
                //if (filter_var($salario, FILTER_VALIDATE_FLOAT)) {
                    //1200.00
                    $array_erro[] = "Campo salário com formato inválido!!";
                }


            if (empty($array_erro)) {
                require_once "conexao.php"; //feita com sucesso executa o insert
                $query="";
                
                if($_POST['alterar']){
                    $id = $_POST['id'];
                    
                    $query = "UPDATE formulario SET 
                    nome='$nome',email='$email', data_nascimento='$data', modalidades='$modalidades', 
                    esporte='$esporte', salario= '". floatval(str_replace(',', '.', $salario)) ."', sexo='$sexo' WHERE id = '$id'";
                    $word="alterados";
                    
                }else{
                $query = "INSERT INTO formulario VALUES
                (NULL, '$nome', '$email', '$data', '$modalidades', '$esporte', '" . floatval(str_replace(',', '.', $salario)) . "', '$sexo')";
                $word="inseridos";    
            }
                $resultado_inserir = $con->query($query);
                if ($resultado_inserir) {
                    echo "<div class='alert alert-success' role='alert'>";
                    echo "Dados $word com sucesso!";
                    echo "</div>";
                    unset($nome);
                    unset($data);
                    unset($email);
                    unset($modalidades);
                    unset($esporte);
                    unset($salario);
                    unset($sexo);
                } else {
                    echo "<div class='alert alert-danger' role='alert'>";
                    echo "Erro na tentativa de enviar dados ao banco: " . $con->connect_error;
                    echo "</div>";
                }
                $con->close();
            }
        }
        require_once "conexao.php";
        if($_POST['deletar']){
        $id = $_POST['id'];
        $result=" DELETE FROM formulario WHERE id='$id'";
        mysqli_query($con,$result);
        $con->close();
        }
        
        

        foreach ($array_erro as $erro) {
            echo "<div class='alert alert-danger' role='alert'>";
            echo $erro;
            echo "</div>";
        }
        ?>

        <form action="#" method="post">
            
            <div class="form-row">
                <div class="form-group col-md-3 text-right">
                    <label for="pesquisa">Pesquisa: </label>
                </div>
                <div class="form-group col-md-3">  
                    <select class="form-control" name="coluna" id="coluna">
                        <option value="">--Selecione---</option>
                        <?php foreach ($array_pesquisa as $pes) : ?>
                            <option value="<?= $pes ?>"><?= $pes ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <input type="text" name="valor_pesquisa" class="form-control" value="" placeholder="Escreva a consulta aqui...">
                </div>
                <div class="form-group col-md-3">
                    <input type="submit" name="pesquisar" value="Pesquisar" class="btn btn-primary text-center">
                </div>

            </div>
            <div class="form-row">
                <div class="form-group col-md-1">
                    <label for="id" >Id:</label><br>
                    <input type="text" name="id" class="form-control" value="<?= $id ?>" disabled>
                    <input type="hidden" name="id" value="<?= $id?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="nome">Nome:</label><br>
                    <input type="text" name="nome" class="form-control <?= $array_erro['nome'] ? 'is-invalid' : '' ?>" value="<?= $nome ?>" placeholder="Escreva o nome aqui...">
                    <div class="invalid-feedback"><?= $array_erro['nome'] ?></div>
                </div>
                <div class="form-group col-md-4">
                    <label for="email">E-mail:</label><br>
                    <input type="text" name="email" class="form-control" value="<?= $email ?>" size="80" placeholder="Escreva o e-mail aqui..."><br>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="dtnascimento">Data de nascimento:</label><br>
                    <input type="date" name="dtnascimento" class="form-control" value="<?= empty($data) ? "" : date("Y-m-d", strtotime($data)) ?>" size="80"><br>

                </div>
                <div class="form-group col-md-6">
                    <label for="modalidades">Modalidades:</label><br>
                    <input type="number" name="modalidades" class="form-control" value="<?= $modalidades ?>" size="80" placeholder="Digite a quantidade..."><br>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="esporte">Esporte: </label>

                    <select class="form-control" name="esporte" id="esporte">
                        <option selected>--Selecione---</option>
                        <option <?= ($esporte == "futebol") ? 'selected' : '' ?> value="futebol">futebol</option>
                        <option <?= ($esporte == "natação") ? 'selected' : '' ?> value="natação">natação</option>
                        <option <?= ($esporte == "fisioculturismo") ? 'selected' : '' ?>value="fisioculturismo">fisioculturismo</option>
                        <option <?= ($esporte == "boxe") ? 'selected' : '' ?> value="boxe">boxe</option>
                        <option <?= ($esporte == "peteca") ? 'selected' : '' ?>value="peteca">peteca</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="salario">Salário:</label><br>
                    <input type="text" name="salario" class="form-control" value="<?= $salario ?>" placeholder="Escreva o nome aqui..."><br>

                </div>

                <div class="form-group col-md-4">
                    <label for="sexo">Sexo: </label><br>
                    <div class="form-check  form-check-inline">
                        <input class="form-check-input" type="radio" name="sexo" id="exampleRadios1" value="Masculino" <?= sexoSelecionado($sexo, "Masculino") ?> checked>
                        <label class="form-check-label" for="exampleRadios1">
                            Masculino
                        </label>
                    </div>
                    <div class="form-check form-check-inline">

                        <input class="form-check-input" type="radio" name="sexo" id="exampleRadios2" value="Feminino" <?= sexoSelecionado($sexo, "Feminino") ?>>
                        <label class="form-check-label" for="exampleRadios2">
                            Feminnino
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sexo" id="sexo" value="Outro" <?= sexoSelecionado($sexo, "Outro") ?>>
                        <label class="form-check-label" for="exampleRadios3">
                            Outro
                        </label>
                    </div>
                </div>
            </div>
           
            <?php if ($flag == 0) : ?>
                <input type="submit" name="enviar" value="Enviar" class="btn btn-primary text-center">
                <!-- <button name="relatorio" class="btn btn-primary text-center">Relatório</button> -->
                <a href="http://localhost/form/relatorio.php"class="btn btn-primary text-center">Relatório</a> 
            <?php else : ?>
                <input type="submit" name="alterar" value="Alterar" class="btn btn-primary text-center">
                <input type="submit" name="deletar" value="Deletar" class="btn btn-primary text-center">
                <input type="submit" name="cadastrar" value="Cadastrar" class="btn btn-primary text-center">
            <?php endif ?>
            </form>
            
    </main>
    
    <?php 
    require_once "rodape.php"; ?>
    <table class='table table-hover table-striped table-bordered'>  
    
       </table>
</body>


<?php

require_once('vendor/autoload.php');

//Criando um objeto do slim chamado app, para configurar os EndPoints
$app = new \Slim\App();

//Listar todos
$app->get('/precos', function ($request, $response, $args){

    require_once('../modulo/config.php');

    require_once('../controller/controllerPreco.php');

    if($dados = listarPreco()){

        //verifica se houve algum tipo de erro no retorno dos dados na controller
        if(!isset($dados['idErro'])){
          //realiza a conversao do array de dados em formato JSON
                if($dadosJSON = createJSON($dados)){

                    //caso exista dados a serem retornados, informamos o statusCode200 e 
                    //enviamos um JSON com todos os dados encontrados
                   return $response ->withStatus(200)
                                    ->withHeader('Content-Type','application/json')
                                    ->write($dadosJSON);

                }
        }else{
          //Converte para JSON  o erro, pois a controller retorna em array
          $dadosJSON = createJSON($dados);

           return $response ->withStatus(404)
                            ->withHeader('Content-Type','application/json')
                            ->write('{"message": "Dados inválidos",
                                      "Erro": '.$dadosJSON.'}');
        }
      }else{
          //retorna um statusCode que significa que a requisicao foi aceita, porem sem
          //conteudo de retoro
            return $response    ->withStatus(204);
                              
      }
});

//Listar pelo id
$app->get('/precos/{id}', function ($request, $response, $args){

    $id = $args['id'];
  
    require_once('../modulo/config.php');
    require_once('../controller/controllerPreco.php');

    if($dados = buscarPreco($id)) {

      if(!isset($dados['idErro'])) {
        if($dadosJSON = createJSON($dados))

        return $response ->withStatus(200)
                         ->withHeader('Content-Type', 'application/json')
                         ->write($dadosJSON);
      } else {
        $dadosJSON = createJSON($dados);

        return $response ->withStatus(404)
                         ->withHeader('Content-Type', 'application/json')
                         ->write('{"message": "Dados inválidos.",
                                  "Erro": '.$dadosJSON.'}');
      } 
    } else {
        return $response ->withStatus(204);
    }
});

//Inserir
$app->post('/precos', function($request, $response, $args){

    $contentTypeHeader = $request -> getHeaderLine('Content-Type');

    $contentType = explode(";", $contentTypeHeader);

    switch($contentType[0]){
        case 'multipart/form-data':

            $dadosPreco = $request ->getParsedBody();

            require_once('../modulo/config.php');
            require_once('../controller/controllerPreco.php');

            //chama a função da controller para inserir od dados
          $resposta = inserirPreco($dadosPreco);

          if(is_bool($resposta) && $resposta == true){

          
            return $response  ->withStatus(200)
                              ->withHeader('Content-Type','application/json')
                              ->write('{"message": "Registro inserido com sucesso!"}');

          }elseif(is_array($resposta) && $resposta['idErro']){

            //Cria o JSON dos dados do erro
            $dadosJSON = createJSON($resposta);
                   
            return $response  ->withStatus(400)
                              ->withHeader('Content-Type','application/json')
                              ->write('{"message": "houve um problema no processo de inserir",
                                "Erro": '.$dadosJSON.'}');
         
          }
        break;
        case 'application/json':
            $dadosPreco = $request ->getParsedBody();
            return $response  ->withStatus(200)
                              ->withHeader('Content-Type','application/json')
                              ->write('{"message": "formato selecionado foi JSON"}');
            break;
  
          default:
                return $response  ->withStatus(400)
                                ->withHeader('Content-Type','application/json')
                                ->write('{"message": "formato inválido. Os formatos válidos são: form/data e JSON"}');
            break;

         }
});

//Editar
$app->post('/precos/{id}', function($request, $response, $args){

  if(is_numeric($args['id'])){

    $id = $args['id'];

    $contentTypeHeader = $request -> getHeaderLine('Content-Type');

    $contentType = explode(";", $contentTypeHeader);

    switch($contentType[0]) {
      case 'multipart/form-data' :

        require_once('../modulo/config.php');
        require_once('../controller/controllerPreco.php');

        if($dadosBody = buscarPreco($id)) {

          $dadosBody = $request -> getParsedBody();

          $arrayDados = array(
            $dadosBody,
            "idpreco"   => $id);

           $resposta = atualizarPreco($arrayDados);

          if(is_bool($resposta) && $resposta == true) {

            return $response ->withStatus(200)
                             ->withHeader('Content-Type','application/json')
                             ->write('{"message": "Registro atualizado com sucesso!"}');
          } elseif(is_array($resposta) && $resposta['idErro']) {

            $dadosJSON = createJSON($resposta);

            return $response ->withStatus(400)
                             ->withHeader('Content-Type','application/json')
                             ->write('{"message": "Houve um problema no processo de editar",
                              "Erro": '.$dadosJSON.'}');
          }
        } else {
          return $response ->withStatus(404)
                           ->withHeader('Content-Type','application/json')
                           ->write('{"message": "O id informado não existe na base de dados"}');
        }
        break;
      case 'application/json' :

        $dadosBody = $request -> getParsedBody();

        return $response ->withStatus(200)
                         ->withHeader('Content-Type','application/json')
                         ->write('{"message": "formato selecionado foi JSON"}');

        break;

      default:
        return $response ->withStatus(400)
                         ->withHeader('Content-Type','application/json')
                         ->write('{"message": "formato inválido. Os formatos válidos são: form/data e JSON"}');
    }
  } else {

    return $response  ->withStatus(404)
                      ->withHeader('Content-Type','application/json')
                      ->write('{"message": "É obrigatório informar um id com formato válido"}'); 
  }
});

$app->run();

?>
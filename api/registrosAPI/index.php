<?php
    /**
     * $request - recebe dados do corpo da requisição (JSON, FROM/DATA, XML, etc)
     * $response - envia dados de retorno da API
     * $args - permite receber dados de atributos na api
     **/

    require_once('vendor/autoload.php');

    $app = new \Slim\App();

    //Listar todos
    $app->get('/registros', function ($request, $response, $args){
     

      require_once('../modulo/config.php');
      require_once('../controller/controllerRegistro.php');

      if($dados = listarRegistro()){

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

    $app->post('/registros', function($request, $response, $args){
          

      $contentTypeHeader = $request -> getHeaderLine('Content-Type');

      $contentType = explode(";", $contentTypeHeader);

    

      switch($contentType[0]){
          case 'multipart/form-data':

              $dadosCarro = $request ->getParsedBody();

              require_once('../modulo/config.php');
              require_once('../controller/controllerRegistro.php');


              //chama a função da controller para inserir od dados
          $resposta = inserirRegistro($dadosCarro);


          if(is_bool($resposta) && $resposta == true){

          
              return $response  ->withStatus(200)
                                ->withHeader('Content-Type','application/json')
                                ->write('{"message": "Registro inserido com sucesso!"}');

          }elseif(is_array($resposta) && $resposta['idErro']){

              

              //Cria o JSON dos dados do erro
              $dadosJSON = createJSON($resposta);
                  
              return $response  ->withStatus(400)
                                ->withHeader('Content-Type','application/json')
                                ->write('{"message": "Houve um problema no processo de inserir",
                                        "Erro": '.$dadosJSON.'}');
          }
          break;
          case 'application/json':
              $dadosCarro= $request ->getParsedBody();
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

  $app->get('/registros/{id}', function ($request, $response, $args){

    $id = $args['id'];

    require_once('../modulo/config.php');
    require_once('../controller/controllerRegistro.php');

    if($dados = buscarRegistro($id)) {

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

  $app->post('/registros/{id}', function($request, $response, $args){
        

    if(is_numeric($args['id'])){
       
      $id = $args['id'];
  
      $contentTypeHeader = $request -> getHeaderLine('Content-Type');
  
      $contentType = explode(";", $contentTypeHeader);

      switch($contentType[0]) {
        case 'multipart/form-data' :
  
          require_once('../modulo/config.php');
          require_once('../controller/controllerRegistro.php');
         
          if($dadosBody = buscarRegistro($id)) {
      
            $dadosBody = $request -> getParsedBody();
  
            $arrayDados = array(
              $dadosBody,
              "id"   => $id);
  
             $resposta = atualizarRegistro($arrayDados);
  
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

  $app->delete('/registros/{id}', function($request, $response, $args){

    if(is_numeric($args['id'])){
  
      //recebe o id enviado no endpoint através da variável id
      $id = $args['id'];
  
      //import da controller de contatos que fará a busca de dados
      require_once('../modulo/config.php');
      require_once('../controller/ControllerRegistro.php');
  
      //Busca o nome da foto para ser excluida na controller
      if($dados = buscarRegistro($id)){
  
        //cria um array com o id e o nome da foro a ser enviada para a controller excluir o registro
        $arrayDados = array(
          "id"    =>  $id
        );
  
        //chama a função de excluir contatos encaminhando o array com o id e a foto
        $resposta = excluirRegistro($arrayDados);
        
        if(is_bool($resposta) && $resposta == true){
  
          return $response  ->withStatus(200)
                            ->withHeader('Content-Type','application/json')
                            ->write('{"message": "registro excluído com sucesso "}');
  
  
  
        }elseif(is_array($resposta) && isset($resposta['idErro'])){
          //Validação
          if($resposta['idErro'] == 5){
  
            //Retorna um erro que significa que o cliente informou o id inválido
            return $response  ->withStatus(200)
                            ->withHeader('Content-Type','application/json')
                            ->write('{"message": "registro excluído com sucesso, porém houve um erro"}');
             }else{
  
          //Converte para JSON  o erro, pois a controller retorna em array
                $dadosJSON = createJSON($resposta);
  
                 return $response ->withStatus(404)
                                  ->withHeader('Content-Type','application/json')
                                  ->write('{"message": "houve um problema no processo de excluir",
                                            "Erro": '.$dadosJSON.'}');
              }
        }
  
      }else{
        //Retorna um erro que significa que o cliente informou o id inválido
        return $response    ->withStatus(404)
                            ->withHeader('Content-Type','application/json')
                            ->write('{"message": "O id informado não existe na base de dados"}');
  
      }
  
    }else{
          //Retorna um erro que significa que o cliente passou dados errados
          return $response  ->withStatus(404)
                            ->withHeader('Content-Type','application/json')
                            ->write('{"message": "É obrigatório informar um id com formato válido"}'); 
    }
    
  });

    $app->run();

    
?>
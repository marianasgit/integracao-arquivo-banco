<?php 

/***********
 * $request   - recebe dados do corpo da requisiçao (JSON, FORM/DATA, XML, etc)
 * $response  - envia dados de retorno da api
 * $args      - permite receber dados de atributos na api
 */

 // import do arquivo autoload, que fara as instancias do slim;
 require_once('vendor/autoload.php');

 //criando um objeto do slim chamandi app, para configurar os EndPoints;
 $app = new \Slim\app();

  //EndPoint Requisicao para listar todos os contatos
  $app->get('/contatos',function($request,$response,$args){
      
   // Import da controller de contatos que fara a busca 
   require_once('../modulo/config.php');
   require_once('../controller/controllerContatos.php');

   // Solicita os dados para a controller
   if ($dados = listarContato()) 
   {
     // Realiza a conversão do array de dados em formato JSON
     if($dadosJSON = createJSON($dados))
     {
       // Caso existam dados a serem retornados, informamos o status code e enviamos o JSON com os dados encontrados
        return $response  ->withStatus(200)
                          ->withHeader('Content-Type', 'application/json')
                          ->write($dadosJSON); 
     }

     // Retorna o status que significa que a requisição não foi encontrada
   } else {

      return $response  ->withStatus(404)
                        ->withHeader('Content-Type', 'application/json')
                        ->write('{"message: "Item não encontrado!"}'); 
   }
   
  });

 //EndPoint Requisicao para listar somente um contatos/id
  $app->get('/contatos/{id}',function($request,$response,$args){

    // Recebe o id do do registro que deverá ser retornado pela API
    $id = $args['id'];

    // Import da controller de contatos que fara a busca 
    require_once('../modulo/config.php');
    require_once('../controller/controllerContatos.php');

    if ($dados = buscarContato($id)) 
    {
      // Verifica se houve algum erro
      if (!isset($dados['idErro']))
      {
        // Realiza a conversão do array de dados em formato JSON
        if($dadosJSON = createJSON($dados))
        {
          // Caso existam dados a serem retornados, informamos o status code e enviamos o JSON com os dados encontrados
            return $response  ->withStatus(200)
                              ->withHeader('Content-Type', 'application/json')
                              ->write($dadosJSON); 
        }

      } else 
      {
        // Convete para JSON o erro, pois a controller retorna uma array
        $dadosJSON = createJSON($dados);

        return $response  ->withStatus(404)
                          ->withHeader('Content-Type', 'application/json')
                          ->write('{"message": "Dados Inválidos",
                                    "Erro": '.$dadosJSON.'}'); 
      }


    // Retorna o status que significa que a requisição não foi encontrada
    } else {

      return $response  ->withStatus(404)
                        ->withHeader('Content-Type', 'application/json')
                        ->write('{"message: "Item não encontrado!"}'); 
    }
  });

  
  //EndPoint Requisicao para inserir um novo contato
  $app->post('/contatos',function($request,$response,$args){

    // Recebe do header da requisição qual será o content-type
    $contentTypeHeader = $request->getHeaderLine('Content-Type');

    // Cria um array pois dependendo do content-type temos mais informações separadas pelo ";"
    $contentType = explode(";", $contentTypeHeader);

    switch ($contentType[0]) {

      case 'multipart/form-data':

        // Recebe os dados enviados pelo corpo da requisição
        $dadosBody = $request->getParsedBody();
        
        // Recebe uma imagem enviada pelo corpo da requisição 
        $uploadFiles = $request->getUploadedFiles();
        
        // Cria um array com todos os dados que chegaram pela requisição
        $arrayFoto = array (

              "name" => $uploadFiles['foto']->getClientFileName(),
              "type" => $uploadFiles['foto']->getClientMediaType(),
              "size" => $uploadFiles['foto']->getSize(),
          "tmp_name" => $uploadFiles['foto']->file
        );

        // Cria uma chave chamada foto para colocar todos os dados do objeto conforme é gerado no form
        $file = array("foto" => $arrayFoto);

        // Cria um array com todos os dados comuns e do arquivo que será enviado aos servidores
        $arrayDados = array (

          $dadosBody,
          "file" => $file
        );

        // Import da controller
        require_once('../modulo/config.php');
        require_once('../controller/controllerContatos.php');
        
        // Chama a função da controller para inserir os dados
        $resposta = inserirContato($arrayDados);
        
        if (is_bool($resposta) && $resposta == true)
        {
          return $response ->withStatus(201)
                           ->withHeader('Content-Type', 'application/json')
                           ->write('{"message": "Registro inserido com sucesso"}');
  

        } elseif (is_array($resposta) && $resposta['idErro'])
        {

          $dadosJSON = createJSON($resposta);

          return $response ->withStatus(404)
                           -> withHeader('Content-Type', 'application/json')
                           -> write('{"message": "Houve um problema no processo de inserção",
                                      "Erro": '.$dadosJSON.'} ');
        }

      break;
      case 'application/json':
        return $response ->withStatus(200)
                         ->withHeader('Content-Type', 'application/json')
                         ->write('{"message": "Formato selecionado foi JSON"}');

        break;

      default:
        return $response ->withStatus(400)
                         ->withHeader('Content-Type', 'application/json')
                         ->write('{"message": "Formato selecionado inválido"}');
        break;
    }

  });
  
  // Endpoint Requisição para deletar um contato pelo Id
  $app->delete('/contatos/{id}', function($request, $response, $args)
  {

      if(is_numeric($args['id']))
      {
        // Recebe o Id enviado no Endpoint
        $id = $args['id'];
        
        // Import da controller de contatos que fara a busca 
        require_once('../modulo/config.php');
        require_once('../controller/controllerContatos.php');
        
        // Busca o nome da foto para ser excluida na controller
        if($dados = buscarContato($id))
        {
          // Recebe o nome da foto que a controller retornou
          $foto = $dados['foto'];

          // Cria um array para a controller excluir o registro
          $arrayDados = array(
              "id"   => $id,
              "foto" => $foto
          );

          // Chama a função de excluir contato, encaminhando um array
          $resposta = excluirContato($arrayDados);
          
          if(is_bool($resposta) && $resposta == true)
          {
            return $response -> withStatus(200)
                             -> withHeader('Content-Type', 'application/json')
                             -> write('{"message": "Registro excluído com sucesso"} ');

          } elseif(is_array($resposta) && isset($resposta['idErro']))
          {

            // Validação para registro excluido, porem com erro para excluir a imagem 
            if ($resposta['idErro'] == 5) 
            {
              return $response -> withStatus(200)
                               -> withHeader('Content-Type', 'application/json')
                               -> write('{"message": "O registro foi excluído com sucesso, porém não foi possível excluir a imagem"} ');

            } else {

              $dadosJSON = createJSON($resposta);

              return $response -> withStatus(404)
                              -> withHeader('Content-Type', 'application/json')
                              -> write('{"message": "Houve um problema no processo de exclusão",
                                          "Erro": '.$dadosJSON.'} ');
            }

          }

        } else
        {
          return $response -> withStatus(404)
                           -> withHeader('Content-Type', 'application/json')
                           -> write('{"message": "O Id informado não foi encontrado na base de dados"} ');
        }


      } else
      {
        return $response -> withStatus(404)
                         ->withHeader('Content-Type', 'application/json')
                         ->write('{"message": "É obrigatório informar um Id com formato válido (número)"} '); 
      }

  });


  // Executa todos os endpoints
  $app->run();


?> 
<?php

/********************************************************************
 * Objetivo: Arquivo responsável pela manipulação de dados de contato 
 * Obs: Este arquivo fará a ponte entre a View e a Model
 * 
 * Autor: Mariana
 * Data: 04/03/2022
 * Versão: 1.0
 *******************************************************************/

// Import do arquivo de configuração do projeto
require_once(SRC.'modulo/config.php');

//função para receber dados da View e encaminhar para a model (Inserir)
function inserirContato($dadosContato)
{

    $nomeFoto = (string) null;

    //validacao para verificar se o objeto esta vazio
    if (!empty($dadosContato)) {

        // Recebe o objeto imagem que foi encaminhado dentro do array
        $file = $dadosContato['file'];

        //validacao de caixa vazia dos elementos: nome, celular e email pois são obrigatorias no BD
        if (!empty($dadosContato[0]['nome']) && !empty($dadosContato[0]['celular']) && !empty($dadosContato[0]['email']) && !empty($dadosContato[0]['estado'])) 
        {

            // Validação para identificar se chegou um arquivo para upload
            if($file['foto']['name'] != null)
            {
                // Import da função de upload
                require_once(SRC.'modulo/upload.php');

                // Chama a função de upload
                $nomeFoto = uploadFile($file['foto']);

                if (is_array($nomeFoto))
                {
                    // Caso aconteça algum erro no processo de upload, a função irá retornar um array com a possível mensagem de erro.
                    //Esse array será retornado na router e a mensagem será exibida para o usuario
                    return $nomeFoto;

                }
                
            }


            //criacao do array de dados que sera encaminhado para a model para inserir no BD,
            // é importante criar esse array conforme a necessidade de manipulação do BD
            //obs: criar as chaves do array conforme os nomes dos atributos do banco de dados 
            $arrayDados = array(
                "nome"     => $dadosContato[0]['nome'],
                "telefone" => $dadosContato[0]['telefone'],
                "celular"  => $dadosContato[0]['celular'],
                "email"    => $dadosContato[0]['email'],
                "foto"     => $nomeFoto,
                "obs"      => $dadosContato[0]['obs'],
                "idEstado" => $dadosContato[0]['estado']
            );

            //import do arquivo de modelagem para manipular o BD
            require_once(SRC.'model/bd/contato.php');

            //chama a funcao que fara o insert no banco de dados (essa funcao esta na model)
            if (insertContato($arrayDados))
                return true;
            else
                return array('idErro' => 1, 'message' => 'Não foi possível inserir os dados no banco de dados');
        } else
            return array(
                'idErro' => 2,
                'message' => 'Existem campos obrigatórios que não foram preenchidos'
            );
    }
}

//função para buscar um contato atraves do id do registro
function buscarContato($id)
{
    // Validação para verificar se id contem um numero valido
    if ($id != 0 && !empty($id) && is_numeric($id)) 
    {
        // Import do arquivo de contato
        require_once(SRC.'model/bd/contato.php');

        //chama a funcao na model que vai buscar no BD
        $dados = selectByIdContato($id);

        //valida se existem dados para serem devolvidos 
        if  (!empty($dados))
            return $dados;
        else 
            return false;

    } else 
        return array(
            'idErro' => 4,
            'message' => 'Não é possível buscar um registro sem informar um Id válido'
        );

}

//função para receber dados da View e encaminhar para a model (Atualizar)
function atualizarContato($dadosContato, $arrayDados)
{
    $statusUpload = (boolean) false;

    // Recebe o id enviado pelo array dados
    $id = $arrayDados['id'];

    // Recebe a foto enviada pelo arrayDados (nome da foto que está no BD)
    $foto = $arrayDados['foto'];

    // Recebe o objeto de array referente a nova foto que poderá ser enviada ao servidor 
    $file = $arrayDados['file'];

    //validacao para verificar se o objeto esta vazio
    if (!empty($dadosContato)) {

        //validacao de caixa vazia dos elementos: nome, celular e email pois são obrugatorias no BD
        if (!empty($dadosContato['nome']) && !empty($dadosContato['celular']) && !empty($dadosContato['email'])) {

            // Validação para garantir que o id seja valido
            if (!empty($id) && $id != 0 && is_numeric($id))
            {
                // Validacao para identificar se sera enviado ao servidor uma nova foto
                if ($file ['foto']['name'] != null)
                {
                    // Import da função de upload
                    require_once('modulo/upload.php');

                    // Chama a função de upload para enviar a nova foto ao servidor
                    $novaFoto = uploadFile($file['foto']);
                    $statusUpload = true;

                } else 
                {
                    // Permanece a mesma foto no banco de dados
                    $novaFoto = $foto;
                }


                //criacao do array de dados que sera encaminhado para a model para inserir no BD,
                // é importante criar esse array conforme a necessidade de manipulação do BD
                //obs: criar as chaves do array conforme os nomes dos atributos do banco de dados 
                $arrayDados = array(
                    "id"       => $id,
                    "nome"     => $dadosContato['nome'],
                    "telefone" => $dadosContato['telefone'],
                    "celular"  => $dadosContato['celular'],
                    "email"    => $dadosContato['email'],
                    "obs"      => $dadosContato['obs'],
                    "foto"     => $novaFoto,
                    "idestado" => $dadosContato['estado']
                );

                //import do arquivo de modelagem para manipular o BD
                require_once('model/bd/contato.php');

                //chama a funcao que fara o insert no banco de dados (essa funcao esta na model)
                if (updateContato($arrayDados))
                {
                    // Validação para verificar se será necessário apagar a foto antiga para subir uma nova no banco
                    if ($statusUpload)
                    {
                        // Apaga a foto antiga da pasta dos servidores
                        unlink(DIRETORIO_FILE_UPLOAD.$foto);
                    }
                    return true;
                }
                else
                    return array('idErro' => 1, 'message' => 'Não foi possível atualizar os dados no banco de dados');
            } else {
                return array(
                    'idErro' => 4,
                    'message' => 'Não é possível editar um registro sem informar um Id válido'
                );
            }       
        } else
            return array(
                'idErro' => 2,
                'message' => 'Existem campos obrigatórios que não foram preenchidos'
            );
    }
}

//função para realizar a exclusão de um contato 
function excluirContato($arrayDados)
{
    // recebe o id do registro
    $id = $arrayDados['id'];

    // recebeo nome da foto que sera excluida
    $foto = $arrayDados['foto'];


    // Validação para verificar se id contem um numero valido
    if ($id != 0 && !empty($id) && is_numeric($id)) {

        // Import do arquivo de contato
        require_once(SRC.'model/bd/contato.php');
        // Import do arquivo de config
        // require_once(SRC.'modulo/config.php');

        // Chama a função da model e valida se o retorno foi verdadeiro ou falso
        if (deleteContato($id))
        {

            if($foto!=null)
            {
                //Permite apagar a foto fisicamente do diretorio nos servidores
                if(@unlink(SRC.DIRETORIO_FILE_UPLOAD.$foto)) // Unlink() = funçção para apagar um arquivo de um diretório
                {
                    return true;
    
                } else {
                    return array(
                        'idErro'  => 5,
                        'message' => 'o registro do Banco de Dados foi excluído com sucesso, porém a imagem não foi excluída do diretório do servidor!'
                    );
                }
            } else {
                return true;
            }

        }
        else
            return array(
                'idErro'  => 3,
                'message' => 'O banco de dados não pode excluir o registro.'
            );
    } else
        return array(
            'idErro'  => 4,
            'message' => 'Não é possível excluir um registro sem informar um Id válido'
        );
}


//função para solicitar os dados da model e encaminhar a lista de contatos para a view
function listarContato()
{
    //import do arquivo que vai buscar os dados
    require_once(SRC.'model/bd/contato.php');

    //chama a funcao que vai buscar os dados no bd
    $dados = selectAllContatos();

    //
    if (!empty($dados))
        return $dados;
    else
        return false;
}

?>
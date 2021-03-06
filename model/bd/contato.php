<?php

/*********************************************************************
 * Objetivo: arquivo responsavel por manipular os dados dentro do BD(insert, select, update, delete)
 * Autor: Mariana
 * Data: 11/03/2022
 * Versão: 1.0
 *********************************************************************/


//import do arquivo que estabelece a conexao com o BD
require_once('conexaoMysql.php');

$statusResposta = (bool) false;

//funcao para realizar o insert no bando de dados
function insertContato($dadosContato)
{
    //abre a conexao com o BD
    $conexao = conexaoMysql();

    //monta o script para enviar para o BD
    $sql = "insert into tblcontatos
                (nome,
                telefone,
                celular,
                email,
                obs,
                foto,
                idestado)
            values
                ('" . $dadosContato['nome'] . "',
                 '" . $dadosContato['telefone'] . "',
                 '" . $dadosContato['celular'] . "',
                 '" . $dadosContato['email'] . "',
                 '" . $dadosContato['obs'] . "',
                 '" . $dadosContato['foto'] . "',
                 '".$dadosContato['idEstado']."');";

    //executa o script no BD

    //validacao para verificar se o script sql esta correto
    if (mysqli_query($conexao, $sql)) {

        if (mysqli_affected_rows($conexao))

            $statusResposta = true;  // Podemos definir a variável criando em qualquer ligar

    } else

        // Solicita o fechamento da conexão
        fecharConexaoMySql($conexao);

    return $statusResposta;
}
//funcao para realizar o update no banco de dados
function updateContato($dadosContato)
{
     //abre a conexao com o BD
     $conexao = conexaoMysql();

     //monta o script para enviar para o BD
     $sql = "update tblcontatos set
                 nome     = '" . $dadosContato['nome'] . "',
                 telefone = '" . $dadosContato['telefone'] . "',
                 celular  = '" . $dadosContato['celular'] . "',
                 email    = '" . $dadosContato['email'] . "',
                 obs      = '" . $dadosContato['obs'] . "',
                 foto     = '" .$dadosContato['foto'] ."',
                 idEstado = '" .$dadosContato['idestado']."'
            where idcontato = " .$dadosContato['id'];
 
     //executa o script no BD
 
     //validacao para verificar se o script sql esta correto
     if (mysqli_query($conexao, $sql)) {
 
         if (mysqli_affected_rows($conexao))
 
             $statusResposta = true;  // Podemos definir a variável criando em qualquer ligar
 
     } else
 
         // Solicita o fechamento da conexão
         fecharConexaoMySql($conexao);
 
     return $statusResposta;
}

//funcao para excluir no banco de dados
function deleteContato($id)
{
    // Abre a conexao com o BD
    $conexao = conexaoMysql();

    // Script para deletar um registro so BD
    $sql = 'delete from tblcontatos where idcontato = ' . $id; // para numeros inteiros nao e preciso concatenacao com aspas e pontos

    // Valida se o script está correto, sem erro de sitaxe e o executa
    if (mysqli_query($conexao, $sql)) {
        // Valida se o BD teve sucesso na conexao do script
        if (mysqli_affected_rows($conexao))
            $statusResposta = true;
    }

    // Fecha a conexao com o BD
    fecharConexaoMySql($conexao);
    return $statusResposta;
}

//funcao para listar todos os contatos no BD
function selectAllContatos()
{
    //abre a conexao com o banco de dados
    $conexao = conexaoMysql();

    //script para listar todos os dados do banco de dados 
    $sql = 'select * from tblcontatos order by idcontato desc';

    //executa o script sql no BD e guarda o retorno dos dados se houver
    $result = mysqli_query($conexao, $sql);                                  //quando mandados um script para o banco do tipo insert, delete, etc.
    // o script nao tera retorno, ao contrario do select que precisa retornal algo

    //valida se o BD retorna registros
    if ($result) {

        $cont = 0;
        //nesta repeticao estamos convertendo os dados do banco de daos do BD em um array ($rsDados),
        // alem de o proprio while conseguir gerenciar a quantidade de vezes que dveria ser feita a repeticao
        while ($rsDados = mysqli_fetch_assoc($result)) {
            //cria um array com os dados do banco de dados 
            $arrayDados[$cont] = array(
                "id"         =>   $rsDados['idcontato'],
                "nome"       =>   $rsDados['nome'],
                "telefone"   =>   $rsDados['telefone'],
                "celular"    =>   $rsDados['celular'],
                "email"      =>   $rsDados['email'],
                "obs"        =>   $rsDados['obs'],
                "foto"       =>   $rsDados['foto'],
                "idestado"   =>   $rsDados['idestado']
            );
            $cont++;
        }

        // Solicita o fechamento da conexão com o BD. Ação obrigatória (abrir e fechar) 
        fecharConexaoMySql($conexao);

        if(isset($arrayDados))
            return $arrayDados;
        else    
            return false;    
    }
}

// funcao para buscar um contato no banco de dados atraves do id do registro
function selectByIdContato($id)
{
    //abre a conexao com o banco de dados
    $conexao = conexaoMysql();

    //script para listar todos os dados do banco de dados 
    $sql = 'select * from tblcontatos where idcontato = ' . $id;

    //executa o script sql no BD e guarda o retorno dos dados se houver
    $result = mysqli_query($conexao, $sql);                                  //quando mandados um script para o banco do tipo insert, delete, etc.
    // o script nao tera retorno, ao contrario do select que precisa retornal algo

    //valida se o BD retorna registros
    if ($result) {

        //nesta repeticao estamos convertendo os dados do banco de daos do BD em um array ($rsDados),
        // alem de o proprio while conseguir gerenciar a quantidade de vezes que dveria ser feita a repeticao
        if ($rsDados = mysqli_fetch_assoc($result)) {

            //cria um array com os dados do banco de dados 
            $arrayDados      =    array(
                "id"         =>   $rsDados['idcontato'],
                "nome"       =>   $rsDados['nome'],
                "telefone"   =>   $rsDados['telefone'],
                "celular"    =>   $rsDados['celular'],
                "email"      =>   $rsDados['email'],
                "obs"        =>   $rsDados['obs'],
                "foto"       =>   $rsDados['foto'],
                "idestado"   =>   $rsDados['idestado']
            );
        }

    }    
        // Solicita o fechamento da conexão com o BD. Ação obrigatória (abrir e fechar) 
        fecharConexaoMySql($conexao);

        if (isset($arrayDados)) 
        {
            return $arrayDados;
            
        } else 
        {
            return false;
        }

    
}
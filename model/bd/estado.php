<?php

/****************************************************************************************************
 * Objetivo: arquivo responsavel por manipular os dados dentro do BD(insert, select, update, delete)
 * Autor: Mariana
 * Data: 10/05/2022
 * Versão: 1.0
 *****************************************************************************************************/

//import do arquivo que estabelece a conexao com o BD
require_once('conexaoMysql.php');

function selectAllEstados()
{
    //abre a conexao com o banco de dados
    $conexao = conexaoMysql();

    //script para listar todos os dados do banco de dados 
    $sql = 'select * from tblestados order by nome';

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
                "idestado"   =>   $rsDados['idEstado'],
                "nome"       =>   $rsDados['nome'],
                "sigla"      =>   $rsDados['sigla']
            );
            $cont++;
        }

        // Solicita o fechamento da conexão com o BD. Ação obrigatória (abrir e fechar) 
        fecharConexaoMySql($conexao);

        return $arrayDados;
    }
}

?>
<?php
    /**********************************************************************
     * Objetivo: Arquivo para criar a conexão com o banco de dados Mysql.
     * Autora: Florbela
     * Data: 01/06/2022
     * Versão: 1.0
     **********************************************************************/

    //Constantes para estabelecer a conexão com o BD (local, usuário, senha e database)
    const SERVER = 'localhost';
    const USER = 'root';
    const PASSWORD = 'bcd127';
    const DATABASE = 'dbestacionamento';

    //Abre a conexão com o banco de dados Mysql
    function conexaoMysql() {
        $conexao = array();

        //Se a conexão for estabelecida com o BD, iremos ter um array de dados sobre a conexão
        $conexao = mysqli_connect(SERVER, USER, PASSWORD, DATABASE);

        //Validação para verificar se a conexão foi realizada com sucesso
        if($conexao)
            return $conexao;
        else
            return false;
    }

    //Fecha a conexão com o BD mySQL
    function fecharConexaoMysql($conexao) {
        mysqli_close($conexao);
    }
?>
<?php
    /**************************************
     * Objetivo: arquivo responsável por manipular os dados dentro do BD
     *    (insert, update, select e delete)
     * Autor: Samea e Florbela
     * Data: 01/06/2022
     * Versão: 1.0
    ************************************/

    require_once('conexaoMysql.php');

    function insertPreco($dadosPreco){

        
        $statusResposta = (boolean) false;

        $conexao = conexaoMysql();

        $sql = "insert into tblpreco
                (valorprimeirahora,
                 valordemaishoras
                 )
        values 
                ('".$dadosPreco['valorprimeirahora']."',
                 '".$dadosPreco['valordemaishoras']."')";

        if(mysqli_query($conexao, $sql)){

            if(mysqli_affected_rows($conexao))
                $statusResposta = true;
            else
                $statusResposta = false;
        }else{
            $statusResposta = false;
        }

        fecharConexaoMysql($conexao);
            return $statusResposta;

    }

    function updatePreco($dadosPreco){

        $statusResposta = (boolean) false;

        $conexao = conexaoMysql();

        $sql = "update tblpreco set
                valorprimeirahora = '".$dadosPreco['valorprimeirahora']."',
                valordemaishoras  = '".$dadosPreco['valordemaishoras']."'
        
            where idpreco  = ".$dadosPreco['id'];

        if(mysqli_query($conexao, $sql)){
          

            if(mysqli_affected_rows($conexao)){

              
                $statusResposta = true;
            }
        }
        
        fecharConexaoMysql($conexao);
        return $statusResposta;

    }

    function selectAllPrecos(){

        $conexao = conexaoMysql();

        $sql ="select * from tblpreco order by idpreco desc";

        $result = mysqli_query($conexao, $sql);

        if($result){

            $cont = 0;

            while($dados = mysqli_fetch_assoc($result)){

                $arrayDados[$cont]= array(
                    "idpreco"              => $dados['idpreco'],
                    "valorprimeirahora"    => $dados['valorprimeirahora'],
                    "valordemaishoras"     => $dados['valordemaishoras']
                );

                $cont++;

            }

            fecharConexaoMysql($conexao);

            if(isset($arrayDados))
                return $arrayDados;
            else
                return false;

            }



    }

    function selectByIdPreco($id){

        $conexao = conexaoMysql();

        $sql ="select * from tblpreco where idpreco = ".$id;

        $result = mysqli_query($conexao, $sql);

        if($result){
            if($dados = mysqli_fetch_assoc($result)){

                $arrayDados = array(
                    "idpreco"            => $dados['idpreco'],
                    "valorprimeirahora"  => $dados['valorprimeirahora'],
                    "valordemaishoras"   => $dados['valordemaishoras']  
                );

                fecharConexaoMysql($conexao);

                if(isset($arrayDados))
                    return $arrayDados;
                else
                    return false;
                }


    }
}

?>
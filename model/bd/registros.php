<?php
    /*********************************************************************
     * Objetivo: arquivo responsável por manipular os dados dentro do BD
     *    (insert, update, select e delete)
     * Autor: Samea e Florbela
     * Data: 01/06/2022
     * Versão: 1.0
    **********************************************************************/

    require_once('conexaoMysql.php');

    function insertRegistro($dadosRegistro){

        $statusResposta = (boolean) false;

        $conexao = conexaoMysql();

        $sql = "insert into tblregistro
                (cliente,
                 modelo,
                 cor,
                 placa,
                 horaentrada,
                 horasaida,
                 valortotal)
        values 
                ('".$dadosRegistro['cliente']."',
                 '".$dadosRegistro['modelo']."',
                 '".$dadosRegistro['cor']."',
                 '".$dadosRegistro['placa']."',
                 '".$dadosRegistro['horaentrada']."',
                 '".$dadosRegistro['horasaida']."',
                 '".$dadosRegistro['valortotal']."')";

        if(mysqli_query($conexao, $sql)){

            if(mysqli_affected_rows($conexao))
                $statusResposta = true;
            else
                $statusResposta = false;
        }

        fecharConexaoMysql($conexao);
            return $statusResposta;

    }

    function deleteRegistro($id){

        $statusResposta = (boolean) false;

        $conexao = conexaoMysql();

        $sql = "delete from tblregistro where idregistro =".$id;

        if(mysqli_query($conexao, $sql)){

            if(mysqli_affected_rows($conexao))
                $statusResposta = true;
        }

        fecharConexaoMysql($conexao);
        return $statusResposta;


    }

    function selectAllRegistro(){

         $conexao = conexaoMysql();

        $sql ="select * from tblregistro order by idregistro desc"; 

        $result = mysqli_query($conexao, $sql);
        
        if($result){
             $cont = 0;

            while($dados = mysqli_fetch_assoc($result)){

                $arrayDados[$cont]= array(
                    "id"              => $dados['idregistro'],
                    "cliente"         => $dados['cliente'],
                    "modelo"          => $dados['modelo'],
                    "cor"             => $dados['cor'],
                    "placa"           => $dados['placa'],
                    "horaentrada"     => $dados['horaentrada'],
                    "horasaida"       => $dados['horasaida'],
                    "valortotal"      => $dados['valortotal']
                );

                $cont++;
             }
          

            fecharConexaoMysql($conexao);

            if(isset($arrayDados)){
               return $arrayDados;

            }else
                return false;

            }

 }

    function updateRegistro($dadosRegistro){

        $statusResposta = (boolean) false;

        $conexao = conexaoMysql();

        $sql = "update tblregistro set
            cliente       ='".$dadosRegistro['cliente']."',
            modelo        = '".$dadosRegistro['modelo']."',
            cor           = '".$dadosRegistro['cor']."',
            placa         = '".$dadosRegistro['placa']."',
            horaentrada   = '".$dadosRegistro['horaentrada']."',
            horasaida     = '".$dadosRegistro['horasaida']."',
            valortotal    = '".$dadosRegistro['valortotal']."'
        
            where idregistro  = ".$dadosRegistro['id'];

        if(mysqli_query($conexao, $sql)){

            if(mysqli_affected_rows($conexao)){

                $statusResposta = true;
            }else{
                $statusResposta = true;
            }
        }
        
        fecharConexaoMysql($conexao);
        return $statusResposta;

    }

    function selectByIdRegistro($id){

        $conexao = conexaoMysql();

        $sql ="select * from tblregistro where idregistro = ".$id;

        $result = mysqli_query($conexao, $sql);

        if($result){
            if($dados = mysqli_fetch_assoc($result)){

                $arrayDados = array(
                    "id"      => $dados['idregistro'],
                    "cliente" =>$dados['cliente'],
                    "modelo"  => $dados['modelo'],
                    "cor"     => $dados['cor'],
                    "placa"   => $dados['placa'],
                    "horaentrada" => $dados['horaentrada'],
                    "horasaida"   => $dados['horasaida'],
                    "valortoal"   => $dados['valortotal'] 
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
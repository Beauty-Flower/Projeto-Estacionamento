<?php
    /****************************************************************************
     * Objetivo: Arquivo responsável pela manipulação de dados de carro.
     * Autora: Florbela e Samea
     * Data: 02/06/2022
     * Versão: 1.0
     ****************************************************************************/

    //Função para receber dados da Wiew e encaminhar para a Model (inserir)
    function inserirRegistro($dadosRegistro) {

        //Validação para verificar se o objeto está vazio
        if(!empty($dadosCarro)) {

            //Validação de caixa vazia dos elementos que são obrigatórios no BD
            if(!empty($dadosRegistro['cliente']) && !empty($dadosRegistro['modelo']) && !empty($dadosRegistro['placa']) && !empty($dadosRegistro['horaentrada']) && !empty($dadosRegistro['horasaida']) && !empty($dadosRegistro['valortotal'])){

                //Criação de um array de dados que será encaminhado à model para inserir no BD
                $arrayDados = array(
                    "cliente"     => $dadosRegistro['cliente'],
                    "modelo"      => $dadosRegistro['modelo'],
                    "cor"         => $dadosRegistro['cor'],
                    "placa"       => $dadosRegistro['placa'],
                    "horaentrada" => $dadosRegistro['horaentrada'],
                    "horasaida"   => $dadosRegistro['horasaida'],
                    "valortotal"  => $dadosRegistro['valortotal']
                );

                //Import do arquivo de modelagem para manipular o BD
                require_once(SRC.'model/bd/registros.php');

                //Chamando a função da model que fará o insert no BD
                if(insertRegistro($arrayDados))
                
                    return true;
                else {
                    return array('idErro'   => 1,
                                'message'   => 'Não foi possível inserir os dados no Banco de Dados.'
                    );
                }

            } else {
                return array('idErro'   => 2,
                            'message'   => 'Existem campos obrigatórios que não foram preenchidos.'
                );
            }
        }
    }

    //Função para receber dados da Wiew e encaminhar para a Model (atualizar)
    function atualizarRegistro($dadosRegistro) {

       //Recebe o id enviado pelo arrayDados
       $id = $dadosRegistro['id'];

       //Validação para verificar se o objeto está vazio
       if(!empty($dadosRegistro)){
           
           //Validação de caixa vazia dos elementos que são obrigatórios no BD
           if(!empty($dadosRegistro[0]['cliente']) && !empty($dadosRegistro[0]['modelo']) && !empty($dadosRegistro[0]['placa']) && !empty($dadosRegistro[0]['horaentrada']) && !empty($dadosRegistro[0]['horasaida']) && !empty($dadosRegistro[0]['valortotal'])) {

               //Validação para garantir que o id seja válido
               if(!empty($id) && $id != 0 && is_numeric($id)) {

                   //Criação de um array de dados que será encaminhado à model para inserir no BD
                   $arrayDados = array(
                       "id"             => $id, 
                       "cliente"        => $dadosRegistro[0]['cliente'],
                       "modelo"         => $dadosRegistro[0]['modelo'],
                       "cor"            => $dadosRegistro[0]['cor'],
                       "placa"          => $dadosRegistro[0]['placa'],
                       "horaentrada"    => $dadosRegistro[0]['horaentrada'],
                       "horasaida"      => $dadosRegistro[0]['horasaida'],
                       "valortotal"     => $dadosRegistro[0]['valortotal']
                   );

                   //Import do arquivo de modelagem para manipular o BD
                   require_once(SRC.'model/bd/registros.php');

                   //Chamando a função da model que fará o update no BD
                   if(updateRegistro($arrayDados)) {
                       return true;
                   } else {
                       return array('idErro' => 1, 
                                   'message' => 'Não foi possível atualizar os dados no Banco de Dados.'
                       );
                   }
               } else {
                   return array('idErro' => 3, 
                               "message" => "Não é possível editar um registro sem informar um id válido."        
                   );
               }
           } else {
               return array('idErro'   => 2,
                           'message'   => 'Existem campos obrigatórios que não foram preenchidos.'
               );
           }
       }
    }

    //Função para realizar a exclusão de um carro
    function excluirRegistro($arrayDados) {

        //Recebe o id do registro que será excluído
        $id = $arrayDados['id'];

        //Validação para verificar se o id é um númedro válido
        if($id != 0 && !empty($id) && is_numeric($id)) {

            //Import do arquivo de modelagem para manipular o BD
            require_once(SRC.'model/bd/registros.php');

            //Chama a função da model que fará a exclusão no BD
            if(deleteRegistro($id)) {
                return true;
            } else {
                return array('idErro' => 1, 
                            "message" => "Não foi possível excluir o registro."
                );
            }
        } else {
            return array('idErro' => 3, 
                        "message" => "Não é possível excluir um registro sem informar um id válido."        
            );
        }
    }

    //Função para solicitar os dados da model e encaminhar a lista de carros para a View
    function listarRegistro() {
    //Import do arquivo que vai buscar os dados
     require_once(SRC.'model/bd/registros.php');

    //Chama a função que vai listar os dados no BD
     $dados = selectAllRegistro();

     //Valida se existe dados para serem devolvidos
     if(!empty($dados))
         return $dados;
     else
         return false;
}

    

    //Função para buscar um carro através do id do registro
    function buscarRegistro($id) {

          //Validação para verificar se o id é um número válido
          if($id != 0 && !empty($id) && is_numeric($id)) {

            //Import do arquivo de preco - model
            require_once(SRC.'model/bd/registros.php');

            //Chama a função na model que vai buscar no BD
            $dados = selectByIdRegistro($id);

            //Valida se existe dados para serem devolvidos
            if(!empty($dados))
                return $dados;
            else
                return false;

        } else {
            return array('idErro' => 3, 
                        "message" => "Não é possível buscar um registro sem informar um id válido.");
        }
    }
?>
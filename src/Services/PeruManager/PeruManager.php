<?php

require_once __DIR__ . '/SunatRUC.php';
require_once __DIR__ . '/BuscaRUC.php';
require_once __DIR__ . '/EsaludDNI.php';

class PeruManager
{
    static public function queryDNI($dni){
        $esalud = new EsaludDNI();
        $response = $esalud->Query($dni);
        if (!$response->success){
            return $response;
        }

        $res = new Result();
        $res->success = true;
        $res->result = [
            'socialReason' => $response->result['socialReason'],
            'documentNumber' => $response->result['documentNumber'],
            'identityDocumentCode' => '1',
            'commercialReason' => '',
            'fiscalAddress' => '',
            'email' => '',
            'telephone' => '',
            'state' => '',
            'condition' => '',
        ];
        return $res;
    }

    static public function queryRUC($ruc){
//        $buscaRUC = new BuscaRUC();
//        $result = $buscaRUC->Query($ruc);
//        if ($result->success){
//            return $result;
//        }

        $sunatRUC = new SunatRUC();
        $response = $sunatRUC->Query($ruc);
        if (!$response->success){
            return $response;
        }

        $res = new Result();
        $res->success = true;
        $res->result = [
            'socialReason' => $response->result['socialReason'],
            'documentNumber' => $response->result['documentNumber'],
            'identityDocumentCode' => '6',
            'commercialReason' => '',
            'fiscalAddress' => $response->result['address'],
            'email' => '',
            'telephone' => '',
            'state' => $response->result['state'],
            'condition' => $response->result['condition'],
        ];
        return $res;
    }

    static public function queryDocument($documentNumber){
        $data = PeruManager::queryRUC($documentNumber);
        if (!$data->success){
            $data = PeruManager::queryDNI($documentNumber);
        }
        return $data;
    }
}
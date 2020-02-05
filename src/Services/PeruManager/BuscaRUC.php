<?php


class BuscaRUC
{
    private $curl;

    public function __construct()
    {
        $this->curl = curl_init();
    }

    public function Query(string $ruc)
    {
        $res = new Result();
        try{
            if(strlen( $ruc ) == 8 && is_numeric($ruc) )
            {
                $ruc = $this->DniToRuc($ruc);
            }
            if(strlen($ruc)!=11 )
            {
                throw new Exception('EL RUC debe contener 11 dígitos');
            }
            $options = [
                CURLOPT_URL => "http://buscaruc.com/consultas/api.php?ruc=" . $ruc,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
            ];
            curl_setopt_array($this->curl, $options);

            $response = curl_exec($this->curl);
            $err = curl_error($this->curl);

            if(!$response || $err){
                $res->message = "No se encontraron datos suficientes";
                return $res;
            }
            $data = json_decode($response,true);

            $res->success = true;
            $res->result = $data;
        }catch (Exception $e){
            $res->message = $e->getMessage();
        }
        return $res;
    }

    private function DniToRuc($dni)
    {
        if ($dni!="" || strlen($dni) == 8)
        {
            $sum = 0;
            $hash = array(5, 4, 3, 2, 7, 6, 5, 4, 3, 2);
            $sum = 5; // 10[NRO_DNI]X (1*5)+(0*4)
            for( $i=2; $i<10; $i++ )
            {
                $sum += ( $dni[$i-2] * $hash[$i] ); //3,2,7,6,5,4,3,2
            }
            $whole = (int)($sum/11);

            $digit = 11 - ( $sum - $whole*11);

            if ($digit == 10)
            {
                $digit = 0;
            }
            else if ($digit == 11)
            {
                $digit = 1;
            }
            return "10".$dni.$digit;
        }
        return false;
    }
}

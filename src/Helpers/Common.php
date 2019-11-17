<?php

class Result extends stdClass
{
    public $success;
    public $message;
    public $result;

    function __construct()
    {
        $this->success = false;
        $this->message = '';
        $this->result = null;
    }
}

function ArrayFindIndexByColumn(array $data, string $column, $value)
{
    $index = array_search($value, array_column($data, $column));
    if ($index === 0) {
        $index = true;
    }
    return $index;
}

function Authorization(PDO $connection, string $module, string $action, string $redirect = '', string $errorMessage = '')
{
    $sql = 'SELECT count(*) as count FROM user_role_authorization as ur
                        INNER JOIN app_authorization app ON ur.app_authorization_id = app.app_authorization_id
                        WHERE ur.user_role_id = :user_role_id AND app.module = :module AND app.action = :action
                        GROUP BY app.module';
    $stmt = $connection->prepare($sql);
    $stmt->execute([
        ':user_role_id' => $_SESSION[SESS_DATA]['user_role_id'] ?? 0,
        ':module' => $module,
        ':action' => $action,
    ]);

    $data = $stmt->fetch();

    $res = new Result();
    if (!((int) $data['count']) > 0) {
        if (strtolower($_SERVER['HTTP_ACCEPT']) == 'application/json') {
            $res->success = false;
            $res->message = 'Lo sentimos, no estás autorizado para realizar esta operación';
            echo json_encode($res);
            die();
        } else {
            header('Location: ' . URL_PATH . '/403?message' . $errorMessage);
        }
    }
    $res->success = true;
    return $res;
}

function ErrorLog($severity, $message, $filename, $lineno){

}

function ValidateDNI($value)
{
    if (is_numeric($value)) {
        return strlen($value) == 8;
    }

    return false;
}

function CharAt($string, $index){
    if($index < strlen($string)){
        return substr($string, $index, 1);
    }
    else{
        return -1;
    }
}

function ValidateRUC(string $value) : bool
{
    if (strlen($value) !== 11){
        return false;
    }

    $value = trim((string)$value);
    $sum = 0;
    $x = 6;
    for ($i = 0; $i < strlen($value) - 1; $i++){
        if ( $i == 4 ) {
            $x = 8;
        }
        $digit = CharAt($value, $i) - '0';
        $x--;
        if ( $i==0 ) {
            $sum += ($digit*$x);
        } else {
            $sum += ($digit*$x);
        }
    }
    $diff = $sum % 11;
    $diff = 11 - $diff;
    if ($diff >= 10) {
        $diff = $diff - 10;
    }
    if ($diff == CharAt($value, strlen($value) - 1 ) - '0') {
        return true;
    }
    return false;
}

function ValidateIdentityDocumentNumber(string $number, string $type) {
    $res = new Result();
    switch ($type){
        case '-':
            if (preg_match('/^[\w-]{1,15}+$/',$number)){
                $res->success = true;
            }else{
                $res->message = 'El dato ingresado como numero de documento de identidad del receptor no cumple con el formato establecido';
            }
            break;
        case '6':
            if (ValidateRUC($number)){
                $res->success = true;
            }else{
                $res->message = 'Número de RUC invalido';
            }
            break;
        case '1':
            if (ValidateDNI($number)){
                $res->success = true;
            }else{
                $res->message = 'Número de DNI invalido';
            }
            break;
        case '0':
        case '4':
        case '7':
        case 'A':
        case 'B':
        case 'C':
        case 'D':
        case 'E':
            if (preg_match('/^[\w]{1,15}+$/',$number)){
                $res->success = true;
            }else{
                $res->message = 'El dato ingresado como numero de documento de identidad del receptor no cumple con el formato establecido';
            }
            break;
        default:
            $res->message = 'Este tipo de documento no fue reconocido por el sistema';
            break;
    }
    return $res;
}

Class ErrorCollector {
    private $error = [];
    private $message = "";
    private $separator = "|";

    private function getMessage(): string
    {
        $message = "";
        foreach ($this->error ?? [] as $key => $row){
            $message .= array_reduce(
                $row['messages'] ?? [],
                function ($a,$b){
                    return $a . $b . $this->separator;
                },
                ''
            );
        }
        foreach ($this->error ?? [] as $key => $row){
            if(isset($row['children'])){
                foreach ($row['children'] ?? [] as $item){
                    foreach ($item ?? [] as $mm) {
                        $message .= array_reduce(
                            $mm['messages'] ?? [],
                            function ($a,$b){
                                return $a . $b . $this->separator;
                            },
                            ''
                        );
                    }
                }
            }
        }
        $this->message = trim(trim($message,$this->separator));
        return $this->message;
    }
    public function getResult()
    {
        $res = new Result();
        $res->message = $this->getMessage();
        $res->error = $this->error;
        if (!(count($this->error)>0)){
            $res->success = true;
        }
        return $res;
    }
    public function setSeparator(string $separator): void
    {
        $this->separator = $separator;
    }
    public function addError(string $key, string $message){
        if (isset($this->error[$key]['messages'])){
            $oldItem = $this->error[$key]['messages'];
            array_push($oldItem,$message);
            $this->error[$key]['messages'] = $oldItem;
        }else{
            $this->error[$key]['messages'] = [$message];
        }
    }
    public function addErrorRowChildren(string $parentName, $row, string $key, string $message){
        $item = $this->error[$parentName]['children'] ?? [];
        $row = '' . $row;
        $message = $parentName . ' - ' . $message;

        if (empty($item)){
            $this -> error [$parentName]['children'] = [
                $row => [
                    $key => [
                        'messages' => [$message]
                    ]
                ]
            ];
            return;
        }

        if(isset($item[$row])) {
            if (isset($item[$row][$key]['messages'])){
                $oldItem = $item[$row][$key]['messages'];
                array_push($oldItem,$message);
                $item[$row][$key]['messages'] = $oldItem;
            }else{
                $item[$row][$key]['messages'] = [ $message ];
            }
        } else {
            $item[$row][$key]['messages'] = [ $message ];
        }

        $this->error[$parentName]['children'] = $item;
    }
}
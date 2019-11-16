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
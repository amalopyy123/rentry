<?php
require 'RentryItem.php';

//If you want to receive application/json post data in your script you can not use $_POST. $_POST does only handle form data.
// Get the JSON contents
$json = file_get_contents('php://input');
// decode the json data
$dataJson = json_decode($json, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo formatErrorResponse('incorrect data format');
    die();
}

// 获取参数
$action = isset($dataJson['action']) ? $dataJson['action'] : null;
$path = isset($dataJson['path']) ? $dataJson['path'] : null;
$password = isset($dataJson['password']) ? $dataJson['password'] : null;
$content = isset($dataJson['content']) ? $dataJson['content'] : null;


//// 成功返回示例:
//insert,update,delete
//{"status":"success","message":"Delete successful.","data":null}
//get
//{"status":"success","message":"Delete successful.","data":"Content "}
//// 失败返回示例:
//{"status":"failed","message":"path not found."}
// 错误响应格式

function formatErrorResponse($message)
{
    return json_encode(['status' => 'failed', 'message' => $message]);
}

// 创建 RentryItem 实例
$rentryItem = new RentryItem();
// 根据请求的 action 执行不同的操作
switch ($action) {
    case 'insert':

        if ($path && $password && $content) {
            // 调用插入方法
            echo $rentryItem->insert($path, $password, $content);
        } else {
            echo formatErrorResponse('Missing required parameters.');
        }
        break;

    case 'get':
        if ($path) {
            // 调用查询方法
            echo $rentryItem->get($path);
        } else {
            echo formatErrorResponse('Missing path parameter.');
        }
        break;

    case 'update':
        if ($path && $password && $content) {
            // 调用更新方法
            echo $rentryItem->update($path, $password, $content);
        } else {
            echo formatErrorResponse('Missing required parameters.');
        }
        break;

    case 'delete':
        if ($path && $password) {
            // 调用删除方法
            echo $rentryItem->delete($path, $password);
        } else {
            echo formatErrorResponse('Missing required parameters.');
        }
        break;

    default:
        echo formatErrorResponse('Invalid action parameter.');
        break;
}
?>
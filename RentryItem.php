<?php

class RentryItem
{
    // 数据库连接配置
    private $host = 'localhost';  // 数据库主机
    private $dbName = 'rentry';  // 数据库名称
    private $dbUser = 'root';  // 数据库用户名
    private $dbPassword = '';  // 数据库密码
    private $charset = 'utf8mb4';  // 字符集
    private $pdo = null;

    public function __construct()
    {
        $this->connectDatabase();
    }

    // 连接数据库
    private function connectDatabase()
    {
        if ($this->pdo === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset={$this->charset}";
                $this->pdo = new PDO($dsn, $this->dbUser, $this->dbPassword);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                $this->errorResponse('Database connection failed: ' . $e->getMessage());
            }
        }
    }

    // 增：插入新的记录
    public function insert($path, $password, $content)
    {
        try {
            if (empty($path)) {
                return $this->errorResponse('path can not be empty');
            }
            if (empty($password)) {
                return $this->errorResponse('password can not be empty');
            }
            // 检查path是否已存在
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM rentry_items WHERE path = :path');
            $stmt->execute(['path' => $path]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                return $this->errorResponse('path already exists.');
            }

            // 插入记录
            $stmt = $this->pdo->prepare(
                'INSERT INTO rentry_items (path, password, content, updated_at) 
                 VALUES (:path, :password, :content, NOW())'
            );
            $stmt->execute([
                'path' => $path,
                'password' => $password,
                'content' => $content
            ]);

            return $this->successResponse('Insert successful.');
        } catch (PDOException $e) {
            return $this->errorResponse('Insert failed: ' . $e->getMessage());
        }
    }

    // 查：根据path获取记录
    public function get($path)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM rentry_items WHERE path = :path');
            $stmt->execute(['path' => $path]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return $this->errorResponse('path not found.');
            }

            return $this->successResponse('Fetch successful.', $result);
        } catch (PDOException $e) {
            return $this->errorResponse('Fetch failed: ' . $e->getMessage());
        }
    }

    // 改：更新记录
    public function update($path, $password, $content)
    {
        try {

            // 检查密码是否正确
            $stmt = $this->pdo->prepare('SELECT password FROM rentry_items WHERE path = :path');
            $stmt->execute(['path' => $path]);
            $existingPassword = $stmt->fetchColumn();

            if (!$existingPassword) {
                return $this->errorResponse('path not found.');
            }

            if ($existingPassword !== $password) {
                return $this->errorResponse('Password mismatch.');
            }

            // 更新记录
            $stmt = $this->pdo->prepare(
                'UPDATE rentry_items SET content = :content, updated_at = NOW() WHERE path = :path'
            );
            $stmt->execute([
                'path' => $path,
                'content' => $content
            ]);

            return $this->successResponse('Update successful.');
        } catch (PDOException $e) {
            return $this->errorResponse('Update failed: ' . $e->getMessage());
        }
    }

    // 删：删除记录
    public function delete($path, $password)
    {
        try {
            // 检查密码是否正确
            $stmt = $this->pdo->prepare('SELECT password FROM rentry_items WHERE path = :path');
            $stmt->execute(['path' => $path]);
            $existingPassword = $stmt->fetchColumn();

            if (!$existingPassword) {
                return $this->errorResponse('path not found.');
            }

            if ($existingPassword !== $password) {
                return $this->errorResponse('Password mismatch.');
            }

            // 删除记录
            $stmt = $this->pdo->prepare('DELETE FROM rentry_items WHERE path = :path');
            $stmt->execute(['path' => $path]);

            return $this->successResponse('Delete successful.');
        } catch (PDOException $e) {
            return $this->errorResponse('Delete failed: ' . $e->getMessage());
        }
    }

    // 成功响应格式
    private function successResponse($message, $data = null)
    {
        return json_encode([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ]);
    }

    // 错误响应格式
    private function errorResponse($message)
    {
        return json_encode([
            'status' => 'failed',
            'message' => $message
        ]);
    }
}

// 使用示例
//$rentry = new RentryItem();

// 新增
//echo $rentry->insert('https://example.com', 'password123', 'This is the content.');

// 查询
//echo $rentry->get('https://example.com');

// 更新
//echo $rentry->update('https://example.com', 'password123', 'Updated content111.');

// 查询
//echo $rentry->get('https://example.com');

// 删除
//echo $rentry->delete('https://example.com', 'password123');
?>
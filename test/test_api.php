<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User API 异步路由测试</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .test-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        h2 {
            color: #555;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .input-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #666;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #45a049;
        }
        .result {
            margin-top: 15px;
            padding: 10px;
            border-radius: 4px;
            font-weight: bold;
        }
        .result.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .result.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .result.info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User API 异步路由测试</h1>
        
        <!-- 测试用户名检查 -->
        <div class="test-section">
            <h2>测试用户名检查 (/api/user/check_name/)</h2>
            <div class="input-group">
                <label for="username">输入用户名:</label>
                <input type="text" id="username" placeholder="请输入要检查的用户名">
            </div>
            <button onclick="checkUsername()">检查用户名</button>
            <div id="username-result" class="result info">请输入用户名并点击检查按钮</div>
        </div>
        
        <!-- 测试邮箱检查 -->
        <div class="test-section">
            <h2>测试邮箱检查 (/api/user/check_email/)</h2>
            <div class="input-group">
                <label for="email">输入邮箱:</label>
                <input type="text" id="email" placeholder="请输入要检查的邮箱">
            </div>
            <button onclick="checkEmail()">检查邮箱</button>
            <div id="email-result" class="result info">请输入邮箱并点击检查按钮</div>
        </div>
    </div>
    
    <script>
        // 辅助函数：显示结果
        function showResult(elementId, message, type) {
            const element = document.getElementById(elementId);
            element.textContent = message;
            element.className = `result ${type}`;
        }
        
        // 检查用户名
        function checkUsername() {
            const username = document.getElementById('username').value;
            
            if (!username) {
                showResult('username-result', '请输入用户名', 'error');
                return;
            }
            
            showResult('username-result', '正在检查...', 'info');
            
            // 使用fetch API异步调用
            fetch(`/PHPweb/api/user/check_name/${username}`)
                .then(response => response.text())
                .then(data => {
                    if (data === 'true') {
                        showResult('username-result', '用户名已存在', 'error');
                    } else {
                        console.log(data);
                        showResult('username-result', '用户名可用', 'success');
                    }
                })
                .catch(error => {
                    showResult('username-result', '检查失败: ' + error.message, 'error');
                    console.error('Error:', error);
                });
        }
        
        // 检查邮箱
        function checkEmail() {
            const email = document.getElementById('email').value;
            
            if (!email) {
                showResult('email-result', '请输入邮箱', 'error');
                return;
            }
            
            showResult('email-result', '正在检查...', 'info');
            
            // 使用fetch API异步调用
            fetch(`/PHPweb/api/user/check_email/${email}`)
                .then(response => response.text())
                .then(data => {
                    if (data === 'true') {
                        showResult('email-result', '邮箱已存在', 'error');
                    } else {
                        showResult('email-result', '邮箱可用', 'success');
                    }
                })
                .catch(error => {
                    showResult('email-result', '检查失败: ' + error.message, 'error');
                    console.error('Error:', error);
                });
        }
        
        // 允许按Enter键提交
        document.getElementById('username').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                checkUsername();
            }
        });
        
        document.getElementById('email').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                checkEmail();
            }
        });
    </script>
</body>
</html>
# 用户API功能推荐实现方案

## 🔐 基础认证功能

### 1. 用户注册 (user_register)

```php
function user_register($username, $password, $email) {
    // 实现用户注册逻辑
}
```

**输入参数：**
- `$username` (string): 用户名，3-20个字符，只能包含字母、数字和下划线
- `$password` (string): 密码，至少8个字符，包含字母和数字
- `$email` (string): 邮箱地址，必须是有效的邮箱格式

**输出：**
- 成功：返回 `['status' => 'success', 'user_id' => 新用户ID]`
- 失败：返回 `['status' => 'error', 'message' => '错误信息']`

**注意事项：**
- 需要验证用户名和邮箱的唯一性
- 密码必须进行哈希加密存储（建议使用password_hash()）
- 应该进行输入验证和过滤，防止SQL注入和XSS攻击
- 考虑添加验证码机制防止恶意注册

**实现方法：**
1. 验证输入参数格式和长度
2. 检查用户名和邮箱是否已存在
3. 对密码进行哈希处理
4. 插入新用户记录到数据库
5. 返回操作结果

---

### 2. 用户登录 (user_login)

```php
function user_login($username_or_email, $password, $remember = false) {
    // 实现用户登录逻辑
}
```

**输入参数：**
- `$username_or_email` (string): 用户名或邮箱
- `$password` (string): 明文密码
- `$remember` (boolean): 是否记住登录状态，默认false

**输出：**
- 成功：返回 `['status' => 'success', 'user_data' => 用户信息数组]`
- 失败：返回 `['status' => 'error', 'message' => '错误信息']`

**注意事项：**
- 必须使用password_verify()验证密码
- 登录成功后应该创建会话
- 考虑实现登录尝试次数限制，防止暴力破解
- 记住登录状态应使用安全的cookie机制

**实现方法：**
1. 根据用户名或邮箱查询用户
2. 验证密码是否正确
3. 创建用户会话
4. 如需记住登录状态，设置安全cookie
5. 返回登录结果

---

### 3. 用户登出 (user_logout)

```php
function user_logout() {
    // 实现用户登出逻辑
}
```

**输入参数：**
- 无

**输出：**
- 成功：返回 `['status' => 'success']`
- 失败：返回 `['status' => 'error', 'message' => '错误信息']`

**注意事项：**
- 销毁所有会话数据
- 清除记住登录状态的cookie
- 重定向到登录页面或首页

**实现方法：**
1. 清除$_SESSION中的用户数据
2. 销毁会话
3. 清除记住登录状态的cookie
4. 返回操作结果

---

## 👤 用户管理功能

### 4. 获取用户信息 (get_user_info)

```php
function get_user_info($user_id) {
    // 获取用户详细信息
}
```

**输入参数：**
- `$user_id` (int): 用户ID

**输出：**
- 成功：返回 `['status' => 'success', 'user' => 用户信息数组]`
- 失败：返回 `['status' => 'error', 'message' => '错误信息']`

**注意事项：**
- 不应返回密码等敏感信息
- 根据当前用户权限决定可返回的信息
- 考虑缓存机制减少数据库查询

**实现方法：**
1. 验证用户ID有效性
2. 从数据库查询用户信息
3. 过滤敏感数据
4. 返回用户信息

---

### 5. 更新用户资料 (update_user_profile)

```php
function update_user_profile($user_id, $data) {
    // 更新用户资料
}
```

**输入参数：**
- `$user_id` (int): 用户ID
- `$data` (array): 要更新的数据，如['username' => '新用户名', 'email' => '新邮箱']

**输出：**
- 成功：返回 `['status' => 'success']`
- 失败：返回 `['status' => 'error', 'message' => '错误信息']`

**注意事项：**
- 验证用户是否有权限修改该用户资料
- 验证新数据的格式和有效性
- 如果修改邮箱或用户名，需要检查唯一性
- 记录重要修改的日志

**实现方法：**
1. 验证当前用户权限
2. 验证新数据格式
3. 检查唯一性约束
4. 更新数据库记录
5. 返回操作结果

---

### 6. 修改密码 (change_password)

```php
function change_password($user_id, $old_password, $new_password) {
    // 修改用户密码
}
```

**输入参数：**
- `$user_id` (int): 用户ID
- `$old_password` (string): 原密码
- `$new_password` (string): 新密码

**输出：**
- 成功：返回 `['status' => 'success']`
- 失败：返回 `['status' => 'error', 'message' => '错误信息']`

**注意事项：**
- 必须验证原密码正确性
- 新密码应符合安全要求
- 修改成功后应使所有其他会话失效
- 建议发送密码修改通知邮件

**实现方法：**
1. 验证用户身份和原密码
2. 验证新密码强度
3. 哈希新密码
4. 更新数据库
5. 清除其他会话
6. 返回操作结果

---

## 🔍 查询功能

### 7. 用户列表 (get_user_list)

```php
function get_user_list($page = 1, $limit = 10, $search = '') {
    // 获取用户列表
}
```

**输入参数：**
- `$page` (int): 页码，默认1
- `$limit` (int): 每页数量，默认10
- `$search` (string): 搜索关键词，默认空字符串

**输出：**
- 成功：返回 `['status' => 'success', 'users' => 用户数组, 'total' => 总数量, 'page' => 当前页码]`
- 失败：返回 `['status' => 'error', 'message' => '错误信息']`

**注意事项：**
- 只有管理员可以访问此功能
- 实现分页查询减少数据库负担
- 搜索功能应支持用户名和邮箱
- 不返回密码等敏感信息

**实现方法：**
1. 验证管理员权限
2. 构建分页查询SQL
3. 如有搜索条件，添加WHERE子句
4. 执行查询并返回结果
5. 计算总数用于分页

---

### 8. 检查用户名是否存在 (check_username_exists)

```php
function check_username_exists($username) {
    // 检查用户名是否已存在
}
```

**输入参数：**
- `$username` (string): 要检查的用户名

**输出：**
- 存在：返回 `['status' => 'success', 'exists' => true]`
- 不存在：返回 `['status' => 'success', 'exists' => false]`
- 错误：返回 `['status' => 'error', 'message' => '错误信息']`

**注意事项：**
- 用于注册时的实时验证
- 应该区分大小写或不区分，根据业务需求
- 考虑添加缓存提高性能

**实现方法：**
1. 验证用户名格式
2. 查询数据库检查是否存在
3. 返回检查结果

---

## 🛡️ 权限管理功能

### 9. 获取用户权限 (get_user_permission)

```php
function get_user_permission($user_id) {
    // 获取用户权限级别
}
```

**输入参数：**
- `$user_id` (int): 用户ID

**输出：**
- 成功：返回 `['status' => 'success', 'permission' => 'user|writer|admin']`
- 失败：返回 `['status' => 'error', 'message' => '错误信息']`

**注意事项：**
- 权限级别：user < writer < admin
- 可以扩展为更复杂的权限系统
- 考虑实现权限继承机制

**实现方法：**
1. 验证用户ID有效性
2. 查询用户权限字段
3. 返回权限级别

---

### 10. 权限验证 (check_permission)

```php
function check_permission($required_permission, $user_id = null) {
    // 检查用户是否有指定权限
}
```

**输入参数：**
- `$required_permission` (string): 所需权限级别 ('user', 'writer', 'admin')
- `$user_id` (int): 用户ID，null表示当前登录用户

**输出：**
- 有权限：返回 `['status' => 'success', 'has_permission' => true]`
- 无权限：返回 `['status' => 'success', 'has_permission' => false]`
- 错误：返回 `['status' => 'error', 'message' => '错误信息']`

**注意事项：**
- 用于保护需要特定权限的功能
- 实现权限级别比较逻辑
- 未登录用户应视为无权限

**实现方法：**
1. 获取用户ID（如未提供则使用当前用户）
2. 查询用户权限级别
3. 比较权限级别
4. 返回验证结果

---

## 📊 用户状态功能

### 11. 用户登录状态检查 (is_user_logged_in)

```php
function is_user_logged_in() {
    // 检查用户是否已登录
}
```

**输入参数：**
- 无

**输出：**
- 已登录：返回 `['status' => 'success', 'logged_in' => true, 'user_id' => 用户ID]`
- 未登录：返回 `['status' => 'success', 'logged_in' => false]`

**注意事项：**
- 检查会话有效性
- 验证用户仍然存在且未被禁用
- 考虑会话过期机制

**实现方法：**
1. 检查$_SESSION中的用户数据
2. 验证用户ID有效性
3. 返回登录状态

---

### 12. 获取当前登录用户 (get_current_user)

```php
function get_current_user() {
    // 获取当前登录用户信息
}
```

**输入参数：**
- 无

**输出：**
- 成功：返回 `['status' => 'success', 'user' => 用户信息数组]`
- 未登录：返回 `['status' => 'error', 'message' => '用户未登录']`

**注意事项：**
- 不返回密码等敏感信息
- 可以包含用户权限等常用信息
- 考虑缓存机制减少数据库查询

**实现方法：**
1. 检查用户登录状态
2. 从数据库获取用户信息
3. 过滤敏感数据
4. 返回用户信息

---

## 🔧 辅助功能

### 13. 密码重置请求 (request_password_reset)

```php
function request_password_reset($email) {
    // 请求密码重置
}
```

**输入参数：**
- `$email` (string): 用户邮箱

**输出：**
- 成功：返回 `['status' => 'success', 'message' => '重置链接已发送到您的邮箱']`
- 失败：返回 `['status' => 'error', 'message' => '错误信息']`

**注意事项：**
- 生成安全的重置令牌
- 设置令牌过期时间（通常1小时）
- 发送重置链接到用户邮箱
- 不应透露邮箱是否存在（安全考虑）

**实现方法：**
1. 验证邮箱格式
2. 查找对应用户
3. 生成安全令牌
4. 存储令牌和过期时间
5. 发送重置邮件
6. 返回操作结果

---

### 14. 验证邮箱 (verify_email)

```php
function verify_email($token) {
    // 验证用户邮箱
}
```

**输入参数：**
- `$token` (string): 邮箱验证令牌

**输出：**
- 成功：返回 `['status' => 'success', 'message' => '邮箱验证成功']`
- 失败：返回 `['status' => 'error', 'message' => '错误信息']`

**注意事项：**
- 令牌应有有效期
- 验证成功后更新用户状态
- 考虑添加邮箱已验证字段到用户表

**实现方法：**
1. 验证令牌格式
2. 查找对应令牌记录
3. 检查令牌是否过期
4. 更新用户邮箱验证状态
5. 删除已使用的令牌
6. 返回验证结果

---

## 实现建议

### 安全考虑
1. **输入验证**：所有用户输入都应进行验证和过滤
2. **SQL注入防护**：使用预处理语句或参数化查询
3. **XSS防护**：输出时进行适当的转义
4. **CSRF防护**：对状态改变操作添加CSRF令牌
5. **密码安全**：使用password_hash()和password_verify()
6. **会话安全**：使用安全的会话配置

### 性能优化
1. **数据库索引**：为常用查询字段添加索引
2. **缓存机制**：对频繁访问的数据进行缓存
3. **分页查询**：避免一次性加载大量数据
4. **连接池**：考虑使用数据库连接池

### 错误处理
1. **统一错误格式**：所有API返回一致的错误格式
2. **日志记录**：记录重要操作和错误信息
3. **用户友好**：向用户显示友好的错误信息
4. **安全日志**：记录登录失败等安全相关事件

这些API功能可以根据您的实际需求选择性实现，建议先完成基础的注册登录功能，再逐步添加其他功能。
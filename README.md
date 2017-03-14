# Cms
# ForAcerCms第一版本简介 

thinkphp(3.2.3) + mysql(5.5.36)

架构 thinkphp+mysql 使用了 jquery plupload 百度编辑器 bootstarp my97date jquery-ui 等组件

其中 对thinkphp 修改了一些东西 增加了标签模版函数利于使用 修改了魔术变量

修改了thinkphp底层的一些东西 
增加对model的单例模式 
远程服务的请求方式 
模版的独立化 
上传组件的的插件化 
多字段拓展的插件化
链接的插件化 

增加标签函数 

使用类标识串读取内容 phpclist 属性 id code type sort
使用模块标识串读取内容 phpmlist 属性 id code type sort
使用单项标识串读取内容 phpcode 属性 id code
根据父类标识串读取子类列表 catelist 属性 id code
无条件获取导航列表 navlist 属性 id

可在网页的随意地方调取数据 使用方法与thinkphp 中的标签函数类似

特点 六种字段类型的扩展 模块化管理内容 后台操作权限管理 自定义字段

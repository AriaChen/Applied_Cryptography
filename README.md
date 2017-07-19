# Applied_Cryptography




# 基于网页的用户注册与登录系统

## 使用https绑定证书到域名

* 设置域名

  * /etc 修改hosts

    127.0.0.1 rachelaria.com


* 配置OpenSSL

  * ```sudo apt-get install ssl-cert```
  * ```make-ssl-cert```    

* 通过OpenSSL命令行创建自签名证书（采用pem格式，包含了私钥和公钥(证书)两部分内容。如果将keyout和out分别采用不同的文件名，那keyout和out所对应的文件就会分别存放私钥和公钥(证书)。）

  * ```
    sudo openssl req -x509 -newkey rsa:1024 -keyout /etc/ssl/private/apache.pem -out /etc/ssl/private/apache.pem -nodes -days 999
    ```
  * ```
    Country Name (2 letter code) [AU]:CN　←输入国家代码
    State or Province Name (full name) [Some-State]:BEIJING　← 输入省名
    Locality Name (eg, city) []:BEIJING　←输入城市名
    Organization Name (eg, company) [Internet Widgits Pty Ltd]:CUC　← 输入公司名
    Organizational Unit Name (eg, section) []:CUC　← 输入组织单位名
    Common Name (eg, YOUR name) []:rachelaria.com　← 输入主机名(想要开启https的主机名)
    Email Address []:chenaria@qq.com　←输入电子邮箱地址
    ```

* 签署证书

  * ```
    sudo  openssl genrsa （-des3） 1024 >server.key //括号里的是密码验证 加了使用证书就需要输入密码
    ```

* 生成证书请求文件

  * ```
    openssl req -new -key server.key > server.csr
    ```

* 配置Apache

  * 开启SSL模块
    ```a2enmod ssl```

  * 启用SSL站点
    ```a2ensite default-ssl```

  * 加入监听端口
    ```sudo vim /etc/apache2/ports.conf```

     编辑Apache端口配置，加入443端口(HTTPS采用的443端口传输数据):
     Listen 443

    ​

  * 配置虚拟主机
      编辑default-ssl文件，加入证书对应的主机头。
      ```sudo vim /etc/apache2/sites-enabled/default-ssl```
      ServerName rachelaria.com

      ​

  * 配置SSL证书 修改/etc/apache2/sites-available 中的default-ssl.conf
  ```
  SSLEngine on
  SSLCertificateFile    /etc/ssl/certs/ssl-cert-snakeoil.pem
  SSLCertificateKeyFile /etc/ssl/private/ssl-cert-snakeoil.key 	     SSLCertificateChainFile /etc/ssl/certs/server-ca.crt
  ```

* 重启Apache

  * apachectl restart

* https://rachelaria.com 测试

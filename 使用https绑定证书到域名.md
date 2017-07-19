## 使用https绑定证书到域名rachelaria.com

### 名词解释

* PKI (Public Key Infrastructure) 公钥基础设施。

  * 它是利用公钥技术所构建的，解决网络安全问题的，普遍适用的一种基础设施;是一种遵循既定标准的密钥管理平台,它能够为所有网络应用提供加密和数字签名等密码服务及所必需的密钥和证书管理体系。PKI既不是一个协议，也不是一个软件，它是一个标准，在这个标准之下发展出的为了实现安全基础服务目的的技术统称为PKI。可以说CA(认证中心)是PKI的核心，而数字证书是PKI的最基本元素，还有如apache等服务器、浏览器等客户端、银行等应用，都是pki的组件。

* CA（Certificate Authority）证书颁发机构。

  * 主要负责证书的颁发、管理以及归档和吊销。它负责管理PKI结构下的所有用户(包括各种应用程序)的证书，把用户的公钥和用户的其他信息捆绑在一起，在网上验证用户的身份。CA机构的数字签名使得攻击者不能伪造和篡改证书。证书内包含了拥有证书者的姓名、地址、电子邮件帐号、公钥、证书有效期、发放证书的 CA 、CA 的数字签名等信息。证书主要有三大功能：加密、签名、身份验证。
  * 认证中心主要有以下5个功能：
    * 证书的颁发：接收、验证用户(包括下级认证中心和最终用户)的数字证书的申请。可以受理或拒绝
    * 证书的更新：认证中心可以定期更新所有用户的证书，或者根据用户的请求来更新用户的证书
    * 证书的查询：查询当前用户证书申请处理过程；查询用户证书的颁发信息，这类查询由目录服务器ldap来完成
    * 证书的作废：由于用户私钥泄密等原因，需要向认证中心提出证书作废的请求；证书已经过了有效期，认证中心自动将该证书作废。认证中心通过维护证书作废列表 (Certificate Revocation List,CRL) 来完成上述功能。
    * 证书的归档：证书具有一定的有效期，证书过了有效期之后就将作废，但是我们不能将作废的证书简单地丢弃，因为有时我们可能需要验证以前的某个交易过程中产生的数字签名，这时我们就需要查询作废的证书。

* SSL (secure socket layer) 安全套接层，其使用对称加密，非对称加密（公钥加密解密），单向加密解密结合证书实现数据传输安全。

* X.509标准

  - "SSL证书"这个词是一个相对较大的概念，整个PKI体系中有很多SSL证书格式标准。PKI的标准规定了PKI的设计、实施和运营，规定了PKI各种角色的"游戏规则"，提供数据语法和语义的共同约定。X.509是PKI中最重要的标准，它定义了公钥证书的基本结构，可以说PKI是在X.509标准基础上发展起来的。

  - SSL公钥证书格式

    ```
    1. 证书版本号(Version)
       版本号指明X.509证书的格式版本，现在的值可以为:
         1) 0: v1
         2) 1: v2
         3) 2: v3
       也为将来的版本进行了预定义
    2. 证书序列号(Serial Number)
       序列号指定由CA分配给证书的唯一的"数字型标识符"。当证书被取消时，实际上是将此证书的序列号放入由CA签发的CRL中，这也是序列号唯一的原因。
    3. 签名算法标识符(Signature Algorithm)
       签名算法标识用来指定由CA签发证书时所使用的"签名算法"。算法标识符用来指定CA签发证书时所使用的:
         1) 公开密钥算法
         2) hash算法
       example: sha256WithRSAEncryption
       须向国际知名标准组织(如ISO)注册
    4. 签发机构名(Issuer)
       此域用来标识签发证书的CA的X.500 DN(DN-Distinguished Name)名字。包括:
         1) 国家(C)
         2) 省市(ST)
         3) 地区(L)
         4) 组织机构(O)
         5) 单位部门(OU)
         6) 通用名(CN)
         7) 邮箱地址
    5. 有效期(Validity)
       指定证书的有效期，包括:
         1) 证书开始生效的日期时间
         2) 证书失效的日期和时间
       每次使用证书时，需要检查证书是否在有效期内。
    6. 证书用户名(Subject)
       指定证书持有者的X.500唯一名字。包括:
         1) 国家(C)
         2) 省市(ST)
         3) 地区(L)
         4) 组织机构(O)
         5) 单位部门(OU)
         6) 通用名(CN)
         7) 邮箱地址
    7. 证书持有者公开密钥信息(Subject Public Key Info)
       证书持有者公开密钥信息域包含两个重要信息:
         1) 证书持有者的公开密钥的值
         2) 公开密钥使用的算法标识符。此标识符包含公开密钥算法和hash算法。
    8. 扩展项(extension)
       X.509 V3证书是在v2的基础上一标准形式或普通形式增加了扩展项，以使证书能够附带额外信息。标准扩展是指由X.509 V3版本定义的对V2版本增加的具有广泛应用前景的扩展项，任何人都可以向一些权威机构，如ISO，来注册一些其他扩展，如果这些扩展项应用广泛，也许以后会成为标准扩展项。
    9. 签发者唯一标识符(Issuer Unique Identifier)
       签发者唯一标识符在第2版加入证书定义中。此域用在当同一个X.500名字用于多个认证机构时，用一比特字符串来唯一标识签发者的X.500名字。可选。
    10. 证书持有者唯一标识符(Subject Unique Identifier)
        持有证书者唯一标识符在第2版的标准中加入X.509证书定义。此域用在当同一个X.500名字用于多个证书持有者时，用一比特字符串来唯一标识证书持有者的X.500名字。可选。
    11. 签名算法(Signature Algorithm)
        证书签发机构对证书上述内容的签名算法
        example: sha256WithRSAEncryption
    12. 签名值(Issuer's Signature)
        证书签发机构对证书上述内容的签名值
    ```

* 区分HTTPS、SSL、OpenSSL三者的关系：

  * SSL是在客户端和服务器之间建立一条SSL安全通道的安全协议，而OpenSSL是TLS/SSL协议的开源实现，提供开发库和命令行程序。常说的HTTPS是HTTP的加密版，底层使用的加密协议是SSL。

### 步骤

* 设置域名

  * /etc 修改hosts

    127.0.0.1 rachelaria.com


* 使用工具OpenSSL

  * openssl 是一个开源程序的套件，这个套件有三个部分组成：

    1. libcryto ，这是一个具有通用功能的加密库，里面实现了众多的加密库；
    2. libssl，这个是实现 ssl 机制的，它是用于实现 TLS/SSL 的功能；
    3. openssl，是个多功能命令行工具，它可以实现加密解密，甚至还可以当 CA 来用，可以让你创建证书、吊销证书。

  * [OpenSSL官网下载openssl-1.0.2l.tar.gz](https://www.openssl.org/source/)

  * ```
    tar zxzf openssl-1.0.2l.tar.gz
    cd openssl-1.0.2l
    ./config
    make && make install
    ```

  * OpenSSL目录中包含：

    ```
    cert.pem        软链接到certs/ca-bundle.crt
    certs/          该服务器上的证书存放目录，可以房子自己的证书和内置证书
    ca-bundle.crt   内置信任的证书
    private         证书密钥存放目录
    openssl.cnf     openssl 的 CA 主配置文件
    demoCA          openssl 的 CA 的证书目录
    ```

  * demoCA 目录（openssl.cnf 中默认的 CA 目录名）其下有：

    ```
    newcerts    存放 CA 签署（颁发）过的数字证书（证书备份目录） 
    private     用于存放CA的私钥
    crl         吊销的证书
    serial      签署证书编号文件
    index.txt   证书缩影数据库
    ```

* 通过OpenSSL命令行**自建 CA 并颁发 SSL 证书**

  * 创建 demoCA 目录，在其下创建：private 目录、newcerts 目录、crl 目录、certs目录及 index.txt、serial 文件

    ```
    cd /etc/ssl
    mkdir demoCA
    cd demoCA
    mkdir private crl certs newcerts
    touch index.txt serial  # 在demoCA目录下新建index.txt的空文件
    echo 01 > serial  # 在demoCA目录下新建serial文件并设定编号初始值
    ```

  * 生成CA根证书的私钥

    ```
    cd /etc/ssl/demoCA
    openssl genrsa -out private/cakey.pem 2048 

    注：
    openssl：这是OpenSSL提供的用于创建和管理证书，密钥，签名请求等的基本命令行工具。
    genrsa : 生成私钥
    -out : 输出到那里
    rsa : 提取公钥  
    2048 : 生成RSA密钥是1024位

    使用命令：( umask 077; openssl genrsa -out private/cakey.pem 2048 ) 可以使 cakey.pem 私钥文件权限为700
    ```

  * 生成CA根证书

    ```
    cd /etc/ssl/demoCA     [可省略]
    openssl req -new -x509 -key private/cakey.pem -out cacert.pem 

    注：
    req : 生成证书签署请求
    -new : 新请求
    -key : 指定私钥文件
    -out : 指定生成证书位置
    -x509 : 生成自签署证书，并指定证书类型
    -days n : 有效天数 
    ```

  * ```
    Country Name (2 letter code) [AU]:CN　←输入国家代码
    State or Province Name (full name) [Some-State]:BEIJING　← 输入省名
    Locality Name (eg, city) []:BEIJING　←输入城市名
    Organization Name (eg, company) [Internet Widgits Pty Ltd]:CUC　← 输入公司名
    Organizational Unit Name (eg, section) []:CUC　← 输入组织单位名
    Common Name (eg, YOUR name) []:rachelaria.com　← 输入主机名(想要开启https的主机名)。必须与证书所有者能解析到的名字保持一致，否则将无法通过验证。
    Email Address []:chenaria@qq.com　←输入电子邮箱地址
    附：以上操作默认选项可通过修改配置文件（/etc/pki/tls/openssl.cnf）修改
    ```

* 为Apache服务器生成ssl密钥

  * 创建 SSL目录（注：我的 apache 在 /etc/apache2）

    ```
    cd /etc/apache2
    mkdir ssl
    cd ssl
    openssl genrsa -out apache.key 2048
    ```

* 颁发SSL证书：为 apache 服务器生成证书签署请求

  * ```
    openssl req -new -key apache.key -out apache.csr
    # 国家、省要与上面CA证书一致，否则签署时必然要失败。
    # Common Name 此时相当重要，请输入需要SSL支持的域名，如 localhost（域名只能一个），否则浏览器提示证书错误。

    Please enter the following 'extra' attributes
    to be sent with your certificate request
    A challenge password []:（可不填）   #证书请求需要加密存放
    An optional company name []:（可不填）

    openssl req -new -key server.key -out server.csr
    ```

* CA根据请求签署服务器证书

  ```
  cp apache.csr /etc/ssl
  cd /etc/ssl
  openssl ca -in apache.csr -out apache.crt

  注：
  ca : CA 证书相关子命令
  openssl ca 默认使用了-cert cacert.pem -keyfile cakey.pem
  ```

* 配置Apache以使用SSL

  * 开启SSL模块
    ```a2enmod ssl```

  * 启用SSL站点
    ```a2ensite default-ssl```

  * 加入监听端口
    ```sudo gedit /etc/apache2/ports.conf```

     编辑Apache端口配置，加入443端口(HTTPS采用的443端口传输数据):
     Listen 443


  * 配置虚拟主机
      编辑default-ssl文件，加入证书对应的主机头。
      ```sudo gedit /etc/apache2/sites-enabled/default-ssl```
      ServerName rachelaria.com

  * 配置SSL证书 修改/etc/apache2/sites-available 中的default-ssl.conf

      SSLEngine on
      SSLCertificateFile    /etc/apache2/ssl/apache.crt   
      SSLCertificateKeyFile /etc/apache2/ssl/apache.key      

* 启用SSL虚拟主机

     ```
     sudo a2ensite default-ssl.conf
     ```

* 重启Apache

  ```
  sudo service apache2 restart
  ```

* 将自签发的 SSL 证书添加为 Firefox 中受信任的根证书

     * 打开 Firefox 的工具栏，单击工具栏中的 **工具（Tools）** —> **选项（Options）**即可打开 Firefox 选项（Options）管理窗口。
     * 在选项（Options）窗口中点击最左边的 **高级（Advanced）**标签，然后单击 **查看证书（View Certificates）**即可打开证书管理器，
     * 在 Firefox 证书管理器的 **机构（Authorities）**标签页可以对证书进行诸如查看、编辑信任、导入、导出、删除或取消信任等操作。
     * 导入apache.crt

* https://rachelaria.com 测试

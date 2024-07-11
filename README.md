<h1 align="center">Laravel-Debugging</h1>

<p align="center">线上环境调试套件，DEBUG三板斧(安装编辑器、禁用OPcache、重启PHP-FPM).</p>

## 安装

| 运行环境要求           |
| ---------------------- |
| PHP ^8.1.0             |
| Laravel Framework ^9.0 |


```bash
composer require yesccx/laravel-debugging
```

## 使用

### 组合拳

```bash
#  php artisan debugging [--F | force] [--editor= nano | vim]

>> php artsan debugging -F
```

执行命令进行以下操作：

1. 离线安装 `nano` 编辑器 (可选在线安装 `vim` );
2. 禁用 `OPcache`;
3. 重启 `PHP-FPM`.

<img src="./imgs/index.gif" alt="image" width="600" height="auto">

### 安装编辑器

内置两种编辑器工具 `vim`、`nano` 的安装，其中 `nano` 支持离线安装, `vim` 目前需要在通外网的情况下安装.

```bash
# php artisan debugging:install [ nano | vim ]

>> php artisan debugging:install vim
```

> 离线安装 `nano` 目前仅支持amd架构.

### 管理OPcache

管理 `OPcache` 的启用与禁用.

```bash
# php artisan debugging:opcache [ enable | true | disable | false ] [ --R | restart ]

>> php artisan debugging:opcache false --restart
```

指定 `--restart` 时，尝试直接重启 `PHP-FPM` 使 `OPcache` 配置生效.

### 管理PHP-FPM

管理 `PHP-FPM` 服务状态.

```bash
# php artisan debugging:service [ restart | stop | start ]

>> php artisan debugging:service restart
```

## License

[MIT](https://choosealicense.com/licenses/mit/)
# 📁 Acesso ao projeto



### O projeto está em desenvolvimento, é possivel realizar clonagem e/ou download de seu ZIP

# 🛠️ Abrir e rodar o projeto



### Tecnologias usadas
- `Wamp`: Para rodar localmente foi utilizado o pacote de softwares WAMP, podendo ser utilizado LAMP e XAMP a depender de sua preferencia e sistema operacional.
- **[WAMP](https://www.wampserver.com/en/download-wampserver-64bits/)**
- `Composer`: Para gerenciar dependencias foi utilizado o Composer.
- **[Composer](https://getcomposer.org/download/)**
- `Laravel`: O framework Laravel foi utilizado na versão : v8.83 - PHP v7.4.
- **[Laravel](https://laravel.com/docs/8.x/installation)**- **[PHP](https://prototype.php.net/versions/7.4/install/)**


# A aplicação



-  Utilizando aquitetura MVC, o principal controlodar fica em `app\Http\Controllers\TransactionsController`
    - Toda transação que ocorre dentro da aplicação é baseada em um usuario autenticado para outro, seja ele qualquer.

- Validações e chamadadas são feitas em `Repositories\Transaction\TransactionsRepository`

- Testes são feitos em `tests\app\Http\Controllers\TransactionsControllerTest`
    - Neste respectivo arquivo há comentarios com os comandos para realizar os testes.

- Serviços de autorização e `notificação estão em app\Services`


# O banco de dados


O schema do banco foi gerado através da ferramenta **[MySql Workbench](https://dev.mysql.com/downloads/workbench/)**

![DB](https://github.com/Rafael2510/project/blob/main/diragrama_bd.png)

# Erros HTTP


|      |                                 Descrição                                                           |
|:-----|:---------------------------------------------------------------------------------------------------:|
|  411 | Código retornado quando o erro é : lojistas não podem realizar transação                            |
|  422 | Código retornadoo quando o erro é : usuário não possui dinheiro suficiente para realizar transação. |
|  433 | Código retornado quando o erro é : serviço de transação não esta disponivel no momento              |
|  441 | Código retornado quando o erro é : chave para transação informada não possui um beneficiario        |


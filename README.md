# PHP-Slim-Restful

Api RESTFULL, trabalhando em conjunto com outros 2 projetos. </br>
App <a href="https://github.com/IgorFachini/Project">Aplicativo</a>.</br>
Site <a href="https://github.com/IgorFachini/ProjectServerManager">Site</a>.

## Objetivo: 
Uma pessoa qualquer pede um produto, seu pedido e sua localização é salva, um funcionário qualquer, visualiza o pedido, aceita e entrega.



## Como funciona:
Cliente: O cliente irá realizar um pedido que conterá N produtos, sua localização e hora atual será armazenada junto com o pedido para entrega futura, enquanto não for atendido, ele poderá modificar o pedido ou cancelar, depois de ser atendido e o produto entregue ao cliente, o pedido ficara armazenado no histórico do cliente.

Funcionário: Ele verá no mapa todos pedidos que não foram atendidos e o que ele está atendendo, ao aceitar um pedido, sua hora atual será salva no pedido e o pedido será colocado em atendimento por ele e sumira da vista de outros funcionários, nesse tempo ele pode cancelar a finalizar a entrega do pedido (se estiver próximo ao local de entrega), quando finalizado a entrega, a sua hora atual é gravada ao pedido e colocado como histórico.



# Executando:

(Com php) instalado no path(cmd), coloque o projeto em uma pasta qualquer, navegue pelo cmd dentro da pasta que contém a pasta do projeto, e rode o comando ( php -S localhost:9000 ).

(Com Xampp) coloque o projeto em xampp\htdocs, e execute o apache do xampp.

# Acessando funções do projeto,

Com Postman(Chrome) ou chamadas HTTP, poderá realizar requisições para ( http://localhost:(80 -> com XAMPP ou 9000 com PHP)/ProjectServer/api/ ) + Tipo.
ATENCAO: Todas o conteúdo de todas as requisições deve ser feito em formato JSON, e retorno de todas as funções será em JSON.

### Ex de função de login em JS: Com AJAX: </br>
```js
let urlServe = "http://localhost:9000/ProjectServer/api/"

function sendOperation(data, type) {
    return $.post(urlServe + type,
        JSON.stringify(data),
        function (data, status) {});
}
var values = {"username":"fc1", "password": "admin123"};

sendOperation(values, "login").then((result) => {
    console.log(result);
    if (result.userData) {
      localStorage.setItem('userData', JSON.stringify(result));
      window.location.href = "index.html"
    } else {
      //Erro wrong user
    }
}, (err) => {
    //Connection failed message
});;

```
Retorno do Serve:

Logado -> 
```js
{
    "userData": {
        "user_id": "5",
        "username": "fc1",
        "password": "240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9",
        "name": "Thesco",
        "email": "thesco@gmail.com",
        "phone": "11223344",
        "userType": "Employee",
        "permissionType": "1",
        "address": "0",
        "latLng": "{\"Lat\":0,\"Lng\":0}",
        "complement": "",
        "cpf": "500.111.157-93",
        "cnpj": "",
        "token": "cb76fce66298afc035ae29ff6fc6a985ac8b7c0b521b6d1939226afd78b2c6ef"
    }
}
```
---
Erro -> 
```js
{
    "error": {
        "text": "Bad request wrong username and password"
    }
}
```

====

# Projeto Youtube API

Projeto teste para utilização da api de dados do Youtube

## Repositórios
Para o projeto foi utilizado o framework Laravel em sua versão mais recente no momento: 8.12.

Também foi utilizado o SDK oficial do google `google/apiclient` na versão 2.0.

## Requisitos
- PHP >= 7.3
- Composer
- Chave de Acesso à API de dados do Youtube

## Instruções para instalação
Para o funcionamento do projeto, deve-se obter uma chave de acesso à API de dados do Youtube.
Para tal, pode acessar o [Console de API do google](https://console.developers.google.com/apis/credentials) e criar uma chave de acesso.

A chave criada deve ser colocada na variável de ambiente `YOUTUBE_API_KEY` no arquivo `.env`.

Após realizar o clone do repositório, acesse a pasta principal do projeto e execute o comando `composer install`

Uma vez realizados os passos anteriores, o projeto já pode ser iniciado com `php artisan serve`.

# Localghost :ghost:

## Description
<p align="center">This project is a web application for people who seeks to organize and maintaim small envents.</p>
<p align="center">It's a minimalistic and simple to use tool, I hope you can have fun while using it.</p>

## Requirements
<p align="center">For this project you need to have docker intalled.</p>

## How to run the project
<ul>
    <li> Fisrt you need to clone this repository on your local;</li>
    <li> After that you need to run <code>docker compose up --build</code>;</li>
    <li>Once it finishes go to the container: <code>docker exec -it localghost-php bash</code> and run the <code>composer install</code> command;</li>
    <li>On the project folder run the command <code>cp .env.example .env</code>;</li>
    <li>Go to the container: <code>docker exec -it localghost-postgres bash</code>;</li>
    <li>Enter de Postgres CLI: <code>psql</code>;</li>
    <li>Create the postgres database named: <code>localghost</code> with the command: <code>CREATE DATABASE localghost;</code>;</li>
    <li>Run the project migrations: <code>php bin/console doctrine:migrations:migrate</code>;</li>
</ul>

### Autors

Feito com ❤️ por Matheus Tavares.
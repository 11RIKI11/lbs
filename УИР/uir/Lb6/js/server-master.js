#!/usr/local/bin/node
const express = require('express');
const { Client } = require('pg');  // Используем pg для подключения к PostgreSQL
'use strict';

// Параметры web-сервера
const server_host = 'localhost';
const server_port = '3000';

// Параметры подключения к PostgreSQL master
const conn = {
  host: 'localhost',
  port: 5432,  // Порт для мастера
  database: 'cities_db',
  user: 'postgres',
  password: '7676',
};

async function run() {
  // Установить соединение с сервером
  const master = new Client(conn);
  await master.connect();
  
  // Создать web сервис
  const app = express();
  app.set('view engine', 'pug');
  
  // По запросу http://server:port/ добавить новую запись в базу master
  app.get('/', wrapAsync(async (req, res, next) => {
    // Создать запись со случайным параметром
    let title = 'some title ' + (Math.random() * 1000).toFixed(0);
    let shortCode = 'SC' + (Math.random() * 1000).toFixed(0);
    await master.query('INSERT INTO cities(title, short_code) VALUES($1, $2)', [title, shortCode]);

    res.render('master', {
      "header": `Writer to master at ${conn.host}:${conn.port}`,
      "text": `A new record has been added to the main database with the following values: title = ${title}; shortCode =${shortCode}`,
    });
  }));

  // Запустить HTTP сервер
  app.listen(server_port, server_host, () => {
    console.log(`Master writer running at http://${server_host}:${server_port}/, press Ctrl-C to exit`);
  });
}

// Вспомогательная функция для обработки возможных ошибок в асинхронных функциях.
function wrapAsync(fn) {
  return function(req, res, next) {
    fn(req, res, next).catch(next);
  };
}

run();
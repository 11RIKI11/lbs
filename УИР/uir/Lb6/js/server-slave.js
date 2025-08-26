#!/usr/local/bin/node
const express = require('express');
const { Client } = require('pg');  // Используем pg для подключения к PostgreSQL
'use strict';

// Параметры web-сервера
const server_host = 'localhost';
const server_port = '3001';

// Параметры подключения к PostgreSQL (мастер и слейв)
const conn = [
  { host: 'localhost', port: 5432, database: 'cities_db', user: 'postgres', password: '7676' },  // master
  { host: 'localhost', port: 5433, database: 'cities_db', user: 'postgres', password: '7676' },  // slave1
  { host: 'localhost', port: 5434, database: 'cities_db', user: 'postgres', password: '7676' },  // slave2
];

async function run() {
  // Установить соединение с серверами
  const master = new Client(conn[0]);
  await master.connect();
  const slave1 = new Client(conn[1]);
  await slave1.connect();
  const slave2 = new Client(conn[2]);
  await slave2.connect();
  
  // Создать web сервис
  const app = express();
  app.set('view engine', 'pug');
  
  // По запросу http://server:port/ получить все записи из базы данных с серверов
  app.get('/', wrapAsync(async (req, res, next) => {
    // Выбрать записи
    let rows = [];
    const resMaster = await master.query('SELECT * FROM cities ORDER BY id');
    rows[0] = resMaster.rows;
    
    const resSlave1 = await slave1.query('SELECT * FROM cities ORDER BY id');
    rows[1] = resSlave1.rows;

    const resSlave2 = await slave2.query('SELECT * FROM cities ORDER BY id');
    rows[2] = resSlave2.rows;
    
    // Отобразить страницу с помощью pug по шаблону slaves.pug
    res.render('slaves', {
      header: 'Database content from all servers',
      names: ['master', 'slave1', 'slave2'],
      conn: conn,
      rows: rows,
    });
  }));

  // Запустить HTTP сервер
  app.listen(server_port, server_host, () => {
    console.log(`Slave reader running at http://${server_host}:${server_port}/, press Ctrl-C to exit`);
  });
}

// Вспомогательная функция для обработки возможных ошибок в асинхронных функциях.
function wrapAsync(fn) {
  return function(req, res, next) {
    fn(req, res, next).catch(next);
  };
}

run();
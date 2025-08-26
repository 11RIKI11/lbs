const express = require('express');
const { Pool } = require('pg');
const hirestime = require('hirestime');

'use strict';

// 🔹 Параметры web-сервера
const server_host = 'localhost';
const server_port = '3000';

// 🔹 Параметры подключения к PostgreSQL
const conn = {
    host: '127.0.0.1',
    user: 'postgres',
    password: '7676',
    database: 'uir5',
    port: 5432, // стандартный порт PostgreSQL
};

const test_id = 100;

// 🔹 Тестовые запросы
const queries = [
    { table: 'cities_no_part', sql: `SELECT * FROM cities_no_part LIMIT 1` },
    { table: 'cities_no_part', sql: `SELECT * FROM cities_no_part WHERE id = ${test_id} LIMIT 1` },
    { table: 'cities_part', sql: `SELECT * FROM cities_part LIMIT 1` },
    { table: 'cities_part', sql: `SELECT * FROM cities_part WHERE id = ${test_id} LIMIT 1` },
];

async function run() {
    // Подключение к базе данных
    const pool = new Pool(conn);

    // Создание web-сервера
    const app = express();

    // Использование pug как шаблонизатора
    app.set('view engine', 'pug');

    // Обработчик запроса на главную страницу
    app.get('/', wrapAsync(async (req, res, next) => {
        // Подсчет количества записей в таблице (только для отчета)
        const { rows } = await pool.query(`SELECT COUNT(*) AS n FROM ${queries[0].table}`);
        const n = rows[0].n;

        // Выполнение тестовых выборок и измерение времени выполнения
        let results = [];
        for (let i in queries) {
            let time = hirestime();
            let { rows } = await pool.query(queries[i].sql);
            let elapsed = time(hirestime.MS).toFixed(2);

            results.push({
                n: n,
                sql: queries[i].sql,
                table: queries[i].table,
                time: elapsed,
            });
        }

        // Отображение страницы с результатами
        res.render('results', { header: `Query results`, results: results });
    }));

    // Запуск сервера
    app.listen(server_port, server_host, () => {
        console.log(`Server running at http://${server_host}:${server_port}/, press Ctrl-C to exit`);
    });
}

// 🔹 Функция-обёртка для обработки ошибок в асинхронных функциях
function wrapAsync(fn) {
    return function(req, res, next) {
        fn(req, res, next).catch(next);
    };
}

// 🔹 Запуск сервера
run();

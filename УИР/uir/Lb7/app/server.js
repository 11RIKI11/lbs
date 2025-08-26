const express = require('express');
const { Pool } = require('pg'); // Используем Pool вместо createConnection

'use strict';

// Параметры web-сервера
const server_host = 'localhost';
const server_port = '3002';

// Количество серверов и максимальное количество записей на сервере согласно варианту
const n_servers = 3;
const n_records = 4;

// Имена серверов для отображения
const server_names = ['server1', 'server2', 'server3'];

// Параметры подключения к PostgreSQL
const conn = [
    { host: 'localhost', port: 5432, database: 'cities_db', user: 'postgres', password: '7676' },
    { host: 'localhost', port: 5435, database: 'cities_db', user: 'postgres', password: '7676' },
    { host: 'localhost', port: 5436, database: 'cities_db', user: 'postgres', password: '7676' },
];

// Создание пулов подключений для каждого сервера
const db = conn.map(cfg => new Pool(cfg));

async function run() {
    // Создать web сервис
    const app = express();

    // Использовать pug (бывший jade) как HTML template движок
    app.set('view engine', 'pug');

    // По запросу http://server:port/ получить все записи из базы данных с серверов
    app.get('/', wrapAsync(async (req, res, next) => {
        // Показать все записи
        let rows = [];
        for (let i = 0; i < db.length; i++) {
            const result = await db[i].query('SELECT * FROM cities ORDER BY id');
            rows[i] = result.rows; // Получаем записи из базы
        }

        // Отобразить страницу с помощью pug по шаблону index.pug
        res.render('index', {
            header: 'Database content from all servers',
            names: server_names,
            conn: conn,
            rows: rows,
        });
    }));

    // По запросу http://server:port/id/<id> получить нужную запись с соответствующего сервера
    app.get('/id/:id', wrapAsync(async (req, res, next) => {
        // Получить id из запроса
        let id = req.params.id;
        let max_id = n_servers * n_records;

        // Вернуть ошибку, если вне допустимого диапазона
        if ((id < 0) || (id > max_id)) {
            res.render('record', {
                'header': `Record ${id} is out of range`,
                'msg': `Valid range is ${0}..${max_id}`,
                'rows': [],
            });
            return;
        }

        let server_id = Math.floor(id / n_records);
        if((id % n_records) == 0) server_id--

        const result = await db[server_id].query('SELECT * FROM cities WHERE id = $1', [id]);

        if (result.rows.length) {
            res.render('record', {
                'header': `Record ${id} was found on ${server_names[server_id]}`,
                'rows': result.rows,
            });
        } else {
            res.render('record', {
                'header': `Record ${id} is expected to be on ${server_names[server_id]}, but was not found`,
                'rows': result.rows,
            });
        }
    }));

    // Запустить HTTP сервер
    app.listen(server_port, server_host, () => {
        console.log(`Server running at http://${server_host}:${server_port}/, press Ctrl-C to exit`);
    });
}

// Вспомогательная функция для обработки возможных ошибок в асинхронных функциях.
// Переназначает exceptions на стандартный обработчик ошибок Express.
function wrapAsync(fn) {
    return function(req, res, next) {
        fn(req, res, next).catch(next);
    };
}

run();
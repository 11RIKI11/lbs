const { Client } = require('pg');

// Данные подключения для каждого из 3 серверов
const servers = [
  {
    id: 1,
    name: 'Server 1',
    user: 'postgres',   // Имя пользователя для подключения
    host: 'localhost',
    database: 'cities_db', // Имя базы данных
    password: '7676',
    port: 5432           // Порт для первого сервера
  },
  {
    id: 2,
    name: 'Server 2',
    user: 'postgres',   // Имя пользователя для подключения
    host: 'localhost',
    database: 'cities_db', // Имя базы данных
    password: '7676',
    port: 5435           // Порт для второго сервера
  },
  {
    id: 3,
    name: 'Server 3',
    user: 'postgres',   // Имя пользователя для подключения
    host: 'localhost',
    database: 'cities_db', // Имя базы данных
    password: '7676',
    port: 5436          // Порт для третьего сервера
  }
];

// Функция для генерации данных
function generateData() {
  const data = [];

  for (let i = 1; i <= 12; i++) {
    const title = `City ${i}`;
    const short_code = `C${i}`; // Генерация короткого кода

    data.push({
      id: i,
      title: title,
      short_code: short_code
    });
  }

  return data;
}

// Функция для распределения данных по серверам
function distributeDataToServers(data, servers) {
  const distributedData = [];

  // Разделим данные на 3 части (по 4 записи на сервер)
  for (let i = 0; i < servers.length; i++) {
    const serverData = data.slice(i * 4, (i + 1) * 4);  // Берем по 4 записи для каждого сервера

    distributedData.push({ server: servers[i], data: serverData });
  }

  return distributedData;
}

// Функция для очистки таблицы на сервере
async function clearTable(client) {
  try {
    await client.query('DELETE FROM cities'); // Очистка таблицы
    console.log('Table cleared.');
  } catch (err) {
    console.error('Error clearing table:', err);
  }
}

// Функция для вставки данных в таблицу на сервер
async function insertData(client, data) {
  try {
    const query = 'INSERT INTO cities(id, title, short_code) VALUES($1, $2, $3)';
    for (const item of data) {
      await client.query(query, [item.id, item.title, item.short_code]);
    }
    console.log('Data inserted.');
  } catch (err) {
    console.error('Error inserting data:', err);
  }
}

// Функция для работы с каждым сервером
async function handleServer(server, data) {
  const client = new Client({
    user: server.user,        // Используем данные из сервера
    host: server.host,
    database: server.database,
    password: server.password,
    port: server.port
  });

  try {
    await client.connect();
    console.log(`Connected to ${server.name} (Port: ${server.port})`);

    // Очищаем таблицу на сервере
    await clearTable(client);

    // Вставляем данные на сервер
    const serverData = data.find(d => d.server.id === server.id).data;
    await insertData(client, serverData);
  } finally {
    await client.end();
  }
}

// Генерация данных
const data = generateData();

// Распределение данных по серверам
const distributedData = distributeDataToServers(data, servers);

// Обработка серверов
async function processServers() {
  for (const server of servers) {
    await handleServer(server, distributedData);
  }
}

processServers();
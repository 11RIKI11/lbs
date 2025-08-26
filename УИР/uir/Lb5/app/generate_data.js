const { Pool } = require('pg');

const dbConfig = {
    host: '127.0.0.1',
    user: 'postgres',
    password: '7676',
    database: 'uir5',
    port: 5432
};
const N = 2000; // Количество записей для генерации

async function generateData() {
    const pool = new Pool(dbConfig);
    const client = await pool.connect();

    try {
        console.log("Подключение к базе данных...");

        // Очищаем таблицы перед вставкой
        await client.query('DELETE FROM cities_no_part');
        await client.query('DELETE FROM cities_part');

        console.log("Таблицы очищены. Начинаем генерацию данных...");

        // Вставка в cities_no_part
        let values = [];
        for (let i = 1; i <= N; i++) {
            values.push([`City ${i}`, `C${i}`]);
        }
        const insertNoPart = `
            INSERT INTO cities_no_part (title, short_code)
            VALUES ${values.map((_, i) => `($${i * 2 + 1}, $${i * 2 + 2})`).join(', ')}
        `;
        await client.query(insertNoPart, values.flat());

        // Вставка в cities_part (с id)
        let valuesWithId = [];
        for (let i = 1; i <= N; i++) {
            valuesWithId.push([i, `City ${i}`, `C${i}`]);
        }
        const insertPart = `
            INSERT INTO cities_part (id, title, short_code)
            VALUES ${valuesWithId.map((_, i) => `($${i * 3 + 1}, $${i * 3 + 2}, $${i * 3 + 3})`).join(', ')}
        `;
        await client.query(insertPart, valuesWithId.flat());

        console.log(`Генерация завершена! ${N} записей добавлено в обе таблицы.`);

    } catch (error) {
        console.error("Ошибка при генерации данных:", error);
    } finally {
        client.release();
        await pool.end();
    }
}

// Запускаем генерацию данных
generateData();

// ЗАВДАННЯ 1: Оголошення та маніпуляція змінними

let intNum = 42;
let floatNum = 3.14;
let str = "Привіт";
let bool = true;

console.log("=== Завдання 1 ===");
console.log(`intNum: ${intNum}, тип: ${typeof intNum}`);
console.log(`floatNum: ${floatNum}, тип: ${typeof floatNum}`);
console.log(`str: ${str}, тип: ${typeof str}`);
console.log(`bool: ${bool}, тип: ${typeof bool}`);

intNum = "тепер рядок";
console.log(`intNum після зміни: ${intNum}, тип: ${typeof intNum}`);

floatNum = false;
console.log(`floatNum після зміни: ${floatNum}, тип: ${typeof floatNum}`);

const num = 7;
const s = "10";
console.log(`Конкатенація числа і рядка: ${num + s}`);
console.log(`true у числі: ${Number(true)}`);
console.log(`false у числі: ${Number(false)}`);
console.log(`Рядок "42" у числі: ${Number("42")}`);
console.log(`Число 0 у булевому: ${Boolean(0)}`);
console.log(`Число 5 у булевому: ${Boolean(5)}`);

const person = {
    name: "Олексій",
    age: 25,
    height: 1.75,
    isStudent: true,
    scores: [90, 85, 92],
};
console.log("Об'єкт у JSON:", JSON.stringify(person, null, 2));


// ЗАВДАННЯ 2: Арифметичні операції та обчислення

console.log("\n=== Завдання 2 ===");

const a = 15, b = -7, c = 22;
console.log(`Числа: a=${a}, b=${b}, c=${c}`);

const avg = (a + b + c) / 3;
console.log(`Середнє арифметичне: ${avg.toFixed(2)}`);

console.log(`|b| (модуль): ${Math.abs(b)}`);
console.log(`ceil(avg): ${Math.ceil(avg)}`);
console.log(`floor(avg): ${Math.floor(avg)}`);
console.log(`a^3: ${Math.pow(a, 3)}`);

[a, b, c].forEach(n => {
    const d5 = Math.abs(n) % 5 === 0 ? "ділиться на 5" : "не ділиться на 5";
    const d7 = Math.abs(n) % 7 === 0 ? "ділиться на 7" : "не ділиться на 7";
    console.log(`${n}: ${d5}, ${d7}`);
});

function canBeTriangle(x, y, z) {
    return x + y > z && x + z > y && y + z > x;
}
console.log(`Трикутник (3,4,5): ${canBeTriangle(3, 4, 5)}`);
console.log(`Трикутник (1,2,10): ${canBeTriangle(1, 2, 10)}`);


// ЗАВДАННЯ 3: Логічні та порівняльні оператори

console.log("\n=== Завдання 3 ===");

const x = 18, y = 5, z = 27;
console.log(`Значення: x=${x}, y=${y}, z=${z}`);

const maxVal = Math.max(x, y, z);
const minVal = Math.min(x, y, z);
console.log(`Найбільше: ${maxVal}, Найменше: ${minVal}`);

const hasEven = x % 2 === 0 || y % 2 === 0 || z % 2 === 0;
console.log(`Хоча б одна парна: ${hasEven}`);

const complexCondition = x > y && y < z;
console.log(`x > y && y < z: ${complexCondition}`);

function isPrime(n) {
    if (n < 2) return false;
    for (let i = 2; i <= Math.sqrt(n); i++) {
        if (n % i === 0) return false;
    }
    return true;
}
[2, 7, 10, 13, 25].forEach(n =>
    console.log(`${n} — просте: ${isPrime(n)}`)
);


// ЗАВДАННЯ 4: Користувацьке введення та складні перевірки

console.log("\n=== Завдання 4 ===");

const userName = "Марія";
const birthYear = 2001;
const userCity = "Київ";

const currentYear = new Date().getFullYear();
const age = currentYear - birthYear;
console.log(`Ім'я: ${userName}, Рік народження: ${birthYear}, Місто: ${userCity}`);
console.log(`Поточний рік: ${currentYear}, Вік: ${age}`);

let ageGroup;
if (age < 13) {
    ageGroup = "дитина";
} else if (age < 18) {
    ageGroup = "підліток";
} else if (age < 60) {
    ageGroup = "дорослий";
} else {
    ageGroup = "літня людина";
}
console.log(`Вікова група: ${ageGroup}`);

const capitals = {
    Україна: "Київ",
    Польща: "Варшава",
    Франція: "Париж",
    Німеччина: "Берлін",
};

let isCapital = false;
let countryName = "";
for (const [country, capital] of Object.entries(capitals)) {
    if (capital.toLowerCase() === userCity.toLowerCase()) {
        isCapital = true;
        countryName = country;
        break;
    }
}

if (isCapital) {
    console.log(`${userCity} є столицею країни: ${countryName}`);
} else {
    console.log(`${userCity} не є столицею жодної з відомих країн`);
} 
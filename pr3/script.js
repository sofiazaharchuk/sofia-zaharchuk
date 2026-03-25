// ВАРІАНТ 1: Обробка масиву чисел

const numbers = [34, 7, 23, 89, 12, 56, 3, 78, 45, 61];

const sum = numbers.reduce((acc, n) => acc + n, 0);
const avg = sum / numbers.length;
const max = Math.max(...numbers);
const min = Math.min(...numbers);
const sorted = [...numbers].sort((a, b) => a - b);

console.log("=== Варіант 1 ===");
console.log("Масив:", numbers);
console.log("Середнє арифметичне:", avg);
console.log("Максимум:", max);
console.log("Мінімум:", min);
console.log("Відсортований масив:", sorted);


// ВАРІАНТ 2: Робота з масивом об'єктів

const users = [
    { name: "Марк", age: 15 },
    { name: "Марія", age: 22 },
    { name: "Іван", age: 17 },
    { name: "Софія", age: 30 },
    { name: "Дмитро", age: 25 },
    { name: "Анна", age: 14 },
];

const adults = users.filter(user => user.age > 18);
const names = adults.map(user => user.name);
const avgAge = adults.reduce((acc, user) => acc + user.age, 0) / adults.length;

console.log("\n=== Варіант 2 ===");
console.log("Всі користувачі:", users);
console.log("Користувачі старше 18:", adults);
console.log("Імена:", names);
console.log("Середній вік:", avgAge.toFixed(1));


// ВАРІАНТ 3: Групування об'єктів

const products = [
    { name: "Яблуко", category: "Фрукти" },
    { name: "Молоко", category: "Молочні" },
    { name: "Банан", category: "Фрукти" },
    { name: "Сир", category: "Молочні" },
    { name: "Морква", category: "Овочі" },
    { name: "Апельсин", category: "Фрукти" },
    { name: "Кефір", category: "Молочні" },
    { name: "Картопля", category: "Овочі" },
];

const grouped = products.reduce((acc, product) => {
    if (!acc[product.category]) {
        acc[product.category] = [];
    }
    acc[product.category].push(product.name);
    return acc;
}, {});

console.log("\n=== Варіант 3 ===");
for (const [category, items] of Object.entries(grouped)) {
    console.log(`${category}: ${items.join(", ")}`);
}


// ВАРІАНТ 4: Обробка вкладених об'єктів

const students = {
    Марк: { математика: 90, фізика: 78, історія: 85 },
    Катерина: { математика: 95, фізика: 88, історія: 92 },
    Богдан: { математика: 70, фізика: 65, історія: 80 },
    Юлія: { математика: 88, фізика: 91, історія: 76 },
};

console.log("\n=== Варіант 4 ===");
for (const [student, grades] of Object.entries(students)) {
    const values = Object.values(grades);
    const avgGrade = values.reduce((acc, g) => acc + g, 0) / values.length;
    console.log(`${student}: середній бал — ${avgGrade.toFixed(1)}`);
}


// ВАРІАНТ 5: Генерація об'єктів з масиву

const names5 = ["Олександр", "Марія", "Іван", "Софія", "Дмитро"];

const nameLengths = names5.reduce((acc, name) => {
    acc[name] = name.length;
    return acc;
}, {});

console.log("\n=== Варіант 5 ===");
console.log(nameLengths);
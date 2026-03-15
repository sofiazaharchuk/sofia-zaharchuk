// ЗАВДАННЯ 1: FizzBuzz

for (let i = 1; i <= 100; i++) {
    if (i % 3 === 0 && i % 5 === 0) {
        console.log("FizzBuzz");
    } else if (i % 3 === 0) {
        console.log("Fizz");
    } else if (i % 5 === 0) {
        console.log("Buzz");
    } else {
        console.log(i);
    }
}


// ЗАВДАННЯ 3: Таблиця множення

const n = 5;
for (let i = 1; i <= 10; i++) {
    console.log(`${n} x ${i} = ${n * i}`);
}


// ЗАВДАННЯ 4: Обчислення факторіалу

function factorial(n) {
    if (n === 0) return 1;
    let result = 1;
    for (let i = 1; i <= n; i++) {
        result *= i;
    }
    return result;
}

console.log(`5! = ${factorial(5)}`);
console.log(`0! = ${factorial(0)}`);
console.log(`7! = ${factorial(7)}`);


// ЗАВДАННЯ 5: Пошук максимуму та мінімуму в масиві

function findMinMax(arr) {
    let max = arr[0];
    let min = arr[0];
    for (const num of arr) {
        if (num > max) max = num;
        if (num < min) min = num;
    }
    return { max, min };
}

const numbers = [3, 17, -5, 42, 8, 0, -13, 99, 4];
const result = findMinMax(numbers);
console.log(`Масив: [${numbers}]`);
console.log(`Максимум: ${result.max}`);
console.log(`Мінімум: ${result.min}`);
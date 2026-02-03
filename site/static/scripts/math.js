const buttons = document.querySelectorAll('.button-menu .retro[data-test]');
const taskProblem = document.getElementById('task-problem');
const taskImage = document.getElementById('task-image');
const showSolutionButton = document.getElementById('show-solution');
const nextTaskButton = document.getElementById('next-task');
const checkAnswerButton = document.getElementById('check-answer');
const solutionText = document.getElementById('solution-text');
const solutionContent = document.getElementById('solution-content');
const solvedCount = document.getElementById('solved-count');
const userAnswerInput = document.getElementById('user-answer');
const checkResult = document.getElementById('check-result');
const themeToggleButton = document.getElementById('theme-toggle');
const optimusTheme = document.getElementById('optimus-theme');
const optimusTheme2 = document.getElementById('optimus-theme2');
const testContainer = document.getElementById('test-container');
const menuToggle = document.getElementById('menu-toggle');
const sidebar = document.getElementById('sidebar');
const emailModal = document.getElementById('email-modal');
const confirmEmailButton = document.getElementById('confirm-email');
const cancelEmailButton = document.getElementById('cancel-email');
const senderNameInput = document.getElementById('sender-name');
const recipientEmailInput = document.getElementById('recipient-email');
const loadingIndicator = document.getElementById('loading-indicator');

let currentTask = null;
let solvedTasks = 0;
let solutionShown = false;
let answerChecked = false;
let currentTestNumber = "1";
const taskStates = {};
let testTasks = [];

const scoreConversion = {
    0: 0, 1: 6, 2: 11, 3: 17, 4: 22, 5: 27, 6: 34, 7: 40, 8: 46, 9: 52, 10: 58, 11: 64, 12: 70
};

function waitForMathJax() {
    return new Promise((resolve) => {
        if (window.MathJax && window.MathJax.typesetPromise) {
            resolve();
        } else {
            window.addEventListener('load', resolve, { once: true });
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    menuToggle.addEventListener("click", function () {
        sidebar.classList.toggle("open");
    });

    optimusTheme.volume = 0.45;
    optimusTheme2.volume = 0.6;

    getRandomTask("1").then(task => {
        if (task) {
            taskStates["1"] = task;
            setTask(task);
            updateCounter();
        } else {
            taskProblem.innerHTML = 'Ошибка загрузки задачи. Попробуйте позже.';
        }
    }).catch(() => {
        taskProblem.innerHTML = 'Ошибка сервера. Попробуйте позже.';
    });
});

async function setTask(task) {
    currentTask = task;
    taskProblem.innerHTML = task.problem || 'Задача не загрузилась';
    if (task.image) {
        taskImage.src = task.image;
        taskImage.style.display = 'block';
    } else {
        taskImage.style.display = 'none';
    }
    const formattedSolution = (task.solution || 'Решение недоступно').replace(/\n/g, '<br>');
    solutionContent.innerHTML = `<strong>Решение:</strong><br>${formattedSolution}`;
    solutionText.style.display = 'none';
    solutionShown = false;
    answerChecked = false;
    userAnswerInput.value = '';
    checkResult.style.display = 'none';
    showSolutionButton.disabled = true;
    testContainer.style.display = 'none';
    taskProblem.style.display = 'block';
    document.querySelector('.answer-input').style.display = 'flex';
    document.querySelector('.solution-button').style.display = 'flex';

    await waitForMathJax();
    MathJax.typesetPromise([taskProblem, solutionContent]).catch(() => {});
}

async function getRandomTask(testNumber) {
    try {
        const response = await fetch(`/api.php?test_number=${testNumber}`);
        if (!response.ok) throw new Error(`Ошибка HTTP: ${response.status}`);
        const task = await response.json();
        if (task.error) throw new Error(task.error);
        return task;
    } catch (error) {
        return null;
    }
}

function updateCounter() {
    solvedCount.textContent = `Решено задач: ${solvedTasks}`;
}

function checkAnswer() {
    const userAnswer = userAnswerInput.value.trim();
    const correctAnswer = currentTask.answer.trim();

    if (!userAnswer) {
        checkResult.textContent = '✗';
        checkResult.style.color = '#dc3545';
        checkResult.style.display = 'inline';
        return;
    }

    let userValue, correctValue;
    try {
        if (userAnswer.includes('/')) {
            const [numerator, denominator] = userAnswer.split('/').map(Number);
            userValue = numerator / denominator;
        } else {
            userValue = parseFloat(userAnswer);
        }
        if (correctAnswer.includes('/')) {
            const [numerator, denominator] = correctAnswer.split('/').map(Number);
            correctValue = numerator / denominator;
        } else {
            correctValue = parseFloat(correctAnswer);
        }

        const isCorrect = Math.abs(userValue - correctValue) < 0.01 || userAnswer === correctAnswer;

        if (isCorrect) {
            checkResult.textContent = '✓';
            checkResult.style.color = document.body.classList.contains('dark-theme') ? '#00e676' : '#28a745';
            checkResult.style.display = 'inline';
            solvedTasks++;
            updateCounter();
        } else {
            checkResult.textContent = '✗';
            checkResult.style.color = '#dc3545';
            checkResult.style.display = 'inline';
        }
        answerChecked = true;
        showSolutionButton.disabled = false;
    } catch (e) {
        checkResult.textContent = '✗';
        checkResult.style.color = '#dc3545';
        checkResult.style.display = 'inline';
        answerChecked = true;
        showSolutionButton.disabled = false;
    }
}

buttons.forEach(button => {
    button.addEventListener('click', () => {
        const testNumber = button.getAttribute('data-test');
        if (!testNumber) {
            taskProblem.innerHTML = 'Ошибка: Номер задачи не указан.';
            return;
        }

        if (testNumber === 'generate-test') {
            generateTestVariant();
            return;
        }

        buttons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        currentTestNumber = testNumber;

        if (taskStates[currentTestNumber]) {
            setTask(taskStates[currentTestNumber]);
        } else {
            getRandomTask(currentTestNumber).then(newTask => {
                if (newTask) {
                    taskStates[currentTestNumber] = newTask;
                    setTask(newTask);
                } else {
                    taskProblem.innerHTML = 'Ошибка загрузки задачи.';
                }
            }).catch(() => {
                taskProblem.innerHTML = 'Ошибка сервера.';
            });
        }
    });
});

checkAnswerButton.addEventListener('click', (event) => {
    event.stopPropagation();
    checkAnswer();
});

showSolutionButton.addEventListener('click', async (event) => {
    event.stopPropagation();
    solutionText.style.display = 'block';
    solutionShown = true;
    await waitForMathJax();
    MathJax.typesetPromise([solutionText]).catch(() => {});
});

nextTaskButton.addEventListener('click', (event) => {
    event.stopPropagation();
    getRandomTask(currentTestNumber).then(newTask => {
        if (newTask) {
            taskStates[currentTestNumber] = newTask;
            setTask(newTask);
        } else {
            taskProblem.innerHTML = 'Ошибка загрузки задачи.';
        }
    }).catch(() => {
        taskProblem.innerHTML = 'Ошибка сервера.';
    });
});

themeToggleButton.addEventListener('click', () => {
    document.body.classList.toggle('dark-theme');
    if (document.body.classList.contains('dark-theme')) {
        themeToggleButton.textContent = 'Светлая тема';
        optimusTheme2.pause();
        optimusTheme.currentTime = 0;
        optimusTheme.play();
    } else {
        themeToggleButton.textContent = 'Тёмная тема';
        optimusTheme.pause();
        optimusTheme2.currentTime = 0;
        optimusTheme2.play();
    }
});

async function generateTestVariant() {
    testTasks = [];
    testContainer.innerHTML = '';
    testContainer.style.display = 'block';
    taskProblem.style.display = 'none';
    taskImage.style.display = 'none';
    document.querySelector('.answer-input').style.display = 'none';
    document.querySelector('.solution-button').style.display = 'none';
    solutionText.style.display = 'none';
    checkResult.style.display = 'none';
    checkResult.textContent = '';

    const header = document.createElement('div');
    header.classList.add('test-header');
    header.innerHTML = '<h1>Вариант ЕГЭ - Часть 1</h1>';
    testContainer.appendChild(header);

    const taskPromises = [];
    for (let i = 1; i <= 12; i++) {
        taskPromises.push(getRandomTask(i.toString()));
    }

    const tasks = await Promise.all(taskPromises);

    tasks.forEach((task, index) => {
        if (task) {
            testTasks.push(task);
            const taskDiv = document.createElement('div');
            taskDiv.classList.add('test-task');
            taskDiv.innerHTML = `
                <p><strong>Задание ${index + 1}:</strong> ${task.problem}</p>
                ${task.image ? `<img src="${task.image}" alt="Задача ${index + 1}" class="test-image">` : ''}
                <input type="text" class="test-answer" data-task-index="${index}" placeholder="Ваш ответ">
                <span class="test-result"></span>
                <div class="test-solution" style="display: none;"></div>
            `;
            testContainer.appendChild(taskDiv);
        } else {
            const taskDiv = document.createElement('div');
            taskDiv.classList.add('test-task');
            taskDiv.innerHTML = `<p><strong>Задание ${index + 1}:</strong> Ошибка загрузки задачи</p>`;
            testContainer.appendChild(taskDiv);
        }
    });

    const buttonContainer = document.createElement('div');
    buttonContainer.classList.add('button-container');

    const checkTestButton = document.createElement('button');
    checkTestButton.classList.add('retro', 'rbtn-big');
    checkTestButton.id = 'check-test-button';
    checkTestButton.textContent = 'Проверить вариант';
    checkTestButton.addEventListener('click', checkTestAnswers);
    buttonContainer.appendChild(checkTestButton);

    testContainer.appendChild(buttonContainer);

    await waitForMathJax();
    MathJax.typesetPromise([testContainer]).catch(() => {});
}

async function checkTestAnswers() {
    const inputs = testContainer.querySelectorAll('.test-answer');
    let correctCount = 0;

    inputs.forEach((input, index) => {
        const userAnswer = input.value.trim();
        const correctAnswer = testTasks[index].answer.trim();
        const resultSpan = input.nextElementSibling;
        const solutionDiv = resultSpan.nextElementSibling;

        if (!userAnswer) {
            resultSpan.textContent = '✗';
            resultSpan.style.color = '#dc3545';
        } else {
            let userValue, correctValue;
            try {
                if (userAnswer.includes('/')) {
                    const [numerator, denominator] = userAnswer.split('/').map(Number);
                    userValue = numerator / denominator;
                } else {
                    userValue = parseFloat(userAnswer);
                }
                if (correctAnswer.includes('/')) {
                    const [numerator, denominator] = correctAnswer.split('/').map(Number);
                    correctValue = numerator / denominator;
                } else {
                    correctValue = parseFloat(correctAnswer);
                }

                const isCorrect = Math.abs(userValue - correctValue) < 0.01 || userAnswer === correctAnswer;
                if (isCorrect) {
                    resultSpan.textContent = '✓';
                    resultSpan.style.color = document.body.classList.contains('dark-theme') ? '#00e676' : '#28a745';
                    correctCount++;
                } else {
                    resultSpan.textContent = '✗';
                    resultSpan.style.color = '#dc3545';
                }
            } catch (e) {
                resultSpan.textContent = '✗';
                resultSpan.style.color = '#dc3545';
            }
        }

        const formattedSolution = testTasks[index].solution.replace(/\n/g, '<br>');
        solutionDiv.innerHTML = `
            <p><strong>Правильный ответ:</strong> ${testTasks[index].answer}</p>
            <p><strong>Решение:</strong><br>${formattedSolution}</p>
        `;
        solutionDiv.style.display = 'block';
    });

    solvedTasks += correctCount;
    updateCounter();

    let scoreDiv = testContainer.querySelector('.test-score');
    if (!scoreDiv) {
        scoreDiv = document.createElement('div');
        scoreDiv.classList.add('test-score');
        testContainer.appendChild(scoreDiv);
    }
    const secondaryScore = scoreConversion[correctCount] || 0;
    scoreDiv.innerHTML = `<p>Правильных ответов: ${correctCount} из 12</p><p>Вторичный балл ЕГЭ: ${secondaryScore}</p>`;

    const checkTestButton = document.getElementById('check-test-button');
    if (checkTestButton) {
        checkTestButton.disabled = true;
        checkTestButton.classList.add('disabled');
        checkTestButton.style.pointerEvents = 'none';
    }

    const buttonContainer = testContainer.querySelector('.button-container');
    if (!buttonContainer.querySelector('.next-variant')) {
        const nextVariantButton = document.createElement('button');
        nextVariantButton.classList.add('retro', 'rbtn-big', 'next-variant');
        nextVariantButton.textContent = 'Следующий вариант';
        nextVariantButton.addEventListener('click', generateTestVariant);
        buttonContainer.appendChild(nextVariantButton);

        const sendResultsButton = document.createElement('button');
        sendResultsButton.classList.add('retro', 'rbtn-big');
        sendResultsButton.textContent = 'Отправить результаты';
        sendResultsButton.addEventListener('click', () => emailModal.style.display = 'flex');
        buttonContainer.appendChild(sendResultsButton);
    }

    await waitForMathJax();
    MathJax.typesetPromise([testContainer]).catch(() => {});
}

function generateTextFile() {
    let content = "Результаты варианта ЕГЭ - Часть 1\n\n";
    testTasks.forEach((task, index) => {
        const userAnswer = testContainer.querySelector(`.test-answer[data-task-index="${index}"]`).value.trim();
        const correctAnswer = task.answer.trim();
        const isCorrect = userAnswer === correctAnswer || (
            !isNaN(parseFloat(userAnswer)) && !isNaN(parseFloat(correctAnswer)) &&
            Math.abs(parseFloat(userAnswer) - parseFloat(correctAnswer)) < 0.01
        );

        content += `Задание ${index + 1}: ${task.problem}\n`;
        content += `Ваш ответ: ${userAnswer || "Не указано"}\n`;
        content += `Правильный ответ: ${correctAnswer}\n`;
        content += `Результат: ${isCorrect ? "Правильно" : "Неправильно"}\n\n`;
    });

    const correctCount = testTasks.filter((task, index) => {
        const userAnswer = testContainer.querySelector(`.test-answer[data-task-index="${index}"]`).value.trim();
        const correctAnswer = task.answer.trim();
        return userAnswer === correctAnswer || (
            !isNaN(parseFloat(userAnswer)) && !isNaN(parseFloat(correctAnswer)) &&
            Math.abs(parseFloat(userAnswer) - parseFloat(correctAnswer)) < 0.01
        );
    }).length;
    const secondaryScore = scoreConversion[correctCount] || 0;

    content += `Правильных ответов: ${correctCount} из 12\n`;
    content += `Вторичный балл ЕГЭ: ${secondaryScore}\n`;

    return new Blob([content], { type: 'text/plain' });
}

async function sendResultsToEmail() {
    const senderName = senderNameInput.value.trim();
    const recipientEmail = recipientEmailInput.value.trim();

    if (!senderName) {
        alert('Пожалуйста, введите ваше имя!');
        return;
    }
    if (!recipientEmail || !/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(recipientEmail)) {
        alert('Пожалуйста, введите корректный email преподавателя!');
        return;
    }

    const textBlob = generateTextFile();
    const formData = new FormData();
    formData.append('file', textBlob, 'results.txt');
    formData.append('sender_name', senderName);
    formData.append('recipient_email', recipientEmail);

    loadingIndicator.style.display = 'block';
    confirmEmailButton.disabled = true;
    cancelEmailButton.disabled = true;

    try {
        const response = await fetch('/send_email.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (!response.ok || result.status !== 'success') {
            throw new Error(result.message || 'Ошибка отправки email');
        }

        loadingIndicator.style.display = 'none';
        emailModal.querySelector('.modal-content').innerHTML = `
            <p>Отправлено</p>
            <button class="retro rbtn-big" id="close-modal">Закрыть</button>
        `;
        document.getElementById('close-modal').addEventListener('click', () => {
            emailModal.style.display = 'none';
        });
    } catch (error) {
        loadingIndicator.style.display = 'none';
        confirmEmailButton.disabled = false;
        cancelEmailButton.disabled = false;
        alert('Ошибка отправки результатов: ' + error.message);
    }
}

confirmEmailButton.addEventListener('click', sendResultsToEmail);
cancelEmailButton.addEventListener('click', () => emailModal.style.display = 'none');
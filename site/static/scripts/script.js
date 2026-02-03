const startButton = document.getElementById('start-button');
const typewriterLeft = document.getElementById('typewriter-left');
const typewriterRight = document.getElementById('typewriter-right');
const optimusTheme = document.getElementById('optimus-theme');
const overlay = document.querySelector('.overlay');
const particlesContainer = document.querySelector('.particles-container');
const mainText = document.querySelector('.main_text');
const title = mainText.querySelector('h1');
const description = mainText.querySelector('.description');

const equationsWithSolutions = [
    { equation: "x + 5 = 10", solution: ["    x + 5 - 5 = 10 - 5", "    x = 5"] },
    { equation: "2x² - 8 = 0", solution: ["    2x² = 8", "    x² = 4", "    x = ±2"] },
    { equation: "3x - 9 = 0", solution: ["    3x = 9", "    x = 3"] },
    { equation: "x/2 + 3 = 7", solution: ["    x/2 = 7 - 3", "    x/2 = 4", "    x = 8"] },
    { equation: "x² + 2x + 1 = 0", solution: ["    (x + 1)² = 0", "    x + 1 = 0", "    x = -1"] },
    { equation: "4x + 2 = 18", solution: ["    4x = 18 - 2", "    x = 16", "    x = 4"] },
    { equation: "x² - 5x + 6 = 0", solution: ["    (x - 2)(x - 3) = 0", "    x - 2 = 0  or  x - 3 = 0", "    x = 2  or  x = 3"] },
    { equation: "2x + 3y = 12", solution: ["    3y = 12 - 2x", "    y = (12 - 2x)/3"] },
    { equation: "sin(x) = 0.5", solution: ["    x = arcsin(0.5)", "    x = π/6  or  5π/6"] },
    { equation: "log₂(x) = 3", solution: ["    x = 2³", "    x = 8"] },
    { equation: "x² - 4x + 4 = 0", solution: ["    (x - 2)² = 0", "    x - 2 = 0", "    x = 2"] },
    { equation: "√(x + 1) = 3", solution: ["    x + 1 = 9", "    x = 8"] },
    { equation: "2x² + 3x - 2 = 0", solution: ["    D = 3² - 4·2·(-2) = 25", "    x = (-3 ± √25)/(2·2)", "    x = 1  or  x = -2"] },
    { equation: "cos(x) = -1", solution: ["    x = arccos(-1)", "    x = π"] },
    { equation: "e^(2x) = 1", solution: ["    2x = ln(1)", "    2x = 0", "    x = 0"] },
    { equation: "x³ - 8 = 0", solution: ["    x³ = 8", "    x = ³√8", "    x = 2"] },
    { equation: "tan(x) = 1", solution: ["    x = arctan(1)", "    x = π/4"] },
    { equation: "5x - 2 = 13", solution: ["    5x = 15", "    x = 3"] },
    { equation: "ln(x) = 2", solution: ["    x = e²", "    x ≈ 7.39"] },
    { equation: "x² + 6x + 9 = 0", solution: ["    (x + 3)² = 0", "    x + 3 = 0", "    x = -3"] }
];

const typingSpeed = 40;
const maxLines = 20;
const initialLines = 15;

function generateEquationText() {
    const randomIndex = Math.floor(Math.random() * equationsWithSolutions.length);
    const { equation, solution } = equationsWithSolutions[randomIndex];
    return [equation, ...solution].join('\n') + '\n\n';
}

function fillColumn(targetTypewriter) {
    targetTypewriter.innerHTML = '';
    for (let i = 0; i < initialLines; i++) {
        const textSpan = document.createElement('span');
        textSpan.textContent = generateEquationText();
        targetTypewriter.appendChild(textSpan);
    }
}

function typeEquation(column, targetTypewriter) {
    const randomIndex = Math.floor(Math.random() * equationsWithSolutions.length);
    const { equation, solution } = equationsWithSolutions[randomIndex];
    const fullText = [equation, ...solution].join('\n') + '\n\n';

    const textSpan = document.createElement('span');
    textSpan.textContent = '';
    targetTypewriter.appendChild(textSpan);

    const lineHeight = parseFloat(getComputedStyle(targetTypewriter).lineHeight);
    const maxHeight = targetTypewriter.offsetHeight;
    const currentHeight = targetTypewriter.scrollHeight;
    if (currentHeight > maxHeight) {
        const firstChild = targetTypewriter.firstChild;
        if (firstChild) {
            firstChild.classList.add('fade-out');
            setTimeout(() => firstChild.remove(), 500);
        }
    }

    let index = 0;
    const chars = fullText.split('');

    const type = () => {
        if (index < chars.length) {
            textSpan.textContent += chars[index];
            targetTypewriter.scrollTop = targetTypewriter.scrollHeight;
            index++;
            setTimeout(type, typingSpeed);
        }
    };
    type();
}

function typeSimultaneously() {
    typeEquation('left', typewriterLeft);
    typeEquation('right', typewriterRight);
    setTimeout(typeSimultaneously, 2000);
}

function resetPageState() {
    overlay.classList.remove('active');
    startButton.classList.remove('transforming');
    title.classList.remove('transforming');
    description.classList.remove('transforming');
    typewriterLeft.classList.remove('transforming');
    typewriterRight.classList.remove('transforming');
    document.body.classList.remove('animating');
    startButton.style.pointerEvents = 'auto';

    fillColumn(typewriterLeft);
    fillColumn(typewriterRight);

    typewriterLeft.style.opacity = '0';
    typewriterRight.style.opacity = '0';
    setTimeout(() => {
        typewriterLeft.style.transition = 'opacity 1s ease';
        typewriterRight.style.transition = 'opacity 1s ease';
        typewriterLeft.style.opacity = '1';
        typewriterRight.style.opacity = '1';
    }, 100);

    setTimeout(typeSimultaneously, 1000);
}

window.addEventListener('pageshow', (event) => {
    resetPageState();
    if (event.persisted) {
        startButton.style.pointerEvents = 'auto';
    }
});

function handleStartClick() {
    startButton.style.pointerEvents = 'none';
    document.body.classList.add('animating');

    if (optimusTheme) {
        optimusTheme.play()
            .then(() => {
                overlay.classList.add('active');
                startButton.classList.add('transforming');
                title.classList.add('transforming');
                description.classList.add('transforming');
                typewriterLeft.classList.add('transforming');
                typewriterRight.classList.add('transforming');

                setTimeout(() => createExplosion(window.innerWidth / 2, window.innerHeight / 2), 1000);
                setTimeout(() => createSparks(20, window.innerWidth / 2, window.innerHeight / 2), 1500);
                setTimeout(() => createParticles(30), 2000);
                setTimeout(() => createSparks(15, window.innerWidth / 3, window.innerHeight / 3), 3000);
                setTimeout(() => createExplosion(window.innerWidth * 0.75, window.innerHeight * 0.25), 4000);
                setTimeout(() => createParticles(20), 4350);
                setTimeout(() => createSparks(25, window.innerWidth / 2, window.innerHeight * 0.75), 4950);

                setTimeout(() => {
                    window.location.href = '/ege-matematika/';
                }, 5300);
            })
            .catch(() => {
                window.location.href = '/ege-matematika/';
            });
    } else {
        window.location.href = '/ege-matematika/';
    }
}

startButton.addEventListener('click', handleStartClick);

function createSparks(count, x, y) {
    for (let i = 0; i < count; i++) {
        const spark = document.createElement('div');
        spark.classList.add('spark');
        spark.style.left = `${x}px`;
        spark.style.top = `${y}px`;
        particlesContainer.appendChild(spark);
        setTimeout(() => spark.remove(), 2000);
    }
}

function createParticles(count) {
    for (let i = 0; i < count; i++) {
        const particle = document.createElement('div');
        particle.classList.add('transform-particle');
        particle.style.left = `${Math.random() * 100}vw`;
        particle.style.top = `${Math.random() * 100}vh`;
        particlesContainer.appendChild(particle);
        setTimeout(() => particle.remove(), 3000);
    }
}

function createExplosion(x, y) {
    const explosion = document.createElement('div');
    explosion.classList.add('explosion');
    explosion.style.left = `${x}px`;
    explosion.style.top = `${y}px`;
    particlesContainer.appendChild(explosion);
    setTimeout(() => explosion.remove(), 1000);
}
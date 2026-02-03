// Запрет правого клика
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
});

// Запрет выделения текста
document.addEventListener('selectstart', function(e) {
    e.preventDefault();
});

// Запрет комбинаций клавиш (Ctrl+U, Ctrl+S)
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && (e.key === 'u' || e.key === 's')) {
        e.preventDefault();
    }
});
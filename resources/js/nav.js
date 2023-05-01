const chevronContainer = document.querySelector('#chevronContainer');

chevronContainer.addEventListener('click', () => {
        chevronContainer.closest('nav').classList.toggle('w-full');
        chevronContainer.classList.toggle('rotate180');
})
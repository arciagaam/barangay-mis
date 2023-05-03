const chevronContainer = document.querySelector('#chevronContainer');
const navChevron = document.querySelector('#nav_chevron')
const maintenanceMain = document.querySelector('#maintenance_main')
const maintenanceBtn = document.querySelector('#maintenance');
const maintenanceLinks = document.querySelector('#maintenance_links');

chevronContainer.addEventListener('click', () => {
        chevronContainer.closest('nav').classList.toggle('w-full');
        chevronContainer.classList.toggle('rotate180');
})

maintenanceBtn.addEventListener('click', () => {
        maintenanceLinks.classList.toggle('max-h-[500px]');
        maintenanceLinks.classList.toggle('max-h-0');
        maintenanceLinks.classList.toggle('mt-5');
        maintenanceMain.classList.toggle('border-t');
        maintenanceMain.classList.toggle('border-b');
        maintenanceMain.classList.toggle('border-white');
        maintenanceMain.classList.toggle('py-5');
        navChevron.classList.toggle('rotate-180')
})
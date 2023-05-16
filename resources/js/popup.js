const popupTrigger = document.querySelectorAll('popup_trigger');
const modal = document.querySelector('#warning_modal');
let fetchUrl = '';
let _method = '';

document.addEventListener('click', (e) => {
    const target = e.target;
    if(target.classList.contains('popup_trigger') || target.closest('.popup_trigger')) {
        const btnTarget = target.classList.contains('.popup_trigger') ? target : target.closest('.popup_trigger')
        const {url, type, group} = btnTarget.dataset
        fetchUrl = url;
        openModal({url, type, group});
    }

    e.stopPropagation()
})

function openModal({url, type, group}) {
    modal.querySelector('p').innerText = `Are you sure you want to ${type} this ${group}`;
    modal.classList.toggle('hidden');
}

modal.querySelector('#submit').addEventListener('click', async () => {
    const data = await fetch(fetchUrl, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'same-origin',
    });

    modal.classList.toggle('hidden');
    location.reload();
})

modal.querySelector('#cancel').addEventListener('click', () => {
    modal.classList.toggle('hidden');
})
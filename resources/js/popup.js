const popupTrigger = document.querySelectorAll('popup_trigger');
const modal = document.querySelector('#warning_modal');
let fetchUrl = '';
let fetchType = '';
let _method = '';

document.addEventListener('click', (e) => {
    const target = e.target;
    if(target.classList.contains('popup_trigger') || target.closest('.popup_trigger')) {
        const btnTarget = target.classList.contains('.popup_trigger') ? target : target.closest('.popup_trigger')
        const {url, type, group} = btnTarget.dataset
        fetchType = type;
        fetchUrl = url;
        openModal({url, type, group});
    }

    e.stopPropagation()
})

function openModal({url, type, group}) {

    if(type == 'archive') {
        modal.querySelector('#archive_input').classList.toggle('hidden');
    }else{
        modal.querySelector('#confirm_text').innerText = `Are you sure you want to ${type} this ${group}`;
    }
    
    modal.classList.toggle('hidden');
}

modal.querySelector('#submit').addEventListener('click', async () => {
    if(fetchType == 'archive') {
        const data = await fetch(fetchUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                reason: modal.querySelector('#reason').value
            }),
        });
        
        location.reload();
    } else if (fetchType == 'delete') {
        const data = await fetch(fetchUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'same-origin',
        });

        location.href = BASE_PATH + "/maintenance/users"
        return false

    } else {
        const data = await fetch(fetchUrl, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'same-origin',
        });
        
        location.reload();
    }

    modal.classList.toggle('hidden');
})

modal.querySelector('#cancel').addEventListener('click', () => {
    modal.classList.toggle('hidden');
})
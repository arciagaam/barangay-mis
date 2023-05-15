const returnBtn = document.querySelector('#return_btn');
const returnModal = document.querySelector('#return_modal');
const closeModal = document.querySelector('#close_modal');
const submitReturn = document.querySelector('#submit_return');

closeModal.addEventListener('click', () => {
    returnModal.classList.add('hidden');
    document.querySelector('#error_returned_quantity').innerText = '';
    document.querySelector('#error_return_remarks').innerText = '';
    document.querySelector('#return_remarks').value = '';
    document.querySelector('#returned_quantity').value = '';
});

if(returnBtn) {
    returnBtn.addEventListener('click', () => {
        returnModal.classList.toggle('hidden');
    });
}

submitReturn.addEventListener('click', async () => {
    console.log(document.querySelector('#remarks').value)

    const data = await fetch(BASE_PATH + `/lend/${submitReturn.dataset.id}/return`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'same-origin',
        body: JSON.stringify({
            returned_quantity: document.querySelector('#returned_quantity').value ,
            remarks: document.querySelector('#return_remarks').value
        })
    });

    if(data.status == 422) {
        const {returned_quantity, remarks} = await data.json();

        document.querySelector('#error_returned_quantity').innerText = returned_quantity ?? '';
        document.querySelector('#error_return_remarks').innerText = remarks ?? '';
    }else {
        closeModal.click();
        location.reload();
    }
});
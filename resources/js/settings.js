const viewBtn = document.querySelectorAll('.view_btn');
const deleteBtn = document.querySelectorAll('.delete_btn');

const addPosition = document.querySelector('#add_position');
const addCivilStatus = document.querySelector('#add_civil_status');
const addOccupation = document.querySelector('#add_occupation');
const addReligion = document.querySelector('#add_religion');
const addSecurityQuestion = document.querySelector('#add_security_question');
const addGender = document.querySelector('#add_gender');

const addModal = document.querySelector('#add_modal');
const submitBtn = document.querySelector('#submit');
const closeModal = document.querySelector('#close_modal');
const modalTitle = document.querySelector('#modal_title');

let id = '';

closeModal.addEventListener('click', () => {
    document.querySelector('#name').value = ""
    document.querySelector('#error_name').innerText = ""
    addModal.classList.add('hidden');
})

viewBtn.forEach(view => {
    view.addEventListener('click', async () => {

        const data = await fetch(BASE_PATH + `/api/${view.dataset.type}/${view.dataset.id}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'same-origin',
        })

        if (data.status == 422) {
            const { message } = await data.json();
            console.log(message);
        }

        const fetchData = await data.json();

        document.querySelector('#name').value = fetchData.data.name;
        id = fetchData.data.id;
        submitBtn.dataset.type = 'update';
        submitBtn.innerText = 'Update Position';
        modalTitle.innerHTML = 'Edit Position'
        addModal.classList.toggle('hidden');

    })
});

deleteBtn.forasd

submitBtn.addEventListener('click', async () => {
    const _method = submitBtn.dataset.type == 'update' ? 'PATCH' : 'PUT'
    console.log(_method)
    const url = _method == 'PATCH' ? `/api/${submitBtn.dataset.group}/${id}` : `/api/${submitBtn.dataset.group}`
    const data = await fetch(BASE_PATH + url, {
        method: _method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            name: document.querySelector('#name').value
        }),
        credentials: 'same-origin',
    })

    if (data.status == 422) {
        const { name } = await data.json();
        document.querySelector('#error_name').innerText = name ?? '';
    }

    if (data.status == 200) {
        closeModal.click();
        location.reload();
    }
})

if (addPosition) {
    addPosition.addEventListener('click', () => {
        submitBtn.dataset.type = 'create';
        submitBtn.innerText = 'Add Position'
        modalTitle.innerHTML = 'Add New Position'
        addModal.classList.toggle('hidden');
    })
}

if (addCivilStatus) {
    addCivilStatus.addEventListener('click', () => {
        submitBtn.dataset.type = 'create';
        submitBtn.innerText = 'Add Civil Status'
        modalTitle.innerHTML = 'Add New Civil Status'
        addModal.classList.toggle('hidden');
    })
}

if (addOccupation) {
    addOccupation.addEventListener('click', () => {
        submitBtn.dataset.type = 'create';
        submitBtn.innerText = 'Add Occupation'
        modalTitle.innerHTML = 'Add New Occupation'
        addModal.classList.toggle('hidden');
    })
}

if (addReligion) {
    addReligion.addEventListener('click', () => {
        submitBtn.dataset.type = 'create';
        submitBtn.innerText = 'Add Religion'
        modalTitle.innerHTML = 'Add New Religion'
        addModal.classList.toggle('hidden');
    })
}

if (addSecurityQuestion) {
    addSecurityQuestion.addEventListener('click', () => {
        submitBtn.dataset.type = 'create';
        submitBtn.innerText = 'Add Security Question'
        modalTitle.innerHTML = 'Add New Security Question'
        addModal.classList.toggle('hidden');
    })
}

if (addGender) {
    addSecurityQuestion.addEventListener('click', () => {
        submitBtn.dataset.type = 'create';
        submitBtn.innerText = 'Add Gender'
        modalTitle.innerHTML = 'Add Gender'
        addModal.classList.toggle('hidden');
    })
}
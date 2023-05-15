const residentSearch = document.querySelector('#resident_search');

if(residentSearch) {

    const searchNamesContainer = document.querySelector('#search_names_container');

    let timeout;
    residentSearch.addEventListener('input', (e) => {
        searchNamesContainer.innerText = '';
        const nameContainer = Object.assign(document.createElement('p'), {
            class: 'flex w-full',
            textContent: 'Searching...'
        })

        searchNamesContainer.append(nameContainer);

        clearTimeout(timeout);

        timeout = setTimeout(() => {
            fetchResident(e.target.value)
        }, 500);
    })

    residentSearch.addEventListener('focus', () => {
        searchNamesContainer.innerText = '';
        const nameContainer = Object.assign(document.createElement('p'), {
            className: 'flex w-full',
            textContent: 'Search for resident.'
        })

        searchNamesContainer.append(nameContainer);
        searchNamesContainer.classList.remove('hidden');

        if(residentSearch.value.length > 0) {
            fetchResident(residentSearch.value);
        }
    });

    residentSearch.addEventListener('blur', (e) => {
        searchNamesContainer.classList.add('hidden');
    });

    function fetchResident(searchQuery) {
        if(searchQuery != ''){
            fetch(BASE_PATH + `/api/residents/?search=${searchQuery}`)
            .then(res => res.json())
            .then(data => {
                if(data.residents.length > 0){
                    searchNamesContainer.innerText = '';
                    data.residents.forEach(resident => {

                        const selectResidentContainer = Object.assign(document.createElement('div'), {
                            className: 'flex flex-col w-full cursor-pointer hover:bg-table-even transition-all duration-300 ease-in-out rounded-md py-1 px-2',
                            onmousedown: (e) => {selectResident(resident.resident_id, resident)}
                        })

                        const nameContainer = Object.assign(document.createElement('p'), {
                            textContent: `${resident.first_name} ${resident.middle_name ?? ''} ${resident.last_name}`
                        })

                        const addressContainer = Object.assign(document.createElement('p'), {
                            textContent: `${resident.house_number} ${resident.others}`,
                            className: 'italic text-xs text-project-blue/40'
                        })

                        selectResidentContainer.append(nameContainer);
                        selectResidentContainer.append(addressContainer);
                        searchNamesContainer.append(selectResidentContainer);
                    })
                } else {
                    searchNamesContainer.innerText = '';
                    const nameContainer = Object.assign(document.createElement('p'), {
                        className: 'flex w-full',
                        textContent: 'No results found.'
                    })
                    searchNamesContainer.append(nameContainer);
                }
            })

        } else {
            searchNamesContainer.innerText = '';
            const nameContainer = Object.assign(document.createElement('p'), {
                className: 'flex w-full',
                textContent: 'Search for resident.'
            })

            searchNamesContainer.append(nameContainer);
        }
    }

    function selectResident(id, resident) {
        const residentIdInput = document.querySelector('#resident_id') 
        if(residentIdInput){
            residentIdInput.value = id;
        }else{
            const hiddenInput = Object.assign(document.createElement('input'), {
                type:'hidden',
                id:'resident_id',
                name:'resident_id',
                value:id,
            })
            document.querySelector('form').append(hiddenInput);
        }

        document.querySelectorAll('.form-input').forEach(input => {
            console.log(input.name);
            if(input.name in resident) {
                input.value = resident[input.name];

                if(input.name == 'sex') {
                    const select = document.querySelector('#sex'); 
                    select.querySelectorAll('option').forEach(option => {
                        if(option.value == resident[input.name]) {
                            option.selected = true;
                        }
                    })
                }

                if(input.name == 'voter_status') {
                    input.value = resident[input.name] == 1 ? 'Registered' : 'Unregistered'
                }

                if(input.name == 'disabled') {
                    input.value = resident[input.name] == 1 ? 'Disabled' : 'Abled'
                }
            }
        })
    }

}
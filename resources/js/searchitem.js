const itemSearch = document.querySelector('#item_search');

if (itemSearch) {

    const searchItemsContainer = document.querySelector('#search_items_container');
    let quantity = 0;

    let timeout;
    itemSearch.addEventListener('input', (e) => {
        searchItemsContainer.innerText = '';
        const nameContainer = Object.assign(document.createElement('p'), {
            class: 'flex w-full',
            textContent: 'Searching...'
        })

        searchItemsContainer.append(nameContainer);

        clearTimeout(timeout);

        timeout = setTimeout(() => {
            fetchResident(e.target.value)
        }, 500);
    })

    itemSearch.addEventListener('focus', () => {
        searchItemsContainer.innerText = '';
        const nameContainer = Object.assign(document.createElement('p'), {
            className: 'flex w-full',
            textContent: 'Search for item.'
        })

        searchItemsContainer.append(nameContainer);
        searchItemsContainer.classList.remove('hidden');

        if (itemSearch.value.length > 0) {
            fetchResident(itemSearch.value);
        }
    });

    itemSearch.addEventListener('blur', (e) => {
        searchItemsContainer.classList.add('hidden');
    });

    function fetchResident(searchQuery) {
        if (searchQuery != '') {
            fetch(BASE_PATH + `/api/inventory/?search=${searchQuery}`)
                .then(res => res.json())
                .then(data => {
                    if (data.items.length > 0) {
                        searchItemsContainer.innerText = '';
                        data.items.forEach(item => {

                            console.log(item);

                            const selectItemContainer = Object.assign(document.createElement('div'), {
                                className: 'flex flex-col w-full cursor-pointer hover:bg-table-even transition-all duration-300 ease-in-out rounded-md py-1 px-2',
                                onmousedown: (e) => { selectItem(item.id, item) }
                            })

                            const nameContainer = Object.assign(document.createElement('p'), {
                                textContent: `${item.name}`
                            })

                            const quantityContainer = Object.assign(document.createElement('p'), {
                                textContent: `${item.quantity}`,
                                className: 'italic text-xs text-project-blue/40'
                            })

                            const remarksContainer = Object.assign(document.createElement('p'), {
                                textContent: `${item.remarks}`,
                                className: 'italic text-xs text-project-blue/40'
                            })

                            selectItemContainer.append(nameContainer);
                            selectItemContainer.append(quantityContainer);
                            selectItemContainer.append(remarksContainer);
                            searchItemsContainer.append(selectItemContainer);
                        })
                    } else {
                        searchItemsContainer.innerText = '';
                        const nameContainer = Object.assign(document.createElement('p'), {
                            className: 'flex w-full',
                            textContent: 'No results found.'
                        })
                        searchItemsContainer.append(nameContainer);
                    }
                })

        } else {
            searchItemsContainer.innerText = '';
            const nameContainer = Object.assign(document.createElement('p'), {
                className: 'flex w-full',
                textContent: 'Search for item.'
            })

            searchItemsContainer.append(nameContainer);
        }
    }

    function selectItem(id, item) {
        const inventoryIdInput = document.querySelector('#id') 
        if(inventoryIdInput){
            inventoryIdInput.value = id;
        }else{
            const hiddenInput = Object.assign(document.createElement('input'), {
                type: 'hidden',
                id: 'id',
                name: 'id',
                value: id,
            })
            quantity = item.quantity;
    
            document.querySelector('form').append(hiddenInput);
            document.querySelectorAll('input').forEach(input => {
                if (input.name in item) {
                    input.value = item[input.name];
                }
            })
    
            document.querySelectorAll('textarea').forEach(textarea => {
                if (textarea.name in item) {
                    textarea.value = item[textarea.name];
                }
            })
        }

    }

    document.addEventListener('change', (e) => {
        const target = e.target;
    
        if(target.id == 'quantity') {
            if(!target.value || target.value == '') return;
    
            if(target.value > quantity) {
                target.value = quantity;
            }
        }
    
        e.stopPropagation();
    })
}




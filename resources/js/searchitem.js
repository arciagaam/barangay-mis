
const searchItemsContainer = document.querySelector('#search_items_container');

document.querySelector('#inventory_id').addEventListener('change', (e) => {
    fetchItem(e.target.value)
})

function fetchItem(id) {
    fetch(BASE_PATH + `/api/inventory/${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.item) {
                selectItem(data.item.id, data.item);
            }
        })
}

function selectItem(id, item) {
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

document.addEventListener('change', (e) => {
    const target = e.target;
    if (target.id == 'quantity') {
        if (!target.value || target.value == '') return;

        const quantity = document.querySelector('#item_quantity').value;

        if (target.value > quantity) {
            target.value = quantity;
        }
    }

    e.stopPropagation();
})





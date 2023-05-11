const logoInput = document.querySelector('#logo_input');

if(logoInput) {

    logoInput.addEventListener('change', (e) => {
        document.querySelector('#img_logo').src = URL.createObjectURL(e.target.files[0]);
    })

}
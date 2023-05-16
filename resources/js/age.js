const birthDate = document.querySelector('#birth_date');

const calculateAge = (birthDate) => {
    const diff_ms = Date.now() - new Date(birthDate).getTime();
    const age_dt = new Date(diff_ms);
    
    return (age_dt.getUTCFullYear() - 1970) < 0 ? 0 : Math.abs(age_dt.getUTCFullYear() - 1970);
};


if (birthDate) {
    const ageInput = document.querySelector('#age');
    window.addEventListener('load', () => {
        ageInput.value = birthDate.value !== '' ? calculateAge(birthDate.value) : 0;
    });
    
    birthDate.addEventListener('change', (event) => {
        console.log(event.target.value)
        ageInput.value = calculateAge(event.target.value);
    });
};
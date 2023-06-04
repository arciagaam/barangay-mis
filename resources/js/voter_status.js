const voterStatus = document.querySelector('#voter_status');
const birthDate = document.querySelector('#birth_date');

const calculateAge = (birthDate) => {
    const diff_ms = Date.now() - new Date(birthDate).getTime();
    const age_dt = new Date(diff_ms);
    
    return (age_dt.getUTCFullYear() - 1970) < 0 ? 0 : Math.abs(age_dt.getUTCFullYear() - 1970);
};


if(voterStatus) {
    let age = 0;

    window.addEventListener('load', () => {
        age = birthDate.value !== '' ? calculateAge(birthDate.value) : 0;
        console.log(age);
        if (age < 18) {
            voterStatus.closest('div').classList.add('hidden');
        }else{
            voterStatus.closest('div').classList.remove('hidden');
        }
    });

    birthDate.addEventListener('change', (event) => {
        age = calculateAge(event.target.value);
        if (age < 18) {
            voterStatus.closest('div').classList.add('hidden');
        }else{
            voterStatus.closest('div').classList.remove('hidden');
        }
    });
}
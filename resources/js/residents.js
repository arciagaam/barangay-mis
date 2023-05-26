const voterStatus = document.querySelector('#voter_status');

if(voterStatus) {
    document.querySelector('#voter_status').addEventListener('change', function () {

        if(voterStatus.value == 1) {
            document.querySelector('#precinct_number').closest('div').classList.remove('hidden');
        }else{
            document.querySelector('#precinct_number').closest('div').classList.add('hidden');
        }
    });
}
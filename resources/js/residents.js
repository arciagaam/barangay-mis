const voterStatus = document.querySelector('#voter_status');

if(voterStatus) {
    document.querySelector('#voter_status').addEventListener('change', function () {
        document.querySelector('#precinct_number').closest('div').classList.toggle('hidden');
    });
}
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

const calendarEl = document.querySelector('#calendarEl')
const addModal = document.querySelector('#add_activity');
const closeModal = document.querySelector('#close_modal');
const allDay = document.querySelector('#all_day');
const submitActivity = document.querySelector('#submit_activity');

const startDate = document.querySelector('#start_date');
const startTime = document.querySelector('#start_time');
const endDate = document.querySelector('#end_date');
const endTime = document.querySelector('#end_time');

let events = []

if(calendarEl){
    let calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
    
        initialView: 'dayGridMonth',
    
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek',
        },
    
        buttonText: {
            today: 'Today',
            month: 'Month',
            list: 'List',
        },
        
        showNonCurrentDates: false,
        fixedWeekCount: false,
    
        datesSet: event => {
            // As the calendar starts from prev month and end in next month I take the day between the range
            var midDate = new Date((event.start.getTime() + event.end.getTime()) / 2)
            var month = midDate.getMonth() + 1
            getMonthEvents(month, midDate.getFullYear())
        },
    
        eventClick: function (info) {
            const id = info.event.extendedProps.id
            location.href = BASE_PATH + '/calendar/activity/' + id
        },
    
        dateClick: function(event) {
            addModal.classList.toggle('hidden');
    
            startDate.value = event.dateStr;
            endDate.value = event.dateStr;
            endDate.min = event.dateStr;
        }
    });
    
    calendar.render();
     
    async function getMonthEvents(month, year) {
        events = []
        const stringMonth = leadingZeroes(month)
    
        const data = await fetch(BASE_PATH + '/api/calendar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                month: stringMonth,
                year: year,
            })
        })
        .then(res => res.json());
    
        data.activities.map((activity) => {
            events.push(
                {extendedProps:{id:activity.id} , title: activity.name, start: activity.start_date}
            )
        })
    
        calendar.removeAllEvents();
        events.forEach(event => {
            calendar.addEvent(event)
        })
    }
    
    function leadingZeroes(numString) {
        if(isNaN(numString)){
            return '00'
        }
    
        if(numString < 10) {
            return `0${numString}`
        }else {
            return numString
        }
    }
    
    closeModal.addEventListener('click', () => {
        addModal.classList.add('hidden');

        startDate.value = ""
        endDate.value = ""
        startTime.value = ""
        endTime.value = ""
        document.querySelector('#name').value = ""
        document.querySelector('#description').value = ""
        document.querySelector('#error_name').innerText = "";
        document.querySelector('#error_description').innerText = "";
        document.querySelector('#error_start_end_time').innerText = "";
    })

    submitActivity.addEventListener('click', async () => {
    
        const data = await fetch(BASE_PATH + '/api/calendar', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                start_date: startDate.value,
                end_date: endDate.value,
                start_time: startTime.value,
                end_time: endTime.value,
                name: document.querySelector('#name').value,
                description: document.querySelector('#description').value,
                is_all_day: allDay.checked ? 1 : 0
            })
        })
    
        if(data.status == 422) {
            const {name, description, message} = await data.json(); 

            if(name || description) {
                document.querySelector('#error_name').innerText = name ?? '';
                document.querySelector('#error_description').innerText = description ?? '';
            }

            if(message) {
                document.querySelector('#error_start_end_time').innerText = message ?? '';
            }
        } else if (data.status == 433) {

        } else {
            const {id} = await data.json(); 

            calendar.addEvent({extendedProps:{id:id}, title:document.querySelector('#name').value, start:startDate.value})
            
            startDate.value = ""
            endDate.value = ""
            startTime.value = ""
            endTime.value = ""
            document.querySelector('#name').value = ""
            document.querySelector('#description').value = ""
            allDay.checked = 0
            closeModal.click();
        }
    
    })
}

allDay.addEventListener('change', () => {
    endTime.closest('.form-input-container').classList.toggle('hidden');
    startTime.closest('.form-input-container').classList.toggle('hidden');

    startTime.value = '';
    endTime.value = '';
})


let selectedTimeSlots = [];

document.addEventListener('DOMContentLoaded', initializeApp);

function initializeApp() {
    console.log('App initializing...');
    try {
        attachTableClickListeners();
        attachCheckAvailabilityButtonListener();
        attachConfirmReservationButtonListener();
    } catch (error) {
        console.error('Initialization Error:', error);
    }
}

function attachTableClickListeners() {
    const tables = document.querySelectorAll('.table');
    
    tables.forEach(table => {
        table.addEventListener('click', function() {
            const seatsInput = document.getElementById('seatsInput');
            const dateInput = document.getElementById('date');
            
            if (!seatsInput || !dateInput) {
                console.error('Seats or Date input element not found.');
                return;
            }

            const seats = seatsInput.value;
            const date = dateInput.value;

            if (!seats || !date) {
                alert("Please input the number of seats and select a date before selecting a table.");
                return;
            }

            if (this.classList.contains('available')) {
                fetchTableSchedule(this.dataset.id);
            }
        });
    });
    console.log(`${tables.length} table click listeners attached.`);
}


function attachCheckAvailabilityButtonListener() {
    const checkAvailabilityButton = document.querySelector("#checkAvailabilityButton");

    if (checkAvailabilityButton) {
        checkAvailabilityButton.addEventListener('click', checkTableAvailability);
        console.log('Check availability button listener attached.');
    } else {
        console.warn('Check availability button not found.');
    }
}

function attachConfirmReservationButtonListener() {
    const confirmButton = document.querySelector("#confirmReservationButton");

    if (confirmButton) {
        confirmButton.addEventListener('click', confirmReservation);
        console.log('Confirm reservation button listener attached.');
    } else {
        console.warn('Confirm reservation button not found.');
    }
}

function checkTableAvailability() {
    console.log('Checking table availability...');
    const seatsInput = document.getElementById('seatsInput');

    if (!seatsInput) {
        console.error('Seats input element not found.');
        return;
    }

    const seats = seatsInput.value;

    fetch(`api.php?seats=${seats}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error ${response.status}`);
            }
            return response.json();
        })
        .then(data => updateTablesAvailability(data.tables))
        .catch(error => console.error('Error fetching table availability:', error));
}

function updateTablesAvailability(availableTables) {
    const tables = document.querySelectorAll('.table');

    tables.forEach(table => {
        const isAvailable = availableTables.some(
            availableTable => availableTable.id == table.dataset.id
        );

        table.classList.toggle('available', isAvailable);
        table.classList.toggle('reserved', !isAvailable);
    });

    console.log(`Table availability updated for ${tables.length} tables.`);
}

function fetchTableSchedule(tableId) {
    console.log(`Fetching schedule for table ${tableId}...`);
    const dateInput = document.getElementById('date');

    if (!dateInput) {
        console.error('Date input element not found.');
        return;
    }

    const date = dateInput.value;

    fetch(`api.php?reservations=true&table_id=${tableId}&date=${date}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error ${response.status}`);
            }
            return response.json();
        })
        .then(data => renderTableSchedule(tableId, data.reservations))
        .catch(error => console.error('Error fetching table schedule:', error));

    const allTables = document.querySelectorAll('.table');
    allTables.forEach(table => {
        if (table.dataset.id !== tableId) {
            const otherSchedule = table.querySelector('.table-schedule');
            if (otherSchedule) {
                otherSchedule.style.display = 'none';
            }
        }
    });
}

function resetSelectedTimeSlots() {
    selectedTimeSlots.forEach(slot => {
        slot.classList.remove('time-slot-selected');
        slot.classList.add('time-slot-available');
    });
    selectedTimeSlots = [];
}

function renderTableSchedule(tableId, reservations) {
    console.log(`Rendering schedule for table ${tableId}...`);

    const existingSchedule = document.querySelector('#table-schedule-container');

    if (!existingSchedule) {
        console.error('Schedule container not found.');
        return;
    }

    existingSchedule.innerHTML = '';

    const startHour = 10;
    const endHour = 21;

    for (let i = startHour; i < endHour; i++) {
        for (let j = 0; j < 2; j++) {
            const currentTime = `${String(i).padStart(2, '0')}:${j === 0 ? '00' : '30'}`;
            
            const isReserved = reservations.some(reservation => {
                const reservationStartTime = reservation.time.slice(0, 5);
                const reservationEndTime = reservation.end_time.slice(0, 5);
                return currentTime >= reservationStartTime && currentTime < reservationEndTime;
            });

            const timeSlot = createTimeSlot(currentTime, isReserved);
            if (!isReserved) {
                timeSlot.addEventListener('click', (event) => selectTime(timeSlot, tableId, event));
            }
            existingSchedule.appendChild(timeSlot);
        }
    }
}



function createTimeSlot(time, isReserved) {
    const timeSlot = document.createElement('div');
    timeSlot.classList.add('time-slot');
    timeSlot.textContent = time;
    timeSlot.classList.add(isReserved ? 'time-slot-reserved' : 'time-slot-available');
    timeSlot.dataset.time = time;
    return timeSlot;
}

let startTime = null;
let endTime = null;

function selectTime(timeSlot, tableId, event) {
    event.stopPropagation();

    const confirmButton = document.querySelector("#confirmReservationButton");

    if (!startTime) {
        resetSelectedTimeSlots(); 
        selectedTimeSlots.push(timeSlot);
        startTime = timeSlot.dataset.time;
        timeSlot.classList.remove('time-slot-available', 'time-slot-reserved');
        timeSlot.classList.add('time-slot-selected');
    } else if (!endTime) {
        endTime = timeSlot.dataset.time;

        if (startTime < endTime) {
            const rangeValid = selectTimeRange(tableId); 
            if (!rangeValid) {
                alert('Cannot reserve overlapping time slots.');
                resetSelectedTimeSlots();
                confirmButton.style.display = 'none';
            } else {
                confirmButton.style.display = 'block';
            }
        } else {
            alert('End time must be after start time');
            resetSelectedTimeSlots();
            confirmButton.style.display = 'none';
        }

        startTime = null;
        endTime = null;
    }
}

function selectTimeRange(tableId) {
    console.log('Selecting time range...');

    const timeSlots = document.querySelectorAll('#table-schedule-container .time-slot');

    let inRange = false;
    let rangeValid = true;
    timeSlots.forEach(slot => {
        const slotTime = slot.dataset.time;
        if (slotTime === startTime || slotTime === endTime) {
            inRange = !inRange;

            if (slot.classList.contains('time-slot-reserved')) {
                rangeValid = false;
            }

            slot.classList.remove('time-slot-available', 'time-slot-reserved');
            slot.classList.add('time-slot-selected');
            selectedTimeSlots.push(slot);
        } else if (inRange) {
            if (slot.classList.contains('time-slot-reserved')) {
                rangeValid = false;
            }

            slot.classList.remove('time-slot-available', 'time-slot-reserved');
            slot.classList.add('time-slot-selected');
            selectedTimeSlots.push(slot);
        }
    });

    return rangeValid;
}

async function confirmReservation(tableId) {
    const dateInput = document.getElementById('date').value;
    const nameInput = document.getElementById('username').value;
    const emailInput = document.getElementById('email').value;

    if (selectedTimeSlots.length === 0) {
        alert('Please select a time slot.');
        return;
    }

    const firstTimeSlot = selectedTimeSlots[0];
    if (!firstTimeSlot) {
        alert('An error occurred. Please try again.');
        return;
    }

    if (selectedTimeSlots.length > 0 && dateInput && nameInput && emailInput) {

        const reservationData = {
            tableId,
            name: nameInput,
            email: emailInput,
            date: dateInput,
            time: selectedTimeSlots[0].dataset.time + ':00',
            end_time: selectedTimeSlots[selectedTimeSlots.length - 1].dataset.time + ':00'
        };

        try {
            const response = await fetch('api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(reservationData),
            });

            const data = await response.json();

            if (data.success) {
                alert('Reservation successful');
            } else {
                alert('Reservation failed: ' + (data.error || ''));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while processing your request.');
        }
    } else {
        alert('Please log in or register before making a reservation.');
    }
}


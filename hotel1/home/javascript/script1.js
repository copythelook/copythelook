document.addEventListener("DOMContentLoaded", function() {
    const monthYearDisplay = document.getElementById("monthYear");
    const calendarBody = document.getElementById("calendarBody");
    const prevMonthButton = document.getElementById("prevMonth");
    const nextMonthButton = document.getElementById("nextMonth");
    let currentDate = new Date();

    function fetchReservations(year, month) {
        fetch(`staff.php?year=${year}&month=${month + 1}`)
            .then(response => response.json())
            .then(data => renderCalendar(currentDate, data));
    }

    function renderCalendar(date, reservations) {
        const year = date.getFullYear();
        const month = date.getMonth();
        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0);
        const daysInMonth = lastDayOfMonth.getDate();

        monthYearDisplay.textContent = date.toLocaleString("default", { month: "long", year: "numeric" });

        calendarBody.innerHTML = "";

        let row = document.createElement("tr");
        for (let i = 0; i < firstDayOfMonth.getDay(); i++) {
            row.appendChild(document.createElement("td"));
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const cell = document.createElement("td");
            const cellDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            cell.textContent = day;

            if (reservations[cellDate]) {
                reservations[cellDate].forEach(reservation => {
                    const reservationDiv = document.createElement("div");
                    reservationDiv.classList.add("reservation");
                    reservationDiv.textContent = reservation;
                    cell.appendChild(reservationDiv);
                });
            }

            row.appendChild(cell);

            if ((firstDayOfMonth.getDay() + day) % 7 === 0) {
                calendarBody.appendChild(row);
                row = document.createElement("tr");
            }
        }
        calendarBody.appendChild(row);
    }

    prevMonthButton.addEventListener("click", function() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        fetchReservations(currentDate.getFullYear(), currentDate.getMonth());
    });

    nextMonthButton.addEventListener("click", function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        fetchReservations(currentDate.getFullYear(), currentDate.getMonth());
    });

    fetchReservations(currentDate.getFullYear(), currentDate.getMonth());
});

// Mock data
const appointments = [
    { time: "09:30", name: "Eleanor", status: "green" },
    { time: "10:15", name: "Chidi", status: "blue" },
    { time: "11:00", name: "Tahani", status: "gray" }
];

// render schedule
const scheduleDiv = document.getElementById("schedule");

appointments.forEach(a => {
    const div = document.createElement("div");
    div.className = "schedule-item";

    div.innerHTML = `
        <div>
            <strong>${a.time}</strong> - ${a.name}
        </div>
        <span class="badge ${a.status}">${a.status}</span>
    `;

    scheduleDiv.appendChild(div);
});

// simple calendar
const calendar = document.getElementById("calendar");

for (let i = 1; i <= 30; i++) {
    const day = document.createElement("div");
    day.className = "day";
    day.innerText = i;

    if (i === 13) {
        day.classList.add("today");
    }

    calendar.appendChild(day);
}

// highlight menu active
const links = document.querySelectorAll(".menu-item");

links.forEach(link => {
    if (link.href === window.location.href) {
        link.classList.add("active");
    }
});
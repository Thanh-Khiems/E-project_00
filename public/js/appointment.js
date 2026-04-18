function addRow() {
  const table = document.getElementById("table");

  let row = table.insertRow();
  row.innerHTML = `
    <td contenteditable="true">New Med</td>
    <td contenteditable="true">--</td>
    <td contenteditable="true">--</td>
    <td contenteditable="true">--</td>
  `;
}

function submitForm() {
  alert("Prescription Submitted!");
}
let meds = [];

// load medication list from DB
async function loadMedications() {
    let res = await fetch('/medications');
    meds = await res.json();
}

// call on page load
loadMedications();

function addRow() {

    // build suggestion list
    let options = meds.map(m => `<option value="${m.name}">`).join("");

    let medicine = prompt("Enter medication name (suggestions available):");

    if (!medicine) return;

    let dosage = prompt("Dosage (vd: 500mg):");
    let frequency = prompt("Frequency (e.g. once/day):");
    let duration = prompt("Duration (e.g. 7 days):");

    let table = document.getElementById("table");

    let row = `
        <tr>
            <td>${medicine}</td>
            <td>${dosage}</td>
            <td>${frequency}</td>
            <td>${duration}</td>
        </tr>
    `;

    table.innerHTML += row;
}
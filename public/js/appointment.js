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

// load danh sách thuốc từ DB
async function loadMedications() {
    let res = await fetch('/medications');
    meds = await res.json();
}

// gọi khi load trang
loadMedications();

function addRow() {

    // tạo danh sách gợi ý
    let options = meds.map(m => `<option value="${m.name}">`).join("");

    let medicine = prompt("Nhập tên thuốc (có gợi ý):");

    if (!medicine) return;

    let dosage = prompt("Dosage (vd: 500mg):");
    let frequency = prompt("Frequency (vd: 1 lần/ngày):");
    let duration = prompt("Duration (vd: 7 ngày):");

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
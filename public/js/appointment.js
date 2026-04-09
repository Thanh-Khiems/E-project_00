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
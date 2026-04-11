function loadMedications() {
    fetch('/medications')
    .then(res => res.json())
    .then(data => {
        let html = '';

        data.forEach(m => {
            html += `
            <div class="card">
                <input value="${m.name}" id="name-${m.id}">

                <button onclick="updateMedication(${m.id})">Update</button>
                <button onclick="deleteMedication(${m.id})">Delete</button>
            </div>
            `;
        });

        document.getElementById('list').innerHTML = html;
    });
}

// ➕ ADD
function addMedication() {
    const name = document.getElementById('name').value;

    fetch('/medications', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({ name })
    })
    .then(() => {
        document.getElementById('name').value = '';
        loadMedications();
    });
}

// ✏️ UPDATE
function updateMedication(id) {
    const name = document.getElementById(`name-${id}`).value;

    fetch('/medications/' + id, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({ name })
    })
    .then(() => loadMedications());
}

// ❌ DELETE
function deleteMedication(id) {
    if (!confirm("Delete this medicine?")) return;

    fetch('/medications/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrf
        }
    })
    .then(() => loadMedications());
}

// LOAD
loadMedications();
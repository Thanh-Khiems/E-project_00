const tabButtons = document.querySelectorAll(".tab-btn");
const panels = document.querySelectorAll(".panel");

tabButtons.forEach(button => {
    button.addEventListener("click", () => {
        tabButtons.forEach(btn => btn.classList.remove("active"));
        panels.forEach(panel => panel.classList.remove("active"));

        button.classList.add("active");
        const target = document.getElementById(button.dataset.tab);
        if (target) target.classList.add("active");
    });
});

const medicineSearch = document.getElementById("medicineSearch");
const typeFilter = document.getElementById("typeFilter");
const rows = document.querySelectorAll("#medicineTable tr");

function filterMedicines() {
    const keyword = medicineSearch.value.toLowerCase().trim();
    const type = typeFilter.value.toLowerCase().trim();

    rows.forEach(row => {
        const name = row.querySelector(".medicine-cell span").textContent.toLowerCase();
        const rowType = row.dataset.type.toLowerCase();

        const matchKeyword = name.includes(keyword);
        const matchType = !type || rowType === type;

        row.style.display = (matchKeyword && matchType) ? "" : "none";
    });
}

if (medicineSearch) {
    medicineSearch.addEventListener("input", filterMedicines);
}

if (typeFilter) {
    typeFilter.addEventListener("change", filterMedicines);
}
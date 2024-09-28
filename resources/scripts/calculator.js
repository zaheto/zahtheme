document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('blinds-calculator');
    const addRowButtons = document.querySelectorAll('.add-row');
    const calculateButton = document.querySelector('button[type="submit"]');

    // Add new row
    addRowButtons.forEach(button => {
        button.addEventListener('click', function() {
            const model = this.dataset.model;
            const container = document.getElementById(model);
            const newRow = container.querySelector('.row').cloneNode(true);
            const inputs = newRow.querySelectorAll('input, select');
            const rowIndex = container.querySelectorAll('.row').length;

            inputs.forEach(input => {
                input.name = input.name.replace(/\[\d+\]/, `[${rowIndex}]`);
                input.value = '';
            });

            container.insertBefore(newRow, this);
        });
    });

    // Real-time calculations
    form.addEventListener('input', debounce(calculateMaterials, 300));

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        calculateMaterials();
    });

    function calculateMaterials() {
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-WP-Nonce': document.querySelector('#_wpnonce').value
            }
        })
        .then(response => response.json())
        .then(data => {
            displayResults(data);
        })
        .catch(error => console.error('Error:', error));
    }

    function displayResults(data) {
        // Clear previous results
        const resultsContainer = document.getElementById('results');
        resultsContainer.innerHTML = '';

        // Display new results
        for (const [model, materials] of Object.entries(data)) {
            const modelResults = document.createElement('div');
            modelResults.innerHTML = `<h3>${model.toUpperCase()} Materials</h3>`;

            materials.forEach((row, index) => {
                const rowElement = document.createElement('div');
                rowElement.innerHTML = `
                    <h4>Row ${index + 1}</h4>
                    <ul>
                        ${Object.entries(row).map(([key, value]) => `<li>${key.replace('_', ' ')}: ${value}</li>`).join('')}
                    </ul>
                `;
                modelResults.appendChild(rowElement);
            });

            resultsContainer.appendChild(modelResults);
        }
    }

    // Debounce function to limit the rate of function calls
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
});
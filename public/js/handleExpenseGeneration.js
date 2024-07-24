document.addEventListener('DOMContentLoaded', () => {
    const headSelect = document.getElementById('head');
    const subheadContainer = document.getElementById('subhead-container');
    const subheadSelect = document.getElementById('subhead');
    const fieldsContainer = document.getElementById('fields-container');
    const form = document.getElementById('farmexpense-form'); // Assuming your form has an ID

    const unitCostNames = ['unit cost', 'unit expense (per acre)', 'per litre price', 'per bag price', 'unit expense (per bag)'];
    const quantityNames = ['no of units', 'quantity (litres)', 'no of bags', 'quantity'];

    // PHP variables
    let addedExpensesFromBackend = added_expenses;
    let removedExpensesFromBackend = removed_expenses;

    // Template expenses
    let expenses = cropExpenseData;

    // Populate head select options
    expenses.forEach(expense => {
        if (!removedExpensesFromBackend.includes(expense.head)) {
            const option = document.createElement('option');
            option.value = expense.head;
            option.textContent = expense.head;
            headSelect.appendChild(option);
        }
    });

    // Add added expenses to head select
    addedExpensesFromBackend.forEach(expenseName => {
        const option = document.createElement('option');
        option.value = expenseName;
        option.textContent = expenseName;
        headSelect.appendChild(option);
    });

    // Event listener for head select
    headSelect.addEventListener('change', (e) => {
        const selectedHead = e.target.value;

        // Clear previous subhead options and fields
        subheadSelect.innerHTML = '<option value="">Select Subtype</option>';
        fieldsContainer.innerHTML = '';
        subheadContainer.classList.add('hidden');
        fieldsContainer.classList.add('hidden');

        if (selectedHead) {
            let expense = expenses.find(exp => exp.head === selectedHead);

            // If the selected head is in the added expenses, create a new expense object for it
            if (addedExpensesFromBackend.includes(selectedHead)) {
                expense = {
                    head: selectedHead,
                    entry: ['Amount', 'Description']
                };
            }

            if (!expense) return;

            // Populate subhead select options if they exist
            if (expense['sub-head'] && expense['sub-head'].length > 0) {
                // subheadSelect.required = true;
                expense['sub-head'].forEach(subhead => {
                    const option = document.createElement('option');
                    option.value = subhead;
                    option.textContent = subhead;
                    subheadSelect.appendChild(option);
                });
                subheadContainer.classList.remove('hidden');
            }

            // Populate dropdown fields if they exist
            if (expense.dropdown && expense.dropdown.length > 0) {
                expense.dropdown.forEach(dropdown => {
                    for (const [labelText, options] of Object.entries(dropdown)) {
                        const div = document.createElement('div');
                        div.classList.add('labelcontainer');
                        const label = document.createElement('label');
                        label.classList.add('w-50');
                        label.textContent = labelText;
                        const select = document.createElement('select');
                        select.classList.add('form-select');
                        select.name = labelText.toLowerCase().replace(/ /g, '_');
                        select.required = true; // Make the field required
                        options.forEach(optionText => {
                            const option = document.createElement('option');
                            option.value = optionText;
                            option.textContent = optionText;
                            select.appendChild(option);
                        });
                        div.appendChild(label);
                        div.appendChild(select);
                        fieldsContainer.appendChild(div);
                    }
                });
                fieldsContainer.classList.remove('hidden');
                document.getElementById('submitdiv').classList.remove('hidden');
            }

            // Populate entry fields if they exist
            if (expense.entry && expense.entry.length > 0) {
                expense.entry.forEach(entry => {
                    const div = document.createElement('div');
                    div.classList.add('labelcontainer');
                    const label = document.createElement('label');
                    label.classList.add('w-50');
                    label.textContent = entry;
                    const input = document.createElement('input');
                    if (entry.toLowerCase() === 'month') {
                        input.type = 'month';
                    } else {
                        input.type = 'text';
                    }
                    input.classList.add('form-control');
                    input.name = entry.toLowerCase().replace(/ /g, '_');
                    if (entry.toLowerCase() !== 'description') {
                        input.required = true; // Make the field required except for description
                    }
                    div.appendChild(label);
                    div.appendChild(input);
                    fieldsContainer.appendChild(div);

                    // Event listener for calculating total
                    if (unitCostNames.includes(entry.toLowerCase()) || quantityNames.includes(entry.toLowerCase())) {
                        input.addEventListener('input', calculateTotal);
                    }
                });
                fieldsContainer.classList.remove('hidden');
                document.getElementById('submitdiv').classList.remove('hidden');
            }

            // Populate textbox fields if they exist
            if (expense.textbox && expense.textbox.length > 0) {
                expense.textbox.forEach(textbox => {
                    const div = document.createElement('div');
                    div.classList.add('labelcontainer');
                    const label = document.createElement('label');
                    label.classList.add('w-50');
                    label.textContent = textbox;
                    const textarea = document.createElement('textarea');
                    textarea.classList.add('form-control');
                    textarea.name = textbox.toLowerCase().replace(/ /g, '_');
                    div.appendChild(label);
                    div.appendChild(textarea);
                    fieldsContainer.appendChild(div);
                });
                fieldsContainer.classList.remove('hidden');
            }
        }
    });

    const calculateTotal = () => {
        let unitCostField;
        let quantityField;

        // Find unit cost, quantity, and weight fields based on possible names
        unitCostNames.forEach(name => {
            const field = document.querySelector(`input[name="${name.toLowerCase().replace(/ /g, '_')}"]`);
            if (field) unitCostField = field;
        });

        quantityNames.forEach(name => {
            const field = document.querySelector(`input[name="${name.toLowerCase().replace(/ /g, '_')}"]`);
            if (field) quantityField = field;
        });

        const totalField = document.querySelector('input[name="total"]') || document.querySelector('input[name="amount"]');

        if (unitCostField && quantityField && totalField) {
            const unitCostValue = parseFloat(unitCostField.value) || 0;
            const quantityValue = parseInt(quantityField.value) || 0;
            const totalValue = unitCostValue * quantityValue;
            totalField.value = totalValue.toFixed(2);
            totalField.readOnly = true;
        }
    };

    if (form) {
        form.addEventListener('submit', (e) => {
            const selectedCrop = document.querySelector('.selected');
            if (!selectedCrop) {
                e.preventDefault();
                alert('Please select at least one crop.');
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const crops = document.querySelectorAll('.selected-crop');
    const selectedCropInput = document.getElementById('selected_crop');
    const deraSelect = document.getElementById('dera');

    crops.forEach(crop => {
        crop.addEventListener('click', () => {
            // Deselect all crops first
            crops.forEach(c => c.classList.remove('selected'));
            crops.forEach(c => c.classList.add('selected-crop'));

            crop.classList.remove('selected-crop');
            crop.classList.add('selected');
            const cropId = crop.getAttribute('data-id');
            selectedCropInput.value = cropId;
            fetch(`/get-deras/${cropId}`)
                .then(response => response.json())
                .then(data => {
                    deraSelect.innerHTML = '<option value="">Select Dera</option>';
                    data.forEach(dera => {
                        const option = document.createElement('option');
                        option.value = dera.name;
                        option.textContent = dera.name;
                        deraSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching Deras:', error));
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    fetch(`/get-all-deras/${farm_Id}`)
        .then(response => response.json())
        .then(data => {
            if (data.length == 0) {
                let deraSelect = document.getElementById('farmdera-container');
                deraSelect.style.display = 'none';
            }
        });
});

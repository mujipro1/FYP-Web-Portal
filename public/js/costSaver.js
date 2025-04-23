document.addEventListener("DOMContentLoaded", function () {
    // get crop data from cropData.js
    cropData = cropExpenseData;
    expense_select = document.getElementById("cost-saver-expense");
    expense_select_subtype = document.getElementById("cost-saver-subtype");

    // Add default "Select Expense Type" option to the first select box
    var defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Select Expense Type";
    defaultOption.disabled = true;
    defaultOption.selected = true;
    expense_select.appendChild(defaultOption);

    // fetch the key "head" from cropData and populate the select options
    cropData.forEach(function (item) {
        var option = document.createElement("option");
        option.value = item.head;
        option.textContent = item.head;
        expense_select.appendChild(option);
    });

    // Add default "Select Subtype" option to the second select box
    var defaultSubtypeOption = document.createElement("option");
    defaultSubtypeOption.value = "";
    defaultSubtypeOption.textContent = "Select Subtype";
    defaultSubtypeOption.disabled = true;
    defaultSubtypeOption.selected = true;
    expense_select_subtype.appendChild(defaultSubtypeOption);

    // each time the user selects a new option in the first select box, update the second select box using the array in key "subhead"
    expense_select.addEventListener("change", function () {
        // clear the previous options in the second select box
        expense_select_subtype.innerHTML = "";

        // Add default "Select Subtype" option again
        var defaultSubtypeOption = document.createElement("option");
        defaultSubtypeOption.value = "";
        defaultSubtypeOption.textContent = "Select Subtype";
        defaultSubtypeOption.disabled = true;
        defaultSubtypeOption.selected = true;
        expense_select_subtype.appendChild(defaultSubtypeOption);

        // get the selected value from the first select box
        var selectedValue = expense_select.value;

        // find the corresponding object in cropData based on the selected value
        var selectedItem = cropData.find(function (item) {
            return item.head === selectedValue;
        });

        // if an object is found and it has a "sub-head" array, populate the second select box
        if (selectedItem && Array.isArray(selectedItem["sub-head"])) {
            selectedItem["sub-head"].forEach(function (subhead) {
                var option = document.createElement("option");
                option.value = subhead;
                option.textContent = subhead;
                expense_select_subtype.appendChild(option);
            });
        }
    });
});
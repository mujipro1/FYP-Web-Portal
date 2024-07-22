
function handleAnalytics() {
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const midYearDate = new Date(currentYear, 6, 1); // July 1st

    let fromDate, toDate;

    if (currentDate < midYearDate) {
        fromDate = new Date(currentYear, 0, 1); // January 1st
    } else {
        fromDate = midYearDate;
    }

    toDate = currentDate;

    const fromDateString = fromDate.toISOString().split('T')[0];
    const toDateString = toDate.toISOString().split('T')[0];

    form = document.getElementById('analytics-form');

    const farmIdInput = document.createElement('input');
    farmIdInput.type = 'hidden';
    farmIdInput.name = 'farm_id';
    farmIdInput.value = farm_id;

    const fromDateInput = document.createElement('input');
    fromDateInput.type = 'hidden';
    fromDateInput.name = 'from_date';
    fromDateInput.value = fromDateString;

    const toDateInput = document.createElement('input');
    toDateInput.type = 'hidden';
    toDateInput.name = 'to_date';
    toDateInput.value = toDateString;

  
    form.appendChild(farmIdInput);
    form.appendChild(fromDateInput);
    form.appendChild(toDateInput);

    document.body.appendChild(form);
    form.submit();
}

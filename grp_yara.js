
function createGoal() {
    var goalType = document.getElementById('goal-type').value;
    var targetAmount = document.getElementById('target-amount').value;
    var targetDate = document.getElementById('target-date').value;

    document.getElementById('goal-type-display').innerText = 'Goal Type: ' + goalType;
    document.getElementById('target-amount-display').innerText = 'Target Amount: $' + targetAmount;
    document.getElementById('target-date-display').innerText = 'Target Date: ' + targetDate;

    var today = new Date();
    var currentDate = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    var monthsDiff = monthDiff(currentDate, new Date(targetDate));
    var monthlySavings = Math.ceil(targetAmount / monthsDiff);

    document.getElementById('monthly-savings').innerText = 'Monthly Savings Required: $' + monthlySavings;
    document.getElementById('goal-details').style.display = 'block';
}

function monthDiff(date1, date2) {
    var months;
    date1 = new Date(date1);
    months = (date2.getFullYear() - date1.getFullYear()) * 12;
    months -= date1.getMonth() + 1;
    months += date2.getMonth() + 1;
    return months <= 0 ? 0 : months;
}

// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    const xValues = ["Food", "Accomodation", "Entertainment", "Makeup", "Transport"];
    const yValues = [55, 49, 44, 24, 15];
    const barColors = ["red", "green","blue","orange","brown"];

    const ctx = document.getElementById('expenseChart');
    
    if (ctx) {
        new Chart(ctx, {
            type: "pie",
            data: {
              labels: xValues,
              datasets: [{
                backgroundColor: barColors,
                data: yValues
              }]
            },
            options: {
              plugins: {
                legend: {display:true},
                title: {
                  display: true,
                  text: "Expenses",
                  font: {size:16}
                }
              }
            }
          });
    }

    const ctx1 = document.getElementById('expenseVSbudgetChart');
    const xValues1 = ["Budget", "Expense"];
    const yValues1 = [100, 85]; // Replace with actual budget and expense values
    const barColors1 = ["#36A2EB", "#FF6384"]; // Different colors for budget vs expense

    if (ctx1) {
        new Chart(ctx1, {
            type: "bar",
            data: {
              labels: xValues1,
              datasets: [{
                backgroundColor: barColors1,
                data: yValues1
              }]
            },
            options: {
              plugins: {
                legend: {display: false},
                title: {
                  display: true,
                  text: "Comparison of Budget and Expense",
                  font: {size: 16}
                }
              },
              scales: {
                y: {
                  beginAtZero: true
                }
              }
            }
          });
    }
});


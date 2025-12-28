// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    const xValues = ["Italy", "France", "Spain", "USA", "Argentina"];
    const yValues = [55, 49, 44, 24, 15];
    const barColors = ["red", "green","blue","orange","brown"];

    const ctx = document.getElementById('expenseChart');
    
    if (ctx) {
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                plugins: {
                    legend: {display: false},
                    title: {
                        display: true,
                        text: "World Wine Production 2018",
                        font: {size: 16}
                    }
                }
            }
        });
    }


    const ctx1 = document.getElementById("")
});


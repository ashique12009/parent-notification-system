document.addEventListener("DOMContentLoaded", function () {
  // Role Chart
  const roleCtx = document.getElementById("roleChart");

  if (roleCtx) {
    new Chart(roleCtx, {
      type: "doughnut",
      data: {
        labels: ["Parent", "Child"],
        datasets: [
          {
            data: [pnsChartData.parent, pnsChartData.child],
            borderWidth: 0,
          },
        ],
      },
    });
  }

  // Status Chart
  const statusCtx = document.getElementById("statusChart");

  if (statusCtx) {
    new Chart(statusCtx, {
      type: "pie",
      data: {
        labels: ["Sent", "Failed"],
        datasets: [
          {
            data: [pnsChartData.sent, pnsChartData.failed],
          },
        ],
      },
    });
  }
});
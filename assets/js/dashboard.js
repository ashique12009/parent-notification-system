// document.addEventListener("DOMContentLoaded", function () {
//   // Role Chart
//   const roleCtx = document.getElementById("roleChart");

//   if (roleCtx) {
//     new Chart(roleCtx, {
//       type: "doughnut",
//       data: {
//         labels: ["Parent", "Child"],
//         datasets: [
//           {
//             data: [pnsChartData.parent, pnsChartData.child],
//             borderWidth: 0,
//           },
//         ],
//       },
//     });
//   }

//   // Status Chart
//   const statusCtx = document.getElementById("statusChart");

//   if (statusCtx) {
//     new Chart(statusCtx, {
//       type: "pie",
//       data: {
//         labels: ["Sent", "Failed"],
//         datasets: [
//           {
//             data: [pnsChartData.sent, pnsChartData.failed],
//           },
//         ],
//       },
//     });
//   }
// });

document.addEventListener("DOMContentLoaded", function () {
  fetch(pnsDashboard.apiUrl, {
    method: "GET",
    headers: {
      "X-WP-Nonce": pnsDashboard.nonce,
    },
  })
    .then((response) => response.json())
    .then((data) => {
      renderRoleChart(data);
      renderStatusChart(data);
    })
    .catch((error) => {
      console.error("Dashboard API Error:", error);
    });
});

function renderRoleChart(data) {
  const ctx = document.getElementById("roleChart");

  if (!ctx) return;

  new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: ["Parent", "Child"],
      datasets: [
        {
          data: [data.parent, data.child],
          borderWidth: 0,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: "bottom",
        },
      },
    },
  });
}

function renderStatusChart(data) {
  const ctx = document.getElementById("statusChart");

  if (!ctx) return;

  new Chart(ctx, {
    type: "pie",
    data: {
      labels: ["Sent", "Failed"],
      datasets: [
        {
          data: [data.sent, data.failed],
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: "bottom",
        },
      },
    },
  });
}
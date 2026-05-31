document.addEventListener('DOMContentLoaded', function () {
  initToasts();
  initDeleteModal();
  initCharts();
});

function initToasts() {
  if (typeof bootstrap === 'undefined') {
    return;
  }

  document.querySelectorAll('.app-toast').forEach(function (toastElement) {
    var toast = new bootstrap.Toast(toastElement);
    toast.show();
  });
}

function initDeleteModal() {
  var modalElement = document.getElementById('deleteConfirmModal');
  var form = document.getElementById('deleteModalForm');

  if (!modalElement || !form || typeof bootstrap === 'undefined') {
    return;
  }

  var modal = new bootstrap.Modal(modalElement);
  var title = document.getElementById('deleteModalTitle');
  var message = document.getElementById('deleteModalMessage');
  var note = document.getElementById('deleteModalNote');

  document.querySelectorAll('[data-delete-url]').forEach(function (button) {
    button.addEventListener('click', function () {
      form.action = button.dataset.deleteUrl || '#';
      title.textContent = button.dataset.deleteTitle || 'Confirmer la suppression';
      message.textContent = button.dataset.deleteMessage || 'Voulez-vous vraiment supprimer cet élément ?';
      note.textContent = button.dataset.deleteNote || 'La suppression peut être bloquée si cet élément est lié à des données.';
      modal.show();
    });
  });
}

function initCharts() {
  if (typeof Chart === 'undefined') {
    return;
  }

  Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
  Chart.defaults.color = '#555';

  document.querySelectorAll('.js-chart').forEach(function (canvas) {
    var labels = readJson(canvas.dataset.labels);
    var values = readJson(canvas.dataset.values).map(function (value) {
      return Number(value) || 0;
    });

    if (!labels.length || !values.length) {
      return;
    }

    var type = canvas.dataset.type || 'bar';
    var isDoughnut = type === 'doughnut';

    new Chart(canvas, {
      type: type,
      data: {
        labels: labels,
        datasets: [{
          label: canvas.dataset.label || 'Total',
          data: values,
          backgroundColor: chartColors(values.length, isDoughnut),
          borderColor: isDoughnut ? '#ffffff' : '#2450a0',
          borderWidth: isDoughnut ? 2 : 1,
          borderRadius: isDoughnut ? 0 : 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: isDoughnut,
            position: 'bottom',
            labels: {
              boxWidth: 12,
              padding: 14
            }
          }
        },
        scales: isDoughnut ? {} : {
          x: {
            grid: { display: false },
            ticks: { maxRotation: 35, minRotation: 0 }
          },
          y: {
            beginAtZero: true,
            ticks: { precision: 0 }
          }
        }
      }
    });
  });
}

function readJson(value) {
  if (!value) {
    return [];
  }

  try {
    return JSON.parse(value);
  } catch (error) {
    return [];
  }
}

function chartColors(count, soft) {
  var base = ['#2450a0', '#f4b942', '#1a7a4a', '#c0392b', '#6f42c1', '#0f8b8d', '#d9822b', '#5b6c8f'];

  return Array.from({ length: count }, function (_, index) {
    var color = base[index % base.length];
    return soft ? color + 'cc' : color;
  });
}

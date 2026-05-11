<div class="card chart-card">
    <div class="card-header">
        <h3>Weekly Sales Overview</h3>
    </div>
    <div class="chart-container">
        <canvas id="weeklyChart"></canvas>
    </div>
</div>
<script>
    fetch('<?= $base_url ?>admin/db/fetch_weekly_sales.php')
        .then(res => res.json())
        .then(data => {
            const labels = data.map(item => item.label);
            const paidTotals = data.map(item => parseFloat(item.paid));
            const pendingTotals = data.map(item => parseFloat(item.pending));
            const cancelledTotals = data.map(item => parseFloat(item.cancelled));

            new Chart(document.getElementById('weeklyChart'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Paid',
                            data: paidTotals,
                            backgroundColor: 'rgba(37, 99, 235, 0.2)',
                            borderColor: 'rgba(37, 99, 235, 1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true,
                            pointRadius: 4
                        },
                        {
                            label: 'Pending',
                            data: pendingTotals,
                            backgroundColor: 'rgba(251, 191, 36, 0.2)',
                            borderColor: 'rgba(251, 191, 36, 1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true,
                            pointRadius: 4
                        },
                        {
                            label: 'Cancelled',
                            data: cancelledTotals,
                            backgroundColor: 'rgba(239, 68, 68, 0.2)',
                            borderColor: 'rgba(239, 68, 68, 1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true,
                            pointRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '₱' + context.raw.toLocaleString('en-PH', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString('en-PH');
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(err => console.error('Error fetching weekly sales:', err));
</script>
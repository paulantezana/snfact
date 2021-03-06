<script src="<?= URL_PATH ?>/assets/script/common/chart.min.js"></script>

<div class="SnContent">
    <div class="SnGrid m-grid-4">
        <div class="SnCard SnMb-4">
            <div class="SnCard-body">
            </div>
        </div>
        <div class="SnCard SnMb-4">
            <div class="SnCard-body">
            </div>
        </div>
        <div class="SnCard SnMb-4">
            <div class="SnCard-body">
            </div>
        </div>
        <div class="SnCard SnMb-4">
            <div class="SnCard-body">
            </div>
        </div>
    </div>

    <div class="SnCard">
        <div class="SnCard-body">
            <canvas id="myChart" width="400" height="400"></canvas>
            <script>
                var ctx = document.getElementById('myChart');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                        datasets: [{
                            label: '# of Votes',
                            data: [12, 19, 3, 5, 2, 3],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            </script>
        </div>
    </div>
</div>

<!--<script src="--><?//= URL_PATH ?><!--/assets/dist/script/dashboard-min.js"></script>-->
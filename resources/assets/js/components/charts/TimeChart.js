import { Bar } from './BaseChart.js'
import { reactiveProp } from './mixins.js'

export default {
    extends: Bar,
    mixins: [reactiveProp],
    data: () => ({
        options: {
            cornerRadius: 0,
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: true
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'hour'
                    },
                    barPercentage: 0.5,
                    stacked: true,
                }],
                yAxes: [{
                    display: false,
                    stacked: true,
                }]
            },
            tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                    label: function(tooltipItem, data) {
                        return tooltipItem.yLabel.toFixed(2) + 'ms';
                    }
                },
            }
        }
    }),

    mounted() {
        this.renderChart(this.chartData, this.options)
    }
}
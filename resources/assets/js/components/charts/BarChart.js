import { Bar } from './BaseChart.js'
import { reactiveProp } from './mixins.js'

export default {
    extends: Bar,
    mixins: [reactiveProp],
    data: () => ({
        options: {
            cornerRadius: 2,
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            scales : {
                xAxes: [{
                    labels : [ 'S' , 'M' , 'T' , 'W' , 'T' , 'F' , 'S' ],
                    barPercentage: 0.5,
                }],
                yAxes: [{
                    display: false
                }]
            },
            tooltips: {
                callbacks: {
						// Use the footer callback to display the sum of the items showing in the tooltip
						title: function(tooltipItems, data) {
							return data.labels[tooltipItems[0].index];
						},
					},
            }
        }
    }),

    mounted () {
        this.renderChart(this.chartData, this.options)
    }
}

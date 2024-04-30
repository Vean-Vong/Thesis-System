import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'
import { Pie } from 'vue-chartjs'
import {defineComponent} from 'vue'

ChartJS.register(ArcElement, Tooltip, Legend)

export default defineComponent({
    name: 'PieChartJs',
    props: {
    chartId: {
        type: String,
            default: 'line-chart',
        },
        width: {
            type: Number,
            default: 400,
        },
        height: {
            type: Number,
            default: 400,
        },
        cssClasses: {
            default: '',
            type: String,
        },
        styles: {
            type: Object,
            default: () => ({}),
        },
        plugins: {
            type: Array,
            default: () => [],
        },
        chartData: {
            type: Object,
            default: () => ({}),
        },
        chartOptions: {
            type: Object,
            default: () => ({}),
        },
    },
    setup(props) {
        return () => h(h(Pie), {
            data: props.chartData,
            options: props.chartOptions,
            chartId: props.chartId,
            width: props.width,
            height: props.height,
            cssClasses: props.cssClasses,
            styles: props.styles,
            plugins: props.plugins,
        })
    },
})
<script>
import {Line, mixins} from "vue-chartjs";
const { reactiveProp } = mixins;    
export default {
    extends: Line,
    mixins: [reactiveProp],
    prop: [
        'chartData',
        'options'
    ],
    mounted(){
        
        this.renderChart(this.chartData,this.options);
        
    }
}

</script>

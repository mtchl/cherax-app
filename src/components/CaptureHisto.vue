<script setup>
	import tagData from '@/assets/data/tags.json';

</script>

<template>

	<div class="histo">
	    <div class="row up">
	      <div class="col-space" v-for="(d, index) in histoData" >
	        <div class="col" v-if="d.birdTags > 0" :style="{height: (histoYScale*Math.sqrt(d.birdTags))+'px'}" ></div>
	        <div class="col context" v-if="d.contextBirdTags > 0 && Object.keys(filterState).length > 0" :style="{height: (histoYScale*Math.sqrt(d.contextBirdTags))+'px'}" :class="{'focus':filterState.month == monthLabels[index]}"></div>
	        <!-- <div class="col focus" v-if="d.focusBirdTags > 0" :style="{height: histoYScale*Math.sqrt(d.focusBirdTags)+'px'}"></div> -->
	      </div>
	    </div>

	    <div class="row label">
	    	<div class="col-space" v-for="(m,index) in monthLabels">
	    		<div class="col label">
	    			<span :class="{'active': filterState.month==m, 'inactive': histoData[index].binTags == 0 }" 
	    			@click="setMonthFilter(m,histoData[index].contextTags)">{{m}}</span>
	    		</div>
	    	</div>
	    </div>

	    <div class="row down">
	        <div class="col-space" v-for="(d,index) in histoData">
	          <div class="col" v-if="d.mammalTags > 0" :style="{height: (histoYScale*Math.sqrt(d.mammalTags))+'px'}"></div>
	          <div class="col context" v-if="d.contextMammalTags > 0 && Object.keys(filterState).length > 0" :style="{height: (histoYScale*Math.sqrt(d.contextMammalTags))+'px'}" :class="{'focus':filterState.month == monthLabels[index]}"></div>
	          <!-- <div class="col focus" v-if="d.focusMammalTags > 0" :style="{height: histoYScale*Math.sqrt(d.focusMammalTags)+'px'}"></div> -->
	        </div>
	    </div>
  </div>


</template>

<script>
export default {

  data () {
    return {
    	histoYScale: 3.5,
    	monthLabels: ["nov","dec","jan","feb"],
    }
  },

  props: ['captureData','contextCaptures','tagMap','filterState'],

  methods: {

  	setMonthFilter(month,count){
  		console.log(month + " | " + count)
  		console.log(this.histoData)
  		if (count > 0){
  			this.$emit('set-filter',{key:'month', value:month})
  		}
  	}
  },

  computed:{

  	 histoData(){
        let h = [];
        for (var m=0; m<this.monthLabels.length; m++){
          let binCaptures = this.captureData.filter(c => c.month == this.monthLabels[m]);
          let binContextCaptures = this.contextCaptures.filter(c => c.month == this.monthLabels[m]);
          let binTags = 0;
          let birdTags = 0;
          let mammalTags = 0;
          let contextTags = 0;
          let contextBirdTags = 0;
          let contextMammalTags = 0;

          binCaptures.forEach(c => {           
            binTags += c.tags.length
            let birdCount = c.tags.filter(t => this.tagMap[t].group != "mammal").length
            birdTags += birdCount;
            let mammalCount = c.tags.filter(t => this.tagMap[t].group == "mammal").length
            mammalTags += mammalCount;
          })

          binContextCaptures.forEach(c => {
          	contextTags += c.tags.length;
            let birdCount = c.tags.filter(t => this.tagMap[t].group != "mammal").length
            contextBirdTags += birdCount;
            let mammalCount = c.tags.filter(t => this.tagMap[t].group == "mammal").length
            contextMammalTags += mammalCount;
          })


           let a = { allTags:binTags, 
                birdTags: birdTags, 
                mammalTags:mammalTags, 
                contextTags:contextTags,
                contextBirdTags: contextBirdTags,
          		contextMammalTags: contextMammalTags
              }
           h.push(a)
        }
        return h;
      },

  }


}
</script>

<style lang="css" scoped>


  .histo{
    margin:1rem;
/*    max-width: 1400px;*/
    width:fit-content;
  }

  .histo .row{
    height:80px;
    display: flex;
    flex-direction: row;
    margin-bottom:1px;
  }

  .row.label{
  	height:1.6rem;
  }

  .col-space{
    width:2.5rem;
    position:relative;
/*    margin-right:1px;*/
  }

  .col-space.viewspan{
    background-color: #f0f0f0;
  }

  .histo .col{
    position:absolute;
    bottom:0;
    width:calc(100% - 2px);
    background-color: #8fcece;
	/*    #b1cee0;*/

  }

  .col.label{
  	background-color: unset;
  	text-transform: uppercase;
  	font-size: 100%;
  	text-align: center;
  	padding: 0 0 0.05rem;
  }

   .col.label span{
   	display: inline-block;
   	background-color: white;
   	border:1px solid #83856d;
   	border-radius:0.6rem;
   	padding:0.07rem 0.3rem;
	margin-bottom:0.1rem;
	cursor:pointer;
   }

   .col.label span:hover, .col.label span.active{
   	background-color: black;
   	color:white;
   }

  .histo .down .col{
    bottom:unset;
    top:0;
    background-color: #e0b2a3;   
	/*    #f8d7bc;*/
  }



  .histo .col.context{
  	background-color: black;
  	opacity:0.25;
  }

  .histo .col.context.focus{
   opacity:1;
  }


</style>
<script setup>
		import SpeciesInfo from '@/components/SpeciesInfoGrid.vue'
</script>

<template>
	<div class="annotations" id="annotations">	
		<div class="annotationRow" v-for="s in speciesAnnotations">
			<div class="speciesLabel-outer" @click="clickSpecies(s.routeTag)" :id="s.routeTag">
				<span class="speciesLabel-inner" :class="{'focus': focusSpecies == s.routeTag}" >
					<img v-if="focusSpecies == s.routeTag" class="prevButton" :class="{'active': s.prevDetection != null}" src="@/assets/img/TriangleLeft.svg" @click.stop="jumpPrev(s)"/>
					{{s.commonName}} 
					<img v-if="focusSpecies == s.routeTag" class="nextButton" :class="{'active': s.nextDetection != null}" src="@/assets/img/TriangleRight.svg" @click.stop="jumpNext(s)"/>
				</span> 
			</div>

			<!-- <div class="annotationChunk" v-for="c in s.chunks" :style="{'left': ((c.startTime*99.88) / dayLenSeconds)+'%', 'width': (chunkLen*0.1067)+'px', 'opacity': 0.2 + 0.1*Math.sqrt(c.detections.length)}"> -->
			<!-- {{c.detections.length}} -->
			<!-- </div> -->
			<div class="detect" v-for="d in s.detections" :class="{'playing': audioTime > d.startTime && audioTime < d.endTime }" :style="{'left': ((d.startTime*100) / dayLenSeconds)+'%'}"></div>
		</div>
	</div>
	<SpeciesInfo v-if="focusedSpeciesFull" :species-name="focusedSpeciesFull.scientificName" context="timeline"/>

</template>

<script>

	import annotationData from '@/assets/data/20241102.json';

	export default {

	  name: 'AudioAnnotations',
	  props: ['date','audioTime','focusSpecies'],
	  emits: ['jumpAudio','stopAudio'],

	  	data () {
	  	  return {
	  	  		error:null,
	  	  		dayLenSeconds: 86275,
	  	  		chunkLen:150,
	  	  		minConfidence:0.5,

	    	}
	  	},

	 

	  	computed:{

	  		annotations(){
	  			return annotationData.detections.filter(d => d.conf > this.minConfidence)
	  			.sort((a,b) => a.startTime - b.startTime);
	  		},

	  		species(){
	  			let sp_set = new Set(this.annotations.map(a => a.species))
	  			let sp = [...sp_set].sort((a,b) => {
					  let da = this.annotations.filter(ad => ad.species == a)
					  let db = this.annotations.filter(ad => ad.species == b)
					  return db.length - da.length; // sort by detection count
					});

	  			let speciesData = sp.map(s => {
	  				return { commonName: s.split("_")[1], 
	  						scientificName: s.split("_")[0],
	  						routeTag: s.split("_")[1].toLowerCase().replaceAll(" ","-").replaceAll("'",""),
	  						fullSpecies:s, 
	  						detections: this.annotations.filter(a => a.species == s)}
	  					});
	  			// speciesData.forEach(sd => { console.log(sd.commonName +"\t"+sd.scientificName)})
	  			return speciesData
	  		},

	  		speciesAnnotations(){
	  			// computed copy of species, updating prev and next detections
	  			// console.log("updating annotations")
	  			return this.species.map(s => {
	  				let sp = {...s};
	  				let nextD = sp.detections.find(a => a.startTime > this.audioTime)
	  				sp.nextDetection = nextD ? nextD : null; //
	  				let prevDetList = sp.detections.filter(a => a.startTime < this.audioTime - 3) // jump back if we're on a detection
	  				let prevD = prevDetList.pop();
	  				sp.prevDetection = prevD ? prevD : null;
	  				return sp;
	  			})
	  		},

	  		focusedSpeciesFull(){ // full tag data for the focused species
		        let s = null;
		        if (this.focusSpecies) s = this.species.find(sp => sp.routeTag == this.focusSpecies)
		        return s;
		    },



	  	},

	  	methods: {

	  		clickSpecies(routeTag){
	  			//console.log(this.focusSpecies)
	  			if (this.focusSpecies == routeTag){
	  				//this.focusSpecies = null;
	  				this.$router.push({path: '/timeline', query: {}})
	  				this.$emit('stopAudio')
	  			} else {
	  				//this.focusSpecies = routeTag
	  				this.$router.push({path: '/timeline', query: {species:routeTag}})
	  			}
	  		},

			jumpNext(speciesRow){
				if (!speciesRow.nextDetection) return
				this.$emit('jumpAudio',{time:speciesRow.nextDetection.startTime})
			},

			jumpPrev(speciesRow){
				if (!speciesRow.prevDetection) return
				this.$emit('jumpAudio',{time:speciesRow.prevDetection.startTime})
			}
	  	},

	  	watch: {
	  		// focusSpecies(n,o){
	  		// 	console.log(n)
	  		// }
	  	}
	}


</script>

<style lang="css" scoped>

	.annotations{
/*		margin-bottom:320px;*/
/*		padding-bottom:320px;*/
	}
	.annotationChunk{
		height:18px;
		background-color: #b2711f;
		position:absolute;
		top:0;
		color:black;
		font-size:80%;
	}

	.annotationRow{
		width:100%;
		position:relative;
		margin-top:0.25rem;
		height:1.5rem;
	}
	

	.speciesLabel-outer{
		display: inline-block;
		position: sticky;
		left:calc(50% - 3px);
		font-size: 90%;
		z-index:1;
		text-align: right;
		color:#fff;

		
	}

	.speciesLabel-inner{
		font-weight: 400;
		text-shadow: 0px 0px 6px rgba(0,0,0,0.5);
		padding:0.2em 0.6em;
		margin:0.2em 0.2em 0 0;
		background-color: #333;
		border-radius:0.5em;
		position: relative;
		left:-100%;
		cursor:pointer;
		border: 1px solid #333;
	}

	.speciesLabel-inner:hover{
		border: 1px solid rgba(255,255,255,0.5);
	}

	.speciesLabel-inner.focus:hover{
		border: 1px solid #333;
	}

	.speciesLabel-inner.focus{
		filter: invert();
	}

	.nextButton, .prevButton{
		width:12px;
		opacity:0.2;
		position:relative;
		top:1px;
	}

	.nextButton.active, .prevButton.active{
		opacity:0.8;
		cursor: pointer;

	}

	.nextButton.active:hover, .prevButton.active:hover{
		opacity:1.0;
	}

	.annotations{
		width:100%;
	}

	.detect{
		position:absolute;
		top:6px;
		width:8px;
		height:8px;
		background-color: aquamarine;
		opacity:0.3;
		border-radius: 50%;
	}

	.detect.playing{
		background-color: white;
		opacity:1;
	}



</style>
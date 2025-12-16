<template>
	<div class="annotations">	
		<div class="annotationRow" v-for="s in speciesAnnotations">
			<div class="speciesLabel-outer">
				<span class="speciesLabel-inner" >
					<img class="prevButton" :class="{'active': s.prevDetection != null}" src="@/assets/img/TriangleLeft.svg" @click.stop="jumpPrev(s)"/>
					{{s.commonName}} 
					<img class="nextButton" :class="{'active': s.nextDetection != null}" src="@/assets/img/TriangleRight.svg" @click.stop="jumpNext(s)"/>
				</span>
			</div>

			<!-- <div class="annotationChunk" v-for="c in s.chunks" :style="{'left': ((c.startTime*99.88) / dayLenSeconds)+'%', 'width': (chunkLen*0.1067)+'px', 'opacity': 0.2 + 0.1*Math.sqrt(c.detections.length)}"> -->
			<!-- {{c.detections.length}} -->
			<!-- </div> -->
			<div class="detect" v-for="d in s.detections" :class="{'playing': audioTime > d.startTime && audioTime < d.endTime }" :style="{'left': ((d.startTime*100) / dayLenSeconds)+'%'}"></div>
		</div>
	</div>


</template>

<script>

	import annotationData from '@/assets/data/20241102.json';
	export default {

	  name: 'AudioAnnotations',
	  props: ['date','audioTime'],

	  	data () {
	  	  return {
	  	  		error:null,
	  	  		dayLenSeconds: 86275,
	  	  		chunkLen:150,
	  	  		minConfidence:0.5
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
	  		}

/*	  		
			annotationChunks(){
			  	let allchunks = [];
				this.species.forEach(b => {
			    	let birdDetections = this.annotations.filter(d => d.species == b.fullSpecies)
			    	let speciesRow = {commonName:b.commonName, scientificName: b.scientificName, fullSpecies:b.fullSpecies, chunks:[]}
			    	// aggregate into chunks of chunkLen seconds
				    for (let s = 0; s<(24*3595); s+= this.chunkLen){
				      let chunkDetections = birdDetections.filter(d =>  d.startTime > s &&  d.endTime <= (s+this.chunkLen))
				      if (chunkDetections.length > 0){
				        let c = {startTime: s, endTime: s+this.chunkLen, detections: chunkDetections}
				        speciesRow.chunks.push(c)
				      }
				    }
				    allchunks.push(speciesRow)
				})
				return allchunks;
	  		}
*/


	  	},

	  	methods: {

	  		// loadAnnotations(date){
	  		// 	console.log("loading annotations for "+date)
	  		// 	// const dataPath = new URL(`@/assets/data/${date}.json`, import.meta.url).href
	  		// 	axios.get('@/assets/data/${date}.json')
			//       .then(response => {
			//       	console.log(response)
			//       	this.annotations = response.data.detections
			//       	.filter(d => d.conf > this.minConfidence)
			//       	.sort((a,b) => a.startTime - b.startTime);
			//       	})
			// },


			jumpNext(speciesRow){
				if (!speciesRow.nextDetection) return
				this.$emit('jump-audio',{time:speciesRow.nextDetection.startTime})
			},

			jumpPrev(speciesRow){
				if (!speciesRow.prevDetection) return
				this.$emit('jump-audio',{time:speciesRow.prevDetection.startTime})
			}
	  	},
	}


</script>

<style lang="css" scoped>
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
		left:50%;
		font-size: 90%;
		z-index:1;
		text-align: right;
		color:#fff;
		
	}

	.speciesLabel-inner{
		font-family: 'Inconsolata', monospace;
		text-shadow: 0px 0px 6px rgba(0,0,0,0.5);
		padding:0.2em 0.6em;
		margin:0.2em 0.2em 0 0;
		background-color: #333;
		border-radius:0.5em;
		position: relative;
		left:-100%;

	}

	.nextButton, .prevButton{
		width:12px;
		opacity:0.2;
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
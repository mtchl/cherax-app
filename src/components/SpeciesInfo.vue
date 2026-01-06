
<template>

	<div class="wrapper">
		<div id="speciesInfo">
			<div class="col left">
				<h3>{{speciesInfo.commonName}}</h3>
				<h4>{{speciesInfo.scientificName}}</h4>
				<p>{{speciesInfo.description}}</p>

				<router-link v-if="context == 'captures' && speciesInfo.audio" :to="{path:'/timeline', hash: '#annotations', query:{species: speciesInfo.routeTag}}">Listen in timeline</router-link>

				<a v-if="speciesInfo.alaUrl" :href="speciesInfo.alaUrl" target="_blank">Learn more</a>
			</div>
			<div class="col right">
				<img :src="imageUrl">
				<p v-if="speciesInfo.imgAttribution" class="attribution">Image: {{speciesInfo.imgAttribution}}</p>
			</div>
		</div>

	</div>

</template>

<script>
	import speciesDataSource from '@/assets/data/speciesinfo.json';

	export default {


	  data () {
	    return {

	    }
	  },

	  props: ['speciesName', 'context'],

	  computed: {
	  	
	  	speciesInfo(){
	  		let s = speciesDataSource.find(sd => sd.scientificName == this.speciesName)
	  		s.routeTag = s.commonName.toLowerCase().replace(" ","-")
	  		return s;
	  	},

	  	imageUrl() {
	  		console.log(this.speciesInfo.imgFilename)
	        return new URL(`../assets/img/species-images/${this.speciesInfo.imgFilename}`, import.meta.url).href
	    }
	  }

	}
</script>

<style lang="css" scoped>
	.wrapper{
		position: fixed;
		bottom:0;
		left:0;
		z-index: 5;
		width:100%;
	}

	#speciesInfo{
		position:relative;
		margin: 0 auto;
		padding: 1rem 2rem;
		width:85%;
		max-width:900px;
		display: flex;
		flex-direction: row;
		flex-wrap: nowrap;
		justify-content: space-between;
		background-color: white;
		border-radius: 1rem 1rem 0 0;
		box-shadow: 0px 0px 12px rgba(0,0,0,0.2);
	}

	h3{
		margin:0 0 0.25rem;
		font-weight: 600;
	}
	h4{
		margin:0;
		font-style: italic;
		font-weight: 400;
	}

	img{
		width:25vw;
		max-width:150px;
		border-radius: 0.5rem;
	}

	p{
		font-weight: 300;
		font-size:90%;
	}

	.col.left{
		margin-right:1rem;
	}

	.attribution{
		font-size: 80%;
		margin:0.25rem 0;
		color:#888;
	}

</style>

<!--   {
    "commonName": "Straw-necked Ibis",
    "scientificName": "Threskiornis spinicollis",
    "description": "Found in shallow freshwater wetlands and grasslands throughout Australia, this species is nomadic, moving in response to rainfall and water conditions. They feed on a range of molluscs, frogs, crayfish and fish, as well as insects and lizards.",
    "alaUrl": "https://bie.ala.org.au/species/https://biodiversity.org.au/afd/taxa/c319dbaf-a363-4853-b333-75f14c47fc82#overview",
    "xenoCantoUrl": "",
    "image filename": "straw-necked-ibis.jpeg",
    "image attribution": "Roy Lowry",
    "image license": "http://creativecommons.org/licenses/by/4.0/",
    "audio": "",
    "capture": true
  }, -->
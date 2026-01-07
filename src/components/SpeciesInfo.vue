
<template>

	<div class="wrapper">
		<div id="speciesInfo" >
			<div class="col left">
				<h3>{{speciesInfo.commonName}}</h3>
				<h4>{{speciesInfo.scientificName}}</h4>
				<p>{{speciesInfo.description}}</p>

				<router-link v-if="context == 'captures' && speciesInfo.audio" :to="{path:'/timeline', 
				hash: '#annotations', 
				hash: '#' + speciesInfo.routeTag,
				query:{species: speciesInfo.routeTag}}">Listen in timeline</router-link>

				<router-link v-if="context == 'timeline' && speciesInfo.capture" :to="{path:'/captures',  query:{species: speciesInfo.routeTag}}">
				Explore Captures
			</router-link>

				<a v-if="speciesInfo.alaUrl" :href="speciesInfo.alaUrl" target="_blank" class=
				"newtab">Learn more: ALA</a>

				<a v-if="speciesInfo.xenoCantoUrl" :href="speciesInfo.xenoCantoUrl" target="_blank" class=
				"newtab">Listen: Xeno Canto</a>


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
	  		s.routeTag = s.commonName.toLowerCase().replaceAll(" ","-").replaceAll("'","")
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
		border: 1px solid rgba(0, 0, 0, 0.3);
		border-radius: 1rem 1rem 0 0;
		box-shadow: 0px 0px 12px rgba(0,0,0,0.2);
	}

	h3{
		margin:0 0 0.25rem;
		font-weight: 600;
		font-size:1.3rem;
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
		font-family: Lato, sans-serif;
		font-weight: 400;
/*		font-size:90%;*/
		margin:0.5rem 0;

	}

	.col.left{
		margin-right:1rem;
	}

	.attribution{
		font-size: 80%;
		margin:0.25rem 0;
		color:#888;
	}

	
	a {
	  display:inline-block;
	  padding: 4px 9px 4px 9px;
	  margin:0.5rem 1rem 0 0;
	  color:black;
	  text-decoration:none;
	  background-color: #e0b2a3;
	  border-radius: 0.66rem;
	  border:1px solid #e0b2a3;
	  cursor: pointer;
	}

	a.newtab{
	  padding: 4px 27px 4px 9px;
	  background-color: rgba(226, 227, 216, 1);
	  border:1px solid rgba(226, 227, 216, 1);
	}

	 a:hover{
	 	border:1px solid black;
	 }


	a.newtab::after{
	    content: " ";
	    background-image: url(@/assets/img/newtab.png);
	    background-size: contain;
	    background-repeat: no-repeat;
	    background-position:20% 0;
	    display: inline-block;
	    height: 0.9em;
	    width: 2em;
	    margin-right: -2em;
	    position: relative;
	    top: 2px;
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
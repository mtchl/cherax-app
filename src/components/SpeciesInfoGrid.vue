
<template>

	<div class="wrapper" :class="{'mobileshow':mobileshow}" @click="mobileshow = !mobileshow" >
		
		<div id="speciesInfo" >
			<div class="mobile-toggle" :class="{'expanded':mobileshow}"></div>
		
			<div class="head">
				<h3 >{{speciesInfo.commonName}}</h3>
				<h4>{{speciesInfo.scientificName}}</h4>
			</div>
			<div class="info">
				<p>{{speciesInfo.description}}</p>
			</div>

		

			<div class="pic">
				<img :src="imageUrl">
				<p v-if="speciesInfo.imgAttribution" class="attribution">Image: {{speciesInfo.imgAttribution}}</p>
			</div>

			<div class="links">
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
		</div>

		
	</div>
	

</template>

<script>
	import speciesDataSource from '@/assets/data/speciesInfo.json';

	export default {


	  data () {
	    return {
	    	mobileshow:false
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
		cursor: pointer;

	}


	#speciesInfo{
		position:relative;
		margin: 0 auto;
		padding: 0rem 2rem 1rem 2rem;

		max-width:900px;
		display: grid;
		grid-template-columns: auto auto;
		grid-template-rows: 1rem 2.75rem 2fr 0.5fr;

		background-color: white;
		border: 1px solid rgba(0, 0, 0, 0.3);
		border-radius: 1rem 1rem 0 0;
		box-shadow: 0px 0px 12px rgba(0,0,0,0.2);
	}

	#speciesInfo .mobile-toggle{
		grid-row:1 / 1;
		grid-column: 1 / span 2;
	}

	#speciesInfo .head{
		grid-row:2 / span 2;
		grid-column:1 / span 1
	}

	#speciesInfo .info{
		grid-row:3 / span 1;
		grid-column:1 / span 1;
		padding-right:1vw;
	}

	#speciesInfo .pic{
		grid-row:2 / span 3;
		grid-column:2 / span 1;
/*		margin-left:0.5rem;*/
	}

	#speciesInfo .links{
		grid-row:4 / span 1;
		grid-column:1 / span 1;
		align-self: start;
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

	.col{

	}

	.col.left{
		margin-right:1rem;
		flex:1;
	}

	.attribution{
		font-size: 80%;
		margin:0.25rem 0;
		color:#888;
	}

	
	a {
	  display:inline-block;
	  padding: 4px 9px 4px 9px;
	  margin:0.25rem 1rem 0.5rem 0;
	  color:black;
	  text-decoration:none;
	  background-color: #e0b2a3;
	  border-radius: 0.66rem;
	  border:1px solid #e0b2a3;
	  cursor: pointer;
	  font-size:0.8rem;
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

	.mobile-toggle{
		height:1.3rem;
		margin:0;
		padding:0;		
/*		width:100%;*/
		flex: 1 1 100%;
		background-image:url('@/assets/img/chevron-up-flat.svg');
		background-repeat: no-repeat;
		background-position: 50% 50%;
		background-size: 1.5rem;
		opacity:0.25;
	
/*		display: none;*/
	}

	.mobile-toggle:hover, .wrapper:hover .mobile-toggle{
		opacity:0.5;
		cursor:pointer;
	}

	.wrapper{
		bottom:unset;
		top:calc(100vh - 80px);
		transition:top 0.5s;
	}

	.wrapper.mobileshow{
		bottom:0;
		top:unset;
	}



	.mobile-toggle.expanded{
		background-image:url('@/assets/img/chevron-down-flat.svg');
	}

	@media screen and (width < 600px) {

/*		.mobile-toggle{
			display: unset;
		}*/


		#speciesInfo{
			padding: 0 1rem 1rem 1rem;
		}

		#speciesInfo .pic{
			grid-row:2 / span 2;
			grid-column:2 / span 1;
			align-self: center;
		}

		#speciesInfo .links{
			grid-column:1 / span 2;
		}

	}

</style>

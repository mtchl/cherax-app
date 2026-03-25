<script>
  
  export default{

    data(){
      return {
        faves:[]
    }
    },

    computed: {
      currentPath(){
        return this.$route.path
      }
    },

    methods: {
      updateFaves(value){
        this.faves = value;
      }
    },
    
    mounted(){
      let storageData = localStorage.getItem('MosaicFaves');
      if (storageData){
        this.faves = JSON.parse(storageData);
      }
    },

  }


</script>
<template>
  <nav>
    <RouterLink to="/" class="logo" activeClass="bright">
      <img src="@/assets/img/mosaic-nav-logo.svg" class="navlogo"/>
    </RouterLink>
    
    <RouterLink to="/" activeClass="bright" class="home">Mosaic</RouterLink>
    
    <RouterLink to="/captures" :class="{'bright': currentPath == '/captures', 'gotfaves': faves.length > 0 && (currentPath == '/captures' || currentPath == '/captures/faves') }">Captures</RouterLink>
    
    <RouterLink class="favecount" to="/captures/faves" v-if="faves.length > 0 && (currentPath == '/captures' || currentPath == '/captures/faves')" :title="'Show ' + faves.length + ' saved favourites'" :class="{'bright': currentPath == '/captures/faves'}">{{faves.length}}</RouterLink>
    <RouterLink to="/timeline" activeClass="bright">Timeline</RouterLink>

    <a href="https://flow-mer.org.au" class="backlink">
      <img class="mobile" src="@/assets/img/flow-mer-back-short.png"/>
      <img class="desktop" src="@/assets/img/flow-mer-back-full.png"/>
    </a>

  </nav>
  <main>
    <RouterView @updateFaves="updateFaves"/>
  </main>
</template>

<style scoped>
  
  nav{
    background-color: #222;
    padding:0rem;
    margin:0;
    position:fixed;
    top:0;
    width:100%;
    z-index:5;
  }

  nav a{
    display: inline-block;
    margin:0.5rem 0.75rem 0.5rem 0.75rem;
    font-weight:500;
    color:white;
    text-decoration: none;
    opacity:0.6;
    transition:opacity 0.25s;
    letter-spacing: 0.0rem;
  }

  nav a.home{
    margin:0.5rem 0.75rem 0.5rem 0.3rem;
  }

  nav a.logo{
    margin:0;
/*    opacity:1;*/
  }

  nav a.gotfaves{
    margin-right:0.1rem;
  }

  nav a.logo img{
    margin:0rem 0 0 0.28rem;
    vertical-align: bottom;
    display: inline-block;
    width:1.6rem;
    object-fit: contain;
    position:relative;
    top:0.19rem;
  }

  .navlogo{
/*    width:1.5rem;*/
/*    height:100%;*/
  }

  nav a.bright, nav a.favecount.bright, nav a:hover{
    opacity:1.0;
  }

  nav a.favecount{
    display: inline-block;
    padding-top:0.5rem;
    padding-right:0.1rem;
    margin-top:0;
    margin-bottom:0;
    margin-left:0.2rem;
    
    background-image: url('@/assets/img/fave-heart-white.svg');
    background-position: 50% 50%;
/*    background-size: ;*/
    background-repeat: no-repeat;
    width:1.60rem;
    height:1.5rem;
    font-size:0.85em;
    letter-spacing: -0.05em;

    color:black;
    text-align: center;
    opacity:0.6;
  }

  nav a.backlink img{
    height:1.6rem;
    position:absolute;
    right:0.1rem;
    top:0.2rem;
  }

  nav a.backlink img.mobile{
    display: none;
  }

  main{
    padding-top:2rem;
  }

  @media screen and (width < 720px) {

    nav a.backlink img.mobile{
      display: unset;
    }

    nav a.backlink img.desktop{
      display: none;
    }



  }


</style>

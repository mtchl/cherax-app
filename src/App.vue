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
    <RouterLink to="/" activeClass="bright">Home</RouterLink>
    <RouterLink to="/captures" :class="{'bright': currentPath == '/captures'}">Captures</RouterLink>
    <RouterLink class="favecount" to="/captures/faves" v-if="faves.length > 0 && (currentPath == '/captures' || currentPath == '/captures/faves')" :title="'Show ' + faves.length + ' saved favourites'" :class="{'bright': currentPath == '/captures/faves'}">{{faves.length}}</RouterLink>
    <RouterLink to="/timeline" activeClass="bright">Timeline</RouterLink>
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
    margin:0.5rem 0rem 0.5rem 1.5rem;
    font-weight:600;
    color:white;
    text-decoration: none;
    opacity:0.6;
    transition:opacity 0.25s;
    letter-spacing: 0.02rem;
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



  main{
    padding-top:2rem;
  }
</style>

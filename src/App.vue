<script>
  
  export default{

    data(){
      faves:[]
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
    <RouterLink to="/captures" activeClass="bright">Captures</RouterLink>
    <RouterLink to="/timeline" activeClass="bright">Timeline</RouterLink>
  </nav>
  <main>
    <RouterView/>
  </main>
</template>

<style scoped>
  
  nav{
    background-color: #222;
    padding:0.5rem;
    margin:0;
    position:fixed;
    top:0;
    width:100%;
    z-index:5;
  }

  nav a{
    margin:0 1rem;
    font-weight:600;
    color:white;
    text-decoration: none;
    opacity:0.6;
    transition:opacity 0.25s;
  }

  nav a.bright, nav a:hover{
    opacity:1.0;
  }

  main{
    padding-top:2rem;
  }
</style>

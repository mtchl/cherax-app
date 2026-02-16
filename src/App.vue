<script>
  

</script>
<template>
  <nav>
    <RouterLink to="/">Home</RouterLink>
    <RouterLink to="/captures">Captures</RouterLink>
    <RouterLink to="/timeline">Timeline</RouterLink>
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
  }

  main{
    padding-top:2rem;
  }
</style>

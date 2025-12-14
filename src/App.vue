<script>
  

</script>
<template>
  <nav>
    <RouterLink to="/">Home</RouterLink>
    <RouterLink to="/captures">Captures</RouterLink>
    <RouterLink to="/timeline">Timeline</RouterLink>
  </nav>
  <main>
    <RouterView />
  </main>
</template>

<style scoped>
  
  nav{
    background-color: black;
    padding:0.5rem;
    margin:0 0 2rem;
  }

  nav a{
    margin:0 1rem;
    color:white;
  }
</style>

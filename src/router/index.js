import { createRouter, createWebHistory } from 'vue-router'


  import homePage from '@/components/HomePage.vue'
  import captureBrowser from '@/components/CaptureBrowser.vue'
  import Timeline from '@/components/Timeline.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: homePage },
    
    { path: '/captures', 
      component: captureBrowser ,
      props: route => ({ filterSpecies: route.query.species, filterCam: route.query.cam, filterMonth: route.query.month })
    },

    { path: '/timeline', 
      component: Timeline,
      props: route => ({ focusSpecies: route.query.species })
     },
  ],
  scrollBehavior(to, from, savedPosition) {
    // If a hash fragment exists in the 'to' route, scroll to that element
    if (to.hash) {
      // return {
      //   el: to.hash,
      //   // top:-640,
      //   behavior: 'smooth',
      // };
      return new Promise((resolve, reject) => {
        setTimeout(() => {
          resolve({ 
            el:to.hash, 
            behavior: 'smooth' 
          })
        }, 500)
      })


    }
    // Otherwise, scroll to the top of the page
    return { top: 0};
  },
});

export default router


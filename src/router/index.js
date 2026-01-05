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

    { path: '/timeline', component: Timeline },
  ]
})

export default router


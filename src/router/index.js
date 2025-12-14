import { createRouter, createWebHistory } from 'vue-router'


  import homePage from '@/components/HomePage.vue'
  import captureBrowser from '@/components/CaptureBrowser.vue'
  import Timeline from '@/components/Timeline.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: homePage },
    { path: '/captures', component: captureBrowser },
    { path: '/timeline', component: Timeline },
  ]
})

export default router


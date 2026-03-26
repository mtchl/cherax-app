import { createRouter, createWebHistory } from 'vue-router'
import { nextTick } from 'vue'; // Import nextTick


  import homePage from '@/components/HomePage.vue'
  import captureBrowser from '@/components/CaptureBrowser.vue'
  import Timeline from '@/components/Timeline.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),

  routes: [
    { path: '/', 
    component: homePage,
    meta: {scrollToTop:true, title: 'Mosaic: Life on the Western Lakes'}
    },
    
    { path: '/captures', 
      component: captureBrowser ,
      meta: {title: 'Mosaic - Captures'},
      props: route => ({ filterSpecies: route.query.species, filterCam: route.query.cam, filterMonth: route.query.month }),
    },

    // { path: '/captures/shared/:sharedCapture', 
    //   component: captureBrowser ,
    //   props: true,
    // },

    { path: '/captures/faves', 
      meta: {title: 'Mosaic - Favourites'},
      component: captureBrowser ,
      props: route => ({showFaves:true}),
    },

    { path: '/timeline',
      meta: {title: 'Mosaic - Timeline'}, 
      component: Timeline,
      props: route => ({ focusSpecies: route.query.species })
     },
  ],
  
  scrollBehavior(to, from, savedPosition) {
    // If a hash fragment exists in the 'to' route, scroll to that element
    
    if (to.path == "/" && savedPosition) { // back button retains vertical pos for story
      //return {top: savedPosition.top - 400}; // why? needs to wait a bit
      return new Promise((resolve, reject) => {
        setTimeout(() => {
          resolve(savedPosition)
        }, 100)
      })
    }
    
    if (to.hash) {
      console.log("scrolling to hash")
      return new Promise((resolve, reject) => {
        setTimeout(() => {
          resolve({ 
            el:to.hash, 
            behavior: 'smooth' 
          })
        }, 300)
      })
    }

    if (to.matched.some(record => record.meta.scrollToTop)) {
      // Scroll to the top of the page
      return { top: 0, left: 0, behavior: 'smooth' };
    }

    if (to.path != from.path){ // scroll to top when returning to captures
      // console.log(" to not from ")
      // but not when staying on timeline 
          return { top: 0};
    }
  },
});

router.afterEach((to, from) => {
    // Use next tick to handle router history correctly
    // see: https://github.com/vuejs/vue-router/issues/914#issuecomment-384477609
    nextTick(() => {
        document.title = to.meta.title;
    });
});


export default router


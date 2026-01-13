
<script setup>
   import TimelapseFrame from '@/components/TimelapseFrame.vue'
   import SlideShow from '@/components/SlideShow.vue'
   // import 'intersection-observer' // for cross-browser support
   import VueScrollama from "vue3-scrollama";
</script>
<script>

   export default{
      data(){
         return {
            currentSlide: null
         }
      },

      methods: {
          stepEnterHandler ({element, index, direction}) {
            if (element.dataset.url) this.currentSlide = element.dataset
          }
      }
   }


</script>


<template>

   <div class="sticky">
      <SlideShow :slide="currentSlide"></SlideShow>
   </div>


   <div class="steps">
        <VueScrollama
            :debug="false"
            offset="0.8"
            @step-enter="stepEnterHandler"
            class="main__scrollama">
            <div class="step" 
            data-url="https://storage.googleapis.com/cherax-media/cam1/20241102/cam1-20241102-40.JPG" 
            data-type="img">
                The water is high in early November 
            </div>
            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam4/cam4-20241102-060724.JPG" 
            data-type="img">
                At dawn, a <router-link :to="'/captures?species=pelican'">pelican</router-link> touches down at the edge of the flooded Crop Paddock 
            </div>
           
           <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam7/cam7-20241106-103414.mp4" data-type="vid">
                A <router-link :to="'/captures?species=yellow-billed-spoonbill'">yellow-billed spoonbill</router-link> and a flock of <router-link :to="'/captures?species=native-hen'">native hens</router-link> feed around the nearby snag... 
            </div>
            
            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam7/cam7-20241109-072636.mp4 " data-type="vid">
                and a <router-link :to="'/captures?species=white-faced-heron'">white-faced heron</router-link> spears a morsel. 
            </div>

            <div class="step">
               These wetlands are bustling with waterbirds.
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam6/cam6-20241122-060704.JPG" data-type="img">
               Later in November, <router-link :to="'/captures?species=great-egret'">great egrets</router-link> and a <router-link :to="'/captures?species=white-necked-heron'">white-necked heron</router-link> pose on Camera D             
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam3/cam3-20241116-055548.JPG" data-type="img">
               <router-link :to="'/captures?cam=A'">Camera A,</router-link> Looking towards Upper Cherax, snaps a flock of <router-link :to="'/captures?species=white-faced-heron'">pacific black ducks</router-link> taking off in the pre-dawn 
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam3/cam3-20241216-093840.JPG" data-type="img">
               This shot captures  <router-link :to="'/captures?species=white-ibis'">white ibis,</router-link> <router-link :to="'/captures?species=straw-necked-ibis'">straw-necked ibis</router-link> and <router-link :to="'/captures?species=white-necked-heron'">white-necked herons</router-link> in flight 
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam3/cam3-20241216-093850.mp4" data-type="vid">
               The <router-link :to="'/captures?species=great-cormorant'">great cormorant</router-link> follows them a second later  
            </div>

             <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam4/cam4-20250131-105624.JPG" data-type="img">
               By the end of January, the water level has dropped, and <router-link :to="'/captures?species=pacific-black-duck'">ducks</router-link>, <router-link :to="'/captures?species=pelican'">pelicans</router-link> and <router-link :to="'/captures?species=black-swan'">black swans</router-link> are gathering in numbers out on the Crop Paddock   
            </div>

            <!-- SECTION -->

             <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam6/cam6-20241207-113632.mp4" data-type="vid">
               Videos capture some of these waterbirds' signature moves.    
            </div>

            <div class="step">
              Herons and egrets “stir” the flooded ground to scare out small fish and insects – here's a <router-link :to="'/captures?species=white-faced-heron'">white-faced heron</router-link> demonstrating   
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam6/cam6-20241121-072410.mp4" data-type="vid">
              This <router-link :to="'/captures?species=yellow-spoonbill'">yellow spoonbill</router-link> sweeps its broad, sensitive bill from side to side, hunting by feel,  
            </div>

            <div class="step">
              Ducks like the <router-link :to="'/captures?species=pacific-black-duck'">pacific black duck</router-link> are named “dabblers” for their head-down, tail-up feeding style.   
            </div>

            <div class="step" data-url="https://storage.googleapis.com/cherax-media/cam7/cam7-20241121-165624.mp4" data-type="vid">
              At the snag, a <router-link :to="'/captures?species=white-ibis'">white ibis</router-link> and a <router-link :to="'/captures?species=straw-necked-ibis'">straw-necked ibis</router-link> squabble over possession of a juicy frog.    
            </div>


        </VueScrollama>
    </div>



</template>

<style scoped>
   
   .steps{
      padding:600px 0;
   }
   .step{
      margin: 80vh auto 80vh;
      width:50%;
      max-width: 480px;
      background-color: white;
      padding:2rem;
      font-family: Lato, sans-serif;
      font-size: 1.3rem;
      line-height: 2rem;
      box-shadow: 0px 0px 12px rgba(0,0,0,0.2);
/*      z-index:1;*/
   }

   .steps{
      position:relative;
      z-index:1;
   }


   .sticky{
      position:sticky;
      top:0;
   }

   .step a {
     display:inline-block;
     padding: 2px 9px;
     margin:0.2rem 0.2rem ;
     color:black;
     text-decoration:none;
     background-color: #e0b2a3ba;
     border-radius: 0.66rem;
     border:1px solid #e0b2a3ba;
     cursor: pointer;
     font-family: 'Inconsolata', monospace;
   }

   .step a:hover{
      border:1px solid black;
    }




</style>
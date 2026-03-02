<script setup>
     import CaptureItem from '@/components/CaptureItem.vue'
     import CaptureHisto from '@/components/CaptureHisto.vue'
     import CamMap from '@/components/CamMap.vue'
     import SpeciesInfo from '@/components/SpeciesInfoGrid.vue'
</script>

<script type="text/javascript">
   import jsonData from '@/assets/data/captures.json';
   import tagData from '@/assets/data/tags.json';
   import { interpolateRgb } from 'd3-interpolate';

   export default{
    data(){
      return {
        captureData: jsonData,
        cams: ["A","B","C","D","E","F","G"],
        months: [{label:"nov", dayrange:[0,35]}, {label:"dec", dayrange:[36,67]},{label:"jan", dayrange: [68,98]},{label:"feb", dayrange:[99,122]} ],
        baseUrl: "https://storage.googleapis.com/cherax-media/",
        viewItems: 10,
      }
    },

    props: ['filterSpecies','filterCam','filterMonth'],

    methods: {

      setFilter(key,value){
        const f = {species:this.filterSpecies, cam: this.filterCam, month: this.filterMonth}
        f[key] = value;
        if (key == 'species' && this.filterSpecies == value) delete f.species;
        if (key == 'cam' && this.filterCam == value) delete f.cam;
        if (key == 'month' && this.filterMonth == value) delete f.month;
     
        if (f.cam == undefined) delete f.cam;
        if (f.species == undefined) delete f.species;
        if (f.month == undefined) delete f.month;
           console.log(f)
        this.$router.push({path: '/captures', query: f})
        this.viewItems = 10;
      },
    
      loadMore(){
        this.viewItems += 10;
      },

      convertTimestampToDate(timestampString) {
        // Ensure the input string has the correct format and length
        if (!timestampString || timestampString.length !== 15 || timestampString[8] !== '-') {
          console.error("Invalid timestamp format. Expected 'YYYYMMDD-HHMMSS'.");
          return null; // Return null for invalid input
        }

        // Extract date and time components using substring
        const year = parseInt(timestampString.substring(0, 4), 10);
        // Month is 0-indexed in JavaScript Date, so subtract 1
        const month = parseInt(timestampString.substring(4, 6), 10) - 1; 
        const day = parseInt(timestampString.substring(6, 8), 10);
        const hours = parseInt(timestampString.substring(9, 11), 10);
        const minutes = parseInt(timestampString.substring(11, 13), 10);
        const seconds = parseInt(timestampString.substring(13, 15), 10);

        // Create and return the new Date object
        return new Date(year, month, day, hours, minutes, seconds);
      },

    },
    computed: {

      filterState(){
        return {species: this.filterSpecies, cam: this.filterCam, month: this.filterMonth}
      },

      tags(){
        // make route friendly tags
        return tagData.map(t => {return {...t, routeTag: t.tag.toLowerCase().replaceAll(" ","-")}})
      },

      focusedSpecies(){ // full tag data for the focused species
        let s = null;
        if (this.filterSpecies) s = this.tags.find(t => t.routeTag == this.filterSpecies)
        return s;
      },


      allTags(){
        let tagData = this.monthFilteredSet.intersection(this.camFilteredSet)
        let tagArray = [...tagData]
        let ft = this.tags.map(t => {
          let c = tagArray.filter(c => c.tags.indexOf(t.tag) > -1)
          return {...t, count:c.length }
        })
        // to filter out tags with no count
        // return ft.filter(c => c.count > 0)
        return ft
      },

      tagMap(){
        let m = {};
        this.tags.forEach(t => m[t.tag] = t);
        return m;
      },

      captures(){
         let caps = this.captureData.map(c => {

          let cam = c.path.split("/")[0]
          let thumbUrl = c.path.split("/")[0]+"/thumbnails/thumb_" +c.path.split("/")[1];
          if (c.type == "video") thumbUrl = c.path.split("/")[0] + "/thumbnails/thumb_" + c.path.split("/")[1].replace("mp4","jpg");

          let date = c.filename.split("-")[1];
          let time = c.filename.split("-")[2].split(".")[0];
          let datetime = date+time;

          let startdate = new Date(2024,9,25); // month is zero indexed!
          let capdate = new Date(parseInt(date.substring(0,4)),parseInt(date.substring(4,6))-1,parseInt(date.substring(6,8)) )
    
          const oneDay = 24 * 60 * 60 * 1000;
          const diffDays = Math.round(Math.abs((startdate - capdate) / oneDay));

          let camLabel;
          if (cam == "cam3") camLabel = "A"
          if (cam == "cam4" && diffDays < 97) camLabel = "B"
          if (cam == "cam4" && diffDays >= 97) camLabel = "F"
          if (cam == "cam5" && diffDays < 97) camLabel = "C"
          if (cam == "cam5" && diffDays >= 97) camLabel = "G"
          if (cam == "cam6") camLabel = "D"
          if (cam == "cam7") camLabel = "E"

          let month;
          let matchMonth = this.months.find(m => ((m.dayrange[0] <= diffDays) && (m.dayrange[1] >= diffDays)));
          month = matchMonth ? matchMonth.label : "none" ;  

          const timestamp = this.convertTimestampToDate(date+"-"+time)

         return {  ...c,  
                   cam: cam,
                   camLabel:camLabel,
                   thumb: thumbUrl, 
                   datetime:parseInt(datetime),
                   timestamp: timestamp,
                   dayIndex: diffDays,
                   month:month
                 };
        })

         caps =  caps.filter(c => !c.blank).sort((a,b) => { return a.datetime - b.datetime})
         return caps
      },


      allCapturesSet(){
        return new Set(this.captures)
      },

      monthFilteredSet(){
        if (this.filterMonth){
          return new Set(this.captures.filter(c => c.month == this.filterMonth))
        } else {
          return this.allCapturesSet;
        }
      },

      camFilteredSet(){
        if (this.filterCam){
          return new Set(this.captures.filter(c => c.camLabel == this.filterCam))
        } else {
          return this.allCapturesSet;
        }
      },

      speciesFilteredSet(){
        if (this.filterSpecies) {
            let matchingTag = this.tags.find(t => t.routeTag == this.filterSpecies)
            if (!matchingTag) {
              console.log(" no matching tag ")
              console.log(this.filterSpecies)
              return this.allCapturesSet
            };
            return new Set(this.captures.filter(c => c.tags.indexOf(matchingTag.tag) > -1))
        } else {
            return this.allCapturesSet;
        }
      },

      filteredCaptures(){
        let allFilterSet = this.monthFilteredSet.intersection(this.camFilteredSet.intersection(this.speciesFilteredSet))
        return [...allFilterSet]
      },


      viewPage(){
        let p = this.filteredCaptures.slice(0,this.viewItems);
        return p
      },

      camData(){
        // use sets to find the intersection of two other filters
        let camHistoData = this.speciesFilteredSet.intersection(this.monthFilteredSet)
        let camHistoArray = [...camHistoData]
        return camHistoArray;
      },

      monthContextHisto(){
        let monthHistoSet = this.speciesFilteredSet.intersection(this.camFilteredSet)
        return [...monthHistoSet]
      }

    }

  }



</script>

<template>
  <div class="controlWrapper">
  <CaptureHisto :capture-data="captures" :context-captures="monthContextHisto" :tag-map="tagMap" :filter-state="filterState" @set-filter="setFilter"></CaptureHisto>
  <CamMap :cam-data="camData" :filter-state="filterState" @set-filter="setFilter"></CamMap>
</div>

 <div class="prompt">
      <p>Filter by month, camera and species to browse over 1500 images and videos</p>
  </div>

  <div class="headerTags">
    <span class="itemTag big label">Species:</span>
    <span v-for="t in allTags" class="itemTag big" :class="{'active': filterSpecies == t.routeTag, 'mammal': t.group == 'mammal', 'bird': t.group != 'mammal', 'zero':t.count == 0}" @click="setFilter('species',t.routeTag)">{{t.tag}} 
          <span v-if="t.count > 0">({{t.count}})</span>
      </span>
  </div>

  <SpeciesInfo v-if="focusedSpecies" :species-name="focusedSpecies.scientificName" context="captures"/>


  <div class="captures">
    <CaptureItem v-for="c in viewPage" :key="c.path" :capture="c" :base-url="baseUrl" @set-filter="setFilter">

    </CaptureItem>
    <div class="loadMore" v-if="filteredCaptures.length > viewItems" >
      <a @click="loadMore()"> + LOAD MORE</a>
    </div>
  </div>



  
</template>

<style>

  p, span{
    font-family: Inconsolata, sans-serif;
  }

  .prompt{
    
    background-color: white;
    padding: 0.5rem 1rem;
    margin: 0 auto 0.5rem;
    width: fit-content;
  }

  .prompt p{
    font-family: Lato, sans-serif;
    margin:0;
    font-size: 0.9rem;
    text-align: center;
  }
  
  .captures, .headerTags{
    display: flex;
    flex-wrap: wrap;
    flex-direction: row;
    margin:0 auto;
    width:95%;
    max-width: 1800px;
    justify-content: center;
  }

  .headerTags{
    margin-bottom: 1rem;
  }

  .headerTags span.label{
    font-weight: 600;
/*    font-size: 1.2rem;*/
    background: none;
    pointer-events: none;
   
/*    height:2rem;*/
/*    display: inline-block;*/
  }

  .captures{
    column-gap: 2rem;
    padding-bottom: 240px;
  }

  .itemTag{
    font-size: 80%;
    display: inline-block;
    margin:0.25rem 0.25rem 0 0;
    background-color: #ddd;
    padding:0.25rem 0.5rem;
    cursor: pointer;
    border-radius:0.75em;
    border:1px solid transparent;
  }

  .itemTag:not(.zero):hover{
    border:1px solid black;
  }

  .controlWrapper{
    display: flex;
    flex-direction: row;
    flex-wrap: no-wrap;
    justify-content: center;
    margin: 1rem 0;
    gap:1rem;
    align-items: center;
  }

  .big{
    font-size: 105%;
  }

  .itemTag.bird{
    background-color: #8fcece;
  }

  .itemTag.mammal{
    background-color: #e0b2a3;
  }

  .itemTag.zero{
 background-color: #aaa;
    opacity:0.5;
    cursor: default;
  }

  .itemTag.active{
    background-color: #222;
    color:white;

  }


  .loadMore{
/*    min-width: 100px;
    max-width:180px;*/
/*    padding:1rem;*/
/*    background-color: white;*/
    text-align: center;
    margin:0.5rem;
    flex-basis: 100%;
  }

  .loadMore a{
    display: inline-block;
    padding:0.5rem 1rem;
    background-color: white;
    cursor:pointer;
    border-radius: 1rem;
  }

  .loadMore a:hover{
    background-color: #222;
    color:white;
  }

    @media screen and (width < 720px) {

      .headerTags{
        font-size:90%;
      }

/*      .prompt{
        flex-basis:100%;
        max-width:unset;
        margin:0 auto;
      }*/

    }



</style>

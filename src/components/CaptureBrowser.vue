<script setup>
     import CaptureItem from '@/components/CaptureItem.vue'
     import CaptureHisto from '@/components/CaptureHisto.vue'
     import CamMap from '@/components/CamMap.vue'
</script>

<script type="text/javascript">
   import jsonData from '@/assets/data/captures.json';
   import tagData from '@/assets/data/tags.json';
   import { interpolateRgb } from 'd3-interpolate';

   export default{
    data(){
      return {
        captureData: jsonData,
        // tags: tagData,
        tagFilters: [],
        filterState:{},
        camFilter: "All",
        cams: ["A","B","C","D","E","F","G"],
        months: [{label:"nov", dayrange:[0,35]}, {label:"dec", dayrange:[36,67]},{label:"jan", dayrange: [68,98]},{label:"feb", dayrange:[99,122]} ],
        baseUrl: "https://storage.googleapis.com/cherax-media/",
        // currentPage: 1,
        // itemsPerPage:20,
        viewItems: 20,
        overviewMode: "histo"
      }
    },

    created() {
        console.log(this.$route.query); // Access all query parameters as an object
        this.setFilterState(this.$route.query)
      },


    methods: {
    
      setFilterState(filter){
       
        if (this.filterState[filter.key] == filter.value ) {
          delete this.filterState[filter.key];
        } else {
          this.filterState[filter.key] = filter.value;
        }
        
        this.$router.push({path: '/captures', query: this.filterState})
        this.viewItems = 20;
      },

      loadMore(){
        this.viewItems += 20;

      },

      changeCamFilter(){
        this.viewItems = 20;
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
      tags(){
        // make route friendly tags
        return tagData.map(t => {return {...t, routeTag: t.tag.toLowerCase().replace(" ","-")}})
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
         //console.log(caps.length + " captures loaded")
         return caps
         //return new Set(caps);
      },

      
      // camItems(){
      //     let filtered = this.captures;
      //     if (this.camFilter != "All") filtered = filtered.filter(c => c.cam == this.camFilter) // apply cam filter if set
      //     return filtered;
      // },

      allCapturesSet(){
        return new Set(this.captures)
      },

      monthFilteredSet(){
        if (this.$route.query.month){
          return new Set(this.captures.filter(c => c.month == this.$route.query.month))
        } else {
          return this.allCapturesSet;
        }
      },

      camFilteredSet(){
        if (this.$route.query.cam){
          return new Set(this.captures.filter(c => c.camLabel == this.$route.query.cam))
        } else {
          return this.allCapturesSet;
        }
      },

      speciesFilteredSet(){
        if (this.$route.query.species) {
            let matchingTag = this.tags.find(t => t.routeTag == this.$route.query.species)
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

      camData(){
        // use sets to find the intersection of two other filters
        let camHistoData = this.speciesFilteredSet.intersection(this.monthFilteredSet)
        let camHistoArray = [...camHistoData]
        return camHistoArray;
      },

      monthContextHisto(){
        let monthHistoSet = this.speciesFilteredSet.intersection(this.camFilteredSet)
        return [...monthHistoSet]
      },

      camMatrix(){
        let m = [];
        for (var d=0; d<123; d++){
          let col = []; // 
          for (const cam of this.cams){
            let caps = this.camItems.filter(c => c.dayIndex == d && c.cam == cam);
            let mammalcount = 0;
            let focus = false
            caps.forEach(c => {
              c.tags.forEach(t => {
                if (this.tagMap[t].group == "mammal") mammalcount++;
                if (this.tagFilters[0] && t == this.tagFilters[0]) focus = true;
              })
            })
            col.push({day:d, cam: cam, count: caps.length, mix: mammalcount/caps.length, focus: focus});
          }
          m.push(col)
        }
        //console.log(m)
        return m;
      },


    }

  }



</script>

<template>
  <div class="controlWrapper">
  <CaptureHisto :capture-data="captures" :context-captures="monthContextHisto" :tag-map="tagMap" :filter-state="filterState" @set-filter="setFilterState"></CaptureHisto>
  <CamMap :cam-data="camData" :filter-state="filterState" @set-filter="setFilterState"></CamMap>
</div>

  <div class="headerTags">
      <span v-for="t in allTags" class="itemTag big" :class="{'active': filterState.species == t.routeTag, 'mammal': t.group == 'mammal', 'bird': t.group != 'mammal', 'zero':t.count == 0}" @click="setFilterState({key:'species',value:t.routeTag})">{{t.tag}} <span v-if="t.count > 0">({{t.count}})</span></span>
  </div>


  <div class="captures">
    <CaptureItem v-for="c in viewPage" :key="c.path" :capture="c" :base-url="baseUrl" @set-filter="setFilterState">

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

  .captures{
    column-gap: 2rem;
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
    justify-content: center;
    margin: 1rem 0;
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
    background-color: black;
    color:white;

  }


  .loadMore{
    min-width: 100px;
    max-width:180px;
    padding:1rem;
    background-color: white;
    text-align: center;
    margin:0.5rem;
  }

  .loadMore a{
    cursor:pointer;
  }



</style>

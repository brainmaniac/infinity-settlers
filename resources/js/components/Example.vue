<template>
  <div>
    <v-stage
      ref="stage"
      :config="configKonva"
      @dragstart="handleDragstart"
      @dragend="handleDragend"
    >
      <v-layer ref="layer">
        <v-regular-polygon
          v-for="item in list"
          :key="item.id"        
          :config="{  
            x: item.x,
            y: item.y,
            sides: 6,
            rotation: item.rotation,
            id: item.id,
            radius: 50,
            outerRadius: 50,
            fill: 'green',
            draggable: true,
          }"
        ></v-regular-polygon>
      </v-layer>
    </v-stage>
  </div>
</template>

<script>

const width = window.innerWidth;
const height = window.innerHeight;

export default {
  data() {
    return {
      list: []  ,
      dragItemId: null,
      configKonva: {
        width: width,
        height: height,       
      }
    };
  },
  methods: {
    handleDragstart(e) {
        //
    },
    handleDragend(e) {
        let item = this.list.find(i => i.id === e.target.id());

        let snapTo = { x: 0, y: 0}

        // Vue.set(this.list, 0, {
        //     ...item,
        //     x: snapTo.x,
        //     y: snapTo.y,
        // })

        this.list = this.list.map(function(shape) {
            if(shape.id === item.id) {
                return {
                    ...item,
                    x: snapTo.x,
                    y: snapTo.y,
                }
            }
        })

        Vue.set(this.list, 0, {
            ...item,
            x: snapTo.x,
            y: snapTo.y,
        })
    },
  },

  mounted() {
    this.list.push({
        id: 1,
        x: 100,
        y: 100,
    });
  }
};
</script>
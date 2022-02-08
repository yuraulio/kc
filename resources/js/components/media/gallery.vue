<template>
  <div class="card-body" style="height: 700px">
    <h4 class="header-title">With captions</h4>
    <p class="sub-header font-13">
      Add captions to your slides easily with the
      <code>.carousel-caption</code> element within any
      <code>.carousel-item</code>.
    </p>

    <div
      id=""
      class="carousel"
    >
      <div  class="carousel-inner" role="listbox">
        <div v-for="img in images" :class="'carousel-item ' + (activeImg == img.id ? ' active' : '')">
          <img
            :src="img.url"
            alt="..."
            class="d-block img-fluid"
          />
          <div class="carousel-caption d-none d-md-block bg-grey">
            <h3 class="text-white">{{ img.name }}</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
          </div>
        </div>
      <a
        class="carousel-control-prev"
        href="#"
        role="button"
        @click.prevent="list('back')"
        data-bs-slide="prev"
      >
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </a>
      <a
        class="carousel-control-next"
        href="#"
        role="button"
        @click.prevent="list('next')"
        data-bs-slide="next"
      >
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </a>
    </div>
  </div>
  </div>
</template>

<script>

export default {
  props: {
    images: {},
    opImage: {}
  },
  data() {
    return {
        activeImg: null
    };
  },
  computed: {
    currentImages() {
      return this.images
        ? _.map(this.images, function (o) {
            return o.url;
          })
        : [];
    },
  },
  methods: {
      list(type) {
          if (type == 'back') {
              this.activeImg = _.findIndex(this.images, {id : this.activeImg }) > 0 ?
              this.images[_.findIndex(this.images, {id : this.activeImg }) - 1].id : this.images[this.images.length - 1].id
          } else {
              this.activeImg = _.findIndex(this.images, {id : this.activeImg }) != this.images.length - 1 ?
              this.images[_.findIndex(this.images, {id : this.activeImg }) + 1].id : this.images[0].id
          }
      }
  },
  mounted() {
      this.activeImg = this.opImage.id;
    console.log("cim", this.currentImages);
  },
};
</script>

<style scoped>
.carousel {
    justify-content: center;
    position: relative;
    align-items: center;
    flex-wrap: wrap;
    width: 100%;
    height: 90%;
    background: #dee2e6;
    display: flex;
}
.carousel-item .active {
    display: inline-flex !important;
    justify-content: center !important;
}

.img-fluid {
    margin: 0 auto !important
}

.carousel-inner {
    position: relative;
    width: 100%;
    overflow: hidden;
    max-height: 600px;
    object-fit: contain;
    display: flex;
    align-items: center;
    flex-direction: row;
    height: 100%;
}
.carousel-control-prev:hover {
    background-color: rgba(0, 0, 0, 0.15)
}
.carousel-control-next:hover {
    background-color: rgba(0, 0, 0, 0.15)
}
</style>

<template>
    <div class="reviews-wrapper">
        <div v-if="reviews.length">
            <div 
                v-for="item in reviews"
                :key="item.id"
                class="reviews-section__item"
            >
                <div class="reviews-section__item__left">
                    <div 
                        class="reviews-rate__rating" 
                        :style="{ '--rating': item.rating }"
                    ></div>
                    <p v-if="item.reviewsFor" v-html="item.reviewsFor"></p>
                </div>
                <div class="reviews-section__item__text">
                    <p v-if="item.reviewText">{{ item.reviewText }}</p>
                    <span v-if="item.reviewAuthor">{{ item.reviewAuthor }}</span>
                </div>
                <div class="reviews-section__item__date">
                    <span>{{ item.date }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Flickity from 'flickity';
	export default {
		data: function () {
			return {
                pages:0,
                reviews:[],
                currentPage:1,
                flickity:null,
			}
		},
		mounted() {
			axios.get('/reviews-api')
            .then(response => this.reviews = response.data)
            .then(data => this.pages = data[0].pages)
            .then(res => this.getFlickity())
		},
        methods:{
            loadMore(){
                this.buttonText = 'Loading...'
                axios.post(`/reviews-api?page=${this.currentPage + 1}`)
                .then(response => {
                    this.reviews = [...this.reviews, ...response.data]
                    this.buttonText = 'More reviews'
                })
                this.currentPage++
            },
            getFlickity() {
				this.flickity = new Flickity(this.$refs.productImage, {
					contain: true,
					cellAlign: 'center',
					percentPosition: false,
					freeScroll: false,
					prevNextButtons: false,
					pageDots: true,
					adaptiveHeight: true,
				})
			}
        }
	}
</script>
<template>
	<div 
		class="product" 
		v-if="product.name" 
		:id="id"
	>
		<product-slider :flickity="flickity" :name="product.name" :index="index"></product-slider>
		<div 
			ref="productImage"
			class="product-image"
			:class="`product-${index}`"
			@flickity-change="changeSlide(index)"
		>
			<div 
				class="product-image-img"
			>
				<img :src="replaceImageExt" :alt="product.name">
			</div>
			<div 
				v-if="infoText.length"
				class="product-image__info"
			>
				<h3 v-if="infoTitle">{{ infoTitle }}</h3>
				<div 
					v-if="infoText.length"
					class="product__popupInfo"
				>
					<div 
						v-for="(item, idx) in infoText"
						:key="item.prodInfoTitle"
						class="product__popupInfo__item"
						:id="idx"
					>
						<div class="product__popupInfo__item__left">
							<h4>{{ item.title }}</h4>
							<p>{{ item.text }}</p>
						</div>
						<div class="product__popupInfo__item__video">
							<img :src="videos[idx]"/>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="product-info">
			<div class="product-info__left">
				<span class="product-title" v-text="product.name"></span>
				<div class="product-descriptions">
					<span v-text="`${product.type.name} ${product.strength}`"></span>
					<div class="product-descriptions--meta">
						<span v-text="product.quantity"></span>
						<span v-show="product.prescriptionOnly">, Rx only</span>
					</div>
				</div>
			</div>
			<div class="product-info__right">
				<span class="product-price" v-text="`\$ ${product.price}.00`" v-if="!couponCode"></span>
				<div v-else>
					<span class="product-price -groupon-price"><del>$ {{ product.grouponPrice}}.00</del></span>
					<span class="product-price -groupon-price">$0.00</span>
					<div class="first-shipment-free">
						<ul>
							<li>FREE order</li>
							<li>Includes free shipping</li>
							<li>Cancel anytime - no commitment</li>
							<li>Refills are offered at the lowest price of any major US telehealth company</li>
						</ul>
					</div>
				</div>
				<div class="product-meta">
					<div v-text="product.info"></div>
				</div>
			</div>
		</div>
		<div class="buttonWrapper">
			<a :href="`/cart/products/${id}`" class="button button-product" v-text="`${action} ${product.name} ${product.strength}`"></a>
		</div>
	</div>
</template>

<script>
	import axios from 'axios';
	import Flickity from 'flickity';
	import productSlider from './ProductSlider.vue';
	export default {
		components:{
			productSlider
		},
		props: {
			id: {},
			action: {default: 'Get'},
			couponCode: { type: String },
			infoTitle:{
				type:String,
				default:''
			},
			infoText:{
				type:Array,
				default:[]
			},
			videos:{
				type:Array,
				default:[]
			},
		},
		data: function () {
			return {
				product: {
					type: {}
				},
				flickity:null,	
				changed:false,
				index:0,
			}
		},
		mounted() {
			axios.get(`/api/v1/products/${this.id}`)
			.then((response) => {
				this.product = response.data.data;
			})
			.then((res) => {
				if(window.innerWidth < 1025) {
					this.getFlickity()
					this.flickity.on('change', this.changeIndex)
				}
			});
		},
		computed:{
			replaceImageExt() {
				return this.product.thumbnail.split('.jpg').join('.png')
			}
		},
		methods:{
			changeIndex(index) {
				this.index = index
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
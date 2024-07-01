<template>
	<span class="cart-count" v-html="cartCount" v-if="gotResponse" v-show="cartCount"></span>
</template>

<script>
	import axios from 'axios';
	export default {
		data: function () {
			return {
				gotResponse: false,
				cartCount: 0
			}
		},
		mounted() {
			axios.get(`/api/v1/cart`)
				.then((response) => {
					this.gotResponse = true;
					var count = 0;
					if (response.data) {
						for (var key in response.data.data.items) {
							count += response.data.data.items[key].qty;
						}
					}
					this.cartCount = count;
				});
		}
	}
</script>
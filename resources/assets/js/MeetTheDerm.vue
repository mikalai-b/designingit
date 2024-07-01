<template>

	<div class="meet-the-derm" v-if="provider.label">
		<input type="hidden" name="provider" :value="provider.id" />
	</div>
	<div v-else-if="noProvider">
		<span class="help-block">
			<strong>We are not currently providing services in your state. Please check back with us again soon.</strong>
		</span>
	</div>
</template>

<script>
	import axios from 'axios';
	export default {
		props: {
			'selectId' : {
				default: 'state'
			}
		},
		data: function () {
			return {
				provider: {
					id: 0
				},
				noProvider: false
			}
		},
		mounted() {
			this.checkStateField();
			document.getElementById(this.selectId).addEventListener('change', () => {
				this.checkStateField();
			});
		},
		methods: {
			checkStateField() {
				var select = document.getElementById(this.selectId);
				var state = select.options[select.selectedIndex].value;
				if (state) {
					this.retrieveDermForState(state);
				}
			},
			retrieveDermForState(state) {
				axios.get('/api/v1/providers?state='+state+'&checkout=1')
					.then((response) => {
						if (response.data.data.length) {
							this.provider = response.data.data[0];
						} else {
							this.provider = {};
							this.noProvider = true;
						}
					}
				).catch(e => console.log(e));;
			},
			getAvatar() {
				if (this.provider && this.provider.avatar) {
					return this.provider.avatar;
				}
				return '/images/default-avatar.png';
			}
		}
	}
</script>
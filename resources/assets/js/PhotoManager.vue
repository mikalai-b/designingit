<template>
	<div class="photo-manager">
		<div class="photo-manager__photo" v-for="(photo, photoIndex) in photos" :key="photoIndex" v-show="photoSlotIsVisible(photoIndex)">
			<div class="photo-manager__number">{{ photoIndex + 1 }}</div>
			<div class="photo-manager__photo-wrapper">
				<div class="preview" v-if="photoHasSource(photo)">
					<label :for="'photo-'+typeId+'-'+photoIndex">
						<img :src="getPhotoSource(photo)" />
					</label>
					<div v-if="photoHasSource(photo)">
						<i class="fa fa-trash remove-cta" @click="removePhoto(photo)"></i>
					</div>
				</div>
				<div v-else>
				<file-pond
					:name="`photos[${typeId}][]`"
					:ref="`pond${typeId}`"
					label-idle='<i class="fa fa-upload"></i> Click here to upload image'
					:allow-multiple="false"
					accepted-file-types="image/*,capture=camera"
					:store-as-file="true"
					:max-files="1"
					:image-resize-target-width="1600"
					:image-resize-upscale="false"
					:required="hasNoPhotos"
					@updatefiles="fileUpdated($event, photoIndex)"
				/>

				</div>


				<span class="help-block" v-if="photo.error">
					<strong>{{ photo.error }}</strong>
				</span>
			</div>




		</div>

		<template v-for="removedPhoto in removedPhotos">

			<input type="hidden" name="remove[]" :value="removedPhoto" :key="removedPhoto">

		</template>

	</div>
</template>

<script>
	import axios from 'axios';
	import vueFilePond from 'vue-filepond'
	import 'filepond/dist/filepond.min.css'
	import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'
	import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'
	import FilePondPluginImageResize from 'filepond-plugin-image-resize'
	import FilePondPluginImagePreview from 'filepond-plugin-image-preview'
	const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImageResize, FilePondPluginImagePreview);
	export default {
		props: ['typeId', 'maxUploads', 'consultationId'],
		data: function () {
			return {
				photos: [],
				removedPhotos: [],
				visibleSlots: 1,
				photosWithSource: [],
				filepondImages: [],
			}
		},
		mounted() {
			this.buildPhotos();
			if (this.consultationId) {
				this.retrievePhotos();
			}
		},
		computed: {
			hasNoPhotos() {
				return this.photosWithSource.length === 0 && this.filepondImages.length === 0
			}
		},
		methods: {
			buildPhotos: function() {
				let photos = [];
				for (let i = photos.length; i < this.maxUploads; i++) {
					photos.push({source: '', error: '', file: ''});
				}
				this.photos = photos;
			},
			retrievePhotos: function() {
				axios.get(`/api/v1/consultation/${this.consultationId}/photos/${this.typeId}`)
					.then((response) => {
						if (response.data) {
							for (let i = 0; i < response.data.length; i++) {
								this.photos[i] = response.data[i];
								this.visibleSlots++;
								this.updatePhotosWithSource();
							}
						}
					});
			},
			photoHasSource: function(photo) {
				return (photo.source || photo.file);
			},
			getPhotoSource: function(photo) {
				return photo.source ? photo.source : photo.file;
			},
			updatePhotosWithSource: function() {
				this.photosWithSource = this.photos.filter((photo) => (this.photoHasSource(photo)));
			},
			photoSlotIsVisible: function(index) {
				return true
			},
			removePhoto: function(photo) {
				if (photo.inputField) {
					var input = document.getElementById(photo.inputField.id);
					input.value = '';
				}
				if (photo.id) {
					this.removedPhotos.push(photo.id);
				}
				photo.source = photo.file = photo.id = '';
				this.updatePhotosWithSource();
			},
			fileUpdated(e, index) {
				if (e.length) {
					if (this.filepondImages.indexOf(index) === -1) {
						this.filepondImages.push(index)
					}
				} else {
					this.filepondImages.splice(this.filepondImages.indexOf(index), 1)
				}
			}
		},
		components: {
			FilePond
		}
	}
</script>
<style scoped>
	.filepond--item::before {
		display: none !important;
	}
</style>
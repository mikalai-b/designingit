<template>
	<div class="notes notes-input">
		<ul>
			<li v-for="(note, index) in notes" :key="index">
				{{ note }} <a href="#" @click.prevent="removeNote(index)"><i class="fa fa-trash"></i></a>
				<input type="hidden" :name="inputName + '[]'" :value="note">
			</li>
			<li>
				<input type="text" v-model="newNote" @keypress.enter.prevent="addNote"> <a href="#" @click.prevent="addNote"><i class="fa fa-plus-circle"></i></a>
			</li>
		</ul>
	</div>
</template>

<script>
	import axios from 'axios';
	export default {
		props: ['inputName', 'prepopulate'],
		data: function () {
			return {
				notes: [],
				newNote: ''		
			}
		},
		mounted() {
			if (this.prepopulate) {
				this.notes = JSON.parse(this.prepopulate);
			}
		},
		methods: {
			removeNote: function(index) {
				this.notes.splice(index, 1);
			},
			addNote: function() {
				if (this.newNote) {
					this.notes.push(this.newNote);
					this.newNote = '';
				}
			}
		}
	}
</script>
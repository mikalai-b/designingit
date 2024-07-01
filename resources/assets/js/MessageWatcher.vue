<template>
	<div class="message-watcher">
		<div class="notifications" v-if="notifications.length">
			<div class="portal-notification" v-for="(notification, index) in notifications" :key="index">New message from <a :href="'/dashboard/patients/'+notification.sender_id">{{ notification.sender_name }}</a></div>
		</div>
		<div v-else>
			There are no new messages.
		</div>
	</div>
</template>

<script>
	import axios from 'axios';
	export default {
		props: ['inputName', 'prepopulate'],
		data: function () {
			return {
				notifications: [],
				pollingInterval: 10000, // in milliseconds,
				newMessages: [],
			}
		},
		mounted() {
			this.lookForNewMessages();
			setInterval(function() {
				this.lookForNewMessages();
			}.bind(this), this.pollingInterval);
		},
		methods: {
			lookForNewMessages: function() {
				axios.get(`/api/v1/messages/new`)
					.then((response) => {
						if (response.data.data) {
							this.newMessages = response.data.data;
							this.processNewMessages();
						} else {
							this.newMessages = [];
						}
					})
					.catch(function (error) {
						
					});
			},

			processNewMessages: function() {
				let newNotifications = [];
				this.newMessages.forEach((message) => {
					if (newNotifications.filter((notification) => { return notification.sender_id == message.sender.id}).length == 0) {
						newNotifications.push({ sender_id: message.sender.id, sender_name: (message.sender.firstName + ' ' + message.sender.lastName) });
					}
				});
				this.notifications = newNotifications;
			}
		}
	}
</script>
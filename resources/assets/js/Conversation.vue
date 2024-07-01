<template>
	<div class="conversation">
		<div class="messages">
			<div class="no-messages" v-if="!messages.length">
				<i class="fa fa-comment-alt"></i>
				<h2>Looks like you don't have any messages yet.</h2>
				<span>Message your dermatologist when you have any questions or concerns regarding your prescription.</span>
			</div>
			<div class="error" v-show="hasLoadingBeforeError">
				Could not retrieve additional messages.
			</div>
			<div class="error" v-show="hasSendMessageError">
				Could not send message.
			</div>
			<div class="loading" v-show="isLoadingBefore">
				<div class="dot"></div>
				<div class="dot"></div>
				<div class="dot"></div>
			</div>
			<div v-for="message in messages" :key="message.id" :class="{ 'message': true, 'my-message': messageIsMine(message)}">
				<div class="message--sender">
					<img v-bind:src="getAvatarForMessage(message)" alt="Message from" class="avatar">
				</div>
				<div class="message--content">
					<div class="name">{{ message.sender.firstName }} {{ message.sender.lastName }}</div>
					<div class="body" v-html="message.body">
						{{ message.body }}
					</div>
				</div>
				<div class="message--meta">
					<div class="date">
						{{ localTime(message.dateCreated.date) }}
					</div>
				</div>
			</div>

			<div class="loading" v-show="isLoadingSince">
				<div class="dot"></div>
				<div class="dot"></div>
				<div class="dot"></div>
			</div>
		</div>
		<div class="new-message">
			<textarea v-model="newMessageBody" v-on:keyup="detectEnter" placeholder="Send a message..."></textarea>
		</div>
	</div>
</template>

<script>
	import axios from 'axios';
	import moment from 'moment';
	export default {
		props: ['myPersonId', 'otherPersonId'],
		data: function () {
			return {
				isLoadingBefore: false,
				isLoadingSince: false,
				hasLoadingBeforeError: false,
				messages: [],
				newMessageBody: null,
				pollingInterval: 4000,
				isPollingPaused: false,
				hasSendMessageError: false
			}
		},
		mounted() {
			this.getLatest();
			setInterval(function() {
				this.getNew();
			}.bind(this), this.pollingInterval);
			this.$el.querySelector(".messages").addEventListener('scroll', this.handleScroll);
		},
		methods: {
			getNew() {
				if (!document.hidden || this.isPollingPaused) {
					if (this.messages.length) {
						var lastMessage = this.messages[(this.messages.length) - 1];
						this.getSince(lastMessage);
					} else {
						this.getLatest();
					}
				}
			},
			handleScroll() {
				if (this.$el.querySelector(".messages").scrollTop == 0) {
					if (this.messages.length) {
						this.getBefore(this.messages[0]);
					}
				}
			},
			getLatest() {
				this.isPollingPaused = true;
				axios.get(`/api/v1/messages/latest?limit=5&with=${this.otherPersonId}`)
					.then((response) => {
						if (response.data.data) {
							this.messages = response.data.data;
							this.messages.reverse();
						}
						this.resetScrollPosition();
						this.isPollingPaused = false;
					})
					.catch(function (error) {
						this.isPollingPaused = false;
					}.bind(this));
			},
			getBefore(message) {
				this.isPollingPaused       = true;
				this.hasLoadingBeforeError = false;
				this.isLoadingBefore       = true;
				var currentMessagesHeight = this.$el.querySelector(".messages").scrollHeight;
				axios.get(`/api/v1/messages/before/${message.id}?with=${this.otherPersonId}`)
					.then((response) => {
						if (response.data.data) {
							var newMessages = response.data.data;
							if (newMessages.length) {
								newMessages.reverse();
								this.messages = newMessages.concat(this.messages);
								this.adjustScroll(currentMessagesHeight);
							}
						}
						this.isLoadingBefore = false;
						this.isLoadingSince  = false;
						this.isPollingPaused = false;
					})
					.catch(function (error) {
						this.isLoadingBefore       = false;
						this.isLoadingSince        = false;
						this.hasLoadingBeforeError = true;
						this.isPollingPaused       = false;
					}.bind(this));
			},
			adjustScroll(fromHeight) {
				var container = this.$el.querySelector(".messages");
					this.$nextTick(() => {
						if (container.scrollHeight > fromHeight) {
							container.scrollTop = container.scrollHeight - fromHeight - 40;
						}
					});
			},
			getSince(message) {
				this.isPollingPaused = true;
				axios.get(`/api/v1/messages/since/${message.id}?with=${this.otherPersonId}`)
					.then((response) => {
						if (response.data.data) {
							var newMessages = response.data.data;
							if (newMessages.length) {
								this.messages = this.messages.concat(newMessages);
								this.resetScrollPosition();
							}
						}
						this.isPollingPaused = false;
						this.isLoadingSince = false;
					})
					.catch(function (error) {
						this.isPollingPaused = false;
						this.isLoadingSince = false;
					}.bind(this));
			},
			resetScrollPosition() {
				var container = this.$el.querySelector(".messages");

				this.$nextTick(() => {
					container.scrollTop = container.scrollHeight;
				});
			},
			messageIsMine(message) {
				return message.sender.id == this.myPersonId;
			},
			getAvatarForMessage(message) {
				if (message.sender.avatar) {
					return message.sender.avatar;
				}
				return "/images/default-avatar.png";
			},
			getLatestMessageId() {
				if (this.messages.length) {
					return this.messages[this.messages.length - 1].id;
				}
			},
			sendNewMessage() {
				this.isLoadingSince       = true;
				this.hasSendMessageError = false;
				this.resetScrollPosition();
				axios.post('/api/v1/messages', { body: this.newMessageBody, re: this.getLatestMessageId(), to: this.otherPersonId })
						.then((response) => {
							this.isLoadingSince      = false;
						})
						.catch(function (error) {
							this.hasSendMessageError = true;
							this.isLoadingSince      = false;
						}.bind(this));
			},
			detectEnter(e) {
				if (e.keyCode === 13) {
					this.newMessageBody = this.newMessageBody.trim();
					if (this.newMessageBody) {
						this.sendNewMessage();
						this.newMessageBody = '';
					}
				}
			},
			localTime(datetime) {
				datetime = datetime.replace(/\.000000/, '');
				let utcDate = moment.utc(datetime);
				return moment(utcDate).local().calendar('', { sameElse: 'M/D/YYYY, h:mm A'});
			}
		}
	}
</script>
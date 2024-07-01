<template>

	<div class="login-wrapper">

		<div v-show="gotResponse">
			<div v-show="currentUser.loggedIn">
        <span class="welcome-message menu-item" v-show="currentUser.loggedIn && currentUser.firstName">
            <a href="/dashboard">Welcome {{ currentUser.firstName }}!</a>
        </span>
				<a href="/dashboard/logout" class="button button-login button-login--logout"></a>
			</div>
			<div v-show="!currentUser.loggedIn">
				<a href="/login" class="button button-login"></a>
			</div>
		</div>

	</div>
</template>

<script>
	import axios from 'axios';
	export default {
		data: function () {
			return {
				gotResponse: false,
				currentUser: { loggedIn: false }
			}
    },
    methods: {
      startIdleTimer() {
        // Auto-logoout Timer
        var minutesIdle = 0;
        var idleChecker = function() {
            minutesIdle += 1;

            if(minutesIdle >= 5) {
                showLogoutPopup();
            }

            $('body').on('mousemove keyup', () => minutesIdle = 0);
        };
        var showLogoutPopup = function() {
            var logoutTimeout = setTimeout(() => {
                window.location.replace('/dashboard/logout')
            }, 60000);

            clearInterval(idleTimer);
            swal("You will be logged out in 1 minute due to inactivity. Click the button below to remain logged in", {
                buttons: {
                    confirm: "Stay Logged In"
                }
            }).then( () => {
                clearTimeout(logoutTimeout);
                var idleTimer = setInterval(idleChecker, 840000);
            } );

        }
        var idleTimer = setInterval(idleChecker, 840000);
      }
    },
		mounted() {
			axios.get(`/api/v1/users/current`)
				.then((response) => {
					this.gotResponse = true;
					if (response.data) {
						this.currentUser = response.data.data;
            this.currentUser.loggedIn = true;
            this.startIdleTimer();
						$('.dashboard-link').show();
					}
				});
		}
	}
</script>
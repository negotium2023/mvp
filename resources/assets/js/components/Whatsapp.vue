<template>
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" @click="clearNotifications">
            <i style="color: red;" class="fa fa-bell" v-if="hasUnread"></i>
            <i id="notificatins-icon" class="far fa-bell" v-else></i>
            <span class="badge badge-pill badge-danger" v-if="hasUnread">{{ counter }}</span>
        </a>
        <div class="dropdown-menu blackboard-notification-dropdown dropdown-menu-right">
            <a class="dropdown-item" v-for="notification in notifications" v-on:click="MarkAsRead(notification)">
                <div class="media">
                    <!--<img v-bind:src="getPic(message)" class="img-circle elevation-2" alt="User Image">-->
                    <div class="media-body">
                        <p class="text-sm">{{notification.name}}</p>
                        <p class="text-sm text-muted float-right"><i class="fa fa-clock mr-1"></i> {{notification.created}}</p>
                    </div>
                </div>
            </a>
            <a class="dropdown-item" v-if="notifications.length==0">No notifications yet!</a>
            <a v-on:click="goto_route()" class="dropdown-item" style="text-align:left;text-decoration: none;font-size:12px;cursor:pointer;">View Notification History</a>
        </div>
    </li>
</template>

<script>
    export default {
        props: {
            blackUser: {
                default: ''
            },
        },
        data() {
            return {
                hasUnreadM: false,
                messages: []
            }
        },
        methods: {
            /*getPic: function(message) {
                return message.image;
            },*/
            goto_Mroute: function () {
                var route = "/messages";
                location.href = route;
            },
            ListenForActivity: function (){
                Echo.private(`messages.${this.blackUser}`)
                    .listen('WhatsappEvent', (e) => {
                        console.log(`${this.blackUser}`);
                        this.hasUnreadM = true;
                        this.GetMessageCount();
                    });
            },
            GetMessageCount: function(){
                axios.post('/getmessagecount')
                    .then(response => {
                        if(response.data.count > 0){

                            this.hasUnreadM = true;
                        } else {
                            this.hasUnreadM = false;
                        }//
                        //
                        this.messagecounter = response.data.count;
                    })
                    .catch(error => {
                        // todo handle error
                    });
            },
            GetMessages: function(){
                axios.post('/getmessages')
                    .then(response => {

                        this.messages = response.data;
                    })
                    .catch(error => {
                        // todo handle error
                    });
            },
            ViewMessage: function(message){
                var data = {
                    id: message.id
                };

                    window.location.href = message.link;

            }
        },
        created() {
            this.GetMessageCount();
            this.GetMessages();
            this.ListenForActivity();
        }
    }
</script>

<style scoped>

</style>

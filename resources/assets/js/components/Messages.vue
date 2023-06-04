<template>
    <li class="nav-item dropdown">

        <a class="nav-link" data-toggle="dropdown" href="#">
            <i style="color: orange;" class="far fa-comments" v-if="1 == 1"></i>
            <i id="message-icon" class="far fa-comments" v-else></i>
            <span class="badge badge-pill badge-success" v-if="hasUnreadM">{{ messagecounter }}</span>
        </a>

        <div class="dropdown-menu blackboard-message-dropdown dropdown-menu-right" style="width:250px;">
            <a v-on:click="goto_Mroute()" class="dropdown-item" style="text-align:right;text-decoration: none;font-size:12px;cursor:pointer;">New Message</a>
            <a class="dropdown-item" v-for="message in messages" v-on:click="ViewMessage(message)" style="cursor:pointer">
                <div class="media">
                    <!--<img v-bind:src="getPic(message)" class="img-circle elevation-2" alt="User Image">-->
                    <div class="media-body">
                        <div style="float:left;width:70%;">
                            <p class="text-sm">
                                <i class="fab fa-whatsapp" aria-hidden="true" v-if="message.type==='whatsapp' && message.type!=='system'"></i>
                                <i class="far fa-comment" aria-hidden="true" v-if="message.type==='system' && message.type!=='whatsapp'"></i>
                                <strong>{{message.sender}}</strong></p>
                            <p class="text-sm"><span v-html="message.body">{{message.body}}</span></p>
                        </div>
                        <div style="float:right;width:20%;">
                            <i class="fas fa-circle" style="color: orange"></i>
                            <p class="text-sm text-muted float-right"><i class="far fa-clock mr-1"></i> {{message.created}}</p>
                        </div>
                    </div>
                </div>
            </a>
            <a class="dropdown-item" v-if="messages.length==0">No new messages</a>
            <a v-on:click="goto_Mroute()" class="dropdown-item" style="text-decoration: none;font-size:12px;cursor:pointer;">View All Messages</a>
        </div>
    </li>
</template>

<script>
    import moment from 'moment';

    Vue.filter('formatDate', function(value) {
        if (value) {
            return moment(String(value)).format('MM/DD')
        }
    });

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
                Echo.private(`messages.${this.blackUser}`)
                    .listen('MessageEvent', (e) => {
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

            },
            GetDate: function(){
                var currentDate = new Date();
                const formattedDate = moment(currentDate).format('MM/DD');
            }
        },
        created() {
            this.GetMessageCount();
            this.GetMessages();
            this.ListenForActivity();
            this.GetDate();
        }
    }
</script>

<style scoped>

</style>
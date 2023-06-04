<template>
    <li class="nav-item dropdown">

        <a class="nav-link" data-toggle="dropdown" href="#" @click="clearQANotifications">
            <i style="color: orange;" class="fas fa-egg" v-if="hasNew"></i>
            <i id="message-icon" class="fas fa-egg" v-else></i>
            <span class="badge badge-pill badge-success" v-if="hasNew">{{ qacounter }}</span>
        </a>

        <div class="dropdown-menu blackboard-message-dropdown dropdown-menu-right" style="width:250px;">
            <!--<a v-on:click="goto_Mroute()" class="dropdown-item" style="text-align:right;text-decoration: none;font-size:12px;cursor:pointer;">New Message</a>-->
            <a class="dropdown-item" v-for="qa in qas" v-on:click="ViewQA(qa)" style="cursor:pointer">
                <div class="media">
                    <!--<img v-bind:src="getPic(message)" class="img-circle elevation-2" alt="User Image">-->
                    <div class="media-body">
                        <p class="text-sm">{{qa.name}}</p>
                        <p class="text-sm text-muted float-right"><i class="fa fa-clock mr-1"></i> {{qa.created}}</p>
                    </div>
                </div>
            </a>
            <a class="dropdown-item" v-if="qas.length==0">No new QA</a>
            <a v-on:click="goto_Mroute()" class="dropdown-item" style="text-decoration: none;font-size:12px;cursor:pointer;">View All QA</a>
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
                hasNew: false,
                qas: []
            }
        },
        methods: {
            /*getPic: function(message) {
                return message.image;
            },*/
            goto_Mroute: function () {
                var route = "/clients?qa=yes";
                location.href = route;
            },
            clearQANotifications: function () {
                this.hasNew = false;
                axios.post('/markallqanotifications').then(response => {
                    console.log('success');
                });
            },
            GetQACount: function(){
                axios.post('/getqacount')
                    .then(response => {
                        if(response.data.count > 0){

                            this.hasNew = true;
                        } else {
                            this.hasNew = false;
                        }//
                        //
                        this.qacounter = response.data.count;
                    })
                    .catch(error => {
                        // todo handle error
                    });
            },
            GetQAs: function(){
                axios.post('/getqas')
                    .then(response => {

                        this.qas = response.data;
                    })
                    .catch(error => {
                        // todo handle error
                    });
            },
            ViewQA: function(qa){
                var data = {
                    id: qa.id
                };

                window.location.href = qa.link;

            }
        },
        created() {
            this.GetQACount();
            this.GetQAs();
        }
    }
</script>

<style scoped>

</style>
<style scoped>
    .action-link {
        cursor: pointer;
    }

    .m-b-none {
        margin-bottom: 0;
    }
</style>

<template>
    <div class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-borderless  table-sm">
                <tbody>
                    <tr>
                        <td><h3>OAuth Clients</h3></td>
                        <td class="text-right">
                            <a class="btn btn-sm btn-dark ml-2 mr-2" @click="showCreateClientForm">
                                <i class="fa fa-plus"></i> Client
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead class="btn-dark">
                    <tr>
                        <th nowrap="nowrap">Client ID</th>
                        <th nowrap="nowrap">Name</th>
                        <th nowrap="nowrap">Secret</th>
                        <th nowrap="nowrap" class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="client in clients">
                        <!-- ID -->
                        <td style="vertical-align: middle;">
                            {{ client.id }}
                        </td>

                        <!-- Name -->
                        <td style="vertical-align: middle;">
                            {{ client.name }}
                        </td>

                        <!-- Secret -->
                        <td style="vertical-align: middle;">
                            <code>{{ client.secret }}</code>
                        </td>

                        <!-- Edit Button -->
                        <td style="vertical-align: middle;" class="text-right">
                            <a class="btn btn-sm btn-dark ml-2 mr-2" @click="edit(client)">
                                <i class="fa fa-edit"></i>
                            </a>

                            <a class="btn btn-sm btn-danger ml-2 mr-2" @click="destroy(client)">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <!-- Current Clients -->
                    <tr v-if="clients.length === 0">
                        <td colspan="4">You have not created any OAuth clients.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Create Client Modal -->
        <div class="modal fade" id="modal-create-client" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="padding-top: 0.5rem !important; padding-bottom: 0.5rem !important;">
                        <h4 class="modal-title">
                            Create Client
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="createForm.errors.length > 0">
                            <p><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in createForm.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Create Client Form -->
                        <form class="form-horizontal" role="form">
                            <!-- Name -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" style="margin: 0px; padding-top: 0px; padding-bottom: 0px;">Name</label>
                                <div class="col-md-7">
                                    <input id="create-client-name" type="text" class="form-control form-control-sm" @keyup.enter="store" v-model="createForm.name">
                                    <span class="help-block">
                                        Something your users will recognize and trust.
                                    </span>
                                </div>
                            </div>
                            <!-- Redirect URL -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" style="margin: 0px; padding-top: 0px; padding-bottom: 0px;">Redirect URL</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control form-control-sm" name="redirect" @keyup.enter="store" v-model="createForm.redirect">
                                    <span class="help-block">
                                        Your application's authorization callback URL.
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-sm btn-primary" @click="store">Create</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Client Modal -->
        <div class="modal fade" id="modal-edit-client" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="padding-top: 0.5rem !important; padding-bottom: 0.5rem !important;">
                        <h4 class="modal-title">
                            Edit Client
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="editForm.errors.length > 0">
                            <p><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in editForm.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>
                        <!-- Edit Client Form -->
                        <form class="form-horizontal" role="form">
                            <!-- Name -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" style="margin: 0px; padding-top: 0px; padding-bottom: 0px;">Name</label>
                                <div class="col-md-7">
                                    <input id="edit-client-name" type="text" class="form-control form-control-sm" @keyup.enter="update" v-model="editForm.name">
                                    <span class="help-block">
                                        Something your users will recognize and trust.
                                    </span>
                                </div>
                            </div>
                            <!-- Redirect URL -->
                            <div class="form-group">
                                <label class="col-md-3 control-label" style="margin: 0px; padding-top: 0px; padding-bottom: 0px;">Redirect URL</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control form-control-sm" name="redirect" @keyup.enter="update" v-model="editForm.redirect">
                                    <span class="help-block">
                                        Your application's authorization callback URL.
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-sm btn-primary" @click="update">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                clients: [],

                createForm: {
                    errors: [],
                    name: '',
                    redirect: ''
                },

                editForm: {
                    errors: [],
                    name: '',
                    redirect: ''
                }
            };
        },

        /**
         * Prepare the component (Vue 1.x).
         */
        ready() {
            this.prepareComponent();
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },

        methods: {
            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getClients();

                $('#modal-create-client').on('shown.bs.modal', () => {
                    $('#create-client-name').focus();
                });

                $('#modal-edit-client').on('shown.bs.modal', () => {
                    $('#edit-client-name').focus();
                });
            },

            /**
             * Get all of the OAuth clients for the user.
             */
            getClients() {
                axios.get('/oauth/clients')
                        .then(response => {
                            this.clients = response.data;
                        });
            },

            /**
             * Show the form for creating new clients.
             */
            showCreateClientForm() {
                $('#modal-create-client').modal('show');
            },

            /**
             * Create a new OAuth client for the user.
             */
            store() {
                this.persistClient(
                    'post', '/oauth/clients',
                    this.createForm, '#modal-create-client'
                );
            },

            /**
             * Edit the given client.
             */
            edit(client) {
                this.editForm.id = client.id;
                this.editForm.name = client.name;
                this.editForm.redirect = client.redirect;

                $('#modal-edit-client').modal('show');
            },

            /**
             * Update the client being edited.
             */
            update() {
                this.persistClient(
                    'put', '/oauth/clients/' + this.editForm.id,
                    this.editForm, '#modal-edit-client'
                );
            },

            /**
             * Persist the client to storage using the given form.
             */
            persistClient(method, uri, form, modal) {
                form.errors = [];

                axios[method](uri, form)
                .then(response => {
                    this.getClients();

                    form.name = '';
                    form.redirect = '';
                    form.errors = [];

                    $(modal).modal('hide');
                })
                .catch(error => {
                    if (typeof error.response.data === 'object') {
                        form.errors = _.flatten(_.toArray(error.response.data));
                    } else {
                        form.errors = ['Something went wrong. Please try again.'];
                    }
                });
            },

            /**
             * Destroy the given client.
             */
            destroy(client) {
                let confirmFlag = confirm('Ayre you sure you want to delete this client?');

                if(!confirmFlag){
                    return false;
                }

                axios.delete('/oauth/clients/' + client.id)
                .then(response => {
                    this.getClients();
                });
            }
        }
    }
</script>

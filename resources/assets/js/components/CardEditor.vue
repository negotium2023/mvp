<template>
    <div>
        <ul class="list-group">
            <li v-for="(inputs, inputs_index) in inputs" class="list-group-item">
                <div class="form-row">
                <div class="form-group col-md-8 form-inline m-0" v-if="inputs.type!=='heading' && inputs.type!=='subheading'">
                        <label style="justify-content: left;width:10%;">Label</label>
                        <input type="hidden" :name="'inputs['+inputs_index+'][id]'" class="form-control form-control-sm" v-model="inputs.id" required/>
                        <input type="text" :name="'inputs['+inputs_index+'][name]'" class="form-control form-control-sm" placeholder="Input Label" style="justify-content: left;width:85%;" v-model="inputs.name" required/>
                </div>
                <div class="form-group col-md-8 form-inline m-0 p-0" v-if="inputs.type==='heading'">
                    <label class="pr-2" style="justify-content: left;width:10%;">Heading</label>
                    <input type="hidden" :name="'inputs['+inputs_index+'][id]'" class="form-control form-control-sm" v-model="inputs.id" required/>
                    <input type="hidden" :name="'inputs['+inputs_index+'][type]'" class="form-control form-control-sm" v-model="inputs.type" required/>
                    <textarea-autosize
                            :name="'inputs[' + inputs_index + '][name]'"
                            placeholder="Heading"
                            v-model="inputs.name"
                            rows="1"
                            :max-height="350"
                            @blur.native="onBlurTextarea"
                            class="form-control form-control-sm"
                            style="width: 85%;"
                            required
                    ></textarea-autosize>
                </div>
                <!-- <div class="form-group col-md-8 form-inline m-0 p-0" v-if="inputs.type==='subheading'">
                    <input type="hidden" :name="'inputs['+inputs_index+'][id]'" class="form-control form-control-sm" v-model="inputs.id" required/>
                    <input type="hidden" :name="'inputs['+inputs_index+'][type]'" class="form-control form-control-sm" v-model="inputs.type" required/>
                    <label class="pr-2" style="justify-content: left;width:10%;">Sub-heading</label>
                    <textarea-autosize
                            :name="'inputs[' + inputs_index + '][name]'"
                            placeholder="Sub-heading"
                            v-model="inputs.name"
                            rows="1"
                            :max-height="350"
                            @blur.native="onBlurTextarea"
                            class="form-control form-control-sm"
                            style="width: 85%;"
                            required
                    ></textarea-autosize>
                </div> -->
                <div class="form-group col-md-4 form-inline m-0 p-0" v-if="inputs.type==='heading' || inputs.type==='subheading'">

                    <div class="form-group col-md-7 form-inline">
                        <label class="pr-1">Level</label>
                        <div class="form-row col-md-10">
                            <div class="form-group col-md-12">
                                <select :name="'inputs['+inputs_index+'][level]'" class="form-control form-control-sm col-md-12" v-model="inputs.level" title="Level" :disabled="inputs.report == 1">
                                    <option v-for="levelType in levelTypes" :value="levelType.value">{{levelType.text}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" :name="'inputs['+inputs_index+'][color]'" :value="inputs.color"/>
                    <verte style="margin-top: 5px;" picker="square" v-model="inputs.color" @click="colorChange(inputs_index)"></verte>

                </div>
                <div class="form-group col-md-4 form-inline" v-if="inputs.type!=='heading' && inputs.type!=='subheading'">

                        <label class="pr-2" style="justify-content: left;width:20%;">Input Type</label>
                        <select :name="'inputs['+inputs_index+'][type]'" class="form-control form-control-sm" v-model="inputs.type" style="justify-content: left;width:80%;">
                            <option v-for="inputfieldType in inputTypes" :value="inputfieldType.value">{{inputfieldType.text}}</option>
                        </select>

                    </div>
                <div class="form-row mt-3" v-if="inputs.type==='dropdown'"> 
                    <!-- <div>
                        <button v-show="!inputs.is_dropdown_items_shown" class="btn btn-secondary-outline btn-sm" type="button" @click="toggleDropdownItemShown(inputs_index)"><i class="fa fa-eye"></i> Show dropdown items</button>
                        <button v-show="inputs.is_dropdown_items_shown" class="btn btn-secondary-outline btn-sm" type="button" @click="toggleDropdownItemShown(inputs_index)"><i class="fa fa-eye-slash"></i> Hide dropdown items</button>
                    </div> -->
                    <!-- <small>test</small> -->
                    <div>
                        <input type="text" class="form-control form-control-sm d-inline ml-2" v-model="inputs.dropdown_item" @keydown.enter.prevent="createDropdownItem(inputs_index)" placeholder="Dropdown options"/>
                        <div v-for="(dropdownItem, dropdown_item_index) in inputs.dropdown_items" class="d-inline ml-2">
                            <button type="button" class="btn btn-sm btn-secondary mt-1" @click="deleteDropdownItem(inputs_index, dropdown_item_index)">
                                {{dropdownItem}} <i class="fa fa-trash"></i>
                            </button>
                            <input type="hidden" :name="'inputs['+inputs_index+'][dropdown_items][]'" :value="dropdownItem"/>
                        </div>
                    </div>
                </div>
                </div>

                <div class="form-row" v-if="inputs.id != 0">
                    <strong>ID: </strong>{{inputs.id}}
                </div>

                
                <p class="text-center">
                    <button type="button" title="Move activity up" class="btn btn-outline-secondary btn-sm" :class="[inputs_index===0 ? 'disabled' : '']" @click="moveInput(true, inputs_index)"><i class="fa fa-fw" :class="[inputs_index===0 ? 'fa-minus' : 'fa-arrow-up']"></i></button>
                    <button type="button" title="Move activity down" class="btn btn-outline-secondary btn-sm" :class="[inputs_index===inputs.length-1 ? 'disabled' : '']" @click="moveInput(false, inputs_index)"><i class="fa fa-fw" :class="[inputs_index===inputs.length-1 ? 'fa-minus' : 'fa-arrow-down']"></i></button>
                    <!-- <button type="button" title="Activity rules"  @click="showRuleModal(inputs_index)" class="btn btn-outline-primary btn-sm"><i class="fa fa-fw fa-gavel"></i></button> -->
                    <!-- <button type="button" title="Mirror another activity's value" v-if="inputs.type!=='heading' && inputs.type!=='subheading' && inputs.type!=='content' && inputs.type!=='notification' && inputs.type!=='document_email' && inputs.type!=='template_email' && inputs.type!=='multiple_attachment' && inputs.type!=='document'" class="btn btn-outline-primary btn-sm" @click="showMirrorModal(inputs_index)"><i class="fa fa-fw fa-map"></i></button> -->
                    <!-- <button type="button" title="Mirror another activity's value" v-if="inputs.type==='heading' || inputs.type==='subheading' || inputs.type==='content' || inputs.type==='notification' || inputs.type==='document_email' || inputs.type==='template_email' || inputs.type==='multiple_attachment' || inputs.type==='document'" class="btn btn-outline-primary btn-sm" :disabled="true" @click="showMirrorModal(inputs_index)"><i class="fa fa-fw fa-map"></i></button> -->
                    <button type="button" title="Delete activity" class="btn btn-outline-danger btn-sm" @click="deleteInput(inputs_index)"><i class="fa fa-fw fa-trash"></i></button>                    
                </p>
            </li>
            <li class="text-center list-group-item">
                <button type="button" class="btn btn-outline-success btn-sm" @click="createHeading()"><i class="fa fa-plus"></i> Heading</button>
                <!-- <button type="button" class="btn btn-outline-success btn-sm" @click="createSubheading()"><i class="fa fa-plus"></i> Sub-heading</button> -->
                <button type="button" class="btn btn-outline-success btn-sm" @click="createInput()"><i class="fa fa-plus"></i> Field</button>
            </li>
        </ul>
    </div>
</template>

<script>
    toastr.options = {
        "preventDuplicates": true
    }

    if (count(errors) > 0) {
        (errors.all()).forEach(error => {
            toastr.error(error)
        });
    }
</script>

<script>

    export default {
        props: {
            blackInputs: {
                type: Array,
                default: () => [
                    {
                        id: 0,
                        type: 'text',
                        kpi: true,
                        comment: false,
                        client: false,
                        weight: 0,
                        threshold: {
                            time: 7,
                            type: 'days'
                        },
                        user: 0,
                        amirrors: [],
                        cmirrors: [],
                        dmirrors: [],
                        mirror:'',
                        show_mirror: false,
                        show_mirror_crm: false,
                        show_mirror_activity: true,
                        show_mirror_default: false,
                        styles:[],
                        srules:[],
                        arules:[],
                        show_rules: false,
                        show_step_rules: false,
                        show_activity_rules: false,
                        rules: [],
                        rule:'',
                    }
                ]
            },
            blackFields:{
                type: Object,
            },
            blackCategories:{
                type: Object,
            }
        },
        data() {
            return {
                id:0,
                color: '',
                inputs: this.blackInputs,
                users: [],
                amirrors:[],
                cmirrors:[],
                dmirrors:[],
                // mappedFields: this.blackFields,
                // categories: this.blackCategories,
                inputTypes: [
                    {text: 'Text', value: 'text'},
                    {text: 'Textarea', value: 'textarea'},
                    // {text: 'Percentage', value: 'percentage'},
                    /*{text: 'Integer', value: 'integer'},*/
                    // {text: 'Letter Email', value: 'template_email'},
                    // {text: 'Document Email', value: 'document_email'},
                    // {text: 'Document Upload', value: 'document'},
                    {text: 'Dropdown', value: 'dropdown'},
                    {text: 'Date', value: 'date'},
                    {text: 'Y/N', value: 'boolean'},
                    // {text: 'Notification', value: 'notification'},
                    /*{text: 'Video', value: 'video'},*/
                    // {text: 'Multiple Attachment', value: 'multiple_attachment'},
                    {text: 'Amount', value: 'amount'},
                    {text: 'Heading', value: 'heading'},
                    // {text: 'Sub-heading', value: 'subheading'},
                    // {text: 'Client', value: 'client'},
                    // {text: 'ID Number', value: 'id'},
                    // {text: 'Passport', value: 'passport'},
                    // {text: 'Passport', value: 'passport'},
                ],
                thresholdTypes: [
                    {text: 'Seconds', value: 'seconds'},
                    {text: 'Minutes', value: 'minutes'},
                    {text: 'Hours', value: 'hours'},
                    {text: 'Days', value: 'days'}
                ],
                levelTypes: [
                    {text: '0', value: '0'},
                    {text: '1', value: '1'},
                    {text: '2', value: '2'}
                ],
                groupingTypes: [
                    {text: '0', value: '0'},
                    {text: '1', value: '1'},
                    {text: '2', value: '2'},
                    {text: '3', value: '3'},
                    {text: '4', value: '4'},
                    {text: '5', value: '5'},
                    {text: '6', value: '6'},
                    {text: '7', value: '7'},
                    {text: '8', value: '8'},
                    {text: '9', value: '9'},
                    {text: '10', value: '10'},
                    {text: '11', value: '11'},
                    {text: '12', value: '12'},
                    {text: '13', value: '13'},
                    {text: '14', value: '14'},
                    {text: '15', value: '15'},
                    {text: '16', value: '16'},
                    {text: '17', value: '17'},
                    {text: '18', value: '18'},
                    {text: '19', value: '19'},
                    {text: '20', value: '20'},
                    {text: '21', value: '21'},
                    {text: '22', value: '22'},
                    {text: '23', value: '23'},
                    {text: '24', value: '24'},
                    {text: '25', value: '25'},
                    {text: '26', value: '26'},
                    {text: '27', value: '27'},
                    {text: '28', value: '28'},
                    {text: '29', value: '29'},
                    {text: '30', value: '30'},
                    {text: '31', value: '31'},
                    {text: '32', value: '32'},
                    {text: '33', value: '33'},
                    {text: '34', value: '34'},
                    {text: '35', value: '35'},
                    {text: '36', value: '36'},
                    {text: '37', value: '37'},
                    {text: '38', value: '38'},
                    {text: '39', value: '39'},
                    {text: '40', value: '40'},
                    {text: '41', value: '41'},
                    {text: '42', value: '42'},
                    {text: '43', value: '43'},
                    {text: '44', value: '44'},
                    {text: '45', value: '45'},
                    {text: '46', value: '46'},
                    {text: '47', value: '47'},
                    {text: '48', value: '48'},
                    {text: '49', value: '49'},
                    {text: '50', value: '50'},
                    {text: '51', value: '51'},
                    {text: '52', value: '52'},
                    {text: '53', value: '53'},
                    {text: '54', value: '54'},
                    {text: '55', value: '55'},
                    {text: '56', value: '56'},
                    {text: '57', value: '57'},
                    {text: '58', value: '58'},
                    {text: '59', value: '59'},
                    {text: '60', value: '60'},
                    {text: '61', value: '61'},
                    {text: '62', value: '62'},
                    {text: '63', value: '63'},
                    {text: '64', value: '64'},
                    {text: '65', value: '65'},
                    {text: '66', value: '66'},
                    {text: '67', value: '67'},
                    {text: '68', value: '68'},
                    {text: '69', value: '69'},
                    {text: '70', value: '70'},
                    {text: '71', value: '71'},
                    {text: '72', value: '72'},
                    {text: '73', value: '73'},
                    {text: '74', value: '74'},
                    {text: '75', value: '75'},
                    {text: '76', value: '76'},
                    {text: '77', value: '77'},
                    {text: '78', value: '78'},
                    {text: '79', value: '79'},
                    {text: '80', value: '80'},
                    {text: '81', value: '81'},
                    {text: '82', value: '82'},
                    {text: '83', value: '83'},
                    {text: '84', value: '84'},
                    {text: '85', value: '85'},
                    {text: '86', value: '86'},
                    {text: '87', value: '87'},
                    {text: '88', value: '88'},
                    {text: '89', value: '89'},
                    {text: '90', value: '90'},
                    {text: '91', value: '91'},
                    {text: '92', value: '92'},
                    {text: '93', value: '93'},
                    {text: '94', value: '94'},
                    {text: '95', value: '95'},
                    {text: '96', value: '96'},
                    {text: '97', value: '97'},
                    {text: '98', value: '98'},
                    {text: '99', value: '99'},
                    {text: '100', value: '100'},
                    {text: '101', value: '101'},
                    {text: '102', value: '102'},
                    {text: '103', value: '103'},
                    {text: '104', value: '104'},
                    {text: '105', value: '105'},
                    {text: '106', value: '106'},
                    {text: '107', value: '107'},
                    {text: '108', value: '108'},
                    {text: '109', value: '109'},
                    {text: '110', value: '110'},
                    {text: '111', value: '111'},
                    {text: '112', value: '112'},
                    {text: '113', value: '113'},
                    {text: '114', value: '114'},
                    {text: '115', value: '115'},
                    {text: '116', value: '116'},
                    {text: '117', value: '117'},
                    {text: '118', value: '118'},
                    {text: '119', value: '119'},
                    {text: '120', value: '120'},
                    {text: '121', value: '121'},
                    {text: '122', value: '122'},
                    {text: '123', value: '123'},
                    {text: '124', value: '124'},
                    {text: '125', value: '125'},
                    {text: '126', value: '126'},
                    {text: '127', value: '127'},
                    {text: '128', value: '128'},
                    {text: '129', value: '129'},
                    {text: '130', value: '130'},
                    {text: '131', value: '131'},
                    {text: '132', value: '132'},
                    {text: '133', value: '133'},
                    {text: '134', value: '134'},
                    {text: '135', value: '135'},
                    {text: '136', value: '136'},
                    {text: '137', value: '137'},
                    {text: '138', value: '138'},
                    {text: '139', value: '139'},
                    {text: '140', value: '140'},
                    {text: '141', value: '141'},
                    {text: '142', value: '142'},
                    {text: '143', value: '143'},
                    {text: '144', value: '144'},
                    {text: '145', value: '145'},
                    {text: '146', value: '146'},
                    {text: '147', value: '147'},
                    {text: '148', value: '148'},
                    {text: '149', value: '149'},
                    {text: '150', value: '150'},
                    {text: '151', value: '151'},
                    {text: '152', value: '152'},
                    {text: '153', value: '153'},
                    {text: '154', value: '154'},
                    {text: '155', value: '155'},
                    {text: '156', value: '156'},
                    {text: '157', value: '157'},
                    {text: '158', value: '158'},
                    {text: '159', value: '159'},
                    {text: '160', value: '160'},
                    {text: '161', value: '161'},
                    {text: '162', value: '162'},
                    {text: '163', value: '163'},
                    {text: '164', value: '164'},
                    {text: '165', value: '165'},
                    {text: '166', value: '166'},
                    {text: '167', value: '167'},
                    {text: '168', value: '168'},
                    {text: '169', value: '169'},
                    {text: '170', value: '170'},
                    {text: '171', value: '171'},
                    {text: '172', value: '172'},
                    {text: '173', value: '173'},
                    {text: '174', value: '174'},
                    {text: '175', value: '175'},
                    {text: '176', value: '176'},
                    {text: '177', value: '177'},
                    {text: '178', value: '178'},
                    {text: '179', value: '179'},
                    {text: '180', value: '180'},
                    {text: '181', value: '181'},
                    {text: '182', value: '182'},
                    {text: '183', value: '183'},
                    {text: '184', value: '184'},
                    {text: '185', value: '185'},
                    {text: '186', value: '186'},
                    {text: '187', value: '187'},
                    {text: '188', value: '188'},
                    {text: '189', value: '189'},
                    {text: '190', value: '190'},
                    {text: '191', value: '191'},
                    {text: '192', value: '192'},
                    {text: '193', value: '193'},
                    {text: '194', value: '194'},
                    {text: '195', value: '195'},
                    {text: '196', value: '196'},
                    {text: '197', value: '197'},
                    {text: '198', value: '198'},
                    {text: '199', value: '199'},
                    {text: '200', value: '200'},
                ],
                ruleTypes:[
                    {text:'Enable / Show Step',value:'step'},
                    {text:'Enable / Show Activity',value:'activity'}
                ],
                arules:[],
                srules:[],
                mirrorTypes:[
                    {text:'Default',value:'default'},
                    {text:'CRM',value:'crm'},
                    {text:'Activity',value:'activity'}
                ],
                is_grouping_items_shown: false,
                show_mirror: false,
            }
        },
        methods: {
            createInput(index) {
                this.inputs.push({
                    name: '',
                    type: 'text',
                    kpi: true,
                    comment: false,
                    client: false,
                    weight: 0,
                    dropdown_item: '',
                    dropdown_items: [],
                    is_tooltip_shown: false,
                    is_dropdown_items_shown: false,
                    is_grouping_items_shown: false,
                    is_client_bucket:false,
                    threshold: {
                        time: 7,
                        type: 'days'
                    },
                    user: 0,
                    currency: 0,
                    level: 0,
                    show_mirror: false,
                        show_mirror_crm: false,
                        show_mirror_activity: true,
                        show_mirror_default: false,
                    categories: []
                });
            },
            createHeading(index) {
                this.inputs.push({
                    color: '',
                    name: '',
                    type: 'heading',
                    kpi: true,
                    comment: false,
                    value: false,
                    procedure: false,
                    client: false,
                    weight: 0,
                    dropdown_item: '',
                    dropdown_items: [],
                    is_dropdown_items_shown: false,
                    is_grouping_items_shown: false,
                    is_default_value_shown: false,
                    is_tooltip_shown: false,
                    is_client_bucket: false,
                    threshold: {
                        time: 7,
                        type: 'days'
                    },
                    user: 0,
                    currency: 0,
                    level: 0,
                    show_mirror: false,
                        show_mirror_crm: false,
                        show_mirror_activity: false,
                        show_mirror_default: false
                });
            },
            createSubheading(index) {
                this.inputs.push({
                    color: '',
                    name: '',
                    type: 'subheading',
                    kpi: true,
                    comment: false,
                    value: false,
                    procedure: false,
                    client: false,
                    weight: 0,
                    dropdown_item: '',
                    dropdown_items: [],
                    is_dropdown_items_shown: false,
                    is_grouping_items_shown: false,
                    is_default_value_shown: false,
                    is_tooltip_shown: false,
                    is_client_bucket: false,
                    threshold: {
                        time: 7,
                        type: 'days'
                    },
                    user: 0,
                    currency: 0,
                    level: 0,
                    show_mirror: false,
                        show_mirror_crm: false,
                        show_mirror_activity: false,
                        show_mirror_default: false
                });
            },
            deleteInput(index) {
                this.inputs.splice(index, 1);
            },
            moveInput(direction, index) {
                if (this.inputs.length !== 1 && !(index === 0 && direction) && !(index === (this.inputs.length - 1) && !direction)) {
                    if (direction) {
                        const b = this.inputs[index];
                        Vue.set(this.inputs, index, this.inputs[index - 1]);
                        Vue.set(this.inputs, index - 1, b);
                    } else {
                        const b = this.inputs[index];
                        Vue.set(this.inputs, index, this.inputs[index + 1]);
                        Vue.set(this.inputs, index + 1, b);
                    }
                }
            },
            createDropdownItem(index) {
                if (!this.inputs[index].dropdown_items) {
                    this.inputs[index].dropdown_items = [];
                }

                this.inputs[index].dropdown_items.push(
                    this.inputs[index].dropdown_item
                );

                this.inputs[index].dropdown_item = '';
            },
            deleteDropdownItem(index, dropdown_item_index) {
                this.inputs[index].dropdown_items.splice(dropdown_item_index, 1);
            },
            toggleDropdownItemShown(index) {
                this.inputs[index].is_dropdown_items_shown = !this.inputs[index].is_dropdown_items_shown;
            },
            toggleTooltipShown(index) {
                this.inputs[index].is_tooltip_shown = !this.inputs[index].is_tooltip_shown;
            },
            disen(event) {
                if(event == 1){
                    this.is_grouping_items_shown = true;
                } else {
                    this.is_grouping_items_shown = false;
                }
            },
                getMirrorProcessSteps(index,mirror_item_index,event){

                    let process_id = event.target.value;

                    axios.get('/getsteps/?process_id='+process_id)
                        .then(response => {


                            this.inputs[index].amirrors[mirror_item_index].stepss = response.data;
                            this.inputs[index].amirrors[mirror_item_index].stepss.$forceUpdate();
                        })
                        .catch(error => {
                            // todo handle error
                        });
                },
                getMirrorFormSections(index,mirror_item_index,activity_type,event){
                    console.log(activity_type);
                    let process_id = event.target.value;

                    axios.get('/getsections/?form_id='+process_id)
                        .then(response => {


                            this.inputs[index].cmirrors[mirror_item_index].stepss = response.data;
                            this.inputs[index].cmirrors[mirror_item_index].stepss.$forceUpdate();
                        })
                        .catch(error => {
                            // todo handle error
                        });
                },
                getMirrorStepinputs(index,mirror_item_index,activity_type,event){

                    let step_id = event.target.value;

                    axios.get('/getstepinputs/?step_id='+step_id+'&atype='+activity_type)
                        .then(response => {

                        if(response.data.length === 0){
                            alert('There are no inputs matching the current activity type');
                        }
                            this.inputs[index].amirrors[mirror_item_index].activitiess = response.data;
                            this.inputs[index].amirrors[mirror_item_index].activitiess.$forceUpdate();
                        })
                        .catch(error => {
                            // todo handle error
                        });
                },
                getMirrorSectionInputs(index,mirror_item_index,activity_type,event){

                    let step_id = event.target.value;


                    axios.get('/getsectioninputs/?form_section_id='+step_id+'&aatype='+activity_type)
                        .then(response => {

                            if(response.data.length === 0){
                                alert('There are no form inputs matching the current input type');
                            }

                            this.inputs[index].cmirrors[mirror_item_index].activitiess = response.data;
                            this.inputs[index].cmirrors[mirror_item_index].activitiess.$forceUpdate();
                        })
                        .catch(error => {
                            // todo handle error
                        });
                },
                showMirrorModal(index) {
                    this.inputs[index].show_mirror = !this.inputs[index].show_mirror;
                },
                hideMirrorModal(index) {
                    this.inputs[index].show_mirror = !this.inputs[index].show_mirror;
                },
                getMirrorType(index,mirror_type,event){
                    let type = event.target.value;

                    if(type === 'crm'){
                        this.inputs[index].show_mirror_default = false;
                        this.inputs[index].show_mirror_crm = true;
                        this.inputs[index].show_mirror_activity = false;
                    }

                    if(type === 'activity'){
                        this.inputs[index].show_mirror_default = false;
                        this.inputs[index].show_mirror_crm = false;
                        this.inputs[index].show_mirror_activity = true;
                    }

                    if(type === 'default'){
                        this.inputs[index].show_mirror_default = true;
                        this.inputs[index].show_mirror_crm = false;
                        this.inputs[index].show_mirror_activity = false;
                    }
                },
                showRuleModal(index) {
                    this.inputs[index].show_rules = !this.inputs[index].show_rules;
                },
                hideRuleModal(index) {
                    this.inputs[index].show_rules = !this.inputs[index].show_rules;
                },
                deleteActivityRuleItem(index, rule_item_index) {
                    this.inputs[index].arules.splice(rule_item_index, 1);
                },
                createActivityRule(index) {
                    console.log(this.inputs[index].arules);
                    let i = (this.inputs[index].arules.length);

                    if (!this.inputs[index].arules) {
                        //this.activities[index].rules = {rule_value:'',rule_process:'',rule_step:'',processs:'',stepss:''};
                        []
                    }

                    this.inputs[index].arules.push(
                        {inputs:null,inputs_id:null,inputs_value:null,boolean:false,dropdown:false,dropdownitems:null,text:false}
                    );

                    this.inputs[index].arules[i].activity_id = '';
                    this.inputs[index].arules[i].boolean = false;
                    this.inputs[index].arules[i].dropdown = false;
                    this.inputs[index].arules[i].dropdownitems = '';
                    this.inputs[index].arules[i].text = false;
                    this.inputs[index].arules[i].activity_value = '';

                    let step_id = this.inputs[index].step_id;
                    let activity_id = this.inputs[index].id;

                    axios.get('/getactivities/?step_id='+step_id+'&activity_id='+activity_id)
                        .then(response => {

                            console.log(response.data.activities);
                            this.inputs[index].arules[i].inputs = response.data.activities;
                            this.inputs[index].arules[i].inputs.$forceUpdate();
                        })
                        .catch(error => {
                            // todo handle error
                        });

                    console.log(this.inputs);
                },
                getRuleType(index,rule_type,event){
                    let type = event.target.value;

                    if(type === 'step'){
                        this.inputs[index].show_step_rules = true;
                        this.inputs[index].show_activity_rules = false;
                    }

                    if(type === 'activity'){
                        this.inputs[index].show_step_rules = false;
                        this.inputs[index].show_activity_rules = true;
                    }
                },
                createStepRule(index) {

                    let i = (this.inputs[index].srules.length);

                    if (!this.inputs[index].srules) {
                        //this.activities[index].rules = {rule_value:'',rule_process:'',rule_step:'',processs:'',stepss:''};
                        []
                    }

                    this.inputs[index].srules.push(
                        {steps:null,step_id:null,activity_value:null,boolean:false,dropdown:false,dropdownitems:null,text:false}
                    );

                    this.inputs[index].srules[i].step_id = '';
                    this.inputs[index].srules[i].boolean = false;
                    this.inputs[index].srules[i].dropdown = false;
                    this.inputs[index].srules[i].dropdownitems = '';
                    this.inputs[index].srules[i].text = false;
                    this.inputs[index].srules[i].activity_value = '';

                    let step_id = this.inputs[index].step_id;
                    let activity_id = this.inputs[index].id;

                    axios.get('/getremainingsections/?step_id='+step_id+'&activity_id='+activity_id)
                        .then(response => {

                            console.log(response.data);
                            this.inputs[index].srules[i].steps = response.data;
                            this.inputs[index].srules[i].steps.$forceUpdate();
                        })
                        .catch(error => {
                            // todo handle error
                        });

                },
                createActivityRule(index) {
                    console.log(this.inputs[index].arules);
                    let i = (this.inputs[index].arules.length);

                    if (!this.inputs[index].arules) {
                        //this.activities[index].rules = {rule_value:'',rule_process:'',rule_step:'',processs:'',stepss:''};
                        []
                    }

                    this.inputs[index].arules.push(
                        {activities:null,activity_id:null,activity_value:null,boolean:false,dropdown:false,dropdownitems:null,text:false}
                    );

                    this.inputs[index].arules[i].activity_id = '';
                    this.inputs[index].arules[i].boolean = false;
                    this.inputs[index].arules[i].dropdown = false;
                    this.inputs[index].arules[i].dropdownitems = '';
                    this.inputs[index].arules[i].text = false;
                    this.inputs[index].arules[i].activity_value = '';

                    let step_id = this.inputs[index].step_id;
                    let activity_id = this.inputs[index].id;

                    axios.get('/getinputs/?step_id='+step_id+'&activity_id='+activity_id)
                        .then(response => {

                            console.log(response.data.activities);
                            this.inputs[index].arules[i].activities = response.data.activities;
                            this.inputs[index].arules[i].activities.$forceUpdate();
                        })
                        .catch(error => {
                            // todo handle error
                        });

                    console.log(this.activities);
                },
                getActivityType(index,activity_rule_item_index,event){

                    let activity_id = event.target.value;

                    axios.get('/getinputtype/?activity_id='+activity_id)
                        .then(response => {
                            if(response.data.activity_type === 'boolean' || response.data.activity_type === 'notification' || response.data.activity_type === 'multiple_attachment' || response.data.activity_type === 'date' || response.data.activity_type === 'document' || response.data.activity_type === 'template_email' || response.data.activity_type === 'document_email'){
                                console.log(activity_rule_item_index);
                                this.inputs[index].arules[activity_rule_item_index].boolean = true;
                                this.inputs[index].arules[activity_rule_item_index].dropdown = false;
                                this.inputs[index].arules[activity_rule_item_index].text = false;
                                this.inputs[index].arules[activity_rule_item_index].boolean.$forceUpdate();
                                this.inputs[index].arules[activity_rule_item_index].dropdown.$forceUpdate();
                                this.inputs[index].arules[activity_rule_item_index].text.$forceUpdate();
                            }

                            if(response.data.activity_type === 'dropdown'){
                                console.log(activity_rule_item_index);
                                this.inputs[index].arules[activity_rule_item_index].boolean = false;
                                this.inputs[index].arules[activity_rule_item_index].dropdown = true;
                                this.inputs[index].arules[activity_rule_item_index].text = false;

                                axios.get('/getinputdropdownitems/?activity_id='+activity_id)
                                    .then(response => {
                                            this.inputs[index].arules[activity_rule_item_index].dropdownitems = response.data.dropdownitems;
                                            this.inputs[index].arules[activity_rule_item_index].dropdownitems.$forceUpdate();
                                    })
                                    .catch(error => {
                                        // todo handle error
                                    });
                                this.inputs[index].arules[activity_rule_item_index].boolean.$forceUpdate();
                                this.inputs[index].arules[activity_rule_item_index].dropdown.$forceUpdate();
                                this.inputs[index].arules[activity_rule_item_index].text.$forceUpdate();
                            }

                            if(response.data.activity_type !== 'boolean' && response.data.activity_type !== 'dropdown' && response.data.activity_type !== 'notification' && response.data.activity_type !== 'multiple_attachment' && response.data.activity_type !== 'date' && response.data.activity_type !== 'document' && response.data.activity_type !== 'template_email' && response.data.activity_type !== 'document_email'){
                                console.log(activity_rule_item_index);
                                this.inputs[index].arules[activity_rule_item_index].boolean = false;
                                this.inputs[index].arules[activity_rule_item_index].dropdown = false;
                                this.inputs[index].arules[activity_rule_item_index].text = true;
                                this.inputs[index].arules[activity_rule_item_index].boolean.$forceUpdate();
                                this.inputs[index].arules[activity_rule_item_index].dropdown.$forceUpdate();
                                this.inputs[index].arules[activity_rule_item_index].text.$forceUpdate();
                            }

                        })
                        .catch(error => {
                            // todo handle error
                        });


                },
                deleteStepRuleItem(index, rule_item_index) {
                    this.inputs[index].srules.splice(rule_item_index, 1);
                },
        },
        created() {

        }
    }
</script>
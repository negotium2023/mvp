<?php

namespace App\Http\Controllers;

use App\FormInputDropdown;
use App\FormInputDropdownItem;
use App\FormInputAmount;
use App\FormInputPercentage;
use App\FormInputInteger;
use App\FormInputVideo;
use App\FormInputText;
use App\FormInputDate;
use App\FormInputBoolean;
use App\FormInputHeading;
use App\FormInputSubheading;
use App\FormInputClient
;
use App\FormInputTextarea;
use App\Forms;
use App\FormSection;
use App\FormSectionInputs;
use Illuminate\Http\Request;
use App\Role;

class FormSectionController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
        //return $this->middleware('auth')->except('index');
    }

    public function create(Forms $form){
        return view('forms.sections.create')->with(['forms' => $form->load('sections')]);
    }

    public function store(Request $request,Forms $form){
        //dd($request);
        $section = new FormSection();
        $section->name = $request->input('name');
        $section->show_name_in_tabs = $request->input('show_name_in_tab');
        $section->group = $request->input('group_section');
        $section->form_id = $form->id;
        $section->order = FormSection::where('form_id', $form->id)->max('order') + 1;
        $section->save();

        //loop over each activity input
        foreach ($request->input('inputs') as $input_key => $input_input) {
            $input = new FormSectionInputs();
            $form_input = $this->createInput($input_input['type']);

            $input->name = $input_input['name'];
            $input->order = $input_key + 1;
            $input->input_id = $form_input->id;
            $input->input_type = $this->getInputType($input_input['type']);
            $input->form_section_id = $section->id;
            $input->kpi = (isset($input_input['kpi']) && $input_input['kpi'] == "on") ? 1 : null;
            if ($input_input['type'] == 'heading' || $input_input['type'] == 'subheading') {
                $input->client_bucket = 1;
            } else {
                $input->client_bucket = (isset($input_input['client_bucket']) && $input_input['client_bucket'] == "on") ? 1 : 0;
            }
            $input->level = (isset($input_input['level']) ? $input_input['level'] : 0);
            $input->color = (isset($input_input['color']) && $input_input['color'] != '#hsla(0,0%,0%,0)' ? $input_input['color'] : null);
            $input->save();

            //if activity is a dropdown type
            if ($input_input['type'] == 'dropdown') {

                //only add dropdown items if there is input
                if (isset($input_input['dropdown_items'])) {
                    //dd($input_input['dropdown_items']);
                    //loop over each dropdown item
                    foreach ($input_input['dropdown_items'] as $dropdown_item) {
                        $actionable_dropdown_item = new FormInputDropdownItem;
                        $actionable_dropdown_item->form_input_dropdown_id = $form_input->id;
                        $actionable_dropdown_item->name = $dropdown_item;
                        $actionable_dropdown_item->save();
                    }
                }
            }

            //if activity is a dropdown type
            if ($input_input['type'] == 'radio') {

                //only add dropdown items if there is input
                if (isset($input_input['radio_items'])) {
                    //dd($input_input['dropdown_items']);
                    //loop over each dropdown item
                    foreach ($input_input['radio_items'] as $radio_item) {
                        $actionable_radio_item = new FormInputRadioItem;
                        $actionable_radio_item->form_input_radio_id = $form_input->id;
                        $actionable_radio_item->name = $radio_item;
                        $actionable_radio_item->save();
                    }
                }
            }

            //if activity is a dropdown type
            if ($input_input['type'] == 'checkbox') {

                //only add dropdown items if there is input
                if (isset($input_input['checkbox_items'])) {
                    //dd($input_input['dropdown_items']);
                    //loop over each dropdown item
                    foreach ($input_input['checkbox_items'] as $checkbox_item) {
                        $actionable_checkbox_item = new FormInputCheckboxItem;
                        $actionable_checkbox_item->form_input_checkbox_id = $form_input->id;
                        $actionable_checkbox_item->name = $checkbox_item;
                        $actionable_checkbox_item->save();
                    }
                }
            }
        }

        return redirect(route('forms.show', $form->id))->with('flash_success', 'Form Section successfully saved.');
    }

    public function edit($formid){

        $form = FormSection::find($formid);


        $section_inputs_array = [];
        foreach ($form->form_section_inputs as $inputs) {

            $section_input_array = [
                'id' => $inputs->id,
                'name' => $inputs->name,
                'tooltip' => $inputs->tooltip,
                'type' => $inputs->getFormTypeName(),
                'kpi' => ($inputs->kpi ==1 ? true : false),
                'level' => $inputs->level,
                'color' => $inputs->color,
                'client_bucket' => ($inputs->client_bucket == "1" ? true : false),
                'dropdown_item' => '',
                'dropdown_items' => [],
                'is_grouping_items_shown' => false,
                'grouping_value' => ($inputs->grouping != null ? $inputs->grouping : 0),
                'is_dropdown_items_shown' => false,
                'radio_item' => '',
                'radio_items' => [],
                'is_tooltip_shown' => false,
                'is_radio_items_shown' => false,
                'checkbox_item' => '',
                'checkbox_items' => [],
                'is_checkbox_items_shown' => false
            ];

            if ($inputs->getFormTypeName() == 'dropdown') {

                $section_input_array['dropdown_items'] = FormInputDropdownItem::where('form_input_dropdown_id',$inputs->input_id)->pluck('name')->toArray();
            }

            if ($inputs->getFormTypeName() == 'radio') {
                $section_input_array['radio_items'] = FormInputRadioItem::where('form_input_radio_id',$inputs->input_id)->pluck('name')->toArray();
                //dd($inputs->id);
            }

            if ($inputs->getFormTypeName() == 'checkbox') {
                $section_input_array['checkbox_items'] = FormInputCheckboxItem::where('form_input_checkbox_id',$inputs->input_id)->pluck('name')->toArray();
            }

            array_push($section_inputs_array, $section_input_array);
        }

        $paramaters = [
            'form' => $form,
            'inputs' => json_encode($section_inputs_array),
            'roles' => Role::orderBy('name')->get()
        ];


        return view('forms.sections.edit')->with($paramaters);
    }

    public function update(FormSection $form_section,Request $request){
        /*dd($request);
        $form_section = FormSection::find($form_sections->id);*/
        $existing_section = FormSection::where('name',$form_section->name)->first();

        if($existing_section != null){
            $section_id = $existing_section->id;

            $form_section->name = $request->input('name');
            $form_section->show_name_in_tabs = $request->input('show_name_in_tab');
            $form_section->group = $request->input('group_section');
            $form_section->save();
        }
        //dd($request->input('activities'));
        $pinputs = array();
        if($request->input("inputs") != null) {
            foreach ($request->input("inputs") as $input) {
                //dd($activities);
                array_push($pinputs, $input["id"]);
            }
        }
        FormSectionInputs::where('form_section_id',$form_section->id)->whereNotIn('id',$pinputs)->delete();


        //loop over each activity input
        if($request->input("inputs") != null) {
            foreach ($request->input('inputs') as $activity_key => $activity_input) {

                $activity = $form_section->form_section_inputs()->where('id', $activity_input['id'])->get()->first();
                $activity_type = $form_section->form_section_inputs()->where('id', $activity_input['id'])->where('input_type', $this->getInputType($activity_input['type']))->get()->first();

                //if there is a previous activity matching the name and type, reactivate it else create a new one
                if (!$activity) {
                    $new_activity = true;
                    if (!$activity_type) {
                        $new_activity_type = true;
                        $activity = new FormSectionInputs;
                        $actionable = $this->createInput($activity_input['type']);
                    } else {
                        $new_activity_type = false;
                        $activity->restore();
                        $actionable = $activity->input;
                    }

                } else {
                    $new_activity = false;
                    if (!$activity_type) {
                        $new_activity_type = true;
                        $activity = FormSectionInputs::find($activity_input['id']);
                        $actionable = $this->createInput($activity_input['type']);
                    } else {
                        $new_activity_type = false;
                        $activity->restore();
                        $actionable = $activity->input;
                    }

                }

                $activity->name = $activity_input['name'];
                $activity->order = $activity_key + 1;
                $activity->input_id = (isset($actionable->id) ? $actionable->id : $actionable);
                $activity->input_type = $this->getInputType($activity_input['type']);
                $activity->form_section_id = $form_section->id;
                $activity->grouped = (isset($activity_input['grouping']) && $activity_input['grouping'] == "on") ? 1 : 0;
                $activity->grouping = (isset($activity_input['grouping_value'])) ? $activity_input['grouping_value'] : 0;
                $activity->kpi = (isset($activity_input['kpi']) && $activity_input['kpi'] == "on") ? 1 : null;
                if ($activity_input['type'] == 'heading' || $activity_input['type'] == 'subheading') {
                    $activity->client_bucket = 1;
                } else {
                    $activity->client_bucket = (isset($activity_input['client_bucket']) && $activity_input['client_bucket'] == "on") ? 1 : 0;
                }
                $activity->level = (isset($activity_input['level']) ? $activity_input['level'] : 0);
                $activity->color = (isset($activity_input['color']) && $activity_input['color'] != '#hsla(0,0%,0%,0)' ? $activity_input['color'] : null);
                $activity->save();


                //if activity is a dropdown type
                if ($activity_input['type'] == 'dropdown') {

                    //delete all previous dropdown items
                    FormInputDropdownItem::where('form_input_dropdown_id', (isset($actionable->id) ? $actionable->id : $actionable))->delete();


                    //only add dropdown items if there is input
                    if (isset($activity_input['dropdown_items'])) {

                        //loop over each dropdown item
                        foreach ($activity_input['dropdown_items'] as $dropdown_item) {

                            //if this is a reactivated activity, search for all old dropdowns
                            if (!$new_activity_type) {

                                //find if there already a dropdown item for that activity
                                $item = $actionable->items()->withTrashed()->where('name', $dropdown_item)->get()->first();

                                //if there is a previous dropdown item, reactivate it else create a new one
                                if (!$item) {
                                    $actionable_dropdown_item = new FormInputDropdownItem;
                                    $actionable_dropdown_item->form_input_dropdown_id = $actionable->id;
                                    $actionable_dropdown_item->name = $dropdown_item;
                                    $actionable_dropdown_item->save();
                                } else {
                                    $item->restore();
                                }

                            } // otherwise create a new dropdown item without searching
                            else {
                                $actionable_dropdown_item = new FormInputDropdownItem;
                                $actionable_dropdown_item->form_input_dropdown_id = (isset($actionable->id) ? $actionable->id : $actionable);
                                $actionable_dropdown_item->name = $dropdown_item;
                                $actionable_dropdown_item->save();
                            }
                        }
                    }
                }

                //if activity is a radio type
                if ($activity_input['type'] == 'radio') {

                    //delete all previous dropdown items
                    FormInputRadioItem::where('form_input_radio_id', (isset($actionable->id) ? $actionable->id : $actionable))->delete();


                    //only add dropdown items if there is input
                    if (isset($activity_input['radio_items'])) {

                        //loop over each dropdown item
                        foreach ($activity_input['radio_items'] as $radio_item) {

                            //if this is a reactivated activity, search for all old dropdowns
                            if (!$new_activity_type) {

                                //find if there already a dropdown item for that activity
                                $item = $actionable->items()->withTrashed()->where('name', $radio_item)->get()->first();

                                //if there is a previous dropdown item, reactivate it else create a new one
                                if (!$item) {
                                    $actionable_radio_item = new FormInputRadioItem;
                                    $actionable_radio_item->form_input_radio_id = $actionable->id;
                                    $actionable_radio_item->name = $radio_item;
                                    $actionable_radio_item->save();
                                } else {
                                    $item->restore();
                                }

                            } // otherwise create a new dropdown item without searching
                            else {
                                $actionable_radio_item = new FormInputRadioItem;
                                $actionable_radio_item->form_input_radio_id = (isset($actionable->id) ? $actionable->id : $actionable);
                                $actionable_radio_item->name = $radio_item;
                                $actionable_radio_item->save();
                            }
                        }
                    }
                }


                //if activity is a checkbox type
                if ($activity_input['type'] == 'checkbox') {

                    //delete all previous dropdown items
                    FormInputCheckboxItem::where('form_input_checkbox_id', (isset($actionable->id) ? $actionable->id : $actionable))->delete();


                    //only add dropdown items if there is input
                    if (isset($activity_input['checkbox_items'])) {

                        //loop over each dropdown item
                        foreach ($activity_input['checkbox_items'] as $checkbox_item) {

                            //if this is a reactivated activity, search for all old dropdowns
                            if (!$new_activity_type) {

                                //find if there already a dropdown item for that activity
                                $item = $actionable->items()->withTrashed()->where('name', $checkbox_item)->get()->first();

                                //if there is a previous dropdown item, reactivate it else create a new one
                                if (!$item) {
                                    $actionable_checkbox_item = new FormInputCheckboxItem;
                                    $actionable_checkbox_item->form_input_checkbox_id = $actionable->id;
                                    $actionable_checkbox_item->name = $checkbox_item;
                                    $actionable_checkbox_item->save();
                                } else {
                                    $item->restore();
                                }

                            } // otherwise create a new dropdown item without searching
                            else {
                                $actionable_checkbox_item = new FormInputCheckboxItem;
                                $actionable_checkbox_item->form_input_checkbox_id = (isset($actionable->id) ? $actionable->id : $actionable);
                                $actionable_checkbox_item->name = $checkbox_item;
                                $actionable_checkbox_item->save();
                            }
                        }
                    }
                }
            }
        }
        return redirect(route('forms.show', $form_section->form_id))->with('flash_success', 'Form updated successfully.');
    }

    public function destroy(FormSection $form){
        $form_id = $form->form_id;

        $form->delete();

        return redirect(route('forms.show', $form_id))->with('flash_success', 'Form Section successfully deleted.');
    }

    public function move(FormSection $form, Request $request)
    {

        if ($request->input('direction') == 'up') {
            $next_step = FormSection::where('form_id', $form->form->id)->where('order', '<', $form->order)->orderBy('order', 'desc')->first();

            if ($next_step) {
                $old_order = $form->order;
                $new_order = $next_step ->order;
                $form->order = $new_order;
                $next_step->order = $old_order;
                $next_step->save();
                $form->save();
            }
        }

        if ($request->input('direction') == 'down') {
            $next_step = FormSection::where('form_id', $form->form->id)->where('order', '<', $form->order)->orderBy('order', 'desc')->first();

            if ($next_step) {
                $old_order = $form->order;
                $new_order = $next_step ->order;
                $form->order = $new_order;
                $next_step->order = $old_order;
                $next_step->save();
                $form->save();
            }
        }

        return redirect(route('forms.show', $form->form))->with('flash_success', 'Form updated successfully.');
    }

    public function getInputType($type)
    {
        //activity type hook
        switch ($type) {
            case 'text':
                return 'App\FormInputText';
                break;
            case 'heading':
                return 'App\FormInputHeading';
                break;
            case 'subheading':
                return 'App\FormInputSubheading';
                break;
            case 'amount':
                return 'App\FormInputAmount';
                break;
            case 'percentage':
                return 'App\FormInputPercentage';
                break;
            case 'integer':
                return 'App\FormInputInteger';
                break;
            case 'video':
                return 'App\FormInputVideo';
                break;
            case 'textarea':
                return 'App\FormInputTextarea';
                break;
            case 'dropdown':
                return 'App\FormInputDropdown';
                break;
            case 'radio':
                return 'App\FormInputRadio';
                break;
            case 'checkbox':
                return 'App\FormInputCheckbox';
                break;
            case 'date':
                return 'App\FormInputDate';
                break;
            case 'boolean':
                return 'App\FormInputBoolean';
                break;
            case 'client':
                return 'App\FormInputClient';
                break;
            default:
                abort(500, 'Error');
                break;
        }
    }

    public function createInput($type)
    {
        //activity type hook
        switch ($type) {
            case 'text':
                return FormInputText::create();
                break;
            case 'heading':
                return FormInputHeading::create();
                break;
            case 'subheading':
                return FormInputSubheading::create();
                break;
            case 'amount':
                return FormInputAmount::create();
                break;
            case 'percentage':
                return FormInputPercentage::create();
                break;
            case 'integer':
                return FormInputInteger::create();
                break;
            case 'video':
                return FormInputVideo::create();
                break;
            case 'textarea':
                return FormInputTextarea::create();
                break;
            case 'dropdown':
                return FormInputDropdown::create();
                break;
            case 'date':
                return FormInputDate::create();
                break;
            case 'boolean':
                return FormInputBoolean::create();
                break;
            case 'client':
                return FormInputClient::create();
                break;
            default:
                abort(500, 'Error');
                break;
        }
    }

    public function getSections(Request $request){

        $sections = FormSection::where('form_id',$request->input('form_id'))->orderBy('order')->get();

        foreach ($sections as $p){
            $section[$p->id] = $p->name;
        }
        return $section;
    }
}

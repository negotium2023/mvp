<input type="hidden" id="active_step_id" value="{{$step['id']??null}}">
<div class="process-slider" style="position: relative;">
 <button class="nav-prev" style="position: absolute;left: -7px;top:35px;z-index: 1;height:70px;width:35px;border-radius:0 50px 50px 0;background:#FFF;-webkit-box-shadow: 0px 0px 8px 2px rgba(0,0,0,0.28);
-moz-box-shadow: 0px 0px 8px 2px rgba(0,0,0,0.28);
box-shadow: 0px 0px 8px 2px rgba(0,0,0,0.28);border:0px;display: none;">
     <i class="fas fa-angle-left" style="padding-right: 10px;
    font-size: 40px;"></i>
</button>
    <div class="js-pscroll scrolling-wrapper cata-sub-nav" style="position: relative" id="scrolling-wrapper">

    <div class="text-center blackboard-steps" id="blackboard-steps">
        <div class="progress-indicator" id="progress-indicator" style="min-height: 100px;">
            @if(isset($steps) && isset($client))
                @forelse($steps as $step)
                    <div class="card" id="step_{{$step['id']}}" style="{{(in_array($step['id'],$step_invisibil) ? 'display:none;' : 'display:table;')}}height: 100%;">
                    <div class="completed{{$step["stage"]}}" style="height: 100%;"> <span class="bubble">
                            <a href="{{route('clients.progress',$client)}}/{{$step['process_id']}}/{{$step['id']}}" title="{{$step['name']}}"><i class="far fa-file-alt completed{{$step["stage"]}}" style="font-size: 2em;line-height: 1.6em;font-weight:regular;"></i></a>
                        </span>
                        <div style="min-height:2em;display: table-cell;vertical-align: middle;text-align: center;min-width: 200px;">
                        <a href="{{route('clients.progress',$client)}}/{{$step['process_id']}}/{{$step['id']}}" title="{{$step['name']}}">{{$step['name']}}</a>
                            <input type="hidden" class="step-cnt-{{$step['id']}}" value="{{(in_array($step['id'],$step_invisibil) ? 0 : 1)}}">
                        </div>
                        {{--<div style="position: absolute;bottom:0;left:41%;">
                            <div class="blackboard-block" style="position: relative;"><a style="width: 100%;position: absolute;bottom: 0px;" href="{{route('clients.activityprogress',Array($client,$process_id,$step['id']))}}"><span style="font-size: 24px;" class="fa fa-angle-down"></span></a></div>
                        </div>--}}</div>
                    </div>
                @empty
                    <p>There are no steps assigned to this process.</p>
                @endforelse
            @else
                @forelse(auth()->user()->office()->processes->first()->steps as $step)
                    <div class="col-lg blackboard-step-{{$step->id}}">
                        {{$step->name}}
                    </div>
                @empty
                    <p>There are no steps assigned to this process.</p>
                @endforelse
            @endif
        </div>
    </div>
</div><button class="nav-next" style="position: absolute;right: -7px;top:30px;z-index: 1;height:70px;width:35px;border-radius:50px 0 0 50px;background:#FFF;-webkit-box-shadow: 0px 0px 8px 2px rgba(0,0,0,0.28);
-moz-box-shadow: 0px 0px 8px 2px rgba(0,0,0,0.28);
box-shadow: 0px 0px 8px 2px rgba(0,0,0,0.28);border: 0px;">
        <i class="fas fa-angle-right" style="padding-left: 10px;
    font-size: 40px;"></i>
</button>
</div>
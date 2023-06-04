@component('mail::message')
# Introduction

The client saved.

@component('mail::button', ['url' => route('clients.overview', [$client,$process_id,$step_id])])
View Client
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

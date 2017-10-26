@component('mail::message')
# NAME requests access to one of your texts

You can quickly accept if you like by clicking the Green button.
REVIEW_RATING_NUMBER of their peers rate their average reliability rating at STARS.
If you'd like you can contact them at EMAIL to negotiate terms.

@component('mail::button', ['url' => '', 'color' => 'green']) Accept @endcomponent
@component('mail::button', ['url' => '','color' => 'red']) Refuse @endcomponent

@endcomponent
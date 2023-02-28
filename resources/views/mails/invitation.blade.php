<div>
    Hi, {{ $recipient['name'] }}
</div>

@if($additionalMessage)
    <div>
        {{ $additionalMessage }}
    </div>
@endif

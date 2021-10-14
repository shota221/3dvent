@if($type)

エラータイプ（ルート）： {{ $type }}

ユーザーID： {{ $user }}

@endif

内容： {{ $content }}

@if($stacktrace)

stacktrace： {{ $stacktrace }}

@endif